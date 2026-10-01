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

        // ⭐ Use current theme so menus are theme-specific, not global (null).
        // This prevents duplicate menus when multiple themes have their own menus.
        $theme = current_theme();

        // Create main menu items
        // ⭐ Idempotent: firstOrCreate by slug — aman di-run berulang tanpa duplikat/unique violation
        $profilMenu = Page::firstOrCreate(['slug' => 'profil-sekolah'], [
            'title' => 'Profil Sekolah',
            'content' => 'Halaman profil sekolah yang berisi informasi lengkap tentang sejarah, visi, misi, dan tujuan sekolah.',
            'excerpt' => 'Informasi lengkap tentang profil sekolah kami.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PROFIL',
            'menu_position' => 'header',
            'theme' => $theme,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $akademikMenu = Page::firstOrCreate(['slug' => 'akademik'], [
            'title' => 'Akademik',
            'content' => 'Informasi tentang program akademik, kurikulum, dan kegiatan pembelajaran.',
            'excerpt' => 'Program akademik dan kurikulum sekolah.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'AKADEMIK',
            'menu_position' => 'header',
            'theme' => $theme,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $layananMenu = Page::firstOrCreate(['slug' => 'layanan-digital'], [
            'title' => 'Layanan Digital',
            'content' => 'Kumpulan layanan digital yang tersedia untuk siswa, orang tua, dan masyarakat.',
            'excerpt' => 'Layanan digital sekolah untuk kemudahan akses informasi.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'LAYANAN DIGITAL',
            'menu_position' => 'header',
            'theme' => $theme,
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for PROFIL
        Page::firstOrCreate(['slug' => 'sejarah-sekolah'], [
            'title' => 'Sejarah Sekolah',
            'content' => 'Sejarah berdirinya sekolah dan perjalanan panjang dalam dunia pendidikan.',
            'excerpt' => 'Sejarah dan perjalanan sekolah dalam dunia pendidikan.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'SEJARAH',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'visi-misi'], [
            'title' => 'Visi & Misi',
            'content' => 'Visi, misi, dan tujuan sekolah dalam membentuk generasi yang berkualitas.',
            'excerpt' => 'Visi, misi, dan tujuan sekolah.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'VISI & MISI',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'struktur-organisasi'], [
            'title' => 'Struktur Organisasi',
            'content' => 'Struktur organisasi sekolah dan susunan kepemimpinan.',
            'excerpt' => 'Struktur organisasi dan kepemimpinan sekolah.',
            'category' => 'profil',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'STRUKTUR ORGANISASI',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $profilMenu->id,
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for AKADEMIK
        Page::firstOrCreate(['slug' => 'kurikulum'], [
            'title' => 'Kurikulum',
            'content' => 'Informasi tentang kurikulum yang digunakan dan mata pelajaran yang diajarkan.',
            'excerpt' => 'Kurikulum dan mata pelajaran yang diajarkan.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'KURIKULUM',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $akademikMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'program-unggulan'], [
            'title' => 'Program Unggulan',
            'content' => 'Program-program unggulan sekolah yang membedakan dengan sekolah lain.',
            'excerpt' => 'Program unggulan sekolah.',
            'category' => 'akademik',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PROGRAM UNGGULAN',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $akademikMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create submenu items for LAYANAN DIGITAL
        Page::firstOrCreate(['slug' => 'e-learning'], [
            'title' => 'E-Learning',
            'content' => 'Platform pembelajaran online untuk siswa dan guru.',
            'excerpt' => 'Platform pembelajaran online.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'E-LEARNING',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $layananMenu->id,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'portal-orang-tua'], [
            'title' => 'Portal Orang Tua',
            'content' => 'Portal khusus untuk orang tua siswa untuk mengakses informasi akademik anak.',
            'excerpt' => 'Portal untuk orang tua siswa.',
            'category' => 'layanan',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'PORTAL ORANG TUA',
            'menu_position' => 'header',
            'theme' => $theme,
            'parent_id' => $layananMenu->id,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        // Create footer menu items
        Page::firstOrCreate(['slug' => 'kebijakan-privasi'], [
            'title' => 'Kebijakan Privasi',
            'content' => 'Kebijakan privasi dan perlindungan data pengguna website sekolah.',
            'excerpt' => 'Kebijakan privasi dan perlindungan data.',
            'category' => 'legal',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Kebijakan Privasi',
            'menu_position' => 'footer',
            'theme' => $theme,
            'menu_sort_order' => 1,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'syarat-ketentuan'], [
            'title' => 'Syarat & Ketentuan',
            'content' => 'Syarat dan ketentuan penggunaan website sekolah.',
            'excerpt' => 'Syarat dan ketentuan penggunaan website.',
            'category' => 'legal',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Syarat & Ketentuan',
            'menu_position' => 'footer',
            'theme' => $theme,
            'menu_sort_order' => 2,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        Page::firstOrCreate(['slug' => 'kontak-kami'], [
            'title' => 'Kontak Kami',
            'content' => 'Informasi kontak sekolah untuk keperluan komunikasi dan informasi.',
            'excerpt' => 'Informasi kontak sekolah.',
            'category' => 'kontak',
            'template' => 'default',
            'status' => 'published',
            'is_featured' => false,
            'is_menu' => true,
            'menu_title' => 'Kontak Kami',
            'menu_position' => 'footer',
            'theme' => $theme,
            'menu_sort_order' => 3,
            'published_at' => now(),
            'user_id' => $superadmin->id,
        ]);

        $this->command->info('Menu data seeded successfully!');
    }
}
