<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voting extends Model
{
    use HasFactory;

    protected $fillable = [
        'calon_id',
        'pemilih_id',
        'siswa_id',
        'election_id',
        'waktu_voting',
        'ip_address',
        'user_agent',
        'is_valid',
    ];

    protected $casts = [
        'waktu_voting' => 'datetime',
        'is_valid' => 'boolean',
    ];

    /**
     * Get the calon that owns the voting.
     */
    public function calon(): BelongsTo
    {
        return $this->belongsTo(Calon::class);
    }

    /**
     * Get the pemilih that owns the voting.
     */
    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class);
    }

    /**
     * Get the siswa that owns the voting.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Get the election that owns the voting.
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(OsisElection::class);
    }

    /**
     * Scope to filter valid votes.
     */
    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }

    /**
     * Scope to filter invalid votes.
     */
    public function scopeInvalid($query)
    {
        return $query->where('is_valid', false);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('waktu_voting', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by calon.
     */
    public function scopeForCalon($query, int $calonId)
    {
        return $query->where('calon_id', $calonId);
    }

    /**
     * Scope to filter by pemilih.
     */
    public function scopeForPemilih($query, int $pemilihId)
    {
        return $query->where('pemilih_id', $pemilihId);
    }

    /**
     * Get voting time formatted.
     */
    public function getVotingTimeFormattedAttribute(): string
    {
        return $this->waktu_voting->format('d/m/Y H:i:s');
    }

    /**
     * Get validity status.
     */
    public function getValidityStatusAttribute(): string
    {
        return $this->is_valid ? 'Valid' : 'Invalid';
    }

    /**
     * Get validity badge color.
     */
    public function getValidityBadgeColorAttribute(): string
    {
        return $this->is_valid ? 'green' : 'red';
    }
}
