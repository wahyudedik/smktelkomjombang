<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenance';

    protected $fillable = [
        'kode_maintenance',
        'jenis_item',
        'item_id',
        'jenis_maintenance',
        'deskripsi_masalah',
        'tindakan_perbaikan',
        'tanggal_maintenance',
        'tanggal_selesai',
        'status',
        'biaya',
        'teknisi',
        'catatan',
        'foto_sebelum',
        'foto_sesudah',
        'user_id',
    ];

    protected $casts = [
        'tanggal_maintenance' => 'date',
        'tanggal_selesai' => 'date',
        'biaya' => 'decimal:2',
    ];

    /**
     * Get the user that owns the maintenance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related barang.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'item_id');
    }

    /**
     * Get the related ruang.
     */
    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'item_id');
    }

    /**
     * Get the related item (barang or ruang) based on jenis_item.
     */
    public function item()
    {
        if ($this->jenis_item === 'barang') {
            return $this->barang();
        } elseif ($this->jenis_item === 'ruang') {
            return $this->ruang();
        }

        return null;
    }

    /**
     * Get the item name based on jenis_item.
     */
    public function getItemNameAttribute(): string
    {
        if ($this->jenis_item === 'barang' && $this->barang) {
            return $this->barang->nama_barang;
        } elseif ($this->jenis_item === 'ruang' && $this->ruang) {
            return $this->ruang->nama_ruang;
        }

        return 'Item tidak ditemukan';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($maintenance) {
            // Delete photos when maintenance is deleted
            if ($maintenance->foto_sebelum) {
                Storage::disk('public')->delete($maintenance->foto_sebelum);
            }
            if ($maintenance->foto_sesudah) {
                Storage::disk('public')->delete($maintenance->foto_sesudah);
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
     * Scope to filter by type.
     */
    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis_maintenance', $jenis);
    }

    /**
     * Scope to filter by item type.
     */
    public function scopeItemType($query, string $jenisItem)
    {
        return $query->where('jenis_item', $jenisItem);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_maintenance', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by technician.
     */
    public function scopeTeknisi($query, string $teknisi)
    {
        return $query->where('teknisi', $teknisi);
    }

    /**
     * Get before photo URL.
     */
    public function getBeforePhotoUrlAttribute(): ?string
    {
        if ($this->foto_sebelum) {
            return Storage::url($this->foto_sebelum);
        }
        return null;
    }

    /**
     * Get after photo URL.
     */
    public function getAfterPhotoUrlAttribute(): ?string
    {
        if ($this->foto_sesudah) {
            return Storage::url($this->foto_sesudah);
        }
        return null;
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'blue',
            'sedang_dikerjakan' => 'yellow',
            'dalam_proses' => 'yellow',
            'selesai' => 'green',
            'dibatalkan' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status display.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'Dijadwalkan',
            'sedang_dikerjakan' => 'Sedang Dikerjakan',
            'dalam_proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    /**
     * Get formatted cost.
     */
    public function getFormattedCostAttribute(): string
    {
        if ($this->biaya) {
            return 'Rp ' . number_format((float)$this->biaya, 0, ',', '.');
        }
        return 'Tidak ada biaya';
    }

    /**
     * Get formatted biaya (alias for formatted cost).
     */
    public function getFormattedBiayaAttribute(): string
    {
        return $this->getFormattedCostAttribute();
    }

    /**
     * Get item type display.
     */
    public function getItemTypeDisplayAttribute(): string
    {
        return match ($this->jenis_item) {
            'barang' => 'Barang',
            'ruang' => 'Ruang',
            default => 'Unknown'
        };
    }

    /**
     * Get duration in days.
     */
    public function getDurationAttribute(): int
    {
        if (!$this->tanggal_maintenance) {
            return 0;
        }

        /** @var Carbon $tanggalMaintenance */
        $tanggalMaintenance = $this->tanggal_maintenance;

        if ($this->tanggal_selesai) {
            return $tanggalMaintenance->diffInDays($this->tanggal_selesai); // @phpstan-ignore-line
        }
        return $tanggalMaintenance->diffInDays(now()); // @phpstan-ignore-line
    }

    /**
     * Check if maintenance is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Check if maintenance is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'sedang_dikerjakan';
    }

    /**
     * Check if maintenance is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'dijadwalkan';
    }

    /**
     * Check if maintenance is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'dibatalkan';
    }
}
