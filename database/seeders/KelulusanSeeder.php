<?php

namespace Database\Seeders;

use App\Models\Kelulusan;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelulusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample graduation data
        $kelulusans = [
            [
                'nama' => 'Ahmad Rizki Pratama',
                'nisn' => '1234567890',
                'nis' => '2024001',
                'jurusan' => 'IPA (Ilmu Pengetahuan Alam)',
                'tahun_ajaran' => 2024,
                'status' => 'lulus',
                'tempat_kuliah' => 'Universitas Indonesia',
                'jurusan_kuliah' => 'Teknik Informatika',
                'no_hp' => '081234567890',
                'no_wa' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'prestasi' => 'Juara 1 Olimpiade Matematika Tingkat Kota 2023',
                'catatan' => 'Siswa berprestasi dengan nilai akademik yang baik',
                'tanggal_lulus' => now()->subDays(30),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nisn' => '0987654321',
                'nis' => '2024002',
                'jurusan' => 'IPS (Ilmu Pengetahuan Sosial)',
                'tahun_ajaran' => 2024,
                'status' => 'lulus',
                'tempat_kerja' => 'PT. Maju Jaya',
                'jabatan_kerja' => 'Staff Administrasi',
                'no_hp' => '081234567891',
                'no_wa' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'prestasi' => 'Juara 2 Lomba Debat Bahasa Inggris Tingkat Provinsi 2023',
                'catatan' => 'Siswa aktif dalam kegiatan ekstrakurikuler',
                'tanggal_lulus' => now()->subDays(25),
            ],
            [
                'nama' => 'Muhammad Fajar',
                'nisn' => '1122334455',
                'nis' => '2024003',
                'jurusan' => 'IPA (Ilmu Pengetahuan Alam)',
                'tahun_ajaran' => 2024,
                'status' => 'lulus',
                'tempat_kuliah' => 'Institut Teknologi Bandung',
                'jurusan_kuliah' => 'Teknik Mesin',
                'no_hp' => '081234567892',
                'no_wa' => '081234567892',
                'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta Barat',
                'prestasi' => 'Juara 3 Olimpiade Fisika Tingkat Nasional 2023',
                'catatan' => 'Siswa dengan kemampuan analisis yang tinggi',
                'tanggal_lulus' => now()->subDays(20),
            ],
            [
                'nama' => 'Dewi Sartika',
                'nisn' => '5566778899',
                'nis' => '2024004',
                'jurusan' => 'IPS (Ilmu Pengetahuan Sosial)',
                'tahun_ajaran' => 2024,
                'status' => 'lulus',
                'tempat_kuliah' => 'Universitas Gadjah Mada',
                'jurusan_kuliah' => 'Psikologi',
                'no_hp' => '081234567893',
                'no_wa' => '081234567893',
                'alamat' => 'Jl. Thamrin No. 321, Jakarta Pusat',
                'prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah Tingkat Nasional 2023',
                'catatan' => 'Siswa dengan kemampuan menulis yang baik',
                'tanggal_lulus' => now()->subDays(15),
            ],
            [
                'nama' => 'Budi Santoso',
                'nisn' => '9988776655',
                'nis' => '2024005',
                'jurusan' => 'Teknik Informatika',
                'tahun_ajaran' => 2024,
                'status' => 'lulus',
                'tempat_kerja' => 'Google Indonesia',
                'jabatan_kerja' => 'Software Developer',
                'no_hp' => '081234567894',
                'no_wa' => '081234567894',
                'alamat' => 'Jl. Kuningan No. 654, Jakarta Selatan',
                'prestasi' => 'Juara 1 Lomba Programming Tingkat Nasional 2023',
                'catatan' => 'Siswa dengan kemampuan programming yang luar biasa',
                'tanggal_lulus' => now()->subDays(10),
            ],
            [
                'nama' => 'Rina Wulandari',
                'nisn' => '4433221100',
                'nis' => '2023001',
                'jurusan' => 'IPA (Ilmu Pengetahuan Alam)',
                'tahun_ajaran' => 2023,
                'status' => 'lulus',
                'tempat_kuliah' => 'Universitas Diponegoro',
                'jurusan_kuliah' => 'Kedokteran',
                'no_hp' => '081234567895',
                'no_wa' => '081234567895',
                'alamat' => 'Jl. Semarang No. 987, Jakarta Utara',
                'prestasi' => 'Juara 1 Olimpiade Biologi Tingkat Nasional 2022',
                'catatan' => 'Siswa dengan cita-cita menjadi dokter',
                'tanggal_lulus' => now()->subDays(365),
            ],
            [
                'nama' => 'Andi Wijaya',
                'nisn' => '6677889900',
                'nis' => '2023002',
                'jurusan' => 'IPS (Ilmu Pengetahuan Sosial)',
                'tahun_ajaran' => 2023,
                'status' => 'lulus',
                'tempat_kerja' => 'Bank Mandiri',
                'jabatan_kerja' => 'Relationship Manager',
                'no_hp' => '081234567896',
                'no_wa' => '081234567896',
                'alamat' => 'Jl. Bank No. 147, Jakarta Pusat',
                'prestasi' => 'Juara 2 Lomba Ekonomi Tingkat Provinsi 2022',
                'catatan' => 'Siswa dengan kemampuan komunikasi yang baik',
                'tanggal_lulus' => now()->subDays(350),
            ],
            [
                'nama' => 'Sari Indah',
                'nisn' => '3344556677',
                'nis' => '2022001',
                'jurusan' => 'Bahasa',
                'tahun_ajaran' => 2022,
                'status' => 'lulus',
                'tempat_kuliah' => 'Universitas Negeri Jakarta',
                'jurusan_kuliah' => 'Sastra Inggris',
                'no_hp' => '081234567897',
                'no_wa' => '081234567897',
                'alamat' => 'Jl. Sastra No. 258, Jakarta Timur',
                'prestasi' => 'Juara 1 Lomba Bahasa Inggris Tingkat Nasional 2021',
                'catatan' => 'Siswa dengan kemampuan bahasa asing yang baik',
                'tanggal_lulus' => now()->subDays(730),
            ],
        ];

        foreach ($kelulusans as $kelulusanData) {
            // Try to find related siswa by NISN or NIS
            $siswa = null;
            if (isset($kelulusanData['nisn'])) {
                $siswa = Siswa::where('nisn', $kelulusanData['nisn'])->first();
            }
            if (!$siswa && isset($kelulusanData['nis'])) {
                $siswa = Siswa::where('nis', $kelulusanData['nis'])->first();
            }

            // Link siswa_id if found
            if ($siswa) {
                $kelulusanData['siswa_id'] = $siswa->id;
            }

            Kelulusan::create($kelulusanData);
        }

        $this->command->info('Kelulusan seeder completed successfully!');
    }
}
