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
        // Normalize NISN: trim whitespace
        $nisn = trim($row['nisn'] ?? '');

        if (empty($nisn)) {
            return null;
        }

        // Check if record already exists by NISN
        $existing = Kelulusan::where('nisn', $nisn)->first();

        if ($existing) {
            // Skip duplicate or update existing record
            Log::info("Skipping duplicate NISN: {$nisn} for {$row['nama']}");
            return null;
        }

        // Normalize tahun_ajaran: accept "2025/2026" format → store as string, or integer
        $tahunAjaran = trim($row['tahun_ajaran'] ?? '');
        // If format is "YYYY/YYYY", keep as-is (string). If pure integer, cast to int.
        if (is_numeric($tahunAjaran)) {
            $tahunAjaran = (int) $tahunAjaran;
        }

        // Normalize status: lowercase, trim
        $status = strtolower(trim($row['status'] ?? ''));
        // Map common variations
        $statusMap = [
            'lulus'       => 'lulus',
            'tidak lulus' => 'tidak_lulus',
            'tidak_lulus' => 'tidak_lulus',
            'mengulang'   => 'mengulang',
            'ulang'       => 'mengulang',
        ];
        $status = $statusMap[$status] ?? $status;

        // Parse tanggal_lulus if provided
        $tanggalLulus = null;
        if (!empty($row['tanggal_lulus']) && trim($row['tanggal_lulus']) !== '-') {
            try {
                $tanggalLulus = \Carbon\Carbon::parse(trim($row['tanggal_lulus']));
            } catch (\Exception $e) {
                Log::warning("Invalid date format for tanggal_lulus: {$row['tanggal_lulus']}");
            }
        }

        // Normalize NIS: skip if empty or "-"
        $nis = !empty($row['nis']) && trim($row['nis']) !== '-' ? trim($row['nis']) : null;

        $this->rowCount++;

        return new Kelulusan([
            'nama' => trim($row['nama']),
            'nisn' => $nisn,
            'nis' => $nis,
            'jurusan' => !empty($row['jurusan']) && trim($row['jurusan']) !== '-' ? trim($row['jurusan']) : null,
            'tahun_ajaran' => $tahunAjaran,
            'status' => $status,
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
            '*.nama'          => 'required|string|max:255',
            '*.nisn'          => 'required|string|max:20',
            '*.nis'           => 'nullable|string|max:30',
            // tahun_ajaran accepts integer OR "YYYY/YYYY" string format
            '*.tahun_ajaran'  => 'required',
            // status is normalized to lowercase before validation, so this covers all cases
            '*.status'        => 'required|string',
            '*.jurusan'       => 'nullable|string|max:100',
            '*.tempat_kuliah' => 'nullable|string|max:255',
            '*.tempat_kerja'  => 'nullable|string|max:255',
            '*.jurusan_kuliah' => 'nullable|string|max:255',
            '*.jabatan_kerja' => 'nullable|string|max:255',
            '*.no_hp'         => 'nullable|string|max:20',
            '*.no_wa'         => 'nullable|string|max:20',
            '*.alamat'        => 'nullable|string',
            '*.prestasi'      => 'nullable|string',
            '*.catatan'       => 'nullable|string',
            '*.tanggal_lulus' => 'nullable',
        ];
    }

    /**
     * Prepare each row before validation — normalize values.
     */
    public function prepareForValidation(array $data, int $index): array
    {
        // Normalize status to lowercase
        if (isset($data['status'])) {
            $data['status'] = strtolower(trim($data['status']));
            $statusMap = [
                'lulus'       => 'lulus',
                'tidak lulus' => 'tidak_lulus',
                'tidak_lulus' => 'tidak_lulus',
                'mengulang'   => 'mengulang',
            ];
            $data['status'] = $statusMap[$data['status']] ?? $data['status'];
        }

        // Normalize dash values to null
        foreach (
            [
                'nis',
                'jurusan',
                'tempat_kuliah',
                'tempat_kerja',
                'jurusan_kuliah',
                'jabatan_kerja',
                'no_hp',
                'no_wa',
                'alamat',
                'prestasi',
                'catatan',
                'tanggal_lulus'
            ] as $field
        ) {
            if (isset($data[$field]) && trim($data[$field]) === '-') {
                $data[$field] = null;
            }
        }

        return $data;
    }
}
