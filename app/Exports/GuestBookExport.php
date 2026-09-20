<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuestBookExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $guests;

    protected int $rowNumber = 0;

    public function __construct($guests)
    {
        $this->guests = $guests;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->guests;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'No. Tiket',
            'Nama Tamu',
            'NIK',
            'Instansi',
            'Jabatan',
            'Kategori',
            'Tujuan',
            'Status',
            'Jam Masuk',
            'Jam Keluar',
            'Durasi',
            'Dibuat Oleh',
        ];
    }

    /**
     * @param mixed $guest
     * @return array
     */
    public function map($guest): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $guest->ticket_number,
            $guest->guest_name,
            $guest->nik ?? '-',
            $guest->organization ?? '-',
            $guest->position ?? '-',
            $this->getCategoryLabel($guest->visit_category),
            $guest->visit_purpose ?? '-',
            $this->getStatusLabel($guest->status),
            $guest->check_in_at?->format('d/m/Y H:i') ?? '-',
            $guest->check_out_at?->format('d/m/Y H:i') ?? '-',
            $guest->duration ?? '-',
            $guest->creator?->name ?? '-',
        ];
    }

    /**
     * Map visit_category key to human-readable label.
     */
    private function getCategoryLabel(string $category): string
    {
        return match ($category) {
            'ppdb'       => 'PPDB',
            'ortu_wali'  => 'Orang Tua/Wali',
            'konsultasi' => 'Konsultasi',
            'dinas'      => 'Dinas',
            'supplier'   => 'Supplier',
            'acara'      => 'Acara',
            'lainnya'    => 'Lainnya',
            default      => $category,
        };
    }

    /**
     * Map status key to human-readable label.
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'check_in'   => 'Sedang Berkunjung',
            'check_out'  => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => $status,
        };
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
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
            'A' => 5,
            'B' => 18,
            'C' => 25,
            'D' => 18,
            'E' => 25,
            'F' => 20,
            'G' => 15,
            'H' => 30,
            'I' => 18,
            'J' => 18,
            'K' => 18,
            'L' => 15,
            'M' => 20,
        ];
    }
}
