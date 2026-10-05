<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('00#####'),
            'nisn' => fake()->unique()->numerify('00#########'),
            'nama_lengkap' => fake()->name('id_ID'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tanggal_lahir' => fake()->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'tempat_lahir' => fake()->city(),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->numerify('08##########'),
            'no_wa' => fake()->numerify('628##########'),
            'email' => fake()->unique()->safeEmail(),
            'foto' => null,
            'kelas' => fake()->randomElement(['X', 'XI', 'XII']) . ' ' . fake()->randomElement(['IPA 1', 'IPA 2', 'IPS 1', 'IPS 2', 'TKJ 1', 'RPL 1']),
            'jurusan' => fake()->randomElement(['TKJ', 'RPL', 'AKL', 'DKV']),
            'tahun_masuk' => fake()->numberBetween(2020, now()->year),
            'tahun_lulus' => null,
            'status' => 'aktif',
            'nama_ayah' => fake()->name('id_ID'),
            'pekerjaan_ayah' => fake()->jobTitle(),
            'nama_ibu' => fake()->name('id_ID'),
            'pekerjaan_ibu' => fake()->jobTitle(),
            'no_telepon_ortu' => fake()->numerify('08##########'),
            'alamat_ortu' => fake()->address(),
            'prestasi' => fake()->sentence(),
            'catatan' => null,
            'nilai_akademik' => null,
            'ekstrakurikuler' => null,
            'user_id' => null,
            'has_voted_osis' => false,
            'voted_at' => null,
            'voting_ip' => null,
            'voting_user_agent' => null,
        ];
    }
}
