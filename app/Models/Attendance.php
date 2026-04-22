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
    ];

    protected $casts = [
        'date' => 'date',
        'first_in_at' => 'datetime',
        'last_out_at' => 'datetime',
    ];

    public function identity(): BelongsTo
    {
        return $this->belongsTo(AttendanceIdentity::class, 'attendance_identity_id');
    }
}
