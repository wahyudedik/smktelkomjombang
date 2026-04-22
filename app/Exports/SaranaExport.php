<?php

namespace App\Exports;

use App\Models\Sarana;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SaranaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $saranas;

    public function __construct($saranas)
    {
        $this->saranas = $saranas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Flatten collection to include sub-barang rows
        $flattened = collect();
        
        foreach ($this->saranas as $sarana) {
            // Add sarana header row
            $flattened->push([
                'type' => 'sarana',
                'sarana' => $sarana,
                'barang' => null,
            ]);
            
            // Add barang rows for this sarana
            foreach ($sarana->barang as $barang) {
                $flattened->push([
                    'type' => 'barang',
                    'sarana' => $sarana,
                    'barang' => $barang,
                ]);
            }
        }
        
        return $flattened;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Inventaris',
            'Ruang',
            'Kode Ruang',
            'Nama Ruang',
            'Lokasi Ruang',
            'Sumber Dana',
            'Kode Sumber Dana',
            'Tanggal',
            'Catatan',
            'Barang - Nama',
            'Barang - Kode',
            'Barang - Kategori',
            'Barang - Jumlah',
            'Barang - Kondisi',
            'Barang - Harga Satuan',
            'Barang - Total Harga',
            'Barang - Merk',
            'Barang - Model',
            'Barang - Serial Number',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param array $row
     * @return array
     */
    public function map($row): array
    {
        $sarana = $row['sarana'];
        $barang = $row['barang'];
        
        if ($row['type'] === 'sarana') {
            // Sarana header row
            return [
                $sarana->kode_inventaris ?? '',
                $sarana->ruang->nama_ruang ?? '',
                $sarana->ruang->kode_ruang ?? '',
                $sarana->ruang->nama_ruang ?? '',
                $sarana->ruang->lokasi ?? '',
                $sarana->sumber_dana ?? '',
                $sarana->kode_sumber_dana ?? '',
                $sarana->tanggal ? $sarana->tanggal->format('Y-m-d') : '',
                $sarana->catatan ?? '',
                '=== ' . $sarana->barang->count() . ' BARANG ===',
                '',
                '',
                $sarana->barang->sum('pivot.jumlah'),
                '',
                '',
                '',
                '',
                '',
                '',
                $sarana->created_at?->format('Y-m-d H:i:s'),
                $sarana->updated_at?->format('Y-m-d H:i:s'),
            ];
        } else {
            // Barang detail row
            $hargaBeli = $barang->harga_beli ?? 0;
            $jumlah = $barang->pivot->jumlah ?? 1;
            $totalHarga = $hargaBeli * $jumlah;
            
            return [
                '', // Kode Inventaris (same as sarana)
                '', // Ruang (same as sarana)
                '', // Kode Ruang (same as sarana)
                '', // Nama Ruang (same as sarana)
                '', // Lokasi Ruang (same as sarana)
                '', // Sumber Dana (same as sarana)
                '', // Kode Sumber Dana (same as sarana)
                '', // Tanggal (same as sarana)
                '', // Catatan (same as sarana)
                $barang->nama_barang ?? '',
                $barang->kode_barang ?? '',
                $barang->kategori->nama_kategori ?? '',
                $jumlah,
                ucfirst($barang->pivot->kondisi ?? 'baik'),
                $hargaBeli > 0 ? number_format($hargaBeli, 0, ',', '.') : '',
                $totalHarga > 0 ? number_format($totalHarga, 0, ',', '.') : '',
                $barang->merk ?? '',
                $barang->model ?? '',
                $barang->serial_number ?? '',
                '',
                '',
            ];
        }
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
        
        $row = 2; // Start from row 2 (after header)
        foreach ($this->saranas as $sarana) {
            // Style sarana header row
            $styles[$row] = [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E7FF'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];
            $row++;
            
            // Style barang rows
            foreach ($sarana->barang as $barang) {
                $styles[$row] = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ];
                $row++;
            }
        }
        
        return $styles;
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Kode Inventaris
            'B' => 20,  // Ruang
            'C' => 15,  // Kode Ruang
            'D' => 20,  // Nama Ruang
            'E' => 20,  // Lokasi Ruang
            'F' => 15,  // Sumber Dana
            'G' => 18,  // Kode Sumber Dana
            'H' => 12,  // Tanggal
            'I' => 30,  // Catatan
            'J' => 30,  // Barang - Nama
            'K' => 15,  // Barang - Kode
            'L' => 20,  // Barang - Kategori
            'M' => 10,  // Barang - Jumlah
            'N' => 12,  // Barang - Kondisi
            'O' => 18,  // Barang - Harga Satuan
            'P' => 18,  // Barang - Total Harga
            'Q' => 15,  // Barang - Merk
            'R' => 15,  // Barang - Model
            'S' => 20,  // Barang - Serial Number
            'T' => 20,  // Created At
            'U' => 20,  // Updated At
        ];
    }
}

