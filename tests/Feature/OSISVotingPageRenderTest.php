<?php

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test render halaman voting OSIS (route admin.osis.voting) + aturan filter gender.
 *
 * Aturan bisnis (dikonfirmasi user):
 * - Siswa L → hanya kandidat L; Siswi P → hanya kandidat P
 * - Guru → semua kandidat aktif
 * - Siswa TANPA baris `siswas` (user_id belum ter-link) → voting() MENOLAK render
 *   form: redirect ke admin.dashboard + error "belum terdaftar"
 *   (perilaku BARU — fix vote OSIS gagal diam-diam; lihat OSISVotingAccessFixTest).
 * - Fallback gender: jenis_kelamin null/kosong → semua kandidat.
 *   CATATAN: `siswas.jenis_kelamin` = enum NOT NULL CHECK ('L','P') (migration
 *   2025_09_26_093230_create_siswas_table.php), sehingga gender null/kosong tidak
 *   bisa direpresentasikan lewat insert di test database. Guard controller
 *   ($gender === 'L' || $gender === 'P' → selain itu SEMUA kandidat) tetap
 *   dipertahankan sebagai defensive coding.
 *
 * Reproduksi bug production 500 "Undefined variable $calon": controller mengirim $calons
 * (plural) — view harus memakai $calons (plural) dan PERTAHANKAN rename tersebut.
 */
class OSISVotingPageRenderTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;

    protected Siswa $siswa;

    protected OsisElection $election;

    protected Calon $calonL;

    protected Calon $calonP;

    protected Calon $calonInactive;

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

        // Kandidat campuran: L & P aktif + 1 nonaktif (nonaktif tidak boleh tampil)
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
        $this->calonInactive = Calon::factory()->create([
            'nama_ketua' => 'Ketua Nonaktif',
            'nama_wakil' => 'Wakil Nonaktif',
            'jenis_kelamin' => 'L',
            'is_active' => false,
            'sort_order' => 3,
        ]);
    }

    /** @test */
    public function siswa_perempuan_hanya_melihat_kandidat_perempuan()
    {
        $response = $this->actingAs($this->siswaUser)
            ->get(route('admin.osis.voting'));

        // Harus 200, bukan 500 "Undefined variable $calon"
        $response->assertStatus(200);
        // Kandidat P tampil
        $response->assertSee('Ketua Perempuan');
        // Kandidat L dan nonaktif TIDAK tampil
        $response->assertDontSee('Ketua Laki');
        $response->assertDontSee('Ketua Nonaktif');
        // Notice kondisional: siswa terfilter — notice "semua calon" tidak boleh muncul
        $response->assertSee('Anda melihat kandidat sesuai jenis kelamin Anda');
        $response->assertDontSee('Anda melihat semua calon');
    }

    /** @test */
    public function siswa_laki_laki_hanya_melihat_kandidat_laki_laki()
    {
        $siswaLUser = User::factory()->create(['email' => 'siswa.l@test.com']);
        $siswaLUser->syncRoles([$this->getOrCreateRole('siswa')]);
        $siswaLUser->updateQuietly(['user_type' => 'siswa']);

        Siswa::factory()->create([
            'user_id' => $siswaLUser->id,
            'nama_lengkap' => 'Siswa Laki Test',
            'kelas' => 'X IPA 2',
            'status' => 'aktif',
            'jenis_kelamin' => 'L',
            'has_voted_osis' => false,
        ]);

        $response = $this->actingAs($siswaLUser)
            ->get(route('admin.osis.voting'));

        $response->assertStatus(200);
        $response->assertSee('Ketua Laki');
        $response->assertDontSee('Ketua Perempuan');
        $response->assertDontSee('Ketua Nonaktif');
        $response->assertSee('Anda melihat kandidat sesuai jenis kelamin Anda');
        $response->assertDontSee('Anda melihat semua calon');
    }

    /** @test */
    public function guru_melihat_semua_kandidat_aktif()
    {
        $guruUser = User::factory()->create(['email' => 'guru.voting@test.com']);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        // voting() mensyaratkan row Pemilih guru
        Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        $response = $this->actingAs($guruUser)
            ->get(route('admin.osis.voting'));

        $response->assertStatus(200);
        $response->assertSee('Ketua Laki');
        $response->assertSee('Ketua Perempuan');
        $response->assertDontSee('Ketua Nonaktif');
        // Notice kondisional: guru melihat semua
        $response->assertSee('Anda melihat semua calon');
    }

    /** @test */
    public function siswa_tanpa_row_siswa_redirect_ke_dashboard_dengan_error()
    {
        // Perilaku BARU (fix vote OSIS gagal diam-diam): siswa tanpa baris `siswas`
        // (user_id belum ter-link) TIDAK lagi dirender form voting — karena submit
        // pasti ditolak processVote() (resolve Siswa::where('user_id') → null).
        // voting() redirect ke admin.dashboard dengan error jelas.
        // (Sebelumnya: 200 + fallback semua kandidat — perilaku ini sengaja diubah.)
        $orphanUser = User::factory()->create(['email' => 'siswa.orphan@test.com']);
        $orphanUser->syncRoles([$this->getOrCreateRole('siswa')]);
        $orphanUser->updateQuietly(['user_type' => 'siswa']);
        // Tidak membuat baris Siswa untuk user ini

        $response = $this->actingAs($orphanUser)
            ->get(route('admin.osis.voting'));

        // Perilaku baru: redirect + error, form voting TIDAK dirender
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('belum terdaftar', (string) session('error'));
        $response->assertDontSee('Kirim Suara');
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

    /** @test */
    public function siswa_ditolak_saat_memilih_kandidat_lawan_jenis_kelamin()
    {
        // Siswa berjenis kelamin P mencoba vote kandidat L via request langsung (manipulasi UI)
        $response = $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonL->id,
            ]);

        // Ditolak dengan redirect + session error (bukan 500, vote tidak tersimpan)
        $response->assertRedirect(route('admin.osis.voting'));
        $response->assertSessionHas('error');

        $this->assertSame(0, Voting::count(), 'Vote kandidat lawan gender tidak boleh tersimpan');
        $this->assertFalse($this->siswa->fresh()->has_voted_osis);
    }

    /** @test */
    public function guru_dapat_memilih_kandidat_gender_apapun()
    {
        $guruUser = User::factory()->create(['email' => 'guru.vote@test.com']);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        // voting()/processVote() mensyaratkan row Pemilih guru
        Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        // Guru memilih kandidat L — diizinkan (guru bebas memilih kandidat mana pun)
        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonL->id,
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('success');
        $this->assertSame(1, Voting::count());
        $this->assertSame($this->calonL->id, Voting::first()->calon_id);
    }
}
