<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Ekonomi',
            'Sosiologi',
            'PPKn',
            'Pendidikan Agama',
            'Seni Budaya',
            'PJOK',
            'TIK',
            'Bahasa Arab',
            'Bahasa Mandarin',
            'Bahasa Jepang',
            'Pendidikan Jasmani',
            'Kewirausahaan',
        ];

        foreach ($mataPelajaran as $mapel) {
            MataPelajaran::firstOrCreate(['nama' => $mapel]);
        }
    }
}
