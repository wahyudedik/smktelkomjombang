<?php

namespace App\Exports;

use App\Models\Calon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalonExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $calons;

    public function __construct($calons)
    {
        $this->calons = $calons;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->calons;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Ketua',
            'Nama Wakil',
            'Jenis Kelamin',
            'Visi Misi',
            'Jenis Pencalonan',
            'Status Aktif',
            'Total Suara',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Calon $calon
     * @return array
     */
    public function map($calon): array
    {
        return [
            $calon->nama_ketua,
            $calon->nama_wakil,
            $calon->gender_display,
            $calon->visi_misi,
            $calon->jenis_pencalonan,
            $calon->is_active ? 'Aktif' : 'Tidak Aktif',
            $calon->votings_count ?? 0,
            $calon->created_at?->format('Y-m-d H:i:s'),
            $calon->updated_at?->format('Y-m-d H:i:s'),
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
            'B' => 25,
            'C' => 50,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 20,
        ];
    }
}
