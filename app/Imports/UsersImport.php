<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Import user via queue dengan perilaku UPSERT idempoten (superadmin).
 *
 * Unique key: email (lowercase, trim).
 * - Email sudah ada di database -> update (nama, password bila diisi,
 *   email_verified_at, is_verified_by_admin, role via Spatie).
 * - Belum ada -> create baru + assignRole (default 'siswa' bila role kosong).
 * - Baris invalid dilewati dengan pesan per baris (SkipsOnFailure/SkipsOnError).
 *
 * Kolom template: name, email, role, password, email_verified_at, is_verified_by_admin.
 *
 * Berbeda dengan App\Imports\UserImport (kelas lama yang dipakai BulkImportController),
 * kelas ini TIDAK dimodifikasi dan hanya dipakai oleh ImportUsersJob.
 *
 * Catatan: sengaja TANPA WithBatchInserts agar setiap baris disimpan per-row
 * (bcrypt + assignRole berat; satu baris bermasalah tidak menjatuhkan chunk).
 */
class UsersImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithChunkReading, WithHeadingRow, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    protected int $createdCount = 0;

    protected int $updatedCount = 0;

    /**
     * Nomor baris asli di sheet (1-based, termasuk baris heading) untuk baris
     * yang sedang diproses — dipakai agar pesan error menyebut nomor baris.
     */
    protected int $currentRowNumber = 0;

    /**
     * Normalisasi data baris SEBELUM validasi oleh Maatwebsite Excel.
     *
     * Menangani masalah yang menyebabkan kegagalan massal pada import:
     * - spasi/whitespace di awal-takhir field (terutama email) yang membuat rule `email` gagal;
     * - kapitalisasi berlebih pada email/role;
     * - email_verified_at berupa serial number Excel (angka) atau objek DateTime;
     * - is_verified_by_admin berupa ya/tidak/1/0/true/false;
     * - karakter BOM hasil export CSV.
     *
     * @param  int  $rowNumber  nomor baris asli di sheet (untuk pesan error per baris)
     */
    public function prepareForValidation(array $row, int $rowNumber = 0): array
    {
        if ($rowNumber > 0) {
            $this->currentRowNumber = $rowNumber;
        }

        // Bersihkan karakter BOM (umum pada CSV hasil export Excel)
        foreach ($row as $key => $value) {
            if (is_string($value)) {
                $row[$key] = preg_replace('/^\xEF\xBB\xBF/', '', $value);
            }
        }

        // Trim semua kolom teks; string kosong -> null (agar lolos rule nullable yang benar)
        $stringFields = ['name', 'email', 'role', 'password', 'email_verified_at', 'is_verified_by_admin'];
        foreach ($stringFields as $field) {
            if (isset($row[$field]) && is_string($row[$field])) {
                $trimmed = trim($row[$field]);
                $row[$field] = $trimmed === '' ? null : $trimmed;
            }
        }

        // Email dinormalkan ke huruf kecil agar lookup upsert konsisten
        if (isset($row['email']) && is_string($row['email'])) {
            $row['email'] = strtolower($row['email']);
        }

        // Konversi scalar (mis. Excel numeric) menjadi string agar bisa dinormalkan
        foreach (['role', 'password', 'email_verified_at', 'is_verified_by_admin'] as $field) {
            if (isset($row[$field]) && ! is_string($row[$field]) && ! is_bool($row[$field])) {
                $row[$field] = (string) $row[$field];
            }
        }

        // Normalisasi role agar lolos validasi rules
        if (isset($row['role']) && is_string($row['role'])) {
            $roleMap = [
                'admin' => 'admin',
                'administrator' => 'admin',
                'guru' => 'guru',
                'teacher' => 'guru',
                'siswa' => 'siswa',
                'student' => 'siswa',
                'sarpras' => 'sarpras',
                'superadmin' => 'superadmin',
            ];
            $roleLower = strtolower($row['role']);
            // Nilai tak dikenal dibiarkan apa adanya: validasi menolak dengan pesan jelas per baris
            if (isset($roleMap[$roleLower])) {
                $row['role'] = $roleMap[$roleLower];
            }
        }

        // email_verified_at: serial Excel / DateTime object / string -> Carbon|null
        // Nilai tak terbaca dipertahankan apa adanya sehingga model() melempar error per baris.
        if (array_key_exists('email_verified_at', $row)) {
            $row['email_verified_at'] = $this->normalizeDateTime($row['email_verified_at']);
        }

        // is_verified_by_admin: yes/y/1/true -> true; no/n/0/false -> false; kosong -> null
        if (array_key_exists('is_verified_by_admin', $row)) {
            $row['is_verified_by_admin'] = $this->normalizeBoolean($row['is_verified_by_admin']);
        }

        return $row;
    }

    /**
     * Proses per baris: UPSERT by email (lowercase, trim).
     *
     * Semua jalur menyimpan di dalam method dan mengembalikan null agar
     * Maatwebsite Excel tidak membuat record duplikat.
     */
    public function model(array $row): ?User
    {
        // Default password dari config (fallback = password123, konsisten dengan template)
        $defaultPassword = config('app.default_user_password', 'password123');

        $email = null;
        if (isset($row['email']) && is_string($row['email'])) {
            $email = strtolower(trim($row['email']));
            if ($email === '') {
                $email = null;
            }
        }

        // Cari user yang sudah ada berdasarkan email (unique key upsert)
        $user = $email !== null ? User::where('email', $email)->first() : null;

        if ($user) {
            // Email existing yang sudah dipakai user lain -> tolak dengan pesan jelas
            if ($email !== null && $email !== strtolower($user->email)) {
                $exists = User::where('email', $email)->where('id', '!=', $user->id)->exists();
                if ($exists) {
                    throw new \Exception("Email '{$email}' sudah digunakan oleh pengguna lain.");
                }
                $user->email = $email;
            }

            $user->name = $row['name'] ?? $user->name;

            // Password HANYA diganti bila kolom password diisi pada file
            if (isset($row['password']) && $row['password'] !== '' && $row['password'] !== null) {
                $user->password = Hash::make($row['password']);
            }

            // Kolom verifikasi hanya ditimpa bila file menyediakan nilainya
            // (re-import dengan kolom kosong tidak menghapus status verifikasi lama).
            if (array_key_exists('email_verified_at', $row) && $row['email_verified_at'] !== null) {
                $user->email_verified_at = $this->parseDateTimeOrThrow($row['email_verified_at']);
            }

            if (array_key_exists('is_verified_by_admin', $row) && $row['is_verified_by_admin'] !== null) {
                $user->is_verified_by_admin = (bool) $row['is_verified_by_admin'];
            }

            $user->save();

            // Role di-sync hanya bila kolom role diisi pada file
            if (! empty($row['role']) && is_string($row['role'])) {
                $roleModel = Role::firstOrCreate(
                    ['name' => $row['role'], 'guard_name' => 'web'],
                );
                $user->syncRoles([$roleModel]);
            }

            $this->updatedCount++;

            return null; // record existing di-update di tempat, bukan dibuat baru
        }

        // User baru: password dari kolom atau default
        $password = $defaultPassword;
        if (isset($row['password']) && $row['password'] !== '' && $row['password'] !== null) {
            $password = $row['password'];
        }

        $newUser = new User([
            'name' => $row['name'] ?? null,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => $row['email_verified_at'] ?? null,
            // Kolom is_verified_by_admin NOT NULL di schema telkom -> default false
            'is_verified_by_admin' => $row['is_verified_by_admin'] ?? false,
        ]);

        // Simpan di sini (bukan oleh Maatwebsite) karena assignRole butuh id.
        $newUser->save();

        $roleName = (! empty($row['role']) && is_string($row['role'])) ? $row['role'] : 'siswa';
        $roleModel = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $newUser->assignRole($roleModel);

        $this->createdCount++;

        return null; // sudah disimpan + di-assign role di atas
    }

    /**
     * Tangani error penyimpanan per baris dengan menyebut nomor baris sheet.
     */
    public function onError(\Throwable $e): void
    {
        $prefix = $this->currentRowNumber > 0 ? "Baris {$this->currentRowNumber}: " : '';
        $this->errors[] = new \RuntimeException($prefix.$e->getMessage(), (int) $e->getCode(), $e);
    }

    /**
     * Rules validasi per baris. Baris yang gagal validasi dilewati (SkipsOnFailure)
     * dengan pesan per baris yang bisa dibaca.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|in:superadmin,admin,guru,siswa,sarpras',
            'password' => 'nullable|string|min:6|max:100',
            'email_verified_at' => 'nullable',
            'is_verified_by_admin' => 'nullable|boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'role.in' => 'Role harus salah satu dari: superadmin, admin, guru, siswa, sarpras',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 100 karakter',
        ];
    }

    /**
     * Konversi berbagai bentuk tanggal verifikasi email menjadi Carbon|null.
     * Serial number Excel dan objek DateTime dikonversi; string kosong -> null;
     * string yang tidak terbaca dipertahankan agar model() melempar error per baris.
     */
    private function normalizeDateTime(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        // Serial number Excel (contoh: 45292)
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value);
            } catch (\Exception $e) {
                return $value; // dipertahankan -> error per baris di model()
            }
        }

        return $value; // string apa adanya; parse ketat dilakukan di model()
    }

    /**
     * Parse ketat tanggal verifikasi; melempar exception bila tidak valid
     * agar baris tercatat gagal dengan pesan jelas (ditangkap SkipsOnError).
     */
    private function parseDateTimeOrThrow(mixed $value): \DateTimeInterface
    {
        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value);
        }

        if (is_string($value)) {
            try {
                return Carbon::parse($value);
            } catch (\Exception $e) {
                foreach (['d/m/Y H:i:s', 'd/m/Y', 'd-m-Y H:i:s', 'd-m-Y', 'd.m.Y'] as $format) {
                    try {
                        return Carbon::createFromFormat($format, $value);
                    } catch (\Exception $ignored) {
                        // lanjut ke format berikutnya
                    }
                }

                throw new \Exception("Format 'email_verified_at' tidak valid: '{$value}' (gunakan YYYY-MM-DD HH:MM:SS)");
            }
        }

        throw new \Exception("Format 'email_verified_at' tidak valid: '".(string) $value."'");
    }

    /**
     * Normalisasi boolean fleksibel untuk kolom is_verified_by_admin.
     */
    private function normalizeBoolean(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['yes', 'y', '1', 'true', 'ya'], true)) {
            return true;
        }

        if (in_array($normalized, ['no', 'n', '0', 'false', 'tidak'], true)) {
            return false;
        }

        return null; // nilai tak dikenal diperlakukan sebagai tidak diisi
    }

    /**
     * Get import statistics.
     *
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        $failureMessages = $this->failures()->map(function ($failure) {
            $field = $failure->attribute();
            $parts = array_map('strval', $failure->errors());

            return "Baris {$failure->row()} ({$field}) — ".implode('; ', $parts);
        })->values()->all();

        $errorMessages = $this->errors()->map(function ($error) {
            return Str::limit($error->getMessage(), 300);
        })->values()->all();

        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'imported' => $this->createdCount + $this->updatedCount,
            'skipped' => $this->failures()->count() + $this->errors()->count(),
            'failure_messages' => $failureMessages,
            'error_messages' => $errorMessages,
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
