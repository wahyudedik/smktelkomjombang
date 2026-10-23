<?php

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Regresi fitur "calon mandiri" (OSIS, scope perbaikan feedback user):
 * - Form create/edit calon mendukung 2 mode sumber data (Siswa/Manual) —
 *   opsi Guru DIHAPUS (guru TIDAK menjadi calon OSIS).
 * - Kolom `calons.user_type` ('siswa'/NULL) HANYA penanda/label asal calon.
 * - Validasi server-side `user_type_* => in:siswa` menolak 'guru' (dan nilai
 *   lain di luar whitelist).
 * - Form calon MANDIRI: nama ketua/wakil teks bebas — TIDAK harus ada di
 *   tabel siswas/gurus (buktikan: kedua tabel kosong total pun form tetap
 *   berfungsi).
 * - Import calon: kolom opsional `user_type` hanya boleh 'siswa'; baris
 *   'guru' di-skip (SkipsOnFailure) TANPA menggagalkan seluruh impor.
 *   BACKWARD COMPAT: file lama tanpa kolom tersebut tetap terimpor.
 * - Template download calon tidak lagi memuat contoh baris guru.
 *
 * Jalur route: admin.osis.* (role admin + permission osis.*).
 */
class OSISCalonManualMandiriTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin-calon@test.com',
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
     * Payload standar store/update calon (mengikuti validasi OSISController).
     */
    private function validCalonPayload(array $overrides = []): array
    {
        return array_merge([
            'nama_ketua' => 'Nama Bebas Tanpa Siswa',
            'nama_wakil' => 'Nama Wakil Bebas',
            'jenis_kelamin' => 'L',
            'visi_misi' => 'Visi dan misi calon mandiri',
            'jenis_pencalonan' => 'pasangan',
            'is_active' => 1,
            'user_type_ketua' => '',
            'user_type_wakil' => '',
        ], $overrides);
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

        $tmpPath = tempnam(sys_get_temp_dir(), 'calon_import') . '.xlsx';
        (new Xlsx($spreadsheet))->save($tmpPath);
        $content = file_get_contents($tmpPath);
        @unlink($tmpPath);
        $spreadsheet->disconnectWorksheets();

        return UploadedFile::fake()->createWithContent('calon-import.xlsx', $content);
    }

    /** @test */
    public function create_calon_page_renders_without_guru_mode_option_when_tables_empty(): void
    {
        // Flow inti "mandiri": kedua tabel kosong total (belum ada setUp factory).
        $this->assertSame(0, \App\Models\Siswa::count());
        $this->assertSame(0, \App\Models\Guru::count());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.calon.create'));

        $response->assertOk();
        $response->assertSee('sumber_ketua');
        $response->assertSee('sumber_wakil');
        $response->assertSee('Manual (ketik nama)');

        // Opsi Guru DIHAPUS dari form calon: tidak ada <option value="guru">
        // maupun blok "Mode: Guru" (kata "guru" pada komentar JS/i18n lain
        // tidak diverifikasi — hanya struktur UI mode guru).
        $response->assertDontSee('value="guru"', false);
        $response->assertDontSee('Mode: Guru', false);
    }

    /** @test */
    public function admin_can_create_manual_calon_with_free_text_name_when_tables_empty(): void
    {
        // Tabel siswas & gurus kosong total — nama teks bebas tetap tersimpan.
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'nama_ketua' => 'Pelajar Tanpa Record Siswa',
                'user_type_ketua' => '',
            ]));

        $response->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Pelajar Tanpa Record Siswa',
            'user_type' => null,
        ]);
    }

    /** @test */
    public function store_calon_with_guru_user_type_is_rejected_by_validation(): void
    {
        // Opsi Guru sudah dihapus dari form, tetapi server-side TETAP menolak
        // nilai 'guru' (mis. request manual / bot / form lama yang di-cache).
        $responseKetua = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'user_type_ketua' => 'guru',
            ]));

        $responseKetua->assertSessionHasErrors('user_type_ketua');
        $this->assertDatabaseMissing('calons', ['nama_ketua' => 'Nama Bebas Tanpa Siswa']);

        $responseWakil = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'user_type_wakil' => 'guru',
            ]));

        $responseWakil->assertSessionHasErrors('user_type_wakil');
        $this->assertDatabaseMissing('calons', ['nama_ketua' => 'Nama Bebas Tanpa Siswa']);
    }

    /** @test */
    public function invalid_user_type_value_is_rejected_by_server_side_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'user_type_ketua' => 'admin', // di luar whitelist siswa
            ]));

        $response->assertSessionHasErrors('user_type_ketua');
        $this->assertDatabaseMissing('calons', ['nama_ketua' => 'Nama Bebas Tanpa Siswa']);
    }

    /** @test */
    public function import_guru_rows_are_skipped_without_failing_whole_import(): void
    {
        // Baris 'guru' gagal validasi (in:siswa) → di-skip oleh SkipsOnFailure,
        // baris siswa & manual tetap terimpor, seluruh impor TIDAK gagal.
        // nama_wakil wajib non-kosong pada semua row (kolom NOT NULL).
        $upload = $this->makeXlsxUpload(
            ['nama_ketua', 'nama_wakil', 'jenis_kelamin', 'user_type', 'visi_misi', 'jenis_pencalonan', 'status_aktif'],
            [
                ['Ketua Siswa Impor', 'Wakil Siswa Impor', 'L', 'siswa', 'Visi impor siswa', 'pasangan', 'aktif'],
                ['Bapak Guru Impor', 'Wakil Guru Impor', 'L', 'guru', 'Visi impor guru', 'ketua', 'aktif'],
                ['Ketua Manual Impor', 'Wakil Manual Impor', 'P', '', 'Visi impor manual', 'pasangan', 'aktif'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.processImport'), ['file' => $upload]);

        // Impor sukses (redirect index) — bukan error seluruh file.
        $response->assertRedirect(route('admin.osis.calon.index'));

        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Ketua Siswa Impor',
            'user_type' => 'siswa',
        ]);
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Ketua Manual Impor',
            'user_type' => null,
        ]);
        $this->assertDatabaseMissing('calons', ['nama_ketua' => 'Bapak Guru Impor']);

        // Hanya 2 baris valid yang tersimpan (baris guru dilewati).
        $this->assertSame(2, Calon::count());
    }

    /** @test */
    public function import_legacy_file_without_user_type_column_still_works(): void
    {
        // Format file LAMA (tanpa kolom user_type) — backward compat: tetap import,
        // user_type dibiarkan NULL (tanpa penanda).
        $upload = $this->makeXlsxUpload(
            ['nama_ketua', 'nama_wakil', 'jenis_kelamin', 'visi_misi', 'jenis_pencalonan', 'status_aktif'],
            [
                ['Calon Lama Satu', 'Calon Lama Dua', 'P', 'Visi dari file lama', 'pasangan', 'aktif'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Calon Lama Satu',
            'nama_wakil' => 'Calon Lama Dua',
            'user_type' => null,
        ]);
    }

    /** @test */
    public function download_calon_template_contains_no_guru_sample_row(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.calon.downloadTemplate'));

        $response->assertDownload('template-import-calon.xlsx');

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
        $rows = $sheet->toArray();

        // Heading tetap memuat kolom user_type.
        $this->assertContains('user_type', $rows[0]);

        // Tidak ada satupun sel (case-insensitive) yang bernilai persis 'guru' —
        // contoh baris guru sudah diganti contoh baris manual.
        $flatCells = [];
        foreach (array_slice($rows, 1) as $row) {
            foreach ($row as $cell) {
                if ($cell !== null && $cell !== '') {
                    $flatCells[] = strtolower(trim((string) $cell));
                }
            }
        }
        $this->assertNotContains('guru', $flatCells);

        // Contoh baris manual (tanpa penanda user_type) tetap ada.
        $this->assertContains('rina wulandari', $flatCells);
    }

    /** @test */
    public function edit_calon_with_null_user_type_defaults_to_manual_and_can_switch_source(): void
    {
        // Data legacy: user_type NULL → edit form harus berfungsi (mode Manual
        // default) dan bisa diganti ke sumber Siswa / kembali Manual.
        // nama_wakil tetap '' (bukan NULL) karena kolom NOT NULL.
        $calon = Calon::create([
            'nama_ketua' => 'Calon Legacy Tanpa Penanda',
            'nama_wakil' => '',
            'jenis_kelamin' => 'L',
            'visi_misi' => 'Visi legacy',
            'jenis_pencalonan' => 'ketua',
            'is_active' => true,
            'user_type' => null,
        ]);

        $editPage = $this->actingAs($this->admin)
            ->get(route('admin.osis.calon.edit', $calon));
        $editPage->assertOk();
        $editPage->assertSee('sumber_ketua');
        $editPage->assertSee('value="Calon Legacy Tanpa Penanda"', false);
        // Opsi Guru tidak ada di form edit calon.
        $editPage->assertDontSee('value="guru"', false);
        $editPage->assertDontSee('Mode: Guru', false);

        // Switch ke sumber Siswa.
        $updateSiswa = $this->actingAs($this->admin)
            ->put(route('admin.osis.calon.update', $calon), $this->validCalonPayload([
                'nama_ketua' => 'Siswa Dari Edit',
                'jenis_pencalonan' => 'ketua',
                'user_type_ketua' => 'siswa',
            ]));

        $updateSiswa->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'id' => $calon->id,
            'nama_ketua' => 'Siswa Dari Edit',
            'user_type' => 'siswa',
        ]);

        // Kembali ke Manual → user_type kembali NULL.
        $updateManual = $this->actingAs($this->admin)
            ->put(route('admin.osis.calon.update', $calon->fresh()), $this->validCalonPayload([
                'nama_ketua' => 'Ketik Nama Manual Lagi',
                'jenis_pencalonan' => 'ketua',
                'user_type_ketua' => '',
            ]));

        $updateManual->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'id' => $calon->id,
            'nama_ketua' => 'Ketik Nama Manual Lagi',
            'user_type' => null,
        ]);
    }

    /** @test */
    public function edit_calon_with_legacy_guru_user_type_falls_back_to_manual_mode(): void
    {
        // Data legacy dari fitur lama: user_type='guru' — form edit harus tetap
        // ter-render (fallback mode Manual; nama lama tampil di input teks).
        $calon = Calon::create([
            'nama_ketua' => 'Calon Legacy Guru',
            'nama_wakil' => '',
            'jenis_kelamin' => 'L',
            'visi_misi' => 'Visi legacy guru',
            'jenis_pencalonan' => 'ketua',
            'is_active' => true,
            'user_type' => 'guru',
        ]);

        $editPage = $this->actingAs($this->admin)
            ->get(route('admin.osis.calon.edit', $calon));

        $editPage->assertOk();
        // Fallback Manual: nama lama tampil pada input teks mode manual.
        $editPage->assertSee('value="Calon Legacy Guru"', false);
        $editPage->assertDontSee('value="guru"', false);
        $editPage->assertDontSee('Mode: Guru', false);
    }
}
