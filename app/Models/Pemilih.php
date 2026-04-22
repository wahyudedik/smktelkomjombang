<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemilih extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'nisn',
        'kelas',
        'jenis_kelamin',
        'email',
        'nomor_hp',
        'alamat',
        'status',
        'waktu_memilih',
        'ip_address',
        'user_agent',
        'is_active',
        'user_id', // Added for relation to User
        'user_type', // Added to track if pemilih is from siswa or guru
    ];

    protected $casts = [
        'waktu_memilih' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the votings for the pemilih.
     */
    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class);
    }

    /**
     * Get the user that owns the pemilih.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the siswa if pemilih is from siswa.
     */
    public function siswa()
    {
        return $this->hasOneThrough(Siswa::class, User::class, 'id', 'user_id')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'siswa');
            });
    }

    /**
     * Get the guru if pemilih is from guru.
     */
    public function guru()
    {
        return $this->hasOneThrough(Guru::class, User::class, 'id', 'user_id')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'guru');
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
     * Scope to filter by class.
     */
    public function scopeKelas($query, string $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Scope to filter active pemilih.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter who haven't voted.
     */
    public function scopeBelumMemilih($query)
    {
        return $query->where('status', 'belum_memilih');
    }

    /**
     * Scope to filter who have voted.
     */
    public function scopeSudahMemilih($query)
    {
        return $query->where('status', 'sudah_memilih');
    }

    /**
     * Check if pemilih has voted.
     */
    public function hasVoted(): bool
    {
        return $this->status === 'sudah_memilih';
    }

    /**
     * Mark as voted.
     */
    public function markAsVoted(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->update([
            'status' => 'sudah_memilih',
            'waktu_memilih' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'sudah_memilih' => 'green',
            'belum_memilih' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status display.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'sudah_memilih' => 'Sudah Memilih',
            'belum_memilih' => 'Belum Memilih',
            default => 'Unknown'
        };
    }

    /**
     * Get voting time formatted.
     */
    public function getVotingTimeFormattedAttribute(): ?string
    {
        return $this->waktu_memilih ? $this->waktu_memilih->format('d/m/Y H:i') : null;
    }

    /**
     * Get gender display attribute.
     */
    public function getGenderDisplayAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Scope to filter by gender.
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('jenis_kelamin', $gender);
    }
}
