<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Events extends Model
{
    use Auditable;

    protected $fillable = [
        'title',
        'description',
        'date',
        'category',
        'image',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Scope: Get only active events
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())
            ->where('status', 'active')
            ->orderBy('date', 'asc');
    }

    /**
     * Scope: Get events by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get dummy events for fallback
     */
    public static function getDummyEvents()
    {
        return [
            [
                'id' => 1,
                'title' => 'SKATELDU 2026',
                'description' => 'Acara tahunan SMK Telekomunikasi Darul Ulum',
                'date' => now()->addDays(30),
                'category' => 'Acara Sekolah',
                'image' => 'assets_telkom/assets/images/event/1.jpg',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'title' => 'Lomba Inovasi Teknologi',
                'description' => 'Kompetisi inovasi teknologi untuk siswa',
                'date' => now()->addDays(45),
                'category' => 'Kompetisi',
                'image' => 'assets_telkom/assets/images/event/2.jpg',
                'status' => 'active',
            ],
            [
                'id' => 3,
                'title' => 'Workshop Industri 4.0',
                'description' => 'Workshop tentang perkembangan industri 4.0',
                'date' => now()->addDays(60),
                'category' => 'Workshop',
                'image' => 'assets_telkom/assets/images/event/3.jpg',
                'status' => 'active',
            ],
        ];
    }
}

