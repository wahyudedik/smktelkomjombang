<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_identity_id',
        'date',
        'first_in_at',
        'last_out_at',
        'status',
        'overtime_minutes',
    ];

    protected $casts = [
        'date' => 'date',
        'first_in_at' => 'datetime',
        'last_out_at' => 'datetime',
        'overtime_minutes' => 'integer',
    ];

    public function identity(): BelongsTo
    {
        return $this->belongsTo(AttendanceIdentity::class, 'attendance_identity_id');
    }

    /**
     * Estimasi nominal lembur (Rupiah) = menit lembur / 60 * rate per jam.
     * Rate diambil dari config `attendance.overtime_rate_per_hour`
     * (priority DB AttendanceSetting → config/attendance.php → 0).
     */
    public function getOvertimePayAttribute(): int
    {
        $ratePerHour = (float) attendance_config('overtime_rate_per_hour', 0);

        return (int) round(($this->overtime_minutes / 60) * $ratePerHour);
    }
}
