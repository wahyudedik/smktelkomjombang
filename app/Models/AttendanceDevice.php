<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'name',
        'ip_address',
        'port',
        'comm_key',
        'is_active',
        'last_seen_at',
        'last_processed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_processed_at' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function commands(): HasMany
    {
        return $this->hasMany(AttendanceCommand::class, 'attendance_device_id');
    }
}
