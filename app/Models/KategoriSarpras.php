<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSarpras extends Model
{
    use HasFactory;

    protected $table = 'kategori_sarpras';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'kode_kategori',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the barang for the kategori.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    /**
     * Scope to filter active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('nama_kategori');
    }

    /**
     * Get barang count for this category.
     */
    public function getBarangCountAttribute(): int
    {
        return $this->barang()->count();
    }

    /**
     * Get active barang count for this category.
     */
    public function getActiveBarangCountAttribute(): int
    {
        return $this->barang()->where('is_active', true)->count();
    }

    /**
     * Get status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        $color = $this->is_active ? 'green' : 'red';
        $text = $this->is_active ? 'Aktif' : 'Tidak Aktif';
        return "<span class=\"badge badge-{$color}\">{$text}</span>";
    }
}
