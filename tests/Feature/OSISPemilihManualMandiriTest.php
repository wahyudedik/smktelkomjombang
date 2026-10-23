<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Regresi fitur "create manual pemilih mendukung user_type siswa/guru"
 * (Bagian 2 Tugas OSIS):
 * - Form create/edit pemilih punya field "Tipe Pemilih" (Siswa default / Guru).
 * - Mode guru: `nis` = NIP + `kelas` DIPAKSA 'Guru' server-side.
 * - Record legacy `user_type=NULL` TIDAK dipaksa overwrite jadi 'siswa'
 *   saat update tanpa perubahan eksplisit.
 * - Kolom import `nis` opsional (NIS siswa / NIP guru) dengan BACKWARD COMPAT
 *   — file lama tanpa kolom `nis` tetap terimport (`nis` NULL).
 * - Pemilih guru hasil create/import BISA diverifikasi via verifikasi publik
 *   (`POST /osis/verify`) tanpa login.
 * - Hardening NIP 18 digit: template `nis` berformat Text (@) + konversi
 *   sel numerik (int/float) ke string utuh tanpa notasi ilmiah.
 *
 * Jalur route: admin.osis.pemilih.* (role admin + permission osis.*)
 * dan osis.public.verify (publik, throttle:osis-public).
 */
class OSISPemilihManualMandiriTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin-pemilih@test.com',
            'password' => Hash::make('password'),
        ]);
        $adminRole = $this->getOrCreateRole('admin');
        $adminRole->givePermissionTo($this->getOrCreatePermission('osis.view'));
        $adminRole->givePermissionTo($this->getOrCreatePermission('osis.create'));
        $adminRole->givePermissionTo($this->getOrCreatePermission('osis.edit'));
        $this->admin->syncRoles([$adminRole]);
        $this->admin->updateQuietly(['user_type' => 'admin']);
    }

    /**
     * Payload standar store/update pemilih (mengikuti validasi OSISController).
     */
    private function validPemilihPayload(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Siswa Manual Baru',
            'nis' => '24001',
            'kelas' => 'XII IPA 1',
            'jenis_kelamin' => 'L',
            'email' => 'siswa.manual@test.com',
            'user_type' => 'siswa',
        ], $overrides);
    }

    /**
     * Pemilihan OSIS aktif — dibutuhkan oleh verifikasi publik
     * (PublicOsisController::verify butuh OsisElection::active()).
     */
    private function createActiveElection(): OsisElection
    {
        return OsisElection::create([
            'title' => 'Pemilihan OSIS Test',
            'description' => 'Pemilihan OSIS untuk pengujian Bagian 2',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'show_results' => false,
        ]);
    }

    /**
     * Generate file XLSX sementara dari headings + rows, kembalikan UploadedFile
     * palsu berisi konten XLSX nyata (lolos validasi mimes:xlsx).
     */
    private function makeXlsxUpload(array $headings, array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($headings, null, 'A1');
        foreach ($rows as $index => $row) {
            $sheet->fromArray($row, null, 'A' . ($index + 2));
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'pemilih_import') . '.xlsx';
        (new Xlsx($spreadsheet))->save($tmpPath);
        $content = file_get_contents($tmpPath);
        @unlink($tmpPath);
        $spreadsheet->disconnectWorksheets();

        return UploadedFile::fake()->createWithContent('pemilih-import.xlsx', $content);
    }

    /** @test */
    public function create_pemilih_page_renders_with_user_type_field_when_siswas_and_gurus_are_empty(): void
    {
        // Tabel siswa & guru kosong total — form tetap berfungsi (import mandiri).
        $this->assertSame(0, Siswa::count());
        $this->assertSame(0, Guru::count());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.pemilih.create'));

        $response->assertOk();
        $response->assertSee('Tipe Pemilih');
        $response->assertSee('user_type');
        $response->assertSee('Siswa');
        $response->assertSee('Guru');

        // Perbaikan feedback UI: placeholder Jenis Kelamin memakai kunci
        // `select_gender` (locale-agnostic; di locale id → "Pilih Jenis
        // Kelamin"), bukan `select_status` ("Select Status").
        $response->assertSee(
            '<option value="">' . e(__('common.select_gender')) . '</option>',
            false
        );

        // Input "Guru" untuk field Kelas hanya tampil di mode guru: wajib
        // punya penyembunyian statis `style="display: none;"` agar tidak
        // nyasar terlihat saat mode siswa (sebelum Alpine init).
        $this->assertMatchesRegularExpression(
            '/id="kelas_guru"[^>]*style="display:\s*none;"/',
            $response->getContent(),
            'Input kelas_guru harus tersembunyi statis (display:none) di form pemilih create.'
        );
    }

    /** @test */
    public function admin_can_create_pemilih_with_explicit_siswa_user_type_and_badge_shows_in_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.store'), $this->validPemilihPayload());

        $response->assertRedirect(route('admin.osis.pemilih.index'));
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Siswa Manual Baru',
            'nis' => '24001',
            'kelas' => 'XII IPA 1',
            'user_type' => 'siswa',
        ]);

        // Badge "Siswa" tampil di index (markup badge tanpa whitespace di
        // dalam tag — cocokkan dengan regex seperti tes Bagian 1).
        $index = $this->actingAs($this->admin)->get(route('admin.osis.pemilih.index'));
        $index->assertOk();
        $this->assertMatchesRegularExpression(
            '/badge-success[^>]*">\s*Siswa\s*<\/span>/',
            $index->getContent()
        );
    }

    /** @test */
    public function store_guru_pemilih_forces_kelas_guru_and_is_publicly_verifiable(): void
    {
        // Client sengaja kirim kelas salah + user_type guru — server wajib paksa 'Guru'.
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.store'), $this->validPemilihPayload([
                'nama' => 'Bapak Guru Verifikasi',
                'nis' => '197901012005011001', // NIP
                'email' => 'guru.verifikasi@test.com',
                'jenis_kelamin' => 'L',
                'user_type' => 'guru',
                'kelas' => 'XII IPA 1', // salah — harus dipaksa 'Guru'
            ]));

        $response->assertRedirect(route('admin.osis.pemilih.index'));
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Bapak Guru Verifikasi',
            'nis' => '197901012005011001',
            'user_type' => 'guru',
            'kelas' => 'Guru',
        ]);

        $pemilih = Pemilih::where('nis', '197901012005011001')->firstOrFail();

        // Badge "Guru" tampil di index & show.
        $index = $this->actingAs($this->admin)->get(route('admin.osis.pemilih.index'));
        $index->assertOk();
        $this->assertMatchesRegularExpression(
            '/badge-warning[^>]*">\s*Guru\s*<\/span>/',
            $index->getContent()
        );

        $show = $this->actingAs($this->admin)->get(route('admin.osis.pemilih.show', $pemilih));
        $show->assertOk();
        $this->assertMatchesRegularExpression(
            '/badge-warning[^>]*">\s*Guru\s*<\/span>/',
            $show->getContent()
        );

        // Verifikasi publik end-to-end: NIP → session voter → halaman vote.
        $this->createActiveElection();
        $verify = $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '197901012005011001',
        ]);

        $verify->assertRedirect(route('osis.public.vote'));
        $this->assertSame($pemilih->id, session('public_osis_voter.pemilih_id'));
        $this->assertSame('guru', session('public_osis_voter.user_type'));
    }

    /** @test */
    public function store_without_user_type_field_keeps_null_backward_compat(): void
    {
        // Form/request lama yang TIDAK mengirim field user_type sama sekali.
        $payload = $this->validPemilihPayload([
            'nama' => 'Pemilih Legacy Form',
            'nis' => '24002',
            'email' => 'legacy.form@test.com',
        ]);
        unset($payload['user_type']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.store'), $payload);

        $response->assertRedirect(route('admin.osis.pemilih.index'));
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Pemilih Legacy Form',
            'nis' => '24002',
            'user_type' => null,
        ]);
    }

    /** @test */
    public function store_rejects_invalid_user_type_staff(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.store'), $this->validPemilihPayload([
                'user_type' => 'staff', // di luar whitelist siswa/guru
            ]));

        $response->assertSessionHasErrors('user_type');
        $this->assertDatabaseMissing('pemilihs', ['nama' => 'Siswa Manual Baru']);
    }

    /** @test */
    public function edit_legacy_pemilih_with_null_user_type_is_not_overwritten_when_unchanged(): void
    {
        $pemilih = Pemilih::create([
            'nama' => 'Pemilih Legacy NULL',
            'nis' => '24003',
            'kelas' => 'XI IPS 2',
            'jenis_kelamin' => 'P',
            'email' => 'legacy.null@test.com',
            'user_type' => null,
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        // Form edit menampilkan 'Siswa' (default display) tapi hidden input
        // mengirim '' (tidak diubah) → NULL tersimpan TIDAK ditimpa ke 'siswa'.
        $response = $this->actingAs($this->admin)
            ->put(route('admin.osis.pemilih.update', $pemilih), [
                'nama' => 'Pemilih Legacy NULL',
                'nis' => '24003',
                'kelas' => 'XI IPS 2',
                'jenis_kelamin' => 'P',
                'email' => 'legacy.null@test.com',
                'user_type' => '',
            ]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));
        $this->assertDatabaseHas('pemilihs', [
            'id' => $pemilih->id,
            'user_type' => null,
        ]);
    }

    /** @test */
    public function edit_can_explicitly_switch_legacy_pemilih_to_guru_and_forces_kelas_guru(): void
    {
        $pemilih = Pemilih::create([
            'nama' => 'Pemilih Akan Naik Guru',
            'nis' => '24004',
            'kelas' => 'XII IPA 1',
            'jenis_kelamin' => 'L',
            'email' => 'naik.guru@test.com',
            'user_type' => null,
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        // Perubahan EKSPLISIT ke 'guru' → tersimpan + kelas dipaksa 'Guru'.
        $response = $this->actingAs($this->admin)
            ->put(route('admin.osis.pemilih.update', $pemilih), [
                'nama' => 'Pemilih Akan Naik Guru',
                'nis' => '24004',
                'kelas' => 'XII IPA 1', // salah — harus dipaksa 'Guru'
                'jenis_kelamin' => 'L',
                'email' => 'naik.guru@test.com',
                'user_type' => 'guru',
            ]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));
        $this->assertDatabaseHas('pemilihs', [
            'id' => $pemilih->id,
            'user_type' => 'guru',
            'kelas' => 'Guru',
        ]);
    }

    /** @test */
    public function import_with_nis_column_saves_nis_and_guru_is_publicly_verifiable(): void
    {
        $upload = $this->makeXlsxUpload(
            ['nama', 'nis', 'email', 'user_type', 'jenis_kelamin', 'kelas', 'status'],
            [
                ['Siswa Import NIS', '24010', 'siswa.import@test.com', 'siswa', 'L', 'XII IPA 1', 'belum_memilih'],
                // Kelas sengaja salah — import wajib paksa 'Guru' untuk baris guru.
                ['Guru Import NIP', '197802022003121002', 'guru.import@test.com', 'guru', 'L', 'XII IPA 1', 'belum_memilih'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));

        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Siswa Import NIS',
            'nis' => '24010',
            'user_type' => 'siswa',
            'kelas' => 'XII IPA 1',
        ]);
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Guru Import NIP',
            'nis' => '197802022003121002', // NIP tersimpan di kolom nis
            'user_type' => 'guru',
            'kelas' => 'Guru', // dipaksa server-side
        ]);

        // Guru hasil import BISA diverifikasi publik tanpa login.
        $this->createActiveElection();
        $verify = $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '197802022003121002',
        ]);

        $verify->assertRedirect(route('osis.public.vote'));
        $guru = Pemilih::where('nis', '197802022003121002')->firstOrFail();
        $this->assertSame($guru->id, session('public_osis_voter.pemilih_id'));
        $this->assertSame('guru', session('public_osis_voter.user_type'));
    }

    /** @test */
    public function import_legacy_file_without_nis_column_still_works_with_null_nis(): void
    {
        // File format LAMA (sebelum kolom nis ditambahkan) — tanpa heading `nis`.
        $upload = $this->makeXlsxUpload(
            ['nama', 'email', 'user_type', 'jenis_kelamin', 'kelas', 'status'],
            [
                ['Siswa Legacy Import', 'siswa.legacy@test.com', 'siswa', 'P', 'XI IPS 2', 'belum_memilih'],
                ['Guru Legacy Import', 'guru.legacy@test.com', 'guru', 'L', 'Guru', 'belum_memilih'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));

        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Siswa Legacy Import',
            'user_type' => 'siswa',
            'kelas' => 'XI IPS 2',
            'nis' => null,
        ]);
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Guru Legacy Import',
            'user_type' => 'guru',
            'kelas' => 'Guru',
            'nis' => null,
        ]);
        $this->assertSame(2, Pemilih::count());
    }

    /** @test */
    public function download_pemilih_template_contains_nis_column_and_four_sample_rows(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.pemilih.downloadTemplate'));

        $response->assertDownload('template-import-pemilih.xlsx');

        // Excel::download menghasilkan BinaryFileResponse — file temp masih
        // ada di test (belum di-send/dihapus).
        $baseResponse = $response->baseResponse;
        $this->assertTrue(
            method_exists($baseResponse, 'getFile'),
            'Expected BinaryFileResponse from Excel::download().'
        );
        $filePath = $baseResponse->getFile()->getPathname();
        $this->assertFileExists($filePath);

        $sheet = IOFactory::load($filePath)->getActiveSheet();

        // Heading wajib memuat kolom `nis`.
        $headings = $sheet->rangeToArray('A1:G1', null, false, false, false)[0];
        $this->assertContains('nis', $headings);
        $this->assertContains('nama', $headings);
        $this->assertContains('user_type', $headings);

        // Minimal 4 baris contoh: 2 siswa (L & P) + baris guru (kelas 'Guru').
        $rows = array_values(array_filter(
            $sheet->rangeToArray('A2:G100', null, false, false, false),
            static fn ($row): bool => !empty($row[0])
        ));
        $this->assertGreaterThanOrEqual(4, count($rows));

        $guruRows = array_values(array_filter(
            $rows,
            static fn ($row): bool => in_array('guru', $row, true)
        ));
        $this->assertNotEmpty($guruRows);
        foreach ($guruRows as $row) {
            $this->assertContains('Guru', $row);   // kelas selalu 'Guru'
            $this->assertNotEmpty($row[1]);         // kolom nis (NIP) terisi
        }

        $siswaRows = array_values(array_filter(
            $rows,
            static fn ($row): bool => in_array('siswa', $row, true)
        ));
        $this->assertGreaterThanOrEqual(2, count($siswaRows));
    }

    /** @test */
    public function import_with_numeric_18_digit_nis_keeps_every_digit_exactly(): void
    {
        // NIP 18 digit sebagai SEL ANGKA (bukan teks) — digit terakhir wajib
        // tersimpan persis di pemilihs.nis (int64 PHP muat, tanpa notasi ilmiah).
        $nip = '198703122010011003';
        $upload = $this->makeXlsxUpload(
            ['nama', 'nis', 'email', 'user_type', 'jenis_kelamin', 'kelas', 'status'],
            [
                ['Guru NIP Angka', (int) $nip, 'guru.nip.angka@test.com', 'guru', 'L', 'Guru', 'belum_memilih'],
                ['Siswa NIS Angka', 24010, 'siswa.nis.angka@test.com', 'siswa', 'L', 'XII IPA 1', 'belum_memilih'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));

        $guru = Pemilih::where('email', 'guru.nip.angka@test.com')->firstOrFail();
        $this->assertSame($nip, $guru->nis);
        $this->assertSame(18, strlen((string) $guru->nis));

        $siswa = Pemilih::where('email', 'siswa.nis.angka@test.com')->firstOrFail();
        $this->assertSame('24010', $siswa->nis);
    }

    /** @test */
    public function import_with_float_nis_stores_plain_digits_without_scientific_notation(): void
    {
        // NIP diisi sebagai ANGKA desimal (Excel menyimpan sebagai double) —
        // hasil import wajib digit polos, bukan "1.9870312201001E+17".
        // Catatan: presisi IEEE 754 sudah membulatkan 18 digit ke float
        // terdekat (198703122010009984) SEBELUM file masuk; konversi import
        // hanya bertanggung jawab menyajikan float itu sebagai digit utuh.
        $nip = '198703122010011003';
        $upload = $this->makeXlsxUpload(
            ['nama', 'nis', 'email', 'user_type', 'jenis_kelamin', 'kelas', 'status'],
            [
                ['Guru NIP Float', (float) $nip, 'guru.nip.float@test.com', 'guru', 'L', 'Guru', 'belum_memilih'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.pemilih.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.pemilih.index'));

        $guru = Pemilih::where('email', 'guru.nip.float@test.com')->firstOrFail();
        $nis = (string) $guru->nis;
        // Digit polos 18 angka — tanpa notasi ilmiah ("E+"), desimal, atau
        // karakter non-digit apa pun. Regex ini langsung menangkap bug lama
        // ((string) $float → "1.9870312201001E+17").
        $this->assertMatchesRegularExpression('/^\d{18}$/', $nis);
        $this->assertStringNotContainsStringIgnoringCase('E', $nis);
        // Nilai tetap very close ke NIP asli (noise IEEE 754 + presisi
        // serialisasi XLSX yang memang tak bisa dihindari untuk float) —
        // menangkap korupsi skala besar / pembulatan keliru, bukan selisih
        // 32-ULP di digit terakhir.
        $this->assertEqualsWithDelta((float) $nip, (float) $nis, (float) $nip * 1e-13);
    }

    /** @test */
    public function download_pemilih_template_formats_nis_column_as_text(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.pemilih.downloadTemplate'));

        $response->assertDownload('template-import-pemilih.xlsx');

        $baseResponse = $response->baseResponse;
        $this->assertTrue(
            method_exists($baseResponse, 'getFile'),
            'Expected BinaryFileResponse from Excel::download().'
        );
        $filePath = $baseResponse->getFile()->getPathname();
        $this->assertFileExists($filePath);

        $sheet = IOFactory::load($filePath)->getActiveSheet();

        // Kolom `nis` (B) wajib format Text (@): baris contoh & seluruh kolom
        // agar NIP 18 digit tampil utuh, bukan notasi ilmiah (1,98703E+17).
        foreach (['B2', 'B4', 'B5'] as $coordinate) {
            $this->assertSame(
                '@',
                $sheet->getStyle($coordinate)->getNumberFormat()->getFormatCode(),
                "Expected Text number format (@) on cell {$coordinate}."
            );
        }

        // Sampel NIP guru tersimpan sebagai TEKS 18 digit utuh (bukan angka).
        $nipCell = $sheet->getCell('B4');
        $this->assertSame(DataType::TYPE_STRING, $nipCell->getDataType());
        $this->assertSame('198703122010011003', $nipCell->getValue());
    }
}
