<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'position',
        'class',
        'graduation_year',
        'testimonial',
        'rating',
        'photo',
        'is_approved',
        'is_featured',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];

    // Scope untuk testimonial yang sudah disetujui
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope untuk testimonial unggulan
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Get dummy testimonials jika database kosong
    public static function getDummyTestimonials()
    {
        return [
            [
                'name' => 'Ahmad Rizki',
                'position' => 'Alumni',
                'class' => null,
                'graduation_year' => '2023',
                'testimonial' => 'Sekolah ini memberikan pendidikan yang sangat baik. Guru-gurunya sangat kompeten dan lingkungan belajar yang kondusif membuat saya bisa berkembang dengan optimal.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/01.jpg'),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'position' => 'Siswa',
                'class' => 'XII IPA 1',
                'graduation_year' => null,
                'testimonial' => 'Sebagai siswa, saya sangat senang belajar di sekolah ini. Fasilitas yang lengkap dan metode pembelajaran yang menarik membuat saya semakin semangat belajar.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/02.jpg'),
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 'Guru',
                'class' => null,
                'graduation_year' => null,
                'testimonial' => 'Mengajar di sekolah ini adalah pengalaman yang sangat berharga. Siswa-siswa yang antusias dan dukungan dari pihak sekolah membuat proses mengajar menjadi menyenangkan.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/03.jpg'),
            ],
            [
                'name' => 'Maya Sari',
                'position' => 'Alumni',
                'class' => null,
                'graduation_year' => '2022',
                'testimonial' => 'Sekolah ini tidak hanya mengajarkan akademik, tetapi juga nilai-nilai kehidupan yang penting. Saya sangat berterima kasih atas semua yang telah diberikan.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/04.jpg'),
            ],
            [
                'name' => 'Rizki Pratama',
                'position' => 'Siswa',
                'class' => 'XI IPS 2',
                'graduation_year' => null,
                'testimonial' => 'Program ekstrakurikuler di sekolah ini sangat beragam dan menarik. Saya bisa mengembangkan bakat dan minat saya di luar akademik.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/05.jpg'),
            ],
            [
                'name' => 'Dr. Sarah Wijaya',
                'position' => 'Guru',
                'class' => null,
                'graduation_year' => null,
                'testimonial' => 'Sebagai pendidik, saya bangga menjadi bagian dari sekolah ini. Komitmen untuk memberikan pendidikan terbaik selalu menjadi prioritas utama.',
                'rating' => 5,
                'photo' => asset('assets/img/testimonial/06.jpg'),
            ],
        ];
    }
}
