<?php

namespace Database\Seeders;

use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Voting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OSISSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample calons
        $calons = [
            [
                'nama_ketua' => 'Ahmad Rizki Pratama',
                'nama_wakil' => 'Siti Nurhaliza',
                'visi_misi' => 'Visi: Menjadikan OSIS sebagai wadah aspirasi siswa yang transparan dan akuntabel. Misi: 1. Meningkatkan partisipasi siswa dalam kegiatan sekolah, 2. Membangun komunikasi yang baik antara siswa dan pihak sekolah, 3. Mengembangkan potensi siswa melalui berbagai kegiatan positif.',
                'jenis_pencalonan' => 'pasangan',
                'program_kerja' => '1. Program Literasi Digital, 2. Kegiatan Ekstrakurikuler yang Beragam, 3. Program Kesehatan Mental Siswa, 4. Kegiatan Sosial dan Bakti Masyarakat',
                'motivasi' => 'Kami ingin berkontribusi untuk kemajuan sekolah dan kesejahteraan seluruh siswa.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'nama_ketua' => 'Muhammad Fajar',
                'nama_wakil' => 'Putri Sari',
                'visi_misi' => 'Visi: Menciptakan lingkungan sekolah yang kondusif untuk belajar dan berkembang. Misi: 1. Meningkatkan kualitas pembelajaran, 2. Mengembangkan bakat dan minat siswa, 3. Menjalin kerjasama yang baik dengan semua pihak.',
                'jenis_pencalonan' => 'pasangan',
                'program_kerja' => '1. Program Beasiswa Siswa Berprestasi, 2. Kegiatan Olahraga dan Seni, 3. Program Lingkungan Hidup, 4. Kegiatan Keagamaan dan Spiritual',
                'motivasi' => 'Kami berkomitmen untuk melayani siswa dengan sepenuh hati dan mengutamakan kepentingan bersama.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'nama_ketua' => 'Rizki Aditya',
                'nama_wakil' => 'Dewi Sartika',
                'visi_misi' => 'Visi: Membangun OSIS yang inovatif dan berdaya saing. Misi: 1. Mengembangkan teknologi dalam pembelajaran, 2. Meningkatkan prestasi akademik dan non-akademik, 3. Membangun karakter siswa yang unggul.',
                'jenis_pencalonan' => 'pasangan',
                'program_kerja' => '1. Program Teknologi dan Inovasi, 2. Kegiatan Kompetisi dan Lomba, 3. Program Pengembangan Karakter, 4. Kegiatan Internasional dan Pertukaran Pelajar',
                'motivasi' => 'Kami ingin membawa sekolah ke level yang lebih tinggi dengan inovasi dan kreativitas.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($calons as $calonData) {
            Calon::create($calonData);
        }

        // Create sample pemilih
        $pemilihs = [
            [
                'nama' => 'Ahmad Rizki Pratama',
                'nis' => '2024001',
                'nisn' => '1234567890',
                'kelas' => 'X IPA 1',
                'status' => 'sudah_memilih',
                'waktu_memilih' => now()->subHours(2),
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_active' => true,
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nis' => '2024002',
                'nisn' => '1234567891',
                'kelas' => 'X IPS 1',
                'status' => 'sudah_memilih',
                'waktu_memilih' => now()->subHours(1),
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_active' => true,
            ],
            [
                'nama' => 'Muhammad Fajar',
                'nis' => '2023001',
                'nisn' => '1234567892',
                'kelas' => 'XI IPA 2',
                'status' => 'sudah_memilih',
                'waktu_memilih' => now()->subMinutes(30),
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_active' => true,
            ],
            [
                'nama' => 'Putri Sari',
                'nis' => '2022001',
                'nisn' => '1234567893',
                'kelas' => 'XII IPS 1',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
            [
                'nama' => 'Rizki Aditya',
                'nis' => '2024003',
                'nisn' => '1234567894',
                'kelas' => 'X IPA 3',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
            [
                'nama' => 'Dewi Sartika',
                'nis' => '2024004',
                'nisn' => '1234567895',
                'kelas' => 'X IPA 1',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
            [
                'nama' => 'Budi Santoso',
                'nis' => '2024005',
                'nisn' => '1234567896',
                'kelas' => 'X IPS 2',
                'status' => 'sudah_memilih',
                'waktu_memilih' => now()->subMinutes(15),
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_active' => true,
            ],
            [
                'nama' => 'Sari Dewi',
                'nis' => '2024006',
                'nisn' => '1234567897',
                'kelas' => 'X IPA 2',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
            [
                'nama' => 'Andi Pratama',
                'nis' => '2024007',
                'nisn' => '1234567898',
                'kelas' => 'X IPS 3',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
            [
                'nama' => 'Lina Sari',
                'nis' => '2024008',
                'nisn' => '1234567899',
                'kelas' => 'X IPA 1',
                'status' => 'belum_memilih',
                'is_active' => true,
            ],
        ];

        foreach ($pemilihs as $pemilihData) {
            Pemilih::create($pemilihData);
        }

        // Create sample votings
        $votings = [
            [
                'calon_id' => 1, // Ahmad Rizki & Siti Nurhaliza
                'pemilih_id' => 1, // Ahmad Rizki Pratama
                'waktu_voting' => now()->subHours(2),
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_valid' => true,
            ],
            [
                'calon_id' => 2, // Muhammad Fajar & Putri Sari
                'pemilih_id' => 2, // Siti Nurhaliza
                'waktu_voting' => now()->subHours(1),
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_valid' => true,
            ],
            [
                'calon_id' => 1, // Ahmad Rizki & Siti Nurhaliza
                'pemilih_id' => 3, // Muhammad Fajar
                'waktu_voting' => now()->subMinutes(30),
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_valid' => true,
            ],
            [
                'calon_id' => 3, // Rizki Aditya & Dewi Sartika
                'pemilih_id' => 7, // Budi Santoso
                'waktu_voting' => now()->subMinutes(15),
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'is_valid' => true,
            ],
        ];

        foreach ($votings as $votingData) {
            Voting::create($votingData);
        }

        $this->command->info('OSIS seeder completed successfully!');
    }
}
