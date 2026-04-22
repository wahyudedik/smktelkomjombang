<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get superadmin user
        $superadmin = User::whereHas('roles', function ($q) {
            $q->where('name', 'superadmin');
        })->first();

        if (!$superadmin) {
            $this->command->warn('Superadmin user not found. Please run UserSeeder first.');
            return;
        }

        // Create sample students
        $siswas = [
            [
                'nis' => '2024001',
                'nisn' => '1234567890',
                'nama_lengkap' => 'Ahmad Rizki Pratama',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-05-15',
                'tempat_lahir' => 'Jakarta',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'no_telepon' => '021-1234567',
                'no_wa' => '081234567890',
                'email' => 'ahmad.rizki@student.com',
                'kelas' => 'X IPA 1',
                'jurusan' => 'IPA',
                'tahun_masuk' => 2024,
                'status' => 'aktif',
                'nama_ayah' => 'Budi Pratama',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'no_telepon_ortu' => '081234567891',
                'alamat_ortu' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'prestasi' => 'Juara 1 Olimpiade Matematika Tingkat Kota 2023',
                'catatan' => 'Siswa berprestasi dengan nilai akademik yang baik',
                'nilai_akademik' => [
                    'matematika' => 95,
                    'fisika' => 92,
                    'kimia' => 88,
                    'biologi' => 90,
                    'bahasa_indonesia' => 85,
                    'bahasa_inggris' => 87
                ],
                'ekstrakurikuler' => ['Basket', 'Debat Bahasa Inggris', 'Science Club'],
                'user_id' => $superadmin->id,
            ],
            [
                'nis' => '2024002',
                'nisn' => '1234567891',
                'nama_lengkap' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-03-22',
                'tempat_lahir' => 'Bandung',
                'alamat' => 'Jl. Pendidikan No. 456, Bandung',
                'no_telepon' => '022-7654321',
                'no_wa' => '081234567892',
                'email' => 'siti.nurhaliza@student.com',
                'kelas' => 'X IPS 1',
                'jurusan' => 'IPS',
                'tahun_masuk' => 2024,
                'status' => 'aktif',
                'nama_ayah' => 'Ahmad Hidayat',
                'pekerjaan_ayah' => 'Guru',
                'nama_ibu' => 'Fatimah',
                'pekerjaan_ibu' => 'PNS',
                'no_telepon_ortu' => '081234567893',
                'alamat_ortu' => 'Jl. Pendidikan No. 456, Bandung',
                'prestasi' => 'Juara 2 Lomba Tari Tradisional Tingkat Provinsi 2023',
                'catatan' => 'Siswa aktif dalam kegiatan seni dan budaya',
                'nilai_akademik' => [
                    'matematika' => 88,
                    'ekonomi' => 95,
                    'sosiologi' => 92,
                    'geografi' => 90,
                    'bahasa_indonesia' => 93,
                    'bahasa_inggris' => 89
                ],
                'ekstrakurikuler' => ['Tari Tradisional', 'Paduan Suara', 'Teater'],
                'user_id' => $superadmin->id,
            ],
            [
                'nis' => '2023001',
                'nisn' => '1234567892',
                'nama_lengkap' => 'Muhammad Fajar',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2007-08-10',
                'tempat_lahir' => 'Surabaya',
                'alamat' => 'Jl. Kemerdekaan No. 789, Surabaya',
                'no_telepon' => '031-9876543',
                'no_wa' => '081234567894',
                'email' => 'muhammad.fajar@student.com',
                'kelas' => 'XI IPA 2',
                'jurusan' => 'IPA',
                'tahun_masuk' => 2023,
                'status' => 'aktif',
                'nama_ayah' => 'Rahmat Hidayat',
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => 'Sari Dewi',
                'pekerjaan_ibu' => 'Dokter',
                'no_telepon_ortu' => '081234567895',
                'alamat_ortu' => 'Jl. Kemerdekaan No. 789, Surabaya',
                'prestasi' => 'Juara 3 Olimpiade Fisika Tingkat Nasional 2023',
                'catatan' => 'Siswa dengan minat tinggi di bidang sains',
                'nilai_akademik' => [
                    'matematika' => 92,
                    'fisika' => 96,
                    'kimia' => 94,
                    'biologi' => 89,
                    'bahasa_indonesia' => 87,
                    'bahasa_inggris' => 91
                ],
                'ekstrakurikuler' => ['Science Club', 'Fotografi', 'Basket'],
                'user_id' => $superadmin->id,
            ],
            [
                'nis' => '2022001',
                'nisn' => '1234567893',
                'nama_lengkap' => 'Putri Sari',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2006-12-05',
                'tempat_lahir' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 321, Yogyakarta',
                'no_telepon' => '0274-1234567',
                'no_wa' => '081234567896',
                'email' => 'putri.sari@student.com',
                'kelas' => 'XII IPS 1',
                'jurusan' => 'IPS',
                'tahun_masuk' => 2022,
                'tahun_lulus' => 2024,
                'status' => 'lulus',
                'nama_ayah' => 'Bambang Sutrisno',
                'pekerjaan_ayah' => 'Pengusaha',
                'nama_ibu' => 'Rina Sari',
                'pekerjaan_ibu' => 'Akuntan',
                'no_telepon_ortu' => '081234567897',
                'alamat_ortu' => 'Jl. Malioboro No. 321, Yogyakarta',
                'prestasi' => 'Juara 1 Debat Bahasa Inggris Tingkat Nasional 2024, Juara 2 Lomba Menulis Essay 2024',
                'catatan' => 'Siswa lulusan dengan prestasi akademik dan non-akademik yang baik',
                'nilai_akademik' => [
                    'matematika' => 85,
                    'ekonomi' => 98,
                    'sosiologi' => 95,
                    'geografi' => 92,
                    'bahasa_indonesia' => 96,
                    'bahasa_inggris' => 94
                ],
                'ekstrakurikuler' => ['Debat Bahasa Inggris', 'Teater', 'Literasi Digital'],
                'user_id' => $superadmin->id,
            ],
            [
                'nis' => '2024003',
                'nisn' => '1234567894',
                'nama_lengkap' => 'Rizki Aditya',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-01-18',
                'tempat_lahir' => 'Medan',
                'alamat' => 'Jl. Gatot Subroto No. 654, Medan',
                'no_telepon' => '061-4567890',
                'no_wa' => '081234567898',
                'email' => 'rizki.aditya@student.com',
                'kelas' => 'X IPA 3',
                'jurusan' => 'IPA',
                'tahun_masuk' => 2024,
                'status' => 'aktif',
                'nama_ayah' => 'Aditya Pratama',
                'pekerjaan_ayah' => 'Insinyur',
                'nama_ibu' => 'Dewi Sartika',
                'pekerjaan_ibu' => 'Guru',
                'no_telepon_ortu' => '081234567899',
                'alamat_ortu' => 'Jl. Gatot Subroto No. 654, Medan',
                'prestasi' => 'Juara 1 Lomba Robotik Tingkat Kota 2024',
                'catatan' => 'Siswa dengan minat tinggi di bidang teknologi',
                'nilai_akademik' => [
                    'matematika' => 90,
                    'fisika' => 93,
                    'kimia' => 87,
                    'biologi' => 85,
                    'bahasa_indonesia' => 88,
                    'bahasa_inggris' => 86
                ],
                'ekstrakurikuler' => ['Robotik', 'Programming Club', 'Futsal'],
                'user_id' => $superadmin->id,
            ],
        ];

        foreach ($siswas as $siswaData) {
            Siswa::create($siswaData);
        }

        $this->command->info('Siswa seeder completed successfully!');
    }
}
