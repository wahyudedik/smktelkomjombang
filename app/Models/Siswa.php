<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\Auditable;

class Siswa extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'no_telepon',
        'no_wa',
        'email',
        'foto',
        'kelas',
        'jurusan',
        'tahun_masuk',
        'tahun_lulus',
        'status',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_telepon_ortu',
        'alamat_ortu',
        'prestasi',
        'catatan',
        'nilai_akademik',
        'ekstrakurikuler',
        'user_id',
        'has_voted_osis',
        'voted_at',
        'voting_ip',
        'voting_user_agent',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'nilai_akademik' => 'array',
        'ekstrakurikuler' => 'array',
        'has_voted_osis' => 'boolean',
        'voted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the siswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the siswa's academic records.
     */
    // public function academicRecords(): HasMany
    // {
    //     return $this->hasMany(SiswaAcademic::class);
    // }

    /**
     * Get the siswa's attendance records.
     */
    // public function attendanceRecords(): HasMany
    // {
    //     return $this->hasMany(SiswaAttendance::class);
    // }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($siswa) {
            // Delete photo when siswa is deleted
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
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
     * Scope to filter by class.
     */
    public function scopeKelas($query, string $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    /**
     * Scope to filter by year.
     */
    public function scopeTahunMasuk($query, int $tahun)
    {
        return $query->where('tahun_masuk', $tahun);
    }

    /**
     * Scope to filter active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope to filter graduated students.
     */
    public function scopeGraduated($query)
    {
        return $query->where('status', 'lulus');
    }

    /**
     * Get age.
     */
    public function getAgeAttribute(): int
    {
        return $this->tanggal_lahir?->age ?? 0;
    }

    /**
     * Get years of study.
     */
    public function getYearsOfStudyAttribute(): int
    {
        if ($this->tahun_lulus) {
            return $this->tahun_lulus - $this->tahun_masuk;
        }
        return now()->year - $this->tahun_masuk;
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
     * Check if siswa is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Check if siswa is graduated.
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
            'aktif' => 'green',
            'lulus' => 'blue',
            'pindah' => 'yellow',
            'keluar' => 'red',
            'meninggal' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get gender display.
     */
    public function getGenderDisplayAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get academic year.
     */
    public function getAcademicYearAttribute(): string
    {
        if ($this->tahun_lulus) {
            return "{$this->tahun_masuk} - {$this->tahun_lulus}";
        }
        return "{$this->tahun_masuk} - Sekarang";
    }

    /**
     * Get parent information.
     */
    public function getParentInfoAttribute(): string
    {
        $info = [];
        if ($this->nama_ayah) {
            $info[] = "Ayah: {$this->nama_ayah}";
        }
        if ($this->nama_ibu) {
            $info[] = "Ibu: {$this->nama_ibu}";
        }
        return implode(', ', $info);
    }

    /**
     * Get extracurricular as string.
     */
    public function getExtracurricularStringAttribute(): string
    {
        return implode(', ', $this->ekstrakurikuler ?? []);
    }

    /**
     * Get academic performance summary.
     */
    public function getAcademicSummaryAttribute(): array
    {
        $nilai = $this->nilai_akademik ?? [];
        if (empty($nilai)) {
            return [
                'average' => 0,
                'grade' => 'Tidak ada data',
                'highest' => 0,
                'lowest' => 0,
                'subjects' => 0,
                'total_subjects' => 0
            ];
        }

        $total = array_sum($nilai);
        $count = count($nilai);
        $average = $count > 0 ? round($total / $count, 2) : 0;
        $highest = $count > 0 ? max($nilai) : 0;
        $lowest = $count > 0 ? min($nilai) : 0;

        $grade = match (true) {
            $average >= 90 => 'A',
            $average >= 80 => 'B',
            $average >= 70 => 'C',
            $average >= 60 => 'D',
            default => 'E'
        };

        return [
            'average' => $average,
            'grade' => $grade,
            'highest' => $highest,
            'lowest' => $lowest,
            'subjects' => $count,
            'total_subjects' => $count
        ];
    }

    /**
     * Check if student has voted in OSIS election
     */
    public function hasVotedOsis(): bool
    {
        return $this->has_voted_osis ?? false;
    }

    /**
     * Mark student as voted
     */
    public function markAsVoted(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->update([
            'has_voted_osis' => true,
            'voted_at' => now(),
            'voting_ip' => $ipAddress,
            'voting_user_agent' => $userAgent,
        ]);
    }

    /**
     * Reset voting status (for admin purposes)
     */
    public function resetVotingStatus(): void
    {
        $this->update([
            'has_voted_osis' => false,
            'voted_at' => null,
            'voting_ip' => null,
            'voting_user_agent' => null,
        ]);
    }

    /**
     * Get voting status badge color
     */
    public function getVotingStatusBadgeColorAttribute(): string
    {
        return $this->hasVotedOsis() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
    }

    /**
     * Get voting status display text
     */
    public function getVotingStatusDisplayAttribute(): string
    {
        return $this->hasVotedOsis() ? 'Sudah Memilih' : 'Belum Memilih';
    }
}
