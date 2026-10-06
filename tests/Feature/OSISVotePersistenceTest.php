<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\Guru;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test regresi PERSISTENSI vote OSIS (bug production: vote tidak tersimpan
 * meski toast "Berhasil - Updated successfully" muncul).
 *
 * Skenario yang dijamin test ini:
 * 1. Vote siswa TERSIMPAN di tabel `votings` (siswa_id + election_id + is_valid + ip + user-agent)
 *    DAN flag `siswas.has_voted_osis` (voted_at, voting_ip, voting_user_agent) ter-update atomik.
 * 2. Vote kedua DITOLAK server-side (lockForUpdate + double-check) — jumlah baris tetap 1.
 * 3. Vote guru TERSIMPAN via `pemilihs.user_type='guru'` (votings.pemilih_id + pemilihs.status).
 *    Aturan bisnis BARU (multi-select): guru submit `calon_ids` array 1–2 pasangan calon
 *    DISTINCT → 1–2 baris Voting per election; setelah submit guru dianggap sudah memilih
 *    (status='sudah_memilih'), submit ke-2 ditolak.
 * 4. Halaman results menampilkan "Sudah Memilih" >= 1 setelah vote (sinkronisasi dual-tracking).
 * 5. POST saat election tidak aktif / berakhir DITOLAK (tidak ada baris votings baru).
 * 6. Siswa tanpa row `siswas` (user_id belum ter-link) ditolak dengan pesan jelas
 *    "Data pemilih belum tersedia, hubungi admin" — TANPA auto-generate pemilih.
 * 7. Dashboard siswa menampilkan widget E-OSIS Voting + menu Student memuat link voting.
 * 8. Guru multi-select: 2 pilihan → 2 baris tersimpan (pemilih_id + election_id benar,
 *    calon_id berbeda); submit ke-2 ditolak (jumlah tetap 2); >2 calon / calon duplikat
 *    DITOLAK validasi server (0 baris); backward-compat `calon_id` tunggal dari form
 *    lama/cache tetap diterima sebagai 1 pilihan untuk guru.
 *
 * CATATAN schema: tabel `pemilihs` TIDAK punya kolom `has_voted` — flag vote guru
 * disimpan sebagai string `status = 'sudah_memilih'` (aksesor Pemilih::hasVoted()).
 * Kolom `siswas.has_voted_osis` (boolean) adalah flag khusus jalur siswa.
 */
class OSISVotePersistenceTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;

    protected Siswa $siswa;

    protected OsisElection $election;

    protected Calon $calonL;

    protected Calon $calonP;

    protected function setUp(): void
    {
        parent::setUp();

        // User siswa + role siswa (route admin.osis.vote menerima role siswa|guru)
        $this->siswaUser = User::factory()->create([
            'email' => 'siswa.persistence@test.com',
        ]);
        $this->siswaUser->syncRoles([$this->getOrCreateRole('siswa')]);
        $this->siswaUser->updateQuietly(['user_type' => 'siswa']);

        // Row Siswa ter-link via user_id — controller resolve Siswa::where('user_id', ...)
        $this->siswa = Siswa::factory()->create([
            'user_id' => $this->siswaUser->id,
            'nama_lengkap' => 'Siswa Persistence Test',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
            'has_voted_osis' => false,
        ]);

        // Election aktif yang mencakup waktu sekarang
        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026/2027',
            'description' => 'Test election persistence',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'allowed_classes' => null,
        ]);

        // Kandidat L & P aktif
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

    /**
     * Helper: buat user guru + Pemilih (user_type='guru') untuk jalur voting guru.
     *
     * @return array{0: User, 1: Pemilih}
     */
    private function createGuruVoter(): array
    {
        $guruUser = User::factory()->create([
            'email' => 'guru.persistence@test.com',
        ]);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        Guru::factory()->create([
            'user_id' => $guruUser->id,
            'nama_lengkap' => 'Guru Persistence Test',
        ]);

        $pemilih = Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        return [$guruUser, $pemilih];
    }

    /** @test */
    public function siswa_vote_tersimpan_lengkap_dan_redirect_ke_results(): void
    {
        // Pemilih row siswa (user_type='siswa') — harus ikut ter-sync ke sudah_memilih
        $pemilihSiswa = Pemilih::factory()->create([
            'user_id' => $this->siswaUser->id,
            'user_type' => 'siswa',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonP->id,
            ]);

        // Redirect ke halaman hasil voting + pesan sukses voting yang benar
        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Suara Anda telah tercatat', (string) session('success'));
        // Toast tidak boleh memakai pesan generic CRUD "Updated successfully"
        $this->assertStringNotContainsString('Updated successfully', (string) session('success'));

        // Baris votings TERSIMPAN dengan field lengkap
        $this->assertSame(1, Voting::count(), 'Vote siswa harus menghasilkan 1 baris votings');
        $vote = Voting::first();
        $this->assertSame($this->calonP->id, $vote->calon_id);
        $this->assertSame($this->siswa->id, $vote->siswa_id, 'Vote siswa dicatat via siswa_id');
        $this->assertNull($vote->pemilih_id, 'Vote siswa tidak mengisi pemilih_id');
        $this->assertSame($this->election->id, $vote->election_id);
        $this->assertTrue((bool) $vote->is_valid);
        $this->assertNotNull($vote->waktu_voting);
        $this->assertNotNull($vote->ip_address);
        $this->assertNotNull($vote->user_agent);

        // Flag siswas ter-update atomik dalam transaksi yang sama
        $freshSiswa = $this->siswa->fresh();
        $this->assertTrue((bool) $freshSiswa->has_voted_osis);
        $this->assertNotNull($freshSiswa->voted_at);
        $this->assertNotNull($freshSiswa->voting_ip);

        // Marker 2: pemilihs (user_type siswa/null) ikut ter-sync
        $this->assertSame('sudah_memilih', $pemilihSiswa->fresh()->status);
    }

    /** @test */
    public function siswa_vote_kedua_kali_ditolak_dan_jumlah_vote_tetap_satu(): void
    {
        // Vote pertama — sukses
        $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), ['calon_id' => $this->calonP->id])
            ->assertRedirect(route('admin.osis.results'));

        // Vote kedua (bahkan ke kandidat berbeda) — DITOLAK server-side
        $response = $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), ['calon_id' => $this->calonL->id]);

        // Double-vote → redirect ke hasil voting dengan pesan info (bukan fake "Berhasil")
        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('info');
        $response->assertSessionMissing('success');

        // Jumlah vote TETAP 1 — kandidat kedua tidak menerima suara
        $this->assertSame(1, Voting::count(), 'Vote kedua tidak boleh menambah baris votings');
        $this->assertSame($this->calonP->id, Voting::first()->calon_id);
        $this->assertTrue((bool) $this->siswa->fresh()->has_voted_osis);
    }

    /** @test */
    public function guru_vote_tersimpan_via_pemilih_id_dan_status_pemilih_berubah(): void
    {
        [$guruUser, $pemilih] = $this->createGuruVoter();

        // Semantik BARU: guru submit via field `calon_ids[]` (multi-select, 1 pilihan)
        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id],
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Suara Anda telah tercatat', (string) session('success'));

        // Baris votings TERSIMPAN via pemilih_id (siswa_id null untuk vote guru)
        $this->assertSame(1, Voting::count(), 'Vote guru 1 pilihan harus menghasilkan 1 baris votings');
        $vote = Voting::first();
        $this->assertSame($this->calonL->id, $vote->calon_id);
        $this->assertSame($pemilih->id, $vote->pemilih_id, 'Vote guru dicatat via pemilih_id');
        $this->assertNull($vote->siswa_id);
        $this->assertSame($this->election->id, $vote->election_id);
        $this->assertTrue((bool) $vote->is_valid);
        $this->assertNotNull($vote->ip_address);
        $this->assertNotNull($vote->user_agent);

        // Flag pemilihs ter-update: status string = 'sudah_memilih'
        // (tabel pemilihs tidak punya kolom boolean has_voted)
        $freshPemilih = $pemilih->fresh();
        $this->assertSame('sudah_memilih', $freshPemilih->status);
        $this->assertTrue($freshPemilih->hasVoted());
        $this->assertNotNull($freshPemilih->waktu_memilih);
    }

    /** @test */
    public function guru_vote_kedua_kali_ditolak_dan_jumlah_vote_tetap_satu(): void
    {
        [$guruUser, $pemilih] = $this->createGuruVoter();

        // Vote pertama (1 pilihan) — sukses
        $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), ['calon_ids' => [$this->calonL->id]])
            ->assertRedirect(route('admin.osis.results'));

        // Vote kedua guru — DITOLAK (status pemilih sudah 'sudah_memilih')
        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), ['calon_ids' => [$this->calonP->id]]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('info');
        $this->assertSame(1, Voting::count(), 'Vote kedua guru tidak boleh menambah baris votings');
        $this->assertSame($pemilih->id, Voting::first()->pemilih_id);
        $this->assertSame('sudah_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_backward_compat_calon_id_tunggal_masih_diterima(): void
    {
        // Form lama / cache browser: user guru masih mengirim `calon_id` tunggal —
        // harus diperlakukan sebagai array 1 elemen (backward compatibility).
        [$guruUser, $pemilih] = $this->createGuruVoter();

        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonL->id,
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('success');

        $this->assertSame(1, Voting::count(), 'Fallback calon_id tunggal harus tetap menghasilkan 1 baris');
        $this->assertSame($this->calonL->id, Voting::first()->calon_id);
        $this->assertSame($pemilih->id, Voting::first()->pemilih_id);
        $this->assertSame('sudah_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function hasil_voting_menampilkan_sudah_memilih_minimal_satu_setelah_vote(): void
    {
        // Siswa vote (dengan pemilih row agar sinkronisasi dual-tracking teruji)
        Pemilih::factory()->create([
            'user_id' => $this->siswaUser->id,
            'user_type' => 'siswa',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), ['calon_id' => $this->calonP->id])
            ->assertRedirect(route('admin.osis.results'));

        // Halaman results harus mencerminkan vote yang baru masuk
        $response = $this->actingAs($this->siswaUser)->get(route('admin.osis.results'));

        $response->assertStatus(200);
        $response->assertViewHas('sudahMemilih', function (int $count): bool {
            return $count >= 1;
        });
        $response->assertViewHas('totalVotes', 1);

        // Total suara kandidat (withCount votings) minimal 1
        $totalPerCalon = collect($response->viewData('calons'))
            ->sum(fn (Calon $calon): int => (int) $calon->total_votes);
        $this->assertSame(1, $totalPerCalon, 'Results harus menampilkan suara kandidat yang masuk');
    }

    /** @test */
    public function post_vote_sa_election_tidak_aktif_ditolak(): void
    {
        // Election dinonaktifkan admin (is_active=false) → scopeActive tidak match
        $this->election->update(['is_active' => false]);

        $response = $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonP->id,
            ]);

        // Ditolak dengan pesan error jelas — ke dashboard (bukan 403 admin.osis.index)
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Tidak ada pemilihan OSIS', (string) session('error'));

        // Tidak ada vote tersimpan, flag siswa tidak berubah
        $this->assertSame(0, Voting::count());
        $this->assertFalse((bool) $this->siswa->fresh()->has_voted_osis);
    }

    /** @test */
    public function post_vote_sa_election_berakhir_ditolak(): void
    {
        // Election aktif tapi end_date sudah lewat → scopeActive tidak match
        $this->election->update(['end_date' => now()->subDay()]);

        $response = $this->actingAs($this->siswaUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonP->id,
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertSame(0, Voting::count());
        $this->assertFalse((bool) $this->siswa->fresh()->has_voted_osis);
    }

    /** @test */
    public function siswa_tanpa_row_siswa_ditolak_dengan_pesan_data_pemilih_belum_tersedia(): void
    {
        // User role siswa tapi baris `siswas` belum ter-link (user_id kosong) —
        // mewakili kasus production: siswa login tapi data pemilih belum ada.
        $orphanUser = User::factory()->create(['email' => 'siswa.orphan.persistence@test.com']);
        $orphanUser->syncRoles([$this->getOrCreateRole('siswa')]);
        $orphanUser->updateQuietly(['user_type' => 'siswa']);

        $response = $this->actingAs($orphanUser)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $this->calonP->id,
            ]);

        // Ditolak dengan pesan jelas + TIDAK auto-generate pemilih (implikasi anti-fraud)
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Data pemilih belum tersedia', (string) session('error'));
        $this->assertSame(0, Voting::count());
    }

    /** @test */
    public function dashboard_siswa_menampilkan_widget_eosis_voting_dan_menu_student(): void
    {
        $response = $this->actingAs($this->siswaUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Widget E-OSIS Voting terisi (election aktif + status hasVoted=false)
        $response->assertViewHas('osisVotingWidget', function ($widget): bool {
            return is_array($widget)
                && isset($widget['election'], $widget['hasVoted'])
                && $widget['election'] instanceof OsisElection
                && $widget['hasVoted'] === false;
        });

        // Widget tampil di halaman dashboard (judul election + CTA voting)
        $response->assertSee('E-OSIS Voting', false);
        $response->assertSee($this->election->title, false);
        $response->assertSee('Mulai Voting', false);

        // Menu "Student" dropdown memuat link voting & hasil voting untuk siswa
        // (E-Services dropdown sengaja TIDAK untuk siswa — gate admin|superadmin|guru|osis)
        $response->assertSee(route('admin.osis.voting'), false);
        $response->assertSee(route('admin.osis.results'), false);
    }

    /** @test */
    public function guru_vote_dua_pilihan_dalam_satu_submit_tersimpan_dua_baris(): void
    {
        // Aturan bisnis BARU: guru centang maksimal 2 pasangan calon → satu submit
        // menghasilkan 2 baris Voting untuk 2 calon berbeda.
        [$guruUser, $pemilih] = $this->createGuruVoter();

        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id, $this->calonP->id],
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('success');
        // Toast guru 2 pilihan mencerminkan jumlah vote
        $this->assertStringContainsString('2 pilihan Anda telah tercatat', (string) session('success'));

        // 2 baris Voting tercatat — keduanya pemilih_id guru + election_id benar,
        // calon_id BERBEDA
        $this->assertSame(2, Voting::count(), 'Guru memilih 2 calon harus menghasilkan 2 baris votings');
        $votes = Voting::orderBy('id')->get();
        $this->assertEqualsCanonicalizing(
            [$this->calonL->id, $this->calonP->id],
            $votes->pluck('calon_id')->all()
        );
        foreach ($votes as $vote) {
            $this->assertSame($pemilih->id, $vote->pemilih_id, 'Vote guru dicatat via pemilih_id');
            $this->assertNull($vote->siswa_id);
            $this->assertSame($this->election->id, $vote->election_id);
            $this->assertTrue((bool) $vote->is_valid);
            $this->assertNotNull($vote->waktu_voting);
            $this->assertNotNull($vote->ip_address);
            $this->assertNotNull($vote->user_agent);
        }

        // Setelah submit guru dianggap sudah memilih (1 submit = selesai, tidak vote lagi)
        $freshPemilih = $pemilih->fresh();
        $this->assertSame('sudah_memilih', $freshPemilih->status);
        $this->assertTrue($freshPemilih->hasVoted());
    }

    /** @test */
    public function guru_vote_dua_pilihan_lalu_submit_lagi_ditolak_jumlah_tetap_dua(): void
    {
        [$guruUser, $pemilih] = $this->createGuruVoter();

        // Submit pertama: 2 pilihan — sukses
        $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id, $this->calonP->id],
            ])
            ->assertRedirect(route('admin.osis.results'));

        $this->assertSame(2, Voting::count());

        // Submit kedua — DITOLAK (guru sudah dianggap memilih)
        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id],
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('info');
        $response->assertSessionMissing('success');

        // Jumlah Voting TETAP 2 — tidak nambah
        $this->assertSame(2, Voting::count(), 'Submit kedua tidak boleh menambah baris votings');
        $this->assertSame('sudah_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_submit_tiga_calon_ditolak_validasi_max_dua(): void
    {
        // Bypass client-side (curl/postman): server WAJIB menolak >2 pilihan
        $calonExtra = Calon::factory()->create([
            'nama_ketua' => 'Ketua Extra',
            'nama_wakil' => 'Wakil Extra',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        [$guruUser, $pemilih] = $this->createGuruVoter();

        $response = $this->actingAs($guruUser)
            ->from(route('admin.osis.voting'))
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id, $this->calonP->id, $calonExtra->id],
            ]);

        // Redirect back + error validasi max:2 + 0 baris voting tersimpan
        $response->assertRedirect(route('admin.osis.voting'));
        $response->assertSessionHasErrors('calon_ids');
        $this->assertSame(0, Voting::count(), 'Vote >2 calon tidak boleh tersimpan');
        $this->assertSame('belum_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_submit_calon_id_duplikat_ditolak(): void
    {
        // Calon yang sama dipilih 2 kali (array berisi nilai sama) → ditolak validasi distinct
        [$guruUser, $pemilih] = $this->createGuruVoter();

        $response = $this->actingAs($guruUser)
            ->from(route('admin.osis.voting'))
            ->post(route('admin.osis.vote'), [
                'calon_ids' => [$this->calonL->id, $this->calonL->id],
            ]);

        $response->assertRedirect(route('admin.osis.voting'));
        // Error validasi distinct — key bisa `calon_ids` atau `calon_ids.1` (elemen duplikat)
        $response->assertSessionHasErrors();
        $errorKeys = collect(session('errors')->getBag('default')->keys());
        $this->assertTrue(
            $errorKeys->contains(fn (string $key): bool => str_starts_with($key, 'calon_ids')),
            'Error validasi harus menunjuk field calon_ids'
        );
        $this->assertSame(0, Voting::count(), 'Vote dengan calon duplikat tidak boleh tersimpan');
        $this->assertSame('belum_memilih', $pemilih->fresh()->status);
    }
}
