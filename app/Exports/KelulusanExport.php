<?php

namespace App\Exports;

use App\Models\Kelulusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KelulusanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $kelulusans;

    public function __construct($kelulusans)
    {
        $this->kelulusans = $kelulusans;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->kelulusans;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama',
            'NISN',
            'NIS',
            'Jurusan',
            'Tahun Ajaran',
            'Status',
            'Tempat Kuliah',
            'Tempat Kerja',
            'Jurusan Kuliah',
            'Jabatan Kerja',
            'No HP',
            'No WA',
            'Alamat',
            'Prestasi',
            'Catatan',
            'Tanggal Lulus',
        ];
    }

    /**
     * @param Kelulusan $kelulusan
     * @return array
     */
    public function map($kelulusan): array
    {
        return [
            $kelulusan->nama,
            $kelulusan->nisn,
            $kelulusan->nis,
            $kelulusan->jurusan,
            $kelulusan->tahun_ajaran,
            $kelulusan->status,
            $kelulusan->tempat_kuliah,
            $kelulusan->tempat_kerja,
            $kelulusan->jurusan_kuliah,
            $kelulusan->jabatan_kerja,
            $kelulusan->no_hp,
            $kelulusan->no_wa,
            $kelulusan->alamat,
            $kelulusan->prestasi,
            $kelulusan->catatan,
            $kelulusan->tanggal_lulus?->format('Y-m-d'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 15,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 25,
            'H' => 25,
            'I' => 20,
            'J' => 20,
            'K' => 15,
            'L' => 15,
            'M' => 30,
            'N' => 20,
            'O' => 30,
            'P' => 15,
        ];
    }
}
