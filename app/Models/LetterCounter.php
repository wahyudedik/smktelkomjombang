<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_format_id',
        'scope_unit_code',
        'year',
        'month',
        'current_value'
    ];

    public function format()
    {
        return $this->belongsTo(LetterFormat::class, 'letter_format_id');
    }
}
