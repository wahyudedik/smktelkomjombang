<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id',
        'user_id',
        'action',
        'details'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
