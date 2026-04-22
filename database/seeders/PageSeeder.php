<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories
        $categories = [
            [
                'name' => 'Berita',
                'description' => 'Berita dan informasi terkini',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Acara',
                'description' => 'Acara dan kegiatan sekolah',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Prestasi',
                'description' => 'Prestasi siswa dan sekolah',
                'color' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Informasi',
                'description' => 'Informasi umum sekolah',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            PageCategory::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryData['name'])],
                $categoryData
            );
        }

        // Get superadmin user
        $superadmin = User::whereHas('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->first();

        if (!$superadmin) {
            $this->command->warn('Superadmin user not found. Please run UserSeeder first.');
            return;
        }

        // Create sample pages
        $pages = [
            [
                'title' => 'Selamat Datang di Website Sekolah',
                'content' => '<h2>Tentang Sekolah Kami</h2><p>Sekolah kami adalah institusi pendidikan yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada setiap siswa. Dengan fasilitas modern dan tenaga pendidik yang berpengalaman, kami siap membimbing siswa menuju masa depan yang cerah.</p><h3>Visi</h3><p>Menjadi sekolah unggulan yang menghasilkan lulusan berkarakter, berprestasi, dan siap menghadapi tantangan global.</p><h3>Misi</h3><ul><li>Menyelenggarakan pendidikan berkualitas tinggi</li><li>Mengembangkan karakter dan kepribadian siswa</li><li>Menyediakan fasilitas pembelajaran yang memadai</li><li>Membangun kerjasama dengan orang tua dan masyarakat</li></ul>',
                'excerpt' => 'Selamat datang di website resmi sekolah kami. Temukan informasi terbaru tentang kegiatan, prestasi, dan program pendidikan.',
                'category' => 'Informasi',
                'template' => 'about',
                'status' => 'published',
                'is_featured' => true,
                'seo_meta' => [
                    'title' => 'Selamat Datang - Website Sekolah',
                    'description' => 'Website resmi sekolah dengan informasi terbaru tentang kegiatan, prestasi, dan program pendidikan.',
                    'keywords' => 'sekolah, pendidikan, informasi, kegiatan, prestasi',
                ],
                'published_at' => now(),
            ],
            [
                'title' => 'Kegiatan Ekstrakurikuler Semester Ini',
                'content' => '<h2>Daftar Ekstrakurikuler</h2><p>Berikut adalah daftar ekstrakurikuler yang tersedia untuk semester ini:</p><h3>Olahraga</h3><ul><li>Basket</li><li>Futsal</li><li>Voli</li><li>Badminton</li></ul><h3>Seni</h3><ul><li>Paduan Suara</li><li>Tari Tradisional</li><li>Teater</li><li>Fotografi</li></ul><h3>Akademik</h3><ul><li>Debat Bahasa Inggris</li><li>Matematika Club</li><li>Science Club</li><li>Literasi Digital</li></ul>',
                'excerpt' => 'Daftar lengkap ekstrakurikuler yang tersedia untuk siswa semester ini, mulai dari olahraga, seni, hingga akademik.',
                'category' => 'Informasi',
                'template' => 'default',
                'status' => 'published',
                'is_featured' => false,
                'seo_meta' => [
                    'title' => 'Ekstrakurikuler Semester Ini - Sekolah',
                    'description' => 'Daftar lengkap ekstrakurikuler yang tersedia untuk siswa semester ini.',
                    'keywords' => 'ekstrakurikuler, kegiatan, olahraga, seni, akademik',
                ],
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Prestasi Siswa di Olimpiade Matematika',
                'content' => '<h2>Prestasi Membanggakan</h2><p>Kami bangga mengumumkan bahwa siswa-siswi kami berhasil meraih prestasi gemilang dalam Olimpiade Matematika tingkat provinsi.</p><h3>Pencapaian</h3><ul><li>Juara 1: Ahmad Rizki (Kelas 12)</li><li>Juara 2: Siti Nurhaliza (Kelas 11)</li><li>Juara 3: Muhammad Fajar (Kelas 10)</li></ul><p>Prestasi ini menunjukkan kualitas pendidikan matematika di sekolah kami yang terus meningkat dari tahun ke tahun.</p>',
                'excerpt' => 'Siswa-siswi kami berhasil meraih prestasi gemilang dalam Olimpiade Matematika tingkat provinsi.',
                'category' => 'Prestasi',
                'template' => 'blog',
                'status' => 'published',
                'is_featured' => true,
                'seo_meta' => [
                    'title' => 'Prestasi Olimpiade Matematika - Sekolah',
                    'description' => 'Siswa-siswi kami berhasil meraih prestasi gemilang dalam Olimpiade Matematika tingkat provinsi.',
                    'keywords' => 'prestasi, olimpiade, matematika, juara, siswa',
                ],
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Jadwal Ujian Semester Ganjil',
                'content' => '<h2>Informasi Ujian</h2><p>Berikut adalah jadwal ujian semester ganjil untuk semua tingkat:</p><h3>Kelas 10</h3><ul><li>Matematika: 15 Desember 2024</li><li>Bahasa Indonesia: 16 Desember 2024</li><li>Bahasa Inggris: 17 Desember 2024</li></ul><h3>Kelas 11</h3><ul><li>Matematika: 18 Desember 2024</li><li>Fisika: 19 Desember 2024</li><li>Kimia: 20 Desember 2024</li></ul><h3>Kelas 12</h3><ul><li>Matematika: 21 Desember 2024</li><li>Bahasa Indonesia: 22 Desember 2024</li><li>Bahasa Inggris: 23 Desember 2024</li></ul>',
                'excerpt' => 'Jadwal lengkap ujian semester ganjil untuk semua tingkat kelas.',
                'category' => 'Informasi',
                'template' => 'default',
                'status' => 'published',
                'is_featured' => false,
                'seo_meta' => [
                    'title' => 'Jadwal Ujian Semester Ganjil - Sekolah',
                    'description' => 'Jadwal lengkap ujian semester ganjil untuk semua tingkat kelas.',
                    'keywords' => 'ujian, semester, jadwal, kelas, mata pelajaran',
                ],
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Galeri Kegiatan Sekolah',
                'content' => '<h2>Momen Berharga</h2><p>Berikut adalah dokumentasi kegiatan-kegiatan yang telah dilaksanakan di sekolah kami:</p><h3>Kegiatan Terbaru</h3><p>Foto-foto dari berbagai kegiatan seperti upacara bendera, lomba antar kelas, dan kegiatan ekstrakurikuler.</p>',
                'excerpt' => 'Dokumentasi kegiatan-kegiatan yang telah dilaksanakan di sekolah kami.',
                'category' => 'Acara',
                'template' => 'gallery',
                'status' => 'published',
                'is_featured' => false,
                'custom_fields' => [
                    'gallery_images' => [
                        [
                            'url' => 'https://images.unsplash.com/photo-1523240798132-8757214e76ba?w=500&h=500&fit=crop',
                            'caption' => 'Upacara Bendera Hari Senin'
                        ],
                        [
                            'url' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=500&h=500&fit=crop',
                            'caption' => 'Lomba Basket Antar Kelas'
                        ],
                        [
                            'url' => 'https://images.unsplash.com/photo-1568667256549-094345857637?w=500&h=500&fit=crop',
                            'caption' => 'Kunjungan Museum Sejarah'
                        ]
                    ]
                ],
                'seo_meta' => [
                    'title' => 'Galeri Kegiatan Sekolah',
                    'description' => 'Dokumentasi kegiatan-kegiatan yang telah dilaksanakan di sekolah kami.',
                    'keywords' => 'galeri, kegiatan, sekolah, foto, dokumentasi',
                ],
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($pages as $index => $pageData) {
            $pageData['user_id'] = $superadmin->id;
            $pageData['slug'] = \Illuminate\Support\Str::slug($pageData['title']);
            $pageData['sort_order'] = $index + 1; // Add sort_order

            $page = Page::create($pageData);

            // Create initial version for each page
            \App\Models\PageVersion::createFromPage($page, 'Initial version');
        }

        $this->command->info('Page seeder completed successfully!');
    }
}
