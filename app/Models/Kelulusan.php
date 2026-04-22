<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;

class Kelulusan extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nama',
        'nisn',
        'nis',
        'jurusan',
        'tahun_ajaran',
        'status',
        'tempat_kuliah',
        'tempat_kerja',
        'jurusan_kuliah',
        'jabatan_kerja',
        'no_hp',
        'no_wa',
        'alamat',
        'prestasi',
        'catatan',
        'foto',
        'is_active',
        'tanggal_lulus',
        'siswa_id',
        'check_count',
        'last_checked_at',
        'check_ip',
        'check_user_agent',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_lulus' => 'datetime',
        'last_checked_at' => 'datetime',
        'check_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kelulusan) {
            // Delete photo when kelulusan is deleted
            if ($kelulusan->foto) {
                Storage::disk('public')->delete($kelulusan->foto);
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
     * Scope to filter by year.
     */
    public function scopeTahunAjaran($query, int $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }

    /**
     * Scope to filter by major.
     */
    public function scopeJurusan($query, string $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }

    /**
     * Scope to filter active graduates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter graduated students.
     */
    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }

    /**
     * Scope to filter by NISN.
     */
    public function scopeByNisn($query, string $nisn)
    {
        return $query->where('nisn', $nisn);
    }

    /**
     * Scope to filter by NIS.
     */
    public function scopeByNis($query, string $nis)
    {
        return $query->where('nis', $nis);
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
     * Check if student is graduated.
     */
    public function isGraduated(): bool
    {
        return $this->status === 'lulus';
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'lulus' => 'green',
            'tidak_lulus' => 'red',
            'mengulang' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Get status display.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'mengulang' => 'Mengulang',
            default => 'Unknown'
        };
    }

    /**
     * Get current activity.
     */
    public function getCurrentActivityAttribute(): string
    {
        if ($this->tempat_kuliah) {
            return "Kuliah di {$this->tempat_kuliah}";
        } elseif ($this->tempat_kerja) {
            return "Bekerja di {$this->tempat_kerja}";
        }
        return 'Belum ada informasi';
    }

    /**
     * Get contact information.
     */
    public function getContactInfoAttribute(): string
    {
        $contacts = [];
        if ($this->no_hp) {
            $contacts[] = "HP: {$this->no_hp}";
        }
        if ($this->no_wa) {
            $contacts[] = "WA: {$this->no_wa}";
        }
        return implode(', ', $contacts);
    }

    /**
     * Get graduation year display.
     */
    public function getGraduationYearDisplayAttribute(): string
    {
        return "Tahun Ajaran {$this->tahun_ajaran}";
    }

    /**
     * Get major display.
     */
    public function getMajorDisplayAttribute(): string
    {
        return $this->jurusan ?? 'Tidak ada data';
    }

    /**
     * Get education path.
     */
    public function getEducationPathAttribute(): string
    {
        if ($this->tempat_kuliah && $this->jurusan_kuliah) {
            return "{$this->tempat_kuliah} - {$this->jurusan_kuliah}";
        }
        return $this->tempat_kuliah ?? 'Tidak ada data';
    }

    /**
     * Get career path.
     */
    public function getCareerPathAttribute(): string
    {
        if ($this->tempat_kerja && $this->jabatan_kerja) {
            return "{$this->tempat_kerja} - {$this->jabatan_kerja}";
        }
        return $this->tempat_kerja ?? 'Tidak ada data';
    }

    /**
     * Relationship with Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Record check attempt
     */
    public function recordCheck(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->increment('check_count');
        $this->update([
            'last_checked_at' => now(),
            'check_ip' => $ipAddress,
            'check_user_agent' => $userAgent,
        ]);
    }

    /**
     * Get check statistics
     */
    public function getCheckStatsAttribute(): array
    {
        return [
            'total_checks' => $this->check_count ?? 0,
            'last_checked' => $this->last_checked_at?->format('d/m/Y H:i'),
            'check_frequency' => $this->getCheckFrequency(),
        ];
    }

    /**
     * Get check frequency description
     */
    private function getCheckFrequency(): string
    {
        $count = $this->check_count ?? 0;

        if ($count === 0) return 'Belum pernah dicek';
        if ($count === 1) return 'Sekali';
        if ($count <= 3) return 'Beberapa kali';
        if ($count <= 10) return 'Sering';

        return 'Sangat sering';
    }

    /**
     * Check if student is eligible for graduation check
     */
    public function isEligibleForCheck(): bool
    {
        // If already graduated, always eligible
        if ($this->status === 'lulus') {
            return true;
        }

        // Only students in grade 12 or graduated can check
        if ($this->siswa) {
            $kelas = $this->siswa->kelas;
            return str_contains($kelas, 'XII');
        }

        // If no siswa relation but has graduation data, consider eligible if status is lulus
        // This handles cases where kelulusan data exists but siswa_id is not linked
        return false;
    }

    /**
     * Get graduation message
     */
    public function getGraduationMessageAttribute(): string
    {
        if ($this->status === 'lulus') {
            return "🎉 Selamat {$this->nama}! Kamu Dinyatakan LULUS! 🎓";
        } elseif ($this->status === 'tidak_lulus') {
            return "😔 Maaf {$this->nama}, kamu belum lulus. Tetap semangat! 💪";
        } else {
            return "⏳ {$this->nama}, status kelulusan masih dalam proses. Sabar ya! 😊";
        }
    }
}
