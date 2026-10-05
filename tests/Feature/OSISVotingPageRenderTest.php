<?php

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test render halaman voting OSIS (route admin.osis.voting).
 *
 * Reproduksi bug production 500 "Undefined variable $calon" di
 * resources/views/osis/voting.blade.php — controller mengirim $calons
 * (plural) sementara view memakai $calon (singular).
 *
 * Skenario data lokal sesuai production:
 * - Election aktif (is_active, rentang tanggal mencakup now, tidak locked)
 * - ≥2 kandidat aktif dengan gender berbeda (P + L)
 * - User siswa dengan baris Siswa ter-link via user_id
 */
class OSISVotingPageRenderTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;

    protected Siswa $siswa;

    protected OsisElection $election;

    protected function setUp(): void
    {
        parent::setUp();

        // User siswa dengan role siswa (CheckRole lolos via hasRole langsung)
        $this->siswaUser = User::factory()->create([
            'email' => 'siswa.voting@test.com',
        ]);
        $siswaRole = $this->getOrCreateRole('siswa');
        $this->siswaUser->syncRoles([$siswaRole]);
        $this->siswaUser->updateQuietly(['user_type' => 'siswa']);

        // Link baris Siswa ke user — controller resolve Siswa via user_id
        $this->siswa = Siswa::factory()->create([
            'user_id' => $this->siswaUser->id,
            'nama_lengkap' => 'Siswa Voting Test',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
            'has_voted_osis' => false,
        ]);

        // Election aktif yang mencakup waktu sekarang
        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026/2027',
            'description' => 'Test election',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'allowed_classes' => null,
        ]);

        // ≥2 kandidat aktif, gender berbeda — membuktikan tidak ada filter gender
        Calon::factory()->create([
            'nama_ketua' => 'Ketua A',
            'nama_wakil' => 'Wakil A',
            'jenis_kelamin' => 'P',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        Calon::factory()->create([
            'nama_ketua' => 'Ketua B',
            'nama_wakil' => 'Wakil B',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        // Kandidat nonaktif tidak boleh tampil
        Calon::factory()->create([
            'nama_ketua' => 'Ketua Nonaktif',
            'nama_wakil' => 'Wakil Nonaktif',
            'is_active' => false,
            'sort_order' => 3,
        ]);
    }

    /** @test */
    public function siswa_with_active_election_sees_all_active_candidates()
    {
        $response = $this->actingAs($this->siswaUser)
            ->get(route('admin.osis.voting'));

        // Harus 200, bukan 500 "Undefined variable $calon"
        $response->assertStatus(200);
        $response->assertSee('Ketua A');
        $response->assertSee('Ketua B');
        $response->assertDontSee('Ketua Nonaktif');
    }

    /** @test */
    public function voting_page_shows_candidates_of_all_genders()
    {
        $response = $this->actingAs($this->siswaUser)
            ->get(route('admin.osis.voting'));

        $response->assertStatus(200);
        // Siswa berjenis_kelamin=P; kandidat L harus tetap tampil (tanpa filter byGender)
        $response->assertSee('Ketua B');
        $response->assertSee('Ketua A');
    }

    /** @test */
    public function voting_page_with_empty_candidates_shows_safe_message()
    {
        Calon::query()->delete();

        $response = $this->actingAs($this->siswaUser)
            ->get(route('admin.osis.voting'));

        // Collection kosong harus aman (pesan "belum ada kandidat"), bukan 500
        $response->assertStatus(200);
        $response->assertSee(__('common.no_candidates'));
    }
}
