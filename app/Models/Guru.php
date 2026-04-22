<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\Auditable;

class Guru extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'no_telepon',
        'no_wa',
        'email',
        'foto',
        'status_kepegawaian',
        'jabatan',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_aktif',
        'pendidikan_terakhir',
        'universitas',
        'tahun_lulus',
        'sertifikasi',
        'mata_pelajaran',
        'jadwal_mengajar',
        'prestasi',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'mata_pelajaran' => 'array',
        'jadwal_mengajar' => 'array',
    ];

    /**
     * Get the user that owns the guru.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the guru's performance records.
     */
    // public function performanceRecords(): HasMany
    // {
    //     return $this->hasMany(GuruPerformance::class);
    // }

    /**
     * Get the guru's schedule records.
     */
    // public function scheduleRecords(): HasMany
    // {
    //     return $this->hasMany(GuruSchedule::class);
    // }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($guru) {
            // Delete photo when guru is deleted
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
        });
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status_aktif', $status);
    }

    /**
     * Scope to filter by employment status.
     */
    public function scopeEmploymentStatus($query, string $status)
    {
        return $query->where('status_kepegawaian', $status);
    }

    /**
     * Scope to filter active gurus.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', 'aktif');
    }

    /**
     * Scope to filter by subject.
     */
    public function scopeBySubject($query, string $subject)
    {
        return $query->whereJsonContains('mata_pelajaran', $subject);
    }

    /**
     * Get full name with titles.
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->nama_lengkap;
        if ($this->gelar_depan) {
            $name = $this->gelar_depan . ' ' . $name;
        }
        if ($this->gelar_belakang) {
            $name = $name . ', ' . $this->gelar_belakang;
        }
        return $name;
    }

    /**
     * Get age.
     */
    public function getAgeAttribute(): int
    {
        return $this->tanggal_lahir?->age ?? 0;
    }

    /**
     * Get years of service.
     */
    public function getYearsOfServiceAttribute(): int
    {
        if (!$this->tanggal_masuk) {
            return 0;
        }
        /** @var Carbon $tanggalMasuk */
        $tanggalMasuk = $this->tanggal_masuk;
        return $tanggalMasuk->diffInYears(now()); // @phpstan-ignore-line
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
     * Check if guru is active.
     */
    public function isActive(): bool
    {
        return $this->status_aktif === 'aktif';
    }

    /**
     * Check if guru has specific subject.
     */
    public function teachesSubject(string $subject): bool
    {
        return in_array($subject, $this->mata_pelajaran ?? []);
    }

    /**
     * Get teaching subjects as string.
     */
    public function getSubjectsStringAttribute(): string
    {
        return implode(', ', $this->mata_pelajaran ?? []);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status_aktif) {
            'aktif' => 'green',
            'tidak_aktif' => 'red',
            'pensiun' => 'blue',
            'meninggal' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get employment status badge color.
     */
    public function getEmploymentBadgeColorAttribute(): string
    {
        return match ($this->status_kepegawaian) {
            'PNS' => 'green',
            'CPNS' => 'blue',
            'GTT' => 'yellow',
            'GTY' => 'orange',
            'Honorer' => 'red',
            default => 'gray'
        };
    }
}
