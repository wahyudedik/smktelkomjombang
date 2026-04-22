<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class OsisElection extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'is_locked',
        'max_votes_per_student',
        'allowed_classes',
        'settings',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'allowed_classes' => 'array',
        'settings' => 'array',
    ];

    /**
     * Get the candidates for this election
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Calon::class, 'election_id');
    }

    /**
     * Get the votes for this election
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Voting::class, 'election_id');
    }

    /**
     * Check if election is currently active
     */
    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->is_active &&
            $this->start_date <= $now &&
            $this->end_date >= $now &&
            !$this->is_locked;
    }

    /**
     * Check if election has ended
     */
    public function hasEnded(): bool
    {
        return $this->end_date < now() || $this->is_locked;
    }

    /**
     * Check if election is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_date > now();
    }

    /**
     * Get election status
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_locked) {
            return 'locked';
        }

        if ($this->isUpcoming()) {
            return 'upcoming';
        }

        if ($this->isCurrentlyActive()) {
            return 'active';
        }

        return 'ended';
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'Akan Datang',
            'active' => 'Sedang Berlangsung',
            'ended' => 'Selesai',
            'locked' => 'Terkunci',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'bg-blue-100 text-blue-800',
            'active' => 'bg-green-100 text-green-800',
            'ended' => 'bg-gray-100 text-gray-800',
            'locked' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get time remaining until election starts
     */
    public function getTimeUntilStartAttribute(): string
    {
        if ($this->isUpcoming()) {
            return $this->start_date->diffForHumans();
        }
        return '';
    }

    /**
     * Get time remaining until election ends
     */
    public function getTimeUntilEndAttribute(): string
    {
        if ($this->isCurrentlyActive()) {
            return $this->end_date->diffForHumans();
        }
        return '';
    }

    /**
     * Scope for active elections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_locked', false);
    }

    /**
     * Scope for upcoming elections
     */
    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '>', now());
    }

    /**
     * Scope for ended elections
     */
    public function scopeEnded($query)
    {
        return $query->where(function ($q) {
            $q->where('end_date', '<', now())
                ->orWhere('is_locked', true);
        });
    }
}
