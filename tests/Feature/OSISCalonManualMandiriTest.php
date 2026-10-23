<?php

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Regresi fitur "calon mandiri" (Bagian 1 Tugas OSIS):
 * - Form create/edit calon mendukung 3 mode sumber data (Siswa/Guru/Manual).
 * - Kolom `calons.user_type` ('siswa'/'guru'/NULL) HANYA penanda/label asal calon.
 * - Form calon MANDIRI: nama ketua/wakil teks bebas — TIDAK harus ada di
 *   tabel siswas/gurus (buktikan: kedua tabel kosong total pun form tetap
 *   berfungsi).
 * - Import calon mendukung kolom opsional `user_type` + BACKWARD COMPAT dengan
 *   file lama tanpa kolom tersebut.
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
    public function create_calon_page_renders_when_siswas_and_gurus_tables_are_empty(): void
    {
        // Flow inti "mandiri": kedua tabel kosong total (belum ada setUp factory).
        $this->assertSame(0, \App\Models\Siswa::count());
        $this->assertSame(0, \App\Models\Guru::count());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.osis.calon.create'));

        $response->assertOk();
        $response->assertSee('Manual (ketik nama)');
        $response->assertSee('sumber_ketua');
        $response->assertSee('sumber_wakil');
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
    public function admin_can_create_calon_with_guru_user_type_and_badge_shows_in_index(): void
    {
        // Catatan: kolom calons.nama_wakil NOT NULL — jangan kirim string kosong
        // (middleware ConvertEmptyStringsToNull mengubahnya jadi NULL → insert gagal).
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'nama_ketua' => 'Bapak Sutrisno',
                'jenis_pencalonan' => 'ketua',
                'user_type_ketua' => 'guru',
            ]));

        $response->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Bapak Sutrisno',
            'user_type' => 'guru',
        ]);

        // Badge penanda tampil di index (label Indonesia; markup punya
        // whitespace/newline antara tag — cocokkan dengan regex).
        $index = $this->actingAs($this->admin)->get(route('admin.osis.calon.index'));
        $index->assertOk();
        $index->assertSee('badge-warning', false);
        $this->assertMatchesRegularExpression(
            '/badge-warning[^>]*">\s*Guru\s*<\/span>/',
            $index->getContent()
        );
    }

    /** @test */
    public function invalid_user_type_value_is_rejected_by_server_side_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.store'), $this->validCalonPayload([
                'user_type_ketua' => 'admin', // di luar whitelist siswa/guru
            ]));

        $response->assertSessionHasErrors('user_type_ketua');
        $this->assertDatabaseMissing('calons', ['nama_ketua' => 'Nama Bebas Tanpa Siswa']);
    }

    /** @test */
    public function import_with_user_type_column_saves_correct_user_type(): void
    {
        // nama_wakil wajib non-kosong pada semua row (kolom NOT NULL; nilai kosong
        // di-null-kan CalonImport lalu row diskip diam-diam oleh SkipsOnFailure).
        $upload = $this->makeXlsxUpload(
            ['nama_ketua', 'nama_wakil', 'jenis_kelamin', 'user_type', 'visi_misi', 'jenis_pencalonan', 'status_aktif'],
            [
                ['Ketua Siswa Impor', 'Wakil Siswa Impor', 'L', 'siswa', 'Visi impor siswa', 'pasangan', 'aktif'],
                ['Bapak Guru Impor', 'Wakil Guru Impor', 'L', 'guru', 'Visi impor guru', 'ketua', 'aktif'],
            ]
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.osis.calon.processImport'), ['file' => $upload]);

        $response->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Ketua Siswa Impor',
            'user_type' => 'siswa',
        ]);
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Bapak Guru Impor',
            'user_type' => 'guru',
        ]);
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
    public function edit_calon_with_null_user_type_defaults_to_manual_and_can_switch_source(): void
    {
        // Data legacy: user_type NULL → edit form harus berfungsi (mode Manual
        // default) dan bisa diganti ke sumber Guru / kembali Manual.
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

        // Switch ke sumber Guru.
        $updateGuru = $this->actingAs($this->admin)
            ->put(route('admin.osis.calon.update', $calon), $this->validCalonPayload([
                'nama_ketua' => 'Bapak Guru Dari Edit',
                'jenis_pencalonan' => 'ketua',
                'user_type_ketua' => 'guru',
            ]));

        $updateGuru->assertRedirect(route('admin.osis.calon.index'));
        $this->assertDatabaseHas('calons', [
            'id' => $calon->id,
            'nama_ketua' => 'Bapak Guru Dari Edit',
            'user_type' => 'guru',
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
}
