<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use App\Traits\Auditable;

class Calon extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'nama_ketua',
        'foto_ketua',
        'nama_wakil',
        'foto_wakil',
        'jenis_kelamin',
        'visi_misi',
        'jenis_pencalonan',
        'program_kerja',
        'motivasi',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the votings for the calon.
     */
    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($calon) {
            // Delete photos when calon is deleted
            if ($calon->foto_ketua) {
                Storage::disk('public')->delete($calon->foto_ketua);
            }
            if ($calon->foto_wakil) {
                Storage::disk('public')->delete($calon->foto_wakil);
            }
        });
    }

    /**
     * Scope to filter active calons.
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
        return $query->orderBy('sort_order')->orderBy('nama_ketua');
    }

    /**
     * Get ketua photo URL.
     */
    public function getKetuaPhotoUrlAttribute(): ?string
    {
        if ($this->foto_ketua) {
            return Storage::url($this->foto_ketua);
        }
        return null;
    }

    /**
     * Get wakil photo URL.
     */
    public function getWakilPhotoUrlAttribute(): ?string
    {
        if ($this->foto_wakil) {
            return Storage::url($this->foto_wakil);
        }
        return null;
    }

    /**
     * Get total votes.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->votings()->where('is_valid', true)->count();
    }

    /**
     * Get vote percentage.
     */
    public function getVotePercentageAttribute(): float
    {
        $totalVotes = Voting::where('is_valid', true)->count();
        if ($totalVotes === 0) {
            return 0;
        }
        return round(($this->total_votes / $totalVotes) * 100, 2);
    }

    /**
     * Get full candidate name.
     */
    public function getFullCandidateNameAttribute(): string
    {
        if ($this->jenis_pencalonan === 'pasangan') {
            return "{$this->nama_ketua} & {$this->nama_wakil}";
        }
        return $this->nama_ketua;
    }

    /**
     * Get pencalonan type display.
     */
    public function getPencalonanTypeDisplayAttribute(): string
    {
        return match ($this->jenis_pencalonan) {
            'ketua' => 'Ketua OSIS',
            'wakil' => 'Wakil Ketua OSIS',
            'pasangan' => 'Pasangan Calon',
            default => 'Calon'
        };
    }

    /**
     * Get gender display.
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
