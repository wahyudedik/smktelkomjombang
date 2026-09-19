<?php

namespace App\Imports;

use App\Models\AttendanceIdentity;
use App\Models\Guru;
use App\Models\Siswa;
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

class AttendanceIdentityImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected int $rowCount = 0;
    protected int $successCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): ?AttendanceIdentity
    {
        try {
            $devicePin = trim($row['device_pin']);
            $kind = strtolower(trim($row['kind']));
            $referenceName = trim($row['reference_name']);

            Log::info("Processing attendance identity import", [
                'device_pin' => $devicePin,
                'kind' => $kind,
                'reference_name' => $referenceName,
            ]);

            // Cek duplikat PIN
            $existing = AttendanceIdentity::where('device_pin', $devicePin)->first();
            if ($existing) {
                Log::info("Skipping duplicate device_pin: {$devicePin}");
                return null;
            }

            // Lookup referensi berdasarkan kind
            $attributes = ['kind' => $kind];

            switch ($kind) {
                case 'user':
                    $user = User::where('name', $referenceName)->first();
                    if (!$user) {
                        Log::warning("User not found for name: {$referenceName}, skipping PIN {$devicePin}");
                        return null;
                    }
                    $attributes['user_id'] = $user->id;
                    break;

                case 'guru':
                    $guru = Guru::where('nama_lengkap', $referenceName)->first();
                    if (!$guru) {
                        Log::warning("Guru not found for name: {$referenceName}, skipping PIN {$devicePin}");
                        return null;
                    }
                    $attributes['guru_id'] = $guru->id;
                    break;

                case 'siswa':
                    $siswa = Siswa::where('nama_lengkap', $referenceName)->first();
                    if (!$siswa) {
                        Log::warning("Siswa not found for name: {$referenceName}, skipping PIN {$devicePin}");
                        return null;
                    }
                    $attributes['siswa_id'] = $siswa->id;
                    break;

                default:
                    Log::warning("Invalid kind: {$kind}, skipping PIN {$devicePin}");
                    return null;
            }

            $this->rowCount++;
            $this->successCount++;

            return new AttendanceIdentity([
                ...$attributes,
                'device_pin' => $devicePin,
                'is_active' => true,
            ]);
        } catch (\Exception $e) {
            Log::error("Error processing attendance identity import: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validation rules untuk import
     */
    public function rules(): array
    {
        return [
            'device_pin'     => 'required|string|max:64',
            'kind'           => 'required|string|in:user,guru,siswa',
            'reference_name' => 'required|string|max:255',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'device_pin.required'     => 'PIN device wajib diisi',
            'device_pin.max'          => 'PIN device maksimal 64 karakter',
            'kind.required'           => 'Jenis (kind) wajib diisi',
            'kind.in'                 => 'Jenis harus user, guru, atau siswa',
            'reference_name.required' => 'Nama referensi wajib diisi',
        ];
    }

    /**
     * Get total rows processed
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * Get successful import count
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}
