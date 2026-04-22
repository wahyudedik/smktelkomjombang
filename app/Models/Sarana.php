<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Auditable;

class Sarana extends Model
{
    use HasFactory, Auditable;

    protected $table = 'sarana';

    protected $fillable = [
        'kode_inventaris',
        'ruang_id',
        'sumber_dana',
        'kode_sumber_dana',
        'tanggal',
        'catatan',
    ];

    protected $attributes = [
        'kode_inventaris' => 'TEMP',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the ruang that owns the sarana.
     */
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    /**
     * Get the barang for this sarana.
     */
    public function barang(): BelongsToMany
    {
        return $this->belongsToMany(Barang::class, 'sarana_barang')
            ->withPivot('jumlah', 'kondisi')
            ->withTimestamps();
    }

    /**
     * Generate kode inventaris.
     * Format: INV/NO.KodeBarang.KodeRuang.JumlahBarang.KodeSumberDana
     */
    public function generateKodeInventaris($no = null, $totalJumlah = null, $kodeBarang = null): string
    {
        // Get next number
        if ($no === null) {
            $lastSarana = self::orderBy('id', 'desc')->first();
            $no = $lastSarana ? $lastSarana->id + 1 : 1;
        }
        
        // Get kode sumber dana (default if not set)
        $kodeSumberDana = $this->kode_sumber_dana ?? 'MAUDU/2025';
        
        // Get total jumlah barang
        if ($totalJumlah === null) {
            $totalJumlah = $this->barang()->sum('sarana_barang.jumlah') ?? 1;
        }
        
        // Get kode ruang
        $kodeRuang = $this->ruang->kode_ruang ?? 'R000';
        
        // Get first barang kode (or default)
        if ($kodeBarang === null) {
            $firstBarang = $this->barang()->first();
            $kodeBarang = $firstBarang ? $firstBarang->kode_barang : 'B000';
        }
        
        // Format: INV/NO.KodeBarang.KodeRuang.JumlahBarang.KodeSumberDana
        $kode = sprintf(
            'INV/%s.%s.%s.%s.%s',
            str_pad($no, 4, '0', STR_PAD_LEFT),
            $kodeBarang,
            $kodeRuang,
            str_pad($totalJumlah, 3, '0', STR_PAD_LEFT),
            $kodeSumberDana
        );
        
        return $kode;
    }

    /**
     * Get total jumlah barang.
     */
    public function getTotalJumlahAttribute(): int
    {
        return $this->barang()->sum('sarana_barang.jumlah') ?? 0;
    }

    /**
     * Get formatted tanggal.
     */
    public function getFormattedTanggalAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Scope to filter by sumber dana.
     */
    public function scopeSumberDana($query, string $sumberDana)
    {
        return $query->where('sumber_dana', $sumberDana);
    }

    /**
     * Scope to filter by kategori.
     */
    public function scopeKategori($query, int $kategoriId)
    {
        return $query->whereHas('barang', function ($q) use ($kategoriId) {
            $q->where('kategori_id', $kategoriId);
        });
    }
}
