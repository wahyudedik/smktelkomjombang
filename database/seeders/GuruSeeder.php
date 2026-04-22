<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
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

        // Create sample gurus
        $gurus = [
            [
                'nip' => '196512151990031001',
                'nama_lengkap' => 'Dr. Ahmad Susanto, M.Pd.',
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'M.Pd.',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1965-12-15',
                'tempat_lahir' => 'Jakarta',
                'alamat' => 'Jl. Pendidikan No. 123, Jakarta Selatan',
                'no_telepon' => '021-1234567',
                'no_wa' => '081234567890',
                'email' => 'ahmad.susanto@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Kepala Sekolah',
                'tanggal_masuk' => '1990-03-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S3 Pendidikan',
                'universitas' => 'Universitas Negeri Jakarta',
                'tahun_lulus' => '2010',
                'sertifikasi' => 'Sertifikasi Kepala Sekolah, Sertifikasi Guru Profesional',
                'mata_pelajaran' => ['Matematika', 'Bimbingan Konseling'],
                'prestasi' => 'Guru Berprestasi Tingkat Nasional 2015, Penulis Buku Pendidikan',
                'catatan' => 'Memiliki pengalaman 30+ tahun di dunia pendidikan',
                'user_id' => $superadmin->id,
            ],
            [
                'nip' => '197203101995032002',
                'nama_lengkap' => 'Siti Nurhaliza, S.Pd.',
                'gelar_depan' => '',
                'gelar_belakang' => 'S.Pd.',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1972-03-10',
                'tempat_lahir' => 'Bandung',
                'alamat' => 'Jl. Merdeka No. 456, Bandung',
                'no_telepon' => '022-7654321',
                'no_wa' => '081234567891',
                'email' => 'siti.nurhaliza@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Wakil Kepala Sekolah',
                'tanggal_masuk' => '1995-03-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Bahasa Indonesia',
                'universitas' => 'Universitas Pendidikan Indonesia',
                'tahun_lulus' => '1995',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => ['Bahasa Indonesia', 'Bahasa Inggris'],
                'prestasi' => 'Guru Berprestasi Tingkat Provinsi 2018',
                'catatan' => 'Ahli dalam pengembangan kurikulum bahasa',
                'user_id' => $superadmin->id,
            ],
            [
                'nip' => '198005201999031003',
                'nama_lengkap' => 'Muhammad Fajar, M.Pd.',
                'gelar_depan' => '',
                'gelar_belakang' => 'M.Pd.',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1980-05-20',
                'tempat_lahir' => 'Surabaya',
                'alamat' => 'Jl. Sudirman No. 789, Surabaya',
                'no_telepon' => '031-9876543',
                'no_wa' => '081234567892',
                'email' => 'muhammad.fajar@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Guru',
                'tanggal_masuk' => '1999-03-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S2 Pendidikan Fisika',
                'universitas' => 'Institut Teknologi Sepuluh Nopember',
                'tahun_lulus' => '2005',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => ['Fisika', 'Matematika'],
                'prestasi' => 'Pembimbing Olimpiade Fisika Tingkat Nasional',
                'catatan' => 'Spesialis dalam pembelajaran sains dan teknologi',
                'user_id' => $superadmin->id,
            ],
            [
                'nip' => '198512151999032004',
                'nama_lengkap' => 'Dewi Sartika, S.Pd.',
                'gelar_depan' => '',
                'gelar_belakang' => 'S.Pd.',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1985-12-15',
                'tempat_lahir' => 'Yogyakarta',
                'alamat' => 'Jl. Malioboro No. 321, Yogyakarta',
                'no_telepon' => '0274-123456',
                'no_wa' => '081234567893',
                'email' => 'dewi.sartika@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Guru',
                'tanggal_masuk' => '1999-03-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Biologi',
                'universitas' => 'Universitas Gadjah Mada',
                'tahun_lulus' => '1999',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => ['Biologi', 'Kimia'],
                'prestasi' => 'Peneliti Pendidikan Sains',
                'catatan' => 'Ahli dalam pembelajaran laboratorium',
                'user_id' => $superadmin->id,
            ],
            [
                'nip' => '199003101999031005',
                'nama_lengkap' => 'Budi Santoso, S.Pd.',
                'gelar_depan' => '',
                'gelar_belakang' => 'S.Pd.',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-03-10',
                'tempat_lahir' => 'Medan',
                'alamat' => 'Jl. Gatot Subroto No. 654, Medan',
                'no_telepon' => '061-4567890',
                'no_wa' => '081234567894',
                'email' => 'budi.santoso@sekolah.com',
                'status_kepegawaian' => 'GTT',
                'jabatan' => 'Guru',
                'tanggal_masuk' => '2015-08-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Sejarah',
                'universitas' => 'Universitas Negeri Medan',
                'tahun_lulus' => '2014',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => ['Sejarah', 'Geografi'],
                'prestasi' => 'Pembimbing Ekstrakurikuler Pramuka',
                'catatan' => 'Aktif dalam kegiatan kepramukaan',
                'user_id' => $superadmin->id,
            ],
        ];

        foreach ($gurus as $guruData) {
            Guru::create($guruData);
        }

        $this->command->info('Guru seeder completed successfully!');
    }
}
