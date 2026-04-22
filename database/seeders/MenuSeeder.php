<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get superadmin user
        $superadmin = User::where('email', 'superadmin@sekolah.com')->first();

        if (!$superadmin) {
            $this->command->error('Superadmin user not found. Please run UserSeeder first.');
            return;
        }

        // Create main menu items
        $profilMenu = Page::create([
            'title' => 'Profil Sekolah',
            'slug' => 'profil-sekolah',
            'content' => 'Halaman profil sekolah yang berisi informasi lengkap tentang sejarah, visi, misi, dan tujuan sekolah.',
            'excerpt' => 'Informasi lengkap tentang profil sekolah kami.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PROFIL',
            'menu_position' => 'header',
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $akademikMenu = Page::create([
            'title' => 'Akademik',
            'slug' => 'akademik',
            'content' => 'Informasi tentang program akademik, kurikulum, dan kegiatan pembelajaran.',
            'excerpt' => 'Program akademik dan kurikulum sekolah.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'AKADEMIK',
            'menu_position' => 'header',
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $layananMenu = Page::create([
            'title' => 'Layanan Digital',
            'slug' => 'layanan-digital',
            'content' => 'Kumpulan layanan digital yang tersedia untuk siswa, orang tua, dan masyarakat.',
            'excerpt' => 'Layanan digital sekolah untuk kemudahan akses informasi.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'LAYANAN DIGITAL',
            'menu_position' => 'header',
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for PROFIL
        Page::create([
            'title' => 'Sejarah Sekolah',
            'slug' => 'sejarah-sekolah',
            'content' => 'Sejarah berdirinya sekolah dan perjalanan panjang dalam dunia pendidikan.',
            'excerpt' => 'Sejarah dan perjalanan sekolah dalam dunia pendidikan.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'SEJARAH',
            'menu_position' => 'header',
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Visi & Misi',
            'slug' => 'visi-misi',
            'content' => 'Visi, misi, dan tujuan sekolah dalam membentuk generasi yang berkualitas.',
            'excerpt' => 'Visi, misi, dan tujuan sekolah.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'VISI & MISI',
            'menu_position' => 'header',
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Struktur Organisasi',
            'slug' => 'struktur-organisasi',
            'content' => 'Struktur organisasi sekolah dan susunan kepemimpinan.',
            'excerpt' => 'Struktur organisasi dan kepemimpinan sekolah.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'STRUKTUR ORGANISASI',
            'menu_position' => 'header',
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for AKADEMIK
        Page::create([
            'title' => 'Kurikulum',
            'slug' => 'kurikulum',
            'content' => 'Informasi tentang kurikulum yang digunakan dan mata pelajaran yang diajarkan.',
            'excerpt' => 'Kurikulum dan mata pelajaran yang diajarkan.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'KURIKULUM',
            'menu_position' => 'header',
            'parent_id' => $akademikMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Program Unggulan',
            'slug' => 'program-unggulan',
            'content' => 'Program-program unggulan sekolah yang membedakan dengan sekolah lain.',
            'excerpt' => 'Program unggulan sekolah.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PROGRAM UNGGULAN',
            'menu_position' => 'header',
            'parent_id' => $akademikMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for LAYANAN DIGITAL
        Page::create([
            'title' => 'E-Learning',
            'slug' => 'e-learning',
            'content' => 'Platform pembelajaran online untuk siswa dan guru.',
            'excerpt' => 'Platform pembelajaran online.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'E-LEARNING',
            'menu_position' => 'header',
            'parent_id' => $layananMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Portal Orang Tua',
            'slug' => 'portal-orang-tua',
            'content' => 'Portal khusus untuk orang tua siswa untuk mengakses informasi akademik anak.',
            'excerpt' => 'Portal untuk orang tua siswa.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PORTAL ORANG TUA',
            'menu_position' => 'header',
            'parent_id' => $layananMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create footer menu items
        Page::create([
            'title' => 'Kebijakan Privasi',
            'slug' => 'kebijakan-privasi',
            'content' => 'Kebijakan privasi dan perlindungan data pengguna website sekolah.',
            'excerpt' => 'Kebijakan privasi dan perlindungan data.',
            'category' => 'legal',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Kebijakan Privasi',
            'menu_position' => 'footer',
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Syarat & Ketentuan',
            'slug' => 'syarat-ketentuan',
            'content' => 'Syarat dan ketentuan penggunaan website sekolah.',
            'excerpt' => 'Syarat dan ketentuan penggunaan website.',
            'category' => 'legal',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Syarat & Ketentuan',
            'menu_position' => 'footer',
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::create([
            'title' => 'Kontak Kami',
            'slug' => 'kontak-kami',
            'content' => 'Informasi kontak sekolah untuk keperluan komunikasi dan informasi.',
            'excerpt' => 'Informasi kontak sekolah.',
            'category' => 'kontak',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Kontak Kami',
            'menu_position' => 'footer',
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $this->command->info('Menu data seeded successfully!');
    }
}
