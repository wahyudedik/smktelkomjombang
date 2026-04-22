<?php

namespace App\Imports;

use App\Models\Calon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CalonImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        // Check if calon already exists by nama_ketua and nama_wakil combination
        $existing = Calon::where('nama_ketua', $row['nama_ketua'])
            ->where('nama_wakil', $row['nama_wakil'])
            ->first();

        if ($existing) {
            Log::info("Skipping duplicate calon: {$row['nama_ketua']} - {$row['nama_wakil']}");
            return null;
        }

        $this->rowCount++;

        // Handle gender conversion
        $jenisKelamin = trim($row['jenis_kelamin']);
        if (in_array(strtolower($jenisKelamin), ['laki-laki', 'laki_laki', 'laki'])) {
            $jenisKelamin = 'L';
        } elseif (in_array(strtolower($jenisKelamin), ['perempuan', 'perempuan'])) {
            $jenisKelamin = 'P';
        }

        return new Calon([
            'nama_ketua' => trim($row['nama_ketua']),
            'nama_wakil' => !empty($row['nama_wakil']) ? trim($row['nama_wakil']) : null,
            'jenis_kelamin' => $jenisKelamin,
            'visi_misi' => !empty($row['visi_misi']) ? trim($row['visi_misi']) : 'Belum ada visi misi',
            'jenis_pencalonan' => !empty($row['jenis_pencalonan']) ? trim($row['jenis_pencalonan']) : 'pasangan',
            'is_active' => isset($row['status_aktif']) ?
                (strtolower($row['status_aktif']) === 'aktif' || $row['status_aktif'] === '1') : true,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_ketua' => 'required|string|max:255',
            '*.nama_wakil' => 'nullable|string|max:255',
            '*.jenis_kelamin' => 'nullable|in:L,P,Laki-laki,Perempuan',
            '*.visi_misi' => 'nullable|string',
            '*.jenis_pencalonan' => 'nullable|in:ketua,wakil,pasangan',
            '*.status_aktif' => 'nullable|in:aktif,tidak_aktif,yes,no,1,0',
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
