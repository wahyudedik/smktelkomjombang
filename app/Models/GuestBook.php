<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        'signature_path',
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
                $guest->ticket_number = static::generateTicketNumber();
            }

            if (is_null($guest->check_in_at)) {
                $guest->check_in_at = now();
            }
        });
    }

    /**
     * Generate a unique ticket number (race-safe).
     *
     * The count query runs inside a transaction with lockForUpdate so concurrent
     * check-ins serialize on the count. The guest_books.ticket_number column has a
     * DB-level unique constraint; on a duplicate-key collision (still possible for
     * the very first guest of the day, when no rows exist to lock) we regenerate
     * and retry.
     *
     * Format: BT-YYYYMMDD-XXXX (unchanged).
     */
    protected static function generateTicketNumber(): string
    {
        $maxAttempts = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return DB::transaction(function () {
                    $date = now()->format('Ymd');
                    $lastTicket = static::query()
                        ->whereDate('check_in_at', today())
                        ->lockForUpdate()
                        ->count();

                    return 'BT-' . $date . '-' . str_pad((string) ($lastTicket + 1), 4, '0', STR_PAD_LEFT);
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // 23000 = integrity constraint violation (duplicate ticket_number)
                if ($attempt === $maxAttempts || (string) $e->getCode() !== '23000') {
                    throw $e;
                }

                Log::warning("GuestBook ticket number collision on attempt {$attempt}, regenerating...");
            }
        }

        // Unreachable in practice; satisfies static analysis.
        throw new \RuntimeException('Failed to generate unique guest book ticket number');
    }

    /**
     * Accessor untuk foto URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    /**
     * Accessor untuk signature URL
     */
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_path ? asset('storage/' . $this->signature_path) : null;
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
