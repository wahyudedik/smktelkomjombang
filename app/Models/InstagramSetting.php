<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstagramSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'user_id',
        'username', // NEW: Instagram username
        'account_type', // NEW: BUSINESS or CREATOR
        'app_id',
        'app_secret',
        'redirect_uri',
        'webhook_verify_token', // NEW: Webhook verification token
        'is_active',
        'last_sync',
        'token_expires_at', // NEW: Token expiry tracking
        'sync_frequency',
        'auto_sync_enabled',
        'cache_duration',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_sync_enabled' => 'boolean',
        'last_sync' => 'datetime',
        'token_expires_at' => 'datetime', // NEW
        'cache_duration' => 'integer',
        'sync_frequency' => 'integer'
    ];

    /**
     * Get the user who created this setting
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this setting
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get active Instagram settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if settings are complete
     */
    public function isComplete()
    {
        return !empty($this->access_token) && !empty($this->user_id);
    }

    /**
     * Get formatted settings for API
     */
    public function getApiConfig()
    {
        return [
            'access_token' => $this->access_token,
            'user_id' => $this->user_id,
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'redirect_uri' => $this->redirect_uri,
        ];
    }

    /**
     * Update last sync time
     */
    public function updateLastSync()
    {
        $this->update(['last_sync' => now()]);
    }

    /**
     * Get sync status
     */
    public function getSyncStatusAttribute()
    {
        if (!$this->last_sync) {
            return 'never';
        }

        $hoursSinceLastSync = $this->last_sync->diffInHours(now());

        if ($hoursSinceLastSync < 1) {
            return 'recent';
        } elseif ($hoursSinceLastSync < 24) {
            return 'today';
        } else {
            return 'outdated';
        }
    }

    /**
     * Get sync status badge color
     */
    public function getSyncStatusBadgeColorAttribute()
    {
        switch ($this->sync_status) {
            case 'recent':
                return 'green';
            case 'today':
                return 'yellow';
            case 'outdated':
                return 'red';
            default:
                return 'gray';
        }
    }

    /**
     * Check if token is expired or will expire soon
     * NEW: Token expiry checking
     */
    public function isTokenExpired()
    {
        if (!$this->token_expires_at) {
            return false; // Unknown expiry, assume valid
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Check if token will expire soon (within 7 days)
     * NEW: Token expiry warning
     */
    public function isTokenExpiringSoon()
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->diffInDays(now()) <= 7 && !$this->isTokenExpired();
    }

    /**
     * Get token status
     * NEW: Token health status
     */
    public function getTokenStatusAttribute()
    {
        if ($this->isTokenExpired()) {
            return 'expired';
        }

        if ($this->isTokenExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'active';
    }
}
