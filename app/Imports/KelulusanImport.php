<?php

namespace App\Imports;

use App\Models\Kelulusan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class KelulusanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        // Check if record already exists by NISN
        $existing = Kelulusan::where('nisn', $row['nisn'])->first();

        if ($existing) {
            // Skip duplicate or update existing record
            Log::info("Skipping duplicate NISN: {$row['nisn']} for {$row['nama']}");
            return null;
        }

        // Convert tahun_ajaran to integer if it's a string
        $tahunAjaran = is_numeric($row['tahun_ajaran']) ? (int)$row['tahun_ajaran'] : $row['tahun_ajaran'];

        // Parse tanggal_lulus if provided
        $tanggalLulus = null;
        if (!empty($row['tanggal_lulus'])) {
            try {
                $tanggalLulus = \Carbon\Carbon::parse($row['tanggal_lulus']);
            } catch (\Exception $e) {
                Log::warning("Invalid date format for tanggal_lulus: {$row['tanggal_lulus']}");
            }
        }

        $this->rowCount++;

        return new Kelulusan([
            'nama' => trim($row['nama']),
            'nisn' => trim($row['nisn']),
            'nis' => !empty($row['nis']) ? trim($row['nis']) : null,
            'jurusan' => !empty($row['jurusan']) ? trim($row['jurusan']) : null,
            'tahun_ajaran' => $tahunAjaran,
            'status' => trim($row['status']),
            'tempat_kuliah' => !empty($row['tempat_kuliah']) ? trim($row['tempat_kuliah']) : null,
            'tempat_kerja' => !empty($row['tempat_kerja']) ? trim($row['tempat_kerja']) : null,
            'jurusan_kuliah' => !empty($row['jurusan_kuliah']) ? trim($row['jurusan_kuliah']) : null,
            'jabatan_kerja' => !empty($row['jabatan_kerja']) ? trim($row['jabatan_kerja']) : null,
            'no_hp' => !empty($row['no_hp']) ? trim($row['no_hp']) : null,
            'no_wa' => !empty($row['no_wa']) ? trim($row['no_wa']) : null,
            'alamat' => !empty($row['alamat']) ? trim($row['alamat']) : null,
            'prestasi' => !empty($row['prestasi']) ? trim($row['prestasi']) : null,
            'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
            'tanggal_lulus' => $tanggalLulus,
            'is_active' => true,
        ]);
    }

    /**
     * Get the number of rows imported
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama' => 'required|string|max:255',
            '*.nisn' => 'required|string|max:20',
            '*.nis' => 'nullable|string|max:20',
            '*.tahun_ajaran' => 'required|integer|min:2000|max:' . date('Y'),
            '*.status' => 'required|in:lulus,tidak_lulus,mengulang',
            '*.jurusan' => 'nullable|string|max:100',
            '*.tempat_kuliah' => 'nullable|string|max:255',
            '*.tempat_kerja' => 'nullable|string|max:255',
            '*.jurusan_kuliah' => 'nullable|string|max:255',
            '*.jabatan_kerja' => 'nullable|string|max:255',
            '*.no_hp' => 'nullable|string|max:20',
            '*.no_wa' => 'nullable|string|max:20',
            '*.alamat' => 'nullable|string',
            '*.prestasi' => 'nullable|string',
            '*.catatan' => 'nullable|string',
            '*.tanggal_lulus' => 'nullable|date',
        ];
    }
}
