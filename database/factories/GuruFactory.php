<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->unique()->numerify('19##########'),
            'nama_lengkap' => fake()->name('id_ID'),
            'gelar_depan' => fake()->optional()->randomElement(['S.Pd.', 'M.Pd.', 'S.Kom.']),
            'gelar_belakang' => fake()->optional()->randomElement(['S.Pd.', 'M.Pd.', 'M.T.']),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'tanggal_lahir' => fake()->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
            'tempat_lahir' => fake()->city(),
            'alamat' => fake()->address(),
            'no_telepon' => fake()->numerify('08##########'),
            'no_wa' => fake()->numerify('628##########'),
            'email' => fake()->unique()->safeEmail(),
            'foto' => null,
            'status_kepegawaian' => fake()->randomElement(['PNS', 'CPNS', 'GTT', 'GTY', 'Honorer']),
            'jabatan' => fake()->optional()->randomElement(['Guru', 'Wali Kelas', 'Kepala Jurusan', 'Wakil Kepala']),
            'tanggal_masuk' => fake()->dateTimeBetween('-15 years', '-1 year')->format('Y-m-d'),
            'tanggal_keluar' => null,
            'status_aktif' => 'aktif',
            'pendidikan_terakhir' => fake()->randomElement(['S.Pd', 'S.Kom', 'S.E', 'S.Pd.I']),
            'universitas' => fake()->company() . ' University',
            'tahun_lulus' => (string) fake()->numberBetween(2005, now()->year),
            'sertifikasi' => fake()->optional()->sentence(),
            'mata_pelajaran' => fake()->randomElements(['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia', 'Bahasa Inggris', 'Informatika', 'Jaringan Komputer'], 2),
            'jadwal_mengajar' => null,
            'prestasi' => null,
            'catatan' => null,
            'user_id' => null,
        ];
    }
}
