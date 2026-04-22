<?php

namespace App\Imports;

use App\Models\Pemilih;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PemilihImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
            Log::info("Processing pemilih row", ['email' => $row['email'] ?? 'N/A', 'nama' => $row['nama'] ?? 'N/A']);

            // Check if pemilih already exists by email
            $existing = Pemilih::where('email', trim($row['email']))->first();

            if ($existing) {
                Log::info("Skipping duplicate email: {$row['email']} for {$row['nama']}");
                return null;
            }

            // Try to find user by email
            $userId = null;
            if (!empty($row['email'])) {
                $user = User::where('email', trim($row['email']))->first();
                if ($user) {
                    $userId = $user->id;
                }
            }

            // Handle gender conversion
            $jenisKelamin = null;
            if (!empty($row['jenis_kelamin'])) {
                $jenisKelamin = trim($row['jenis_kelamin']);
                if (in_array(strtolower($jenisKelamin), ['laki-laki', 'laki_laki', 'laki', 'l'])) {
                    $jenisKelamin = 'L';
                } elseif (in_array(strtolower($jenisKelamin), ['perempuan', 'p'])) {
                    $jenisKelamin = 'P';
                }
            }

            // Handle status conversion
            $status = 'active';
            if (!empty($row['status'])) {
                $statusInput = strtolower(trim($row['status']));
                if (in_array($statusInput, ['active', 'aktif', '1', 'yes'])) {
                    $status = 'active';
                } elseif (in_array($statusInput, ['inactive', 'tidak_aktif', 'tidak aktif', '0', 'no'])) {
                    $status = 'inactive';
                }
            }

            $this->rowCount++;

            Log::info("Creating pemilih", ['email' => trim($row['email']), 'nama' => trim($row['nama'])]);

            return new Pemilih([
                'user_id' => $userId,
                'nama' => trim($row['nama']),
                'email' => trim($row['email']),
                'user_type' => !empty($row['user_type']) ? trim($row['user_type']) : 'siswa',
                'jenis_kelamin' => $jenisKelamin,
                'kelas_jabatan' => !empty($row['kelas_jabatan']) ? trim($row['kelas_jabatan']) : null,
                'status' => $status,
                'has_voted' => false, // Default belum memilih
            ]);
        } catch (\Exception $e) {
            Log::error("Error creating pemilih", [
                'email' => $row['email'] ?? 'N/A',
                'nama' => $row['nama'] ?? 'N/A',
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
            '*.nama' => 'required|string|max:255',
            '*.email' => 'required|email|max:255',
            '*.user_type' => 'nullable|in:guru,siswa',
            '*.jenis_kelamin' => 'nullable|in:L,P,Laki-laki,Perempuan,laki-laki,perempuan,l,p',
            '*.kelas_jabatan' => 'nullable|string|max:100',
            '*.status' => 'nullable|in:active,inactive,aktif,tidak_aktif,yes,no,1,0',
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
