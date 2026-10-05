<?php

namespace Tests\Feature;

use App\Jobs\ImportUsersJob;
use App\Models\User;
use App\Models\UserImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Kontrak alur queue-based import user (diport dari repo induk lms).
 *
 * Route: admin.superadmin.users.processImport (role:superadmin, throttle:10,1).
 * Import idempoten: upsert by email; kolom email_verified_at & is_verified_by_admin persist.
 */
class UserImportQueueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'guru', 'siswa', 'sarpras'] as $role) {
            $this->getOrCreateRole($role);
        }
    }

    /**
     * Membuat file XLSX sungguhan untuk diupload ke route import.
     *
     * @param  array<int, array<string, mixed>>  $dataRows
     */
    protected function createUploadFile(array $dataRows): UploadedFile
    {
        $headers = ['name', 'email', 'role', 'password', 'email_verified_at', 'is_verified_by_admin'];

        $sheetRows = [$headers];
        foreach ($dataRows as $row) {
            $sheetRows[] = array_map(fn ($header) => $row[$header] ?? null, $headers);
        }

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray($sheetRows, null, 'A1');

        $path = tempnam(sys_get_temp_dir(), 'telkom_user_import_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return new UploadedFile(
            $path,
            'users-import.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
    }

    /**
     * Membuat konten XLSX sebagai string (untuk disimpan langsung ke storage fake).
     *
     * @param  array<int, array<string, mixed>>  $dataRows
     */
    protected function xlsxContent(array $dataRows): string
    {
        $headers = ['name', 'email', 'role', 'password', 'email_verified_at', 'is_verified_by_admin'];

        $sheetRows = [$headers];
        foreach ($dataRows as $row) {
            $sheetRows[] = array_map(fn ($header) => $row[$header] ?? null, $headers);
        }

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray($sheetRows, null, 'A1');

        $path = tempnam(sys_get_temp_dir(), 'telkom_user_import_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $content = file_get_contents($path);
        unlink($path);

        return $content;
    }

    protected function createSuperadmin(): User
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('superadmin');

        return $user;
    }

    public function test_post_import_valid_membuat_record_pending_dan_mendispatch_job(): void
    {
        Queue::fake();
        Storage::fake('local');

        $admin = $this->createSuperadmin();

        $file = $this->createUploadFile([
            ['name' => 'Siswa Queue', 'email' => 'queue@example.com', 'role' => 'siswa'],
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.superadmin.users.processImport'),
            ['file' => $file]
        );

        // Record dibuat dengan status pending
        $record = UserImport::where('user_id', $admin->id)->latest()->first();
        $this->assertNotNull($record);
        $this->assertSame('pending', $record->status);
        $this->assertSame('users-import.xlsx', $record->file_name);

        // Job terdispatch (tidak dieksekusi — Queue::fake)
        Queue::assertPushed(
            ImportUsersJob::class,
            fn (ImportUsersJob $job) => $job->userImport->id === $record->id
        );

        // User BELUM terbuat di DB (diproses oleh worker)
        $this->assertSame(0, User::where('email', 'queue@example.com')->count());

        // Response redirect + flash pesan background
        $response->assertRedirect(route('admin.superadmin.users'));
        $response->assertSessionHas('success');
    }

    public function test_handle_job_membuat_user_dengan_role_verifikasi_dan_counts(): void
    {
        Storage::fake('local');

        $admin = $this->createSuperadmin();

        Storage::disk('local')->put('imports/direct-run.xlsx', $this->xlsxContent([
            [
                'name' => 'Siswa Direct',
                'email' => 'direct1@example.com',
                'role' => 'siswa',
                'password' => 'rahasia123',
                'email_verified_at' => '2024-01-01 10:00:00',
                'is_verified_by_admin' => 'yes',
            ],
            [
                'name' => 'Guru Direct',
                'email' => 'direct2@example.com',
                'role' => 'guru',
            ],
        ]));

        $record = UserImport::create([
            'user_id' => $admin->id,
            'file_name' => 'direct-run.xlsx',
            'file_path' => 'imports/direct-run.xlsx',
            'status' => UserImport::STATUS_PENDING,
        ]);

        (new ImportUsersJob($record))->handle();

        $record->refresh();

        $this->assertSame('completed', $record->status, json_encode($record->errors));
        $this->assertSame(2, $record->created_count);
        $this->assertSame(0, $record->updated_count);
        $this->assertSame(0, $record->failed_count);
        $this->assertSame(2, $record->total_rows);
        $this->assertNotNull($record->finished_at);

        // User dibuat dengan role Spatie sesuai kolom role
        $siswa = User::where('email', 'direct1@example.com')->first();
        $this->assertNotNull($siswa);
        $this->assertTrue($siswa->hasRole('siswa'));

        // email_verified_at + is_verified_by_admin persist dari file
        $this->assertNotNull($siswa->email_verified_at);
        $this->assertTrue((bool) $siswa->is_verified_by_admin);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('rahasia123', $siswa->password));

        // Baris tanpa password -> default config('app.default_user_password', 'password123')
        $guru = User::where('email', 'direct2@example.com')->first();
        $this->assertNotNull($guru);
        $this->assertTrue($guru->hasRole('guru'));
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $guru->password));

        // File sementara dihapus setelah selesai
        $this->assertFalse(Storage::disk('local')->exists('imports/direct-run.xlsx'));
    }

    public function test_reimport_bersifat_idempoten_tanpa_duplikat(): void
    {
        Storage::fake('local');

        $admin = $this->createSuperadmin();

        $rows = [
            ['name' => 'Siswa Idem', 'email' => 'idem1@example.com', 'role' => 'siswa'],
            ['name' => 'Siswa Idem Dua', 'email' => 'idem2@example.com', 'role' => 'siswa'],
        ];

        // Run pertama
        Storage::disk('local')->put('imports/idem-1.xlsx', $this->xlsxContent($rows));
        $record1 = UserImport::create([
            'user_id' => $admin->id,
            'file_name' => 'idem-1.xlsx',
            'file_path' => 'imports/idem-1.xlsx',
            'status' => UserImport::STATUS_PENDING,
        ]);
        (new ImportUsersJob($record1))->handle();

        $record1->refresh();
        $this->assertSame('completed', $record1->status);
        $this->assertSame(2, $record1->created_count);
        $this->assertSame(1, User::where('email', 'idem1@example.com')->count());

        // Run kedua — data sama (email beda kapitalisasi sekalipun tetap idempoten)
        $rowsRun2 = [
            ['name' => 'Siswa Idem Baru', 'email' => 'IDEM1@example.com', 'role' => 'guru'],
            ['name' => 'Siswa Idem Dua', 'email' => 'idem2@example.com', 'role' => 'siswa'],
        ];
        Storage::disk('local')->put('imports/idem-2.xlsx', $this->xlsxContent($rowsRun2));
        $record2 = UserImport::create([
            'user_id' => $admin->id,
            'file_name' => 'idem-2.xlsx',
            'file_path' => 'imports/idem-2.xlsx',
            'status' => UserImport::STATUS_PENDING,
        ]);
        (new ImportUsersJob($record2))->handle();

        $record2->refresh();
        $this->assertSame('completed', $record2->status);
        $this->assertSame(0, $record2->created_count);
        $this->assertSame(2, $record2->updated_count);
        $this->assertSame(0, $record2->failed_count);

        // Jumlah user tidak berubah (tanpa duplikat)
        $this->assertSame(1, User::where('email', 'idem1@example.com')->count());
        $this->assertSame(1, User::where('email', 'idem2@example.com')->count());

        // Update diterapkan: nama + role berubah sesuai file kedua
        $updated = User::where('email', 'idem1@example.com')->first();
        $this->assertSame('Siswa Idem Baru', $updated->name);
        $this->assertTrue($updated->hasRole('guru'));
        $this->assertFalse($updated->hasRole('siswa'));
    }

    public function test_file_rusak_membuat_record_failed_dan_file_dihapus(): void
    {
        Storage::fake('local');

        $admin = $this->createSuperadmin();

        // Bukan xlsx valid — reader akan melempar exception
        Storage::disk('local')->put('imports/broken.xlsx', 'ini bukan file xlsx yang valid >>>');

        $record = UserImport::create([
            'user_id' => $admin->id,
            'file_name' => 'broken.xlsx',
            'file_path' => 'imports/broken.xlsx',
            'status' => UserImport::STATUS_PENDING,
        ]);

        (new ImportUsersJob($record))->handle();

        $record->refresh();

        $this->assertSame('failed', $record->status);
        $this->assertNotNull($record->errors);
        $this->assertStringContainsString('Import gagal', $record->errors[0]);
        $this->assertNotNull($record->finished_at);

        // File sementara dihapus meski gagal
        $this->assertFalse(Storage::disk('local')->exists('imports/broken.xlsx'));
    }

    public function test_user_non_superadmin_ditolak_mengakses_route_import(): void
    {
        Storage::fake('local');

        $siswa = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $siswa->assignRole('siswa');

        $file = $this->createUploadFile([
            ['name' => 'Siswa', 'email' => 'blocked@example.com', 'role' => 'siswa'],
        ]);

        $response = $this->actingAs($siswa)->post(
            route('admin.superadmin.users.processImport'),
            ['file' => $file]
        );

        $response->assertForbidden();

        $this->assertSame(0, UserImport::count());
        $this->assertSame(0, User::where('email', 'blocked@example.com')->count());
    }

    public function test_handle_job_menandai_processing_sebelum_selesai(): void
    {
        Storage::fake('local');

        $admin = $this->createSuperadmin();

        Storage::disk('local')->put('imports/status-track.xlsx', $this->xlsxContent([
            ['name' => 'Siswa Status', 'email' => 'status@example.com', 'role' => 'siswa'],
        ]));

        $record = UserImport::create([
            'user_id' => $admin->id,
            'file_name' => 'status-track.xlsx',
            'file_path' => 'imports/status-track.xlsx',
            'status' => UserImport::STATUS_PENDING,
        ]);

        $observed = [];
        $record->updating(function (UserImport $model) use (&$observed) {
            $observed[] = $model->status;
        });

        (new ImportUsersJob($record))->handle();

        $this->assertContains(UserImport::STATUS_PROCESSING, $observed);
        $this->assertSame(UserImport::STATUS_COMPLETED, $record->refresh()->status);
    }
}
