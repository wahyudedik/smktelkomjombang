<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';

    protected $fillable = [
        'kode_ruang',
        'nama_ruang',
        'deskripsi',
        'jenis_ruang',
        'luas_ruang',
        'kapasitas',
        'lantai',
        'gedung',
        'kondisi',
        'status',
        'fasilitas',
        'catatan',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'luas_ruang' => 'decimal:2',
        'fasilitas' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the barang in this ruang.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'ruang_id');
    }

    /**
     * Get the maintenance records for this ruang.
     */
    public function maintenance(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'item_id')->where('jenis_item', 'ruang');
    }

    /**
     * Get the sarana in this ruang.
     */
    public function sarana(): HasMany
    {
        return $this->hasMany(Sarana::class, 'ruang_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($ruang) {
            // Delete photo when ruang is deleted
            if ($ruang->foto) {
                Storage::disk('public')->delete($ruang->foto);
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
     * Scope to filter by type.
     */
    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis_ruang', $jenis);
    }

    /**
     * Scope to filter by building.
     */
    public function scopeGedung($query, string $gedung)
    {
        return $query->where('gedung', $gedung);
    }

    /**
     * Scope to filter by floor.
     */
    public function scopeLantai($query, string $lantai)
    {
        return $query->where('lantai', $lantai);
    }

    /**
     * Scope to filter active ruang.
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
            'aktif' => 'green',
            'tidak_aktif' => 'red',
            'renovasi' => 'yellow',
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
            'renovasi' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Get formatted area.
     */
    public function getFormattedAreaAttribute(): string
    {
        if ($this->luas_ruang) {
            return number_format((float)$this->luas_ruang, 2, ',', '.') . ' m²';
        }
        return 'Tidak ada data';
    }

    /**
     * Get formatted luas (alias for formatted_area).
     */
    public function getFormattedLuasAttribute(): string
    {
        return $this->getFormattedAreaAttribute();
    }

    /**
     * Get condition badge.
     */
    public function getConditionBadgeAttribute(): string
    {
        $color = $this->getKondisiBadgeColorAttribute();
        $text = match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak' => 'Rusak',
            'renovasi' => 'Renovasi',
            default => 'Tidak Diketahui'
        };
        return "<span class=\"badge badge-{$color}\">{$text}</span>";
    }

    /**
     * Get status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        $color = $this->getStatusBadgeColorAttribute();
        $text = match ($this->status) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'renovasi' => 'Renovasi',
            default => 'Tidak Diketahui'
        };
        return "<span class=\"badge badge-{$color}\">{$text}</span>";
    }

    /**
     * Get facilities list.
     */
    public function getFacilitiesListAttribute(): string
    {
        if ($this->fasilitas && is_array($this->fasilitas)) {
            return implode(', ', $this->fasilitas);
        }
        return 'Tidak ada fasilitas';
    }

    /**
     * Get location (gedung and lantai).
     */
    public function getLokasiAttribute(): ?string
    {
        $parts = [];
        
        if ($this->gedung) {
            $parts[] = $this->gedung;
        }
        
        if ($this->lantai) {
            $parts[] = 'Lantai ' . $this->lantai;
        }
        
        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Get full location.
     */
    public function getFullLocationAttribute(): string
    {
        $location = $this->nama_ruang;
        if ($this->gedung) {
            $location .= ' - Gedung ' . $this->gedung;
        }
        if ($this->lantai) {
            $location .= ' - Lantai ' . $this->lantai;
        }
        return $location;
    }
}
