<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type', // 'in' or 'out'
        'period_mode', // 'year', 'month', 'all'
        'counter_scope', // 'global', 'unit'
        'format_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function segments()
    {
        return $this->hasMany(LetterFormatSegment::class)->orderBy('order');
    }

    public function counters()
    {
        return $this->hasMany(LetterCounter::class);
    }
}
