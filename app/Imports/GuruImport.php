<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $rowCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            Log::info("Processing row", ['nip' => $row['nip'] ?? 'N/A', 'nama' => $row['nama_lengkap'] ?? 'N/A']);

            // Check if guru already exists by NIP
            $existing = Guru::where('nip', $row['nip'])->first();

            if ($existing) {
                Log::info("Skipping duplicate NIP: {$row['nip']} for {$row['nama_lengkap']}");
                return null;
            }

            // Create user account if email provided
            $userId = null;
            if (!empty($row['email'])) {
                // Check if email already exists
                $existingUser = User::where('email', trim($row['email']))->first();

                if ($existingUser) {
                    Log::warning("Skipping user creation, email already exists: {$row['email']}");
                    $userId = $existingUser->id; // Link to existing user
                } else {
                    $user = User::create([
                        'name' => trim($row['nama_lengkap']),
                        'email' => trim($row['email']),
                        'password' => Hash::make($row['password'] ?? 'password123'),
                        'user_type' => 'guru',
                        'email_verified_at' => now(),
                        'is_verified_by_admin' => true,
                    ]);
                    $userId = $user->id;
                }
            }

            // Convert mata_pelajaran string to array
            $mataPelajaran = [];
            if (!empty($row['mata_pelajaran'])) {
                $mataPelajaran = array_map('trim', explode(',', $row['mata_pelajaran']));
            }

            // Parse dates
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir']);
                } catch (\Exception $e) {
                    Log::warning("Invalid date format for tanggal_lahir: {$row['tanggal_lahir']}");
                }
            }

            $tanggalMasuk = null;
            if (!empty($row['tanggal_masuk'])) {
                try {
                    $tanggalMasuk = \Carbon\Carbon::parse($row['tanggal_masuk']);
                } catch (\Exception $e) {
                    Log::warning("Invalid date format for tanggal_masuk: {$row['tanggal_masuk']}");
                }
            }

            $tanggalKeluar = null;
            if (!empty($row['tanggal_keluar'])) {
                try {
                    $tanggalKeluar = \Carbon\Carbon::parse($row['tanggal_keluar']);
                } catch (\Exception $e) {
                    Log::warning("Invalid date format for tanggal_keluar: {$row['tanggal_keluar']}");
                }
            }

            Log::info("Creating guru", ['nip' => trim($row['nip']), 'nama' => trim($row['nama_lengkap'])]);

            $guru = new Guru([
                'nip' => (string)trim($row['nip']),
                'nama_lengkap' => trim($row['nama_lengkap']),
                'gelar_depan' => !empty($row['gelar_depan']) ? trim($row['gelar_depan']) : null,
                'gelar_belakang' => !empty($row['gelar_belakang']) ? trim($row['gelar_belakang']) : null,
                'jenis_kelamin' => trim($row['jenis_kelamin']),
                'tanggal_lahir' => $tanggalLahir ?: now()->subYears(25), // Default 25 tahun lalu
                'tempat_lahir' => !empty($row['tempat_lahir']) ? trim($row['tempat_lahir']) : 'Tidak Diketahui',
                'alamat' => !empty($row['alamat']) ? trim($row['alamat']) : 'Alamat belum diisi',
                'no_telepon' => !empty($row['no_telepon']) ? trim($row['no_telepon']) : null,
                'no_wa' => !empty($row['no_wa']) ? trim($row['no_wa']) : null,
                'email' => !empty($row['email']) ? trim($row['email']) : null,
                'status_kepegawaian' => !empty($row['status_kepegawaian']) ? trim($row['status_kepegawaian']) : 'PNS',
                'jabatan' => !empty($row['jabatan']) ? trim($row['jabatan']) : null,
                'tanggal_masuk' => $tanggalMasuk ?: now(), // Default hari ini
                'tanggal_keluar' => $tanggalKeluar,
                'status_aktif' => !empty($row['status_aktif']) ? trim($row['status_aktif']) : 'aktif',
                'pendidikan_terakhir' => !empty($row['pendidikan_terakhir']) ? trim($row['pendidikan_terakhir']) : 'S1',
                'universitas' => !empty($row['universitas']) ? trim($row['universitas']) : 'Universitas',
                'tahun_lulus' => !empty($row['tahun_lulus']) ? trim($row['tahun_lulus']) : '2000',
                'sertifikasi' => !empty($row['sertifikasi']) ? trim($row['sertifikasi']) : null,
                'mata_pelajaran' => !empty($mataPelajaran) ? $mataPelajaran : ['Matematika'],
                'prestasi' => !empty($row['prestasi']) ? trim($row['prestasi']) : null,
                'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
                'user_id' => $userId,
            ]);

            $this->rowCount++;
            Log::info("Guru created successfully", ['nip' => $guru->nip, 'nama' => $guru->nama_lengkap]);
            return $guru;
        } catch (\Exception $e) {
            Log::error("Error creating guru", [
                'nip' => $row['nip'] ?? 'N/A',
                'nama' => $row['nama_lengkap'] ?? 'N/A',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nip' => 'required|max:20',
            '*.nama_lengkap' => 'required|string|max:255',
            '*.jenis_kelamin' => 'required|in:L,P',
            // Semua field lain dibuat nullable untuk memungkinkan import data minimal
            '*.tanggal_lahir' => 'nullable',
            '*.tempat_lahir' => 'nullable',
            '*.alamat' => 'nullable',
            '*.status_kepegawaian' => 'nullable',
            '*.tanggal_masuk' => 'nullable',
            '*.status_aktif' => 'nullable',
            '*.pendidikan_terakhir' => 'nullable',
            '*.universitas' => 'nullable',
            '*.tahun_lulus' => 'nullable',
            '*.email' => 'nullable',
            '*.tanggal_keluar' => 'nullable',
            '*.mata_pelajaran' => 'nullable',
            '*.no_telepon' => 'nullable',
            '*.no_wa' => 'nullable',
            '*.jabatan' => 'nullable',
            '*.sertifikasi' => 'nullable',
            '*.prestasi' => 'nullable',
            '*.catatan' => 'nullable',
            '*.gelar_depan' => 'nullable',
            '*.gelar_belakang' => 'nullable',
        ];
    }

    /**
     * Get the number of rows imported
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
