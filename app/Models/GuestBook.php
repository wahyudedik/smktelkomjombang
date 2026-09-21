<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class GuestBook extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'ticket_number',
        'guest_name',
        'nik',
        'address',
        'phone',
        'email',
        'organization',
        'position',
        'visit_category',
        'visit_purpose',
        'visit_target',
        'photo_path',
        'vehicle_type',
        'vehicle_plate',
        'status',
        'check_in_at',
        'check_out_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'check_in');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('check_in_at', today());
    }

    /**
     * Scope untuk check-out guests
     */
    public function scopeCheckOut($query)
    {
        return $query->where('status', 'check_out');
    }

    /**
     * Scope untuk data bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('check_in_at', now()->month)
                     ->whereYear('check_in_at', now()->year);
    }

    /**
     * Boot method — auto-generate ticket_number and check_in_at
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (GuestBook $guest): void {
            if (empty($guest->ticket_number)) {
                $date = now()->format('Ymd');
                $lastTicket = static::whereDate('check_in_at', today())->count() + 1;
                $guest->ticket_number = 'BT-' . $date . '-' . str_pad((string) $lastTicket, 4, '0', STR_PAD_LEFT);
            }

            if (is_null($guest->check_in_at)) {
                $guest->check_in_at = now();
            }
        });
    }

    /**
     * Accessor untuk foto URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    /**
     * Helper: durasi kunjungan
     */
    public function getDurationAttribute(): ?string
    {
        if (! $this->check_out_at) {
            return null;
        }

        $diff = $this->check_in_at->diff($this->check_out_at);

        if ($diff->h > 0) {
            return $diff->h . ' jam ' . $diff->i . ' menit';
        }

        return $diff->i . ' menit';
    }
}
