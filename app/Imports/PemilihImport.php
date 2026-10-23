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

            // Handle status conversion (schema enum: belum_memilih / sudah_memilih)
            $status = 'belum_memilih';
            if (!empty($row['status'])) {
                $statusInput = strtolower(trim($row['status']));
                if (in_array($statusInput, ['sudah_memilih', 'sudah memilih', 'active', 'aktif', 'ya', 'yes', '1'], true)) {
                    $status = 'sudah_memilih';
                } elseif (in_array($statusInput, ['belum_memilih', 'belum memilih', 'inactive', 'tidak_aktif', 'tidak aktif', 'tidak', 'no', '0'], true)) {
                    $status = 'belum_memilih';
                }
            }

            $userType = !empty($row['user_type']) ? strtolower(trim($row['user_type'])) : 'siswa';
            if (!in_array($userType, ['siswa', 'guru'], true)) {
                $userType = 'siswa';
            }

            // Accept both 'kelas' and legacy 'kelas_jabatan' headings
            $kelas = !empty($row['kelas'])
                ? trim($row['kelas'])
                : (!empty($row['kelas_jabatan']) ? trim($row['kelas_jabatan']) : '-');

            // Guru rows always use kelas 'Guru' (same rule as manual create on the server)
            if ($userType === 'guru') {
                $kelas = 'Guru';
            }

            // Optional `nis` column: NIS for siswa / NIP for guru.
            // Backward compatible — old files without the column get NULL.
            // Sel numerik Excel terbaca int/float — dinormalkan lewat
            // normalizeNis() agar tidak pernah berakhir sebagai notasi ilmiah.
            $nis = $this->normalizeNis($row['nis'] ?? null);

            $this->rowCount++;

            Log::info("Creating pemilih", ['email' => trim($row['email']), 'nama' => trim($row['nama'])]);

            return new Pemilih([
                'user_id' => $userId,
                'nama' => trim($row['nama']),
                'nis' => $nis,
                'email' => trim($row['email']),
                'user_type' => $userType,
                'jenis_kelamin' => $jenisKelamin,
                'kelas' => $kelas, // kelas column is NOT NULL
                'status' => $status,
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
     * Normalisasi nilai kolom `nis` (NIS siswa / NIP guru) menjadi string utuh.
     *
     * Sel numerik Excel terbaca sebagai int/float. Cast `(string) $float` di
     * PHP >= 8.0 mengikuti `precision` (default 14) sehingga NIP 18 digit bisa
     * berubah jadi notasi ilmiah ("1.9870312201001E+17") dan verifikasi publik
     * (exact match ke pemilihs.nis) gagal. Bilangan bulat (NIS/NIP) selalu
     * ditulis penuh tanpa notasi ilmiah; nilai kosong/non-scalar → NULL agar
     * file lama tanpa kolom `nis` tetap kompatibel.
     */
    private function normalizeNis(mixed $value): ?string
    {
        if (!is_scalar($value) || is_bool($value)) {
            return null;
        }

        if (is_float($value)) {
            if (!is_finite($value)) {
                return null;
            }
            // NIS/NIP bilangan bulat: number_format(..., 0) membuang bagian
            // desimal tanpa pernah memakai notasi ilmiah, dan tetap aman
            // melebihi rentang int64.
            $value = number_format($value, 0, '', '');
        } elseif (is_int($value)) {
            // int64 PHP (maks ±9.2×10^18) muat untuk NIP 18 digit (~2×10^17).
            $value = (string) $value;
        } else {
            $value = trim((string) $value);
            // Buang sisa ".0" hasil ekspor spreadsheet ("24001.0" → "24001");
            // nilai berawalan 0 (mis. "0024001") tidak disentuh.
            if (preg_match('/^[+-]?\d+\.0+$/', $value) === 1) {
                $value = preg_replace('/\.0+$/', '', $value);
            }
        }

        return $value !== '' ? $value : null;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama' => 'required|string|max:255',
            // NIS (siswa) / NIP (guru) — opsional, backward compatible.
            // Sengaja TANPA batasan tipe: sel numerik Excel terbaca sebagai int
            // / float (NIS/NIP tanpa format "Text") — nilai dinormalisasi ke
            // string utuh oleh normalizeNis() di model().
            '*.nis' => 'nullable',
            '*.email' => 'required|email|max:255',
            '*.user_type' => 'nullable|in:guru,siswa',
            '*.jenis_kelamin' => 'nullable|in:L,P,Laki-laki,Perempuan,laki-laki,perempuan,l,p',
            '*.kelas' => 'nullable|string|max:100',
            '*.kelas_jabatan' => 'nullable|string|max:100', // legacy heading support
            '*.status' => 'nullable|in:active,inactive,aktif,tidak_aktif,yes,no,1,0,sudah_memilih,belum_memilih,ya,tidak',
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
