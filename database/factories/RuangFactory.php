<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruang>
 */
class RuangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_ruang' => 'RUANG-' . $this->faker->unique()->numerify('###'),
            'nama_ruang' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(),
            'jenis_ruang' => $this->faker->randomElement(['kelas', 'laboratorium', 'perpustakaan', 'kantor', 'aula', 'gudang', 'lainnya']),
            'luas_ruang' => $this->faker->randomFloat(2, 20, 200),
            'kapasitas' => $this->faker->numberBetween(10, 50),
            'lantai' => $this->faker->randomElement(['1', '2', '3', 'Lantai Dasar']),
            'gedung' => 'Gedung ' . $this->faker->randomElement(['A', 'B', 'C', 'Utama']),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak', 'renovasi']),
            'status' => $this->faker->randomElement(['aktif', 'tidak_aktif', 'renovasi']),
            'fasilitas' => $this->faker->randomElements(['Proyektor', 'AC', 'Meja Kursi', 'Papan Tulis', 'Komputer', 'Sound System'], $this->faker->numberBetween(1, 4)),
            'catatan' => $this->faker->sentence(),
            'foto' => null,
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
        ];
    }

    /**
     * Indicate that the ruang is a classroom.
     */
    public function classroom(): static
    {
        return $this->state(fn(array $attributes) => [
            'jenis_ruang' => 'kelas',
            'kapasitas' => $this->faker->numberBetween(25, 40),
            'fasilitas' => ['Meja Kursi', 'Papan Tulis', 'Proyektor'],
        ]);
    }

    /**
     * Indicate that the ruang is a laboratory.
     */
    public function laboratory(): static
    {
        return $this->state(fn(array $attributes) => [
            'jenis_ruang' => 'laboratorium',
            'kapasitas' => $this->faker->numberBetween(20, 30),
            'fasilitas' => ['Meja Kursi', 'Komputer', 'Proyektor', 'AC'],
        ]);
    }

    /**
     * Indicate that the ruang is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'aktif',
            'kondisi' => 'baik',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the ruang is under renovation.
     */
    public function renovation(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'renovasi',
            'kondisi' => 'renovasi',
        ]);
    }
}
