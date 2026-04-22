<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;
use Carbon\Carbon;
use App\Traits\Auditable;

class Barang extends Model
{
    use HasFactory, Auditable;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'deskripsi',
        'kategori_id',
        'merk',
        'model',
        'serial_number',
        'barcode',
        'qr_code',
        'harga_beli',
        'tanggal_pembelian',
        'sumber_dana',
        'kondisi',
        'lokasi',
        'ruang_id',
        'status',
        'catatan',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'harga_beli' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the kategori that owns the barang.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSarpras::class, 'kategori_id');
    }

    /**
     * Get the ruang that owns the barang.
     */
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    /**
     * Get the maintenance records for the barang.
     */
    public function maintenance(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'item_id')->where('jenis_item', 'barang');
    }

    /**
     * Get the sarana that use this barang.
     */
    public function sarana(): BelongsToMany
    {
        return $this->belongsToMany(Sarana::class, 'sarana_barang')
            ->withPivot('jumlah', 'kondisi')
            ->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($barang) {
            // Delete photo when barang is deleted
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
        });
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by condition.
     */
    public function scopeKondisi($query, string $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeKategori($query, int $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Scope to filter by room.
     */
    public function scopeRuang($query, int $ruangId)
    {
        return $query->where('ruang_id', $ruangId);
    }

    /**
     * Scope to filter active barang.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        return null;
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'tersedia' => 'green',
            'dipinjam' => 'blue',
            'rusak' => 'red',
            'hilang' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get condition badge color.
     */
    public function getKondisiBadgeColorAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'green',
            'rusak' => 'red',
            'hilang' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->harga_beli) {
            return 'Rp ' . number_format((float) $this->harga_beli, 0, ',', '.');
        }
        return 'Tidak ada data';
    }

    /**
     * Get formatted harga (alias for formatted_price).
     */
    public function getFormattedHargaAttribute(): string
    {
        return $this->getFormattedPriceAttribute();
    }

    /**
     * Get kondisi display.
     */
    public function getKondisiDisplayAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get age in years.
     */
    public function getAgeAttribute(): int
    {
        if ($this->tanggal_pembelian) {
            /** @var Carbon $tanggalPembelian */
            $tanggalPembelian = $this->tanggal_pembelian;
            return $tanggalPembelian->diffInYears(now());
        }
        return 0;
    }

    /**
     * Generate barcode for this item.
     */
    public function generateBarcode(): string
    {
        if (!$this->barcode) {
            $this->barcode = 'BRG' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
            $this->save();
        }
        return $this->barcode;
    }

    /**
     * Generate QR code for this item.
     */
    public function generateQRCode(): string
    {
        if (!$this->qr_code) {
            $this->qr_code = 'QR' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
            $this->save();
        }
        return $this->qr_code;
    }

    /**
     * Get barcode image URL.
     */
    public function getBarcodeImageUrlAttribute(): string
    {
        $barcode = $this->generateBarcode();
        return route('sarpras.barcode', ['code' => $barcode]);
    }

    /**
     * Get QR code image URL.
     */
    public function getQRCodeImageUrlAttribute(): string
    {
        $qrCode = $this->generateQRCode();
        return route('sarpras.qrcode', ['code' => $qrCode]);
    }

    /**
     * Get barcode data for scanning.
     */
    public function getBarcodeDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'kategori' => $this->kategori?->nama_kategori,
            'lokasi' => $this->lokasi,
            'kondisi' => $this->kondisi,
            'status' => $this->status,
            'serial_number' => $this->serial_number,
            'barcode' => $this->barcode,
            'qr_code' => $this->qr_code,
        ];
    }

    /**
     * Get condition badge.
     */
    public function getConditionBadgeAttribute(): string
    {
        $color = match ($this->kondisi) {
            'baik' => 'green',
            'rusak' => 'red',
            'hilang' => 'gray',
            default => 'gray'
        };
        $text = match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => 'Tidak Diketahui'
        };
        return "<span class=\"badge badge-{$color}\">{$text}</span>";
    }

    /**
     * Get status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        $color = match ($this->status) {
            'tersedia' => 'green',
            'dipinjam' => 'blue',
            'rusak' => 'red',
            'hilang' => 'gray',
            default => 'gray'
        };
        $text = match ($this->status) {
            'tersedia' => 'Tersedia',
            'dipinjam' => 'Dipinjam',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => 'Tidak Diketahui'
        };
        return "<span class=\"badge badge-{$color}\">{$text}</span>";
    }
}
