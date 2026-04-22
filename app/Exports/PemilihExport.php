<?php

namespace App\Exports;

use App\Models\Pemilih;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemilihExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $pemilihs;

    public function __construct($pemilihs)
    {
        $this->pemilihs = $pemilihs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->pemilihs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'User Type',
            'Jenis Kelamin',
            'Status',
            'Kelas/Jabatan',
            'Has Voted',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Pemilih $pemilih
     * @return array
     */
    public function map($pemilih): array
    {
        return [
            $pemilih->nama,
            $pemilih->email,
            $pemilih->user_type_display,
            $pemilih->gender_display ?? '-',
            $pemilih->status_display,
            $pemilih->kelas_jabatan ?? '-',
            $pemilih->has_voted ? 'Sudah' : 'Belum',
            $pemilih->created_at?->format('Y-m-d H:i:s'),
            $pemilih->updated_at?->format('Y-m-d H:i:s'),
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
            'A' => 30,
            'B' => 30,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 20,
            'G' => 12,
            'H' => 20,
            'I' => 20,
        ];
    }
}
