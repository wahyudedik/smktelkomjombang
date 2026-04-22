<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceCommand extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_device_id',
        'kind',
        'device_pin',
        'command',
        'status',
        'sent_at',
        'executed_at',
        'result_code',
        'result_raw',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'executed_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(AttendanceDevice::class, 'attendance_device_id');
    }
}
