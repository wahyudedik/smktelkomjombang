<?php

namespace Database\Factories;

use App\Models\KategoriSarpras;
use App\Models\Ruang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_barang' => 'BRG-' . $this->faker->unique()->numerify('######'),
            'nama_barang' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(),
            'kategori_id' => KategoriSarpras::factory(),
            'merk' => $this->faker->company(),
            'model' => $this->faker->word(),
            'serial_number' => 'SN' . $this->faker->numerify('########'),
            'harga_beli' => $this->faker->numberBetween(100000, 10000000),
            'tanggal_pembelian' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'sumber_dana' => $this->faker->randomElement(['APBN', 'APBD', 'BOS', 'Donasi']),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak', 'hilang']),
            'ruang_id' => Ruang::factory(),
            'status' => $this->faker->randomElement(['tersedia', 'dipinjam', 'rusak', 'hilang']),
            'catatan' => $this->faker->sentence(),
            'foto' => null,
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the barang is in good condition.
     */
    public function goodCondition(): static
    {
        return $this->state(fn(array $attributes) => [
            'kondisi' => 'baik',
            'status' => 'tersedia',
        ]);
    }

    /**
     * Indicate that the barang is damaged.
     */
    public function damaged(): static
    {
        return $this->state(fn(array $attributes) => [
            'kondisi' => 'rusak',
            'status' => 'rusak',
        ]);
    }

    /**
     * Indicate that the barang is missing.
     */
    public function missing(): static
    {
        return $this->state(fn(array $attributes) => [
            'kondisi' => 'hilang',
            'status' => 'hilang',
        ]);
    }

    /**
     * Indicate that the barang is borrowed.
     */
    public function borrowed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'dipinjam',
        ]);
    }
}
