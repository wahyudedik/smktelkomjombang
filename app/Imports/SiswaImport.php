<?php

namespace App\Imports;

use App\Models\Siswa;
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

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
            Log::info("Processing siswa row", ['nis' => $row['nis'] ?? 'N/A', 'nama' => $row['nama_lengkap'] ?? 'N/A']);

            // Check if siswa already exists by NIS
            $existing = Siswa::where('nis', (string)$row['nis'])->first();

            if ($existing) {
                Log::info("Skipping duplicate NIS: {$row['nis']} for {$row['nama_lengkap']}");
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
                        'user_type' => 'siswa',
                        'email_verified_at' => null,
                        'is_verified_by_admin' => false,
                    ]);
                    $userId = $user->id;
                }
            }

            // Parse tanggal_lahir
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir']);
                } catch (\Exception $e) {
                    Log::warning("Invalid date format for tanggal_lahir: {$row['tanggal_lahir']}");
                }
            }

            $this->rowCount++;

            Log::info("Creating siswa", ['nis' => (string)$row['nis'], 'nama' => trim($row['nama_lengkap'])]);

            $siswa = new Siswa([
                'nis' => (string)trim($row['nis']),
                'nisn' => (string)trim($row['nisn']),
                'nama_lengkap' => trim($row['nama_lengkap']),
                'jenis_kelamin' => trim($row['jenis_kelamin']),
                'tanggal_lahir' => $tanggalLahir ?: now()->subYears(15), // Default 15 tahun lalu
                'tempat_lahir' => !empty($row['tempat_lahir']) ? trim($row['tempat_lahir']) : 'Tidak Diketahui',
                'alamat' => !empty($row['alamat']) ? trim($row['alamat']) : 'Alamat belum diisi',
                'no_telepon' => !empty($row['no_telepon']) ? trim($row['no_telepon']) : null,
                'no_wa' => !empty($row['no_wa']) ? trim($row['no_wa']) : null,
                'email' => !empty($row['email']) ? trim($row['email']) : null,
                'kelas' => !empty($row['kelas']) ? trim($row['kelas']) : null,
                'jurusan' => !empty($row['jurusan']) ? trim($row['jurusan']) : null,
                'tahun_masuk' => !empty($row['tahun_masuk']) ? (int)$row['tahun_masuk'] : date('Y'),
                'tahun_lulus' => !empty($row['tahun_lulus']) ? (int)$row['tahun_lulus'] : null,
                'status' => !empty($row['status']) ? trim($row['status']) : 'aktif',
                'nama_ayah' => !empty($row['nama_ayah']) ? trim($row['nama_ayah']) : null,
                'pekerjaan_ayah' => !empty($row['pekerjaan_ayah']) ? trim($row['pekerjaan_ayah']) : null,
                'nama_ibu' => !empty($row['nama_ibu']) ? trim($row['nama_ibu']) : null,
                'pekerjaan_ibu' => !empty($row['pekerjaan_ibu']) ? trim($row['pekerjaan_ibu']) : null,
                'no_telepon_ortu' => !empty($row['no_telepon_ortu']) ? trim($row['no_telepon_ortu']) : null,
                'alamat_ortu' => !empty($row['alamat_ortu']) ? trim($row['alamat_ortu']) : null,
                'prestasi' => !empty($row['prestasi']) ? trim($row['prestasi']) : null,
                'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
                'user_id' => $userId,
            ]);

            $this->rowCount++;
            Log::info("Siswa created successfully", ['nis' => $siswa->nis, 'nama' => $siswa->nama_lengkap]);
            return $siswa;
        } catch (\Exception $e) {
            Log::error("Error creating siswa", [
                'nis' => $row['nis'] ?? 'N/A',
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
            '*.nis' => 'required|max:20',
            '*.nisn' => 'required|max:20',
            '*.nama_lengkap' => 'required|string|max:255',
            '*.jenis_kelamin' => 'required|in:L,P',
            // Semua field lain dibuat nullable untuk memungkinkan import data minimal
            '*.tanggal_lahir' => 'nullable',
            '*.tempat_lahir' => 'nullable',
            '*.alamat' => 'nullable',
            '*.tahun_masuk' => 'nullable',
            '*.status' => 'nullable',
            '*.email' => 'nullable',
            '*.tahun_lulus' => 'nullable',
            '*.kelas' => 'nullable',
            '*.jurusan' => 'nullable',
            '*.no_telepon' => 'nullable',
            '*.no_wa' => 'nullable',
            '*.nama_ayah' => 'nullable',
            '*.pekerjaan_ayah' => 'nullable',
            '*.nama_ibu' => 'nullable',
            '*.pekerjaan_ibu' => 'nullable',
            '*.no_telepon_ortu' => 'nullable',
            '*.alamat_ortu' => 'nullable',
            '*.prestasi' => 'nullable',
            '*.catatan' => 'nullable',
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
