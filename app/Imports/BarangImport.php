<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\KategoriSarpras;
use App\Models\Ruang;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BarangImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        // Check if barang already exists by kode_barang
        $existing = Barang::where('kode_barang', $row['kode_barang'])->first();

        if ($existing) {
            Log::info("Skipping duplicate kode_barang: {$row['kode_barang']} for {$row['nama_barang']}");
            return null;
        }

        // Find kategori by name
        $kategoriId = null;
        if (!empty($row['kategori'])) {
            $kategori = KategoriSarpras::where('nama_kategori', trim($row['kategori']))->first();
            if ($kategori) {
                $kategoriId = $kategori->id;
            } else {
                Log::warning("Kategori not found: {$row['kategori']} - will be imported without kategori");
            }
        }

        // Find ruang by name
        $ruangId = null;
        if (!empty($row['ruang'])) {
            $ruang = Ruang::where('nama_ruang', trim($row['ruang']))->first();
            if ($ruang) {
                $ruangId = $ruang->id;
            } else {
                Log::warning("Ruang not found: {$row['ruang']} - will be imported without ruang");
            }
        }

        // Parse tanggal_pembelian
        $tanggalPembelian = null;
        if (!empty($row['tanggal_pembelian'])) {
            try {
                $tanggalPembelian = \Carbon\Carbon::parse($row['tanggal_pembelian']);
            } catch (\Exception $e) {
                Log::warning("Invalid date format for tanggal_pembelian: {$row['tanggal_pembelian']}");
            }
        }

        // Parse harga_beli - handle formatted input (e.g., "Rp 450.000")
        $hargaBeli = 0;
        if (!empty($row['harga_beli'])) {
            $hargaBeli = (float) preg_replace('/[^0-9.]/', '', $row['harga_beli']);
        }

        // Generate barcode and QR code if not provided
        $barcode = !empty($row['barcode']) ? trim($row['barcode']) : null;
        $qrCode = !empty($row['qr_code']) ? trim($row['qr_code']) : null;

        $this->rowCount++;

        return new Barang([
            'nama_barang' => trim($row['nama_barang']),
            'kode_barang' => trim($row['kode_barang']),
            'kategori_id' => $kategoriId,
            'ruang_id' => $ruangId,
            'lokasi' => !empty($row['lokasi']) ? trim($row['lokasi']) : null,
            'merk' => !empty($row['merk']) ? trim($row['merk']) : null,
            'model' => !empty($row['model']) ? trim($row['model']) : null,
            'serial_number' => !empty($row['serial_number']) ? trim($row['serial_number']) : null,
            'kondisi' => !empty($row['kondisi']) ? trim($row['kondisi']) : 'baik',
            'status' => !empty($row['status']) ? trim($row['status']) : 'tersedia',
            'harga_beli' => $hargaBeli,
            'tanggal_pembelian' => $tanggalPembelian,
            'sumber_dana' => !empty($row['sumber_dana']) ? trim($row['sumber_dana']) : null,
            'deskripsi' => !empty($row['deskripsi']) ? trim($row['deskripsi']) : null,
            'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
            'barcode' => $barcode,
            'qr_code' => $qrCode,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_barang' => 'required|string|max:255',
            '*.kode_barang' => 'required|string|max:50',
            '*.kategori' => 'nullable|string|max:255',
            '*.ruang' => 'nullable|string|max:255',
            '*.lokasi' => 'nullable|string|max:255',
            '*.merk' => 'nullable|string|max:100',
            '*.model' => 'nullable|string|max:100',
            '*.serial_number' => 'nullable|string|max:100',
            '*.kondisi' => 'nullable|in:baik,rusak,hilang',
            '*.status' => 'nullable|in:tersedia,dipinjam,rusak,hilang',
            '*.harga_beli' => 'nullable|numeric|min:0',
            '*.tanggal_pembelian' => 'nullable|date',
            '*.sumber_dana' => 'nullable|string|max:255',
            '*.deskripsi' => 'nullable|string',
            '*.catatan' => 'nullable|string',
            '*.barcode' => 'nullable|string|max:255',
            '*.qr_code' => 'nullable|string|max:255',
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
