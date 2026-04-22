<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
use Carbon\Carbon;

class JadwalPelajaran extends Model
{
    use HasFactory, Auditable;

    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'mata_pelajaran_id',
        'guru_id',
        'kelas_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang',
        'tahun_ajaran',
        'semester',
        'catatan',
        'status',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    /**
     * Get the mata pelajaran that owns the jadwal.
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    /**
     * Get the guru that owns the jadwal.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Get the kelas that owns the jadwal.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Scope to filter by kelas.
     */
    public function scopeByKelas($query, int $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Scope to filter by guru.
     */
    public function scopeByGuru($query, int $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    /**
     * Scope to filter by hari.
     */
    public function scopeByHari($query, string $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Scope to filter by tahun ajaran.
     */
    public function scopeByTahunAjaran($query, string $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }

    /**
     * Scope to filter by semester.
     */
    public function scopeBySemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope to filter active jadwal.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope to get current semester schedules.
     */
    public function scopeCurrent($query, string $tahunAjaran, string $semester)
    {
        return $query->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('status', 'aktif');
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return Carbon::parse($this->jam_mulai)->format('H:i') . ' - ' .
            Carbon::parse($this->jam_selesai)->format('H:i');
    }

    /**
     * Get duration in minutes.
     */
    public function getDurationAttribute(): int
    {
        $mulai = Carbon::parse($this->jam_mulai);
        $selesai = Carbon::parse($this->jam_selesai);
        return $mulai->diffInMinutes($selesai);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'green',
            'nonaktif' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get hari badge color.
     */
    public function getHariBadgeColorAttribute(): string
    {
        return match ($this->hari) {
            'Senin' => 'blue',
            'Selasa' => 'indigo',
            'Rabu' => 'green',
            'Kamis' => 'yellow',
            'Jumat' => 'orange',
            'Sabtu' => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if jadwal is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Check if jadwal conflicts with another.
     */
    public function hasConflict(): bool
    {
        return static::where('id', '!=', $this->id)
            ->where('kelas_id', $this->kelas_id)
            ->where('hari', $this->hari)
            ->where('tahun_ajaran', $this->tahun_ajaran)
            ->where('semester', $this->semester)
            ->where('status', 'aktif')
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($q) {
                        $q->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            })
            ->exists();
    }

    /**
     * Check if guru has conflict at this time.
     */
    public function guruHasConflict(): bool
    {
        return static::where('id', '!=', $this->id)
            ->where('guru_id', $this->guru_id)
            ->where('hari', $this->hari)
            ->where('tahun_ajaran', $this->tahun_ajaran)
            ->where('semester', $this->semester)
            ->where('status', 'aktif')
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($q) {
                        $q->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            })
            ->exists();
    }
}
