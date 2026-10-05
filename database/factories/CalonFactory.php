<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calon>
 */
class CalonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_ketua' => fake()->name('id_ID'),
            'foto_ketua' => null,
            'nama_wakil' => fake()->name('id_ID'),
            'foto_wakil' => null,
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'visi_misi' => fake()->paragraph(),
            'jenis_pencalonan' => 'pasangan',
            'program_kerja' => fake()->paragraph(),
            'motivasi' => fake()->sentence(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
