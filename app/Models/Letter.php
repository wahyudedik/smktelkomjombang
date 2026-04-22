<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', // incoming, outgoing
        'letter_format_id',
        'letter_number',
        'sequence_number',
        'reference_number',
        'letter_date',
        'sender',
        'recipient',
        'subject',
        'description',
        'file_path',
        'status',
        'created_by'
    ];

    protected $casts = [
        'letter_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function format()
    {
        return $this->belongsTo(LetterFormat::class, 'letter_format_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(LetterActivityLog::class);
    }
}
