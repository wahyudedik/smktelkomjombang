<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemilih>
 */
class PemilihFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'user_type' => null,
            'nama' => fake()->name('id_ID'),
            'nis' => fake()->unique()->numerify('00#####'),
            'nisn' => fake()->unique()->numerify('00#########'),
            'kelas' => fake()->randomElement(['X', 'XI', 'XII']) . ' ' . fake()->randomElement(['IPA 1', 'IPA 2', 'IPS 1', 'IPS 2', 'TKJ 1', 'RPL 1']),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'email' => fake()->unique()->safeEmail(),
            'nomor_hp' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
            'status' => 'belum_memilih',
            'waktu_memilih' => null,
            'ip_address' => null,
            'user_agent' => null,
            'is_active' => true,
        ];
    }
}
