<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $barangs;

    public function __construct($barangs)
    {
        $this->barangs = $barangs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->barangs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Barang',
            'Kode Barang',
            'Kategori',
            'Ruang',
            'Lokasi',
            'Merk',
            'Model',
            'Serial Number',
            'Kondisi',
            'Status',
            'Harga Beli',
            'Tanggal Pembelian',
            'Sumber Dana',
            'Deskripsi',
            'Catatan',
            'Barcode',
            'QR Code',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param Barang $barang
     * @return array
     */
    public function map($barang): array
    {
        return [
            $barang->nama_barang ?? '',
            $barang->kode_barang ?? '',
            $barang->kategori->nama_kategori ?? '',
            $barang->ruang->nama_ruang ?? '',
            $barang->lokasi ?? '',
            $barang->merk ?? '',
            $barang->model ?? '',
            $barang->serial_number ?? '',
            $barang->kondisi ?? '',
            $barang->status ?? '',
            $barang->harga_beli ? 'Rp ' . number_format($barang->harga_beli, 0, ',', '.') : '',
            $barang->tanggal_pembelian ? $barang->tanggal_pembelian->format('Y-m-d') : '', // @phpstan-ignore-line
            $barang->sumber_dana ?? '',
            $barang->deskripsi ?? '',
            $barang->catatan ?? '',
            $barang->barcode ?? '',
            $barang->qr_code ?? '',
            $barang->created_at?->format('Y-m-d H:i:s'),
            $barang->updated_at?->format('Y-m-d H:i:s'),
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
            'A' => 30,  // Nama Barang
            'B' => 15,  // Kode Barang
            'C' => 20,  // Kategori
            'D' => 20,  // Ruang
            'E' => 20,  // Lokasi
            'F' => 15,  // Merk
            'G' => 15,  // Model
            'H' => 20,  // Serial Number
            'I' => 12,  // Kondisi
            'J' => 12,  // Status
            'K' => 18,  // Harga Beli
            'L' => 18,  // Tanggal Pembelian
            'M' => 20,  // Sumber Dana
            'N' => 35,  // Deskripsi
            'O' => 25,  // Catatan
            'P' => 18,  // Barcode
            'Q' => 18,  // QR Code
            'R' => 20,  // Created At
            'S' => 20,  // Updated At
        ];
    }
}
