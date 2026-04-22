<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TestimonialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'token',
        'description',
        'target_audience',
        'active_from',
        'active_until',
        'is_active',
        'max_submissions',
        'current_submissions',
        'created_by',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'active_from' => 'datetime',
        'active_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Generate unique token
    public static function generateToken()
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());
        
        return $token;
    }

    // Check if link is currently active
    public function isCurrentlyActive()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $now->gte($this->active_from) && 
               $now->lte($this->active_until);
    }

    // Check if link has reached max submissions
    public function hasReachedMaxSubmissions()
    {
        if ($this->max_submissions === null) {
            return false;
        }
        
        return $this->current_submissions >= $this->max_submissions;
    }

    // Check if user can submit (based on target audience)
    public function canSubmitForPosition($position)
    {
        return in_array($position, $this->target_audience);
    }

    // Increment submission count
    public function incrementSubmissions()
    {
        $this->increment('current_submissions');
    }

    // Get public URL
    public function getPublicUrl()
    {
        return route('testimonials.public', ['token' => $this->token]);
    }

    // Scope for active links
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('active_from', '<=', now())
                    ->where('active_until', '>=', now());
    }

    // Scope for expired links
    public function scopeExpired($query)
    {
        return $query->where('active_until', '<', now());
    }
}
