<?php

namespace Database\Factories;

use App\Models\Calon;
use App\Models\Pemilih;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voting>
 */
class VotingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calon_id' => Calon::factory(),
            'pemilih_id' => Pemilih::factory(),
            'siswa_id' => null,
            'election_id' => null,
            'waktu_voting' => now(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'is_valid' => true,
        ];
    }
}
