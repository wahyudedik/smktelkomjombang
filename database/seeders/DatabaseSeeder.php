<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core system seeders (urut sesuai dependency)
            RoleSeeder::class, // Create roles first (superadmin, admin, guru, siswa, sarpras)
            PermissionSeeder::class, // Create permissions and assign to superadmin
            UserSeeder::class, // Create superadmin user and assign role
            TestimonialLinksPermissionSeeder::class, // Assign testimonial-links permissions

            // Data management seeders
            DataManagementSeeder::class, // Create kelas, jurusan, ekstrakurikuler
            MataPelajaranSeeder::class, // Create mata pelajaran

            // Module-specific seeders (butuh UserSeeder untuk user_id)
            GuruSeeder::class, // Butuh UserSeeder
            SiswaSeeder::class, // Butuh UserSeeder
            KelulusanSeeder::class, // Tidak ada dependency
            SarprasSeeder::class, // Butuh UserSeeder untuk maintenance user_id
            OSISSeeder::class, // Tidak ada dependency

            // Content management seeders (butuh UserSeeder untuk user_id)
            MenuSeeder::class, // Butuh UserSeeder
            PageSeeder::class, // Butuh UserSeeder
            NotificationSeeder::class, // Butuh UserSeeder
            LetterSeeder::class, // Format Surat
            AttendanceSeeder::class, // Absensi
        ]);
    }
}
