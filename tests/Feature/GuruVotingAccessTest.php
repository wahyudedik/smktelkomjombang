<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\Guru;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test akses voting OSIS untuk role guru:
 * - Navigation menu E-Services memuat link E-OSIS Voting & Hasil Voting (role siswa|guru)
 * - Dashboard guru menampilkan widget E-OSIS Voting
 * - Guru dapat mengakses halaman voting & hasil voting
 * - Route admin OSIS index tetap ditolak untuk guru (403)
 */
class GuruVotingAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $guruUser;

    protected OsisElection $election;

    protected Calon $calonL;

    protected Calon $calonP;

    protected function setUp(): void
    {
        parent::setUp();

        // User guru dengan role guru (CheckRole lolos via hasRole langsung di route voting)
        $this->guruUser = User::factory()->create([
            'email' => 'guru.voting@test.com',
        ]);
        $guruRole = $this->getOrCreateRole('guru');
        $this->guruUser->syncRoles([$guruRole]);
        $this->guruUser->updateQuietly(['user_type' => 'guru']);

        Guru::factory()->create([
            'user_id' => $this->guruUser->id,
            'nama_lengkap' => 'Guru Voting Test',
        ]);

        // Pemilih record untuk guru (user_type='guru') — dibutuhkan controller voting & widget dashboard
        Pemilih::factory()->create([
            'user_id' => $this->guruUser->id,
            'user_type' => 'guru',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        // Election aktif
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

        // Kandidat campuran
        $this->calonL = Calon::factory()->create([
            'nama_ketua' => 'Ketua Laki',
            'nama_wakil' => 'Wakil Laki',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $this->calonP = Calon::factory()->create([
            'nama_ketua' => 'Ketua Perempuan',
            'nama_wakil' => 'Wakil Perempuan',
            'jenis_kelamin' => 'P',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }

    /** @test */
    public function guru_dashboard_menu_memuat_link_voting_dan_hasil_voting()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        // Navigation E-Services harus memuat link voting & hasil untuk guru
        $response->assertSee(route('admin.osis.voting'), false);
        $response->assertSee(route('admin.osis.results'), false);
    }

    /** @test */
    public function guru_dashboard_menampilkan_widget_eosis_voting()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('E-OSIS Voting', false);
        $response->assertSee('Mulai Voting', false);
    }

    /** @test */
    public function guru_yang_sudah_memilih_widget_menampilkan_status_dan_link_hasil()
    {
        Pemilih::where('user_id', $this->guruUser->id)
            ->where('user_type', 'guru')
            ->update([
                'status' => 'sudah_memilih',
                'waktu_memilih' => now(),
            ]);

        $response = $this->actingAs($this->guruUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Anda sudah memilih', false);
        $response->assertSee('Lihat Hasil', false);
    }

    /** @test */
    public function guru_dapat_mengakses_halaman_voting()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.osis.voting'));

        $response->assertStatus(200);
        // Guru melihat semua kandidat aktif
        $response->assertSee('Anda melihat semua calon', false);
        // Back button guru harus ke dashboard, bukan admin.osis.index
        // (cek href persis — URL admin.osis.voting/results juga mengandung substring '/admin/osis')
        $response->assertDontSee('href="'.route('admin.osis.index').'"', false);
    }

    /** @test */
    public function guru_dapat_mengakses_halaman_hasil_voting()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.osis.results'));

        $response->assertStatus(200);
        // Tombol voting tampil untuk guru (route terbuka siswa|guru)
        $response->assertSee(route('admin.osis.voting'), false);
        // Back button guru harus ke dashboard, bukan admin.osis.index
        // (cek href persis — URL admin.osis.voting/results juga mengandung substring '/admin/osis')
        $response->assertDontSee('href="'.route('admin.osis.index').'"', false);
    }

    /** @test */
    public function guru_tidak_dapat_melihat_tombol_export_hasil_voting()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.osis.results'));

        $response->assertStatus(200);
        // Export route butuh permission:osis.results — guru tidak boleh melihat tombolnya
        $response->assertDontSee('/admin/osis/results/export/pdf', false);
        $response->assertDontSee('/admin/osis/results/export/json', false);
        $response->assertDontSee('/admin/osis/results/export/xml', false);
    }

    /** @test */
    public function admin_osis_index_tetap_ditolak_untuk_guru()
    {
        $response = $this->actingAs($this->guruUser)->get(route('admin.osis.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guru_tanpa_record_pemilih_redirect_ke_dashboard()
    {
        Pemilih::where('user_id', $this->guruUser->id)->where('user_type', 'guru')->delete();

        $response = $this->actingAs($this->guruUser)->get(route('admin.osis.voting'));

        $response->assertRedirect(route('admin.dashboard'));
    }
}
