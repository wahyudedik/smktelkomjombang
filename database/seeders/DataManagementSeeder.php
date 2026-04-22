<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Kelas
        $kelas = [
            'X IPA 1',
            'X IPA 2',
            'X IPA 3',
            'X IPS 1',
            'X IPS 2',
            'X IPS 3',
            'XI IPA 1',
            'XI IPA 2',
            'XI IPA 3',
            'XI IPS 1',
            'XI IPS 2',
            'XI IPS 3',
            'XII IPA 1',
            'XII IPA 2',
            'XII IPA 3',
            'XII IPS 1',
            'XII IPS 2',
            'XII IPS 3',
        ];

        foreach ($kelas as $nama) {
            DB::table('kelas')->insertOrIgnore([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seed Jurusan
        $jurusan = [
            'IPA (Ilmu Pengetahuan Alam)',
            'IPS (Ilmu Pengetahuan Sosial)',
            'Bahasa',
            'Teknik Informatika',
            'Teknik Mesin',
            'Teknik Elektro',
            'Akuntansi',
            'Administrasi Perkantoran',
            'Pemasaran',
        ];

        foreach ($jurusan as $nama) {
            DB::table('jurusan')->insertOrIgnore([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seed Ekstrakurikuler
        $ekstrakurikuler = [
            'Basket',
            'Futsal',
            'Voli',
            'Badminton',
            'Paduan Suara',
            'Tari Tradisional',
            'Teater',
            'Fotografi',
            'Debat Bahasa Inggris',
            'Matematika Club',
            'Science Club',
            'Literasi Digital',
            'Pramuka',
            'Paskibra',
            'OSIS',
            'PMR',
            'KIR',
        ];

        foreach ($ekstrakurikuler as $nama) {
            DB::table('ekstrakurikuler')->insertOrIgnore([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
