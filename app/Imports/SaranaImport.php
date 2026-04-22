<?php

namespace App\Imports;

use App\Models\Sarana;
use App\Models\Ruang;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class SaranaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithEvents
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $currentSarana = null;
    protected $processedSarana = [];
    protected $rowCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->rowCount++;
        
        // Normalize column names (handle both 'barang_nama' and 'barang - nama' formats)
        // Excel converts "Barang - Nama" to "barang___nama" (triple underscore)
        $barangNama = $row['barang_nama'] ?? $row['barang___nama'] ?? $row['barang___nama'] ?? '';
        $barangKode = $row['barang_kode'] ?? $row['barang___kode'] ?? '';
        $barangJumlah = $row['barang_jumlah'] ?? $row['barang___jumlah'] ?? null;
        $barangKondisi = $row['barang_kondisi'] ?? $row['barang___kondisi'] ?? 'baik';
        $kodeInventaris = $row['kode_inventaris'] ?? '';
        
        // Check if this is a sarana header row (has kode_inventaris and barang_nama contains "===" or is empty)
        $isSaranaRow = !empty($kodeInventaris) && (empty($barangNama) || strpos($barangNama, '===') !== false);
        
        if ($isSaranaRow) {
            // This is a sarana header row
            $kodeInventaris = trim($kodeInventaris);
            
            // Check if sarana already exists
            $existingSarana = Sarana::where('kode_inventaris', $kodeInventaris)->first();
            
            if ($existingSarana) {
                Log::info("Skipping duplicate sarana: {$kodeInventaris}");
                $this->currentSarana = $existingSarana;
                return null;
            }
            
            // Find ruang by nama_ruang or kode_ruang
            $ruangId = null;
            $ruangName = trim($row['ruang'] ?? $row['nama_ruang'] ?? '');
            if (!empty($ruangName)) {
                $ruang = Ruang::where('nama_ruang', $ruangName)
                    ->orWhere('kode_ruang', $ruangName)
                    ->first();
                if ($ruang) {
                    $ruangId = $ruang->id;
                } else {
                    Log::warning("Ruang not found: {$ruangName}");
                }
            }
            
            // Parse tanggal
            $tanggal = null;
            if (!empty($row['tanggal'])) {
                try {
                    $tanggal = \Carbon\Carbon::parse($row['tanggal']);
                } catch (\Exception $e) {
                    Log::warning("Invalid date format for tanggal: {$row['tanggal']}");
                }
            }
            
            // Create sarana
            $sarana = Sarana::create([
                'kode_inventaris' => $kodeInventaris,
                'ruang_id' => $ruangId,
                'sumber_dana' => !empty($row['sumber_dana']) ? trim($row['sumber_dana']) : null,
                'kode_sumber_dana' => !empty($row['kode_sumber_dana']) ? trim($row['kode_sumber_dana']) : 'MAUDU/2025',
                'tanggal' => $tanggal ?? now(),
                'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
            ]);
            
            // Generate kode inventaris if needed (after barang is attached)
            // This will be done after all barang are attached
            
            $this->currentSarana = $sarana;
            $this->processedSarana[$kodeInventaris] = $sarana;
            
            return null; // Don't return sarana as it's already created
        } else {
            // This is a barang row
            if (!$this->currentSarana) {
                Log::warning("Barang row found without sarana header at row {$this->rowCount}");
                return null;
            }
            
            // Find barang by kode_barang or nama_barang (handle both formats)
            $barang = null;
            if (!empty($barangKode)) {
                $barang = Barang::where('kode_barang', trim($barangKode))->first();
            }
            
            if (!$barang && !empty($barangNama)) {
                $barang = Barang::where('nama_barang', trim($barangNama))->first();
            }
            
            if (!$barang) {
                Log::warning("Barang not found: " . ($barangKode ?: $barangNama ?: 'unknown'));
                return null;
            }
            
            // Parse jumlah
            $jumlah = !empty($barangJumlah) ? (int) $barangJumlah : 1;
            
            // Parse kondisi
            $kondisi = strtolower(trim($barangKondisi));
            if (!in_array($kondisi, ['baik', 'rusak', 'hilang'])) {
                $kondisi = 'baik';
            }
            
            // Attach barang to sarana (skip if already attached)
            if (!$this->currentSarana->barang()->where('barang_id', $barang->id)->exists()) {
                $this->currentSarana->barang()->attach($barang->id, [
                    'jumlah' => $jumlah,
                    'kondisi' => $kondisi,
                ]);
                
                // Update ruang_id for barang if not set
                if (!$barang->ruang_id && $this->currentSarana->ruang_id) {
                    $barang->update(['ruang_id' => $this->currentSarana->ruang_id]);
                }
                
                // Regenerate kode inventaris after attaching barang (if kode was auto-generated)
                // Only regenerate if kode doesn't match expected format
                $this->currentSarana->refresh();
                if ($this->currentSarana->barang()->count() > 0) {
                    $totalJumlah = $this->currentSarana->barang()->sum('sarana_barang.jumlah');
                    $firstBarang = $this->currentSarana->barang()->first();
                    $expectedKode = $this->currentSarana->generateKodeInventaris(
                        $this->currentSarana->id,
                        $totalJumlah,
                        $firstBarang ? $firstBarang->kode_barang : null
                    );
                    
                    // Only update if current kode doesn't match expected format
                    // This allows manual kode from Excel to be preserved
                    if ($this->currentSarana->kode_inventaris !== $expectedKode && 
                        strpos($this->currentSarana->kode_inventaris, 'INV/') === 0) {
                        // Kode seems to be in correct format, keep it
                        // Otherwise, regenerate
                        if (strpos($this->currentSarana->kode_inventaris, 'TEMP') !== false) {
                            $this->currentSarana->kode_inventaris = $expectedKode;
                            $this->currentSarana->save();
                        }
                    }
                }
            }
            
            return null; // Don't return model as we're using attach
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.kode_inventaris' => 'nullable|string|max:255',
            '*.ruang' => 'nullable|string|max:255',
            '*.nama_ruang' => 'nullable|string|max:255',
            '*.sumber_dana' => 'nullable|string|max:255',
            '*.kode_sumber_dana' => 'nullable|string|max:100',
            '*.tanggal' => 'nullable|date',
            '*.catatan' => 'nullable|string',
            '*.barang_nama' => 'nullable|string|max:255',
            '*.barang_kode' => 'nullable|string|max:50',
            '*.barang_jumlah' => 'nullable|integer|min:1',
            '*.barang_kondisi' => 'nullable|in:baik,rusak,hilang',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
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
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Regenerate kode inventaris for all processed sarana after import
                foreach ($this->processedSarana as $sarana) {
                    if ($sarana && $sarana->barang()->count() > 0) {
                        $totalJumlah = $sarana->barang()->sum('sarana_barang.jumlah');
                        $firstBarang = $sarana->barang()->first();
                        $sarana->kode_inventaris = $sarana->generateKodeInventaris(
                            $sarana->id,
                            $totalJumlah,
                            $firstBarang ? $firstBarang->kode_barang : null
                        );
                        $sarana->save();
                    }
                }
            },
        ];
    }
}

