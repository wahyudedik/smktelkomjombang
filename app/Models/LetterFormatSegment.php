<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterFormatSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_format_id',
        'type', // 'sequence', 'text', 'unit_code', 'day', 'month_roman', 'month_number', 'year', 'year_roman'
        'value', // For 'text' type
        'padding', // For 'sequence' type (e.g., 3 for 001)
        'order'
    ];

    public function format()
    {
        return $this->belongsTo(LetterFormat::class, 'letter_format_id');
    }
}
