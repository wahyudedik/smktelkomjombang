<?php

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test untuk Public E-OSIS Voting (pemungutan suara publik tanpa login).
 *
 * Alur: landing (form verifikasi) → vote (kartu kandidat) → thanks.
 * Hasil publik hanya tampil jika election resultsVisible().
 */
class PublicOsisVotingTest extends TestCase
{
    use RefreshDatabase;

    private OsisElection $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026',
            'description' => 'Test election',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'show_results' => false,
        ]);
    }

    /** @test */
    public function landing_page_is_accessible_without_login()
    {
        $response = $this->get(route('osis.public.landing'));

        $response->assertStatus(200);
        $response->assertSee('Verifikasi Identitas');
    }

    /** @test */
    public function landing_shows_no_election_banner_when_election_inactive()
    {
        $this->election->update(['is_active' => false]);

        $response = $this->get(route('osis.public.landing'));

        $response->assertStatus(200);
        $response->assertSee('belum dimulai');
    }

    /** @test */
    public function verify_requires_tipe_and_identifier()
    {
        $response = $this->post(route('osis.public.verify'), []);

        $response->assertSessionHasErrors(['tipe', 'identifier']);
    }

    /** @test */
    public function voter_with_unknown_identifier_is_rejected_with_generic_error()
    {
        $response = $this->from(route('osis.public.landing'))
            ->post(route('osis.public.verify'), [
                'tipe' => 'siswa',
                'identifier' => '99999999',
            ]);

        $response->assertRedirect(route('osis.public.landing'));
        $response->assertSessionHas('error');
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function siswa_can_verify_with_nis_and_vote()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calon = Calon::factory()->create([
            'jenis_kelamin' => 'L',
            'is_active' => true,
        ]);

        // Verify dengan NIS
        $verifyResponse = $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        $verifyResponse->assertRedirect(route('osis.public.vote'));
        $this->assertNotNull(session('public_osis_voter'));
        $this->assertEquals($pemilih->id, session('public_osis_voter.pemilih_id'));

        // Halaman vote menampilkan kartu kandidat
        $votePage = $this->get(route('osis.public.vote'));
        $votePage->assertStatus(200);
        $votePage->assertSee($calon->nama_ketua);

        // Proses vote
        $voteResponse = $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calon->id,
        ]);

        $voteResponse->assertRedirect(route('osis.public.thanks'));

        $this->assertDatabaseHas('votings', [
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $this->assertEquals('sudah_memilih', $pemilih->fresh()->status);
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function siswa_can_verify_with_nisn()
    {
        Pemilih::factory()->create([
            'nisn' => '009876543210',
            'user_type' => 'siswa',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $response = $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '009876543210',
        ]);

        $response->assertRedirect(route('osis.public.vote'));
        $this->assertNotNull(session('public_osis_voter'));
    }

    /** @test */
    public function guru_can_verify_with_nip()
    {
        // Guru: NIP disimpan di kolom pemilihs.nis oleh generatePemilihFromUsers()
        Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $response = $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response->assertRedirect(route('osis.public.vote'));
        $this->assertNotNull(session('public_osis_voter'));
        $this->assertEquals('guru', session('public_osis_voter.user_type'));
    }

    /** @test */
    public function siswa_identifier_does_not_match_guru_pemilih()
    {
        // Pemilih guru — siswa verify tidak boleh match
        Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'is_active' => true,
        ]);

        $response = $this->from(route('osis.public.landing'))
            ->post(route('osis.public.verify'), [
                'tipe' => 'siswa',
                'identifier' => '19870001',
            ]);

        $response->assertRedirect(route('osis.public.landing'));
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function already_voted_voter_is_redirected_to_thanks()
    {
        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'is_active' => true,
            'status' => 'sudah_memilih',
        ]);

        $response = $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        $response->assertRedirect(route('osis.public.thanks'));
        $this->assertTrue(session('already_voted'));
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function vote_page_redirects_to_landing_without_verification_session()
    {
        $response = $this->get(route('osis.public.vote'));

        $response->assertRedirect(route('osis.public.landing'));
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function vote_store_rejects_request_without_verification_session()
    {
        $calon = Calon::factory()->create(['is_active' => true]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calon->id,
        ]);

        $response->assertRedirect(route('osis.public.landing'));
        $this->assertDatabaseCount('votings', 0);
    }

    /** @test */
    public function vote_page_shows_only_gender_matched_candidates_for_siswa()
    {
        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calonL = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);
        $calonP = Calon::factory()->create(['jenis_kelamin' => 'P', 'is_active' => true]);

        // Verify dulu agar session terisi
        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        $response = $this->get(route('osis.public.vote'));

        $response->assertStatus(200);
        $response->assertSee($calonL->nama_ketua);
        $response->assertDontSee($calonP->nama_ketua);
    }

    /** @test */
    public function vote_page_shows_all_candidates_for_guru()
    {
        Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calonL = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);
        $calonP = Calon::factory()->create(['jenis_kelamin' => 'P', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->get(route('osis.public.vote'));

        $response->assertStatus(200);
        $response->assertSee($calonL->nama_ketua);
        $response->assertSee($calonP->nama_ketua);
    }

    /** @test */
    public function gender_mismatch_vote_is_rejected_server_side()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L', // cowok
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calonP = Calon::factory()->create(['jenis_kelamin' => 'P', 'is_active' => true]);

        // Verify dulu
        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        // Vote ke calon beda gender — harus ditolak server-side
        $response = $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calonP->id,
        ]);

        $response->assertRedirect(route('osis.public.vote'));
        $this->assertDatabaseCount('votings', 0);
        $this->assertEquals('belum_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function inactive_candidate_vote_is_rejected()
    {
        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $inactiveCalon = Calon::factory()->create(['is_active' => false]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_id' => $inactiveCalon->id,
        ]);

        $response->assertSessionHasErrors('calon_id');
        $this->assertDatabaseCount('votings', 0);
    }

    /** @test */
    public function inactive_voter_cannot_vote_via_public_flow()
    {
        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'is_active' => false, // tidak aktif
            'status' => 'belum_memilih',
        ]);

        $response = $this->from(route('osis.public.landing'))
            ->post(route('osis.public.verify'), [
                'tipe' => 'siswa',
                'identifier' => '12345',
            ]);

        $response->assertRedirect(route('osis.public.landing'));
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function voter_outside_allowed_classes_is_rejected()
    {
        $this->election->update(['allowed_classes' => ['XI TKJ 1']]);

        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'kelas' => 'X RPL 1', // tidak diizinkan
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $response = $this->from(route('osis.public.landing'))
            ->post(route('osis.public.verify'), [
                'tipe' => 'siswa',
                'identifier' => '12345',
            ]);

        $response->assertRedirect(route('osis.public.landing'));
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function voter_cannot_vote_twice_via_public_flow()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calon = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        // Vote pertama
        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);
        $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calon->id,
        ]);

        // Vote kedua — session sudah dihapus, harus redirect landing
        $secondVote = $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calon->id,
        ]);

        $secondVote->assertRedirect(route('osis.public.landing'));
        $this->assertEquals(1, Voting::where('pemilih_id', $pemilih->id)->count());
    }

    /** @test */
    public function thanks_page_shows_voted_state_after_voting()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);
        $calon = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);
        $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calon->id,
        ]);

        // Setelah redirect ke thanks — session 'voted' true
        $response = $this->get(route('osis.public.thanks'));

        $response->assertStatus(200);
        $response->assertSee('Terima Kasih');
    }

    /** @test */
    public function thanks_page_shows_already_voted_state()
    {
        session(['already_voted' => true]);

        $response = $this->get(route('osis.public.thanks'));

        $response->assertStatus(200);
        $response->assertSee('Sudah Menggunakan Hak Pilih');
    }

    /** @test */
    public function thanks_page_redirects_without_session()
    {
        $response = $this->get(route('osis.public.thanks'));

        $response->assertRedirect(route('osis.public.landing'));
    }

    /** @test */
    public function public_results_page_is_accessible()
    {
        $response = $this->get(route('osis.public.results'));

        $response->assertStatus(200);
    }

    /** @test */
    public function public_results_hidden_when_show_results_disabled()
    {
        $calon = Calon::factory()->create(['is_active' => true]);
        $pemilih = Pemilih::factory()->create(['is_active' => true]);

        Voting::factory()->create([
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
            'election_id' => $this->election->id,
        ]);

        // Election aktif tapi show_results=false, belum locked, belum berakhir
        $response = $this->get(route('osis.public.results'));

        $response->assertStatus(200);
        $response->assertSee('Belum Diumumkan');
        // Rincian per calon tidak boleh tampil
        $response->assertDontSee($calon->nama_ketua);
    }

    /** @test */
    public function public_results_visible_when_show_results_enabled()
    {
        $this->election->update(['show_results' => true]);

        $calon = Calon::factory()->create(['is_active' => true]);
        $pemilih = Pemilih::factory()->create(['is_active' => true]);

        Voting::factory()->create([
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
            'election_id' => $this->election->id,
        ]);

        $response = $this->get(route('osis.public.results'));

        $response->assertStatus(200);
        $response->assertSee($calon->nama_ketua);
    }

    /** @test */
    public function public_results_always_shows_participation_summary()
    {
        Pemilih::factory()->create(['is_active' => true, 'status' => 'belum_memilih']);
        Pemilih::factory()->create(['is_active' => true, 'status' => 'sudah_memilih']);

        $response = $this->get(route('osis.public.results'));

        $response->assertStatus(200);
        $response->assertSee('Partisipasi');
        $response->assertSee('Total Pemilih');
    }

    /*
    |--------------------------------------------------------------------------
    | Guru multi-vote publik (1–2 kandidat) — mirror jalur login
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function guru_public_can_vote_single_candidate()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '19870001', // NIP guru
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calon = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => [$calon->id],
        ]);

        $response->assertRedirect(route('osis.public.thanks'));

        // Tepat 1 baris Voting terinsert
        $this->assertDatabaseCount('votings', 1);
        $this->assertDatabaseHas('votings', [
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
            'siswa_id' => null,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $this->assertEquals('sudah_memilih', $pemilih->fresh()->status);
        $this->assertNull(session('public_osis_voter'));
    }

    /** @test */
    public function guru_public_can_vote_two_candidates_in_one_transaction()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        // Guru boleh memilih kandidat laki-laki & perempuan dalam satu vote
        $calonL = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);
        $calonP = Calon::factory()->create(['jenis_kelamin' => 'P', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => [$calonL->id, $calonP->id],
        ]);

        $response->assertRedirect(route('osis.public.thanks'));

        // 2 baris Voting terinsert dalam satu transaksi — pemilih_id & election_id sama
        $this->assertDatabaseCount('votings', 2);
        $this->assertEquals(2, Voting::where('pemilih_id', $pemilih->id)
            ->where('election_id', $this->election->id)
            ->where('is_valid', true)
            ->count());
        $this->assertDatabaseHas('votings', [
            'calon_id' => $calonL->id,
            'pemilih_id' => $pemilih->id,
            'siswa_id' => null,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);
        $this->assertDatabaseHas('votings', [
            'calon_id' => $calonP->id,
            'pemilih_id' => $pemilih->id,
            'siswa_id' => null,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $this->assertEquals('sudah_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_public_vote_three_candidates_is_rejected()
    {
        Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calons = Calon::factory()->count(3)->create(['is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => $calons->pluck('id')->all(),
        ]);

        // Validasi max:2 → tolak, tidak ada vote terinsert
        $response->assertSessionHasErrors('calon_ids');
        $this->assertDatabaseCount('votings', 0);
    }

    /** @test */
    public function guru_public_vote_with_duplicate_candidate_is_rejected()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calon = Calon::factory()->create(['is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        // Kandidat yang sama dipilih 2x → validasi distinct menolak
        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => [$calon->id, $calon->id],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('votings', 0);
        $this->assertEquals('belum_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_public_vote_with_inactive_candidate_is_rejected()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $activeCalon = Calon::factory()->create(['is_active' => true]);
        $inactiveCalon = Calon::factory()->create(['is_active' => false]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => [$activeCalon->id, $inactiveCalon->id],
        ]);

        // Validasi exists (is_active=true) menolak calon non-aktif
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('votings', 0);
        $this->assertEquals('belum_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function siswa_public_cannot_use_calon_ids_array()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calonL = Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        // Siswa TIDAK bisa memakai calon_ids[] — hanya calon_id tunggal (radio)
        $response = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => [$calonL->id],
        ]);

        $response->assertSessionHasErrors('calon_id');
        $this->assertDatabaseCount('votings', 0);
        $this->assertEquals('belum_memilih', $pemilih->fresh()->status);

        // Vote siswa tetap single-select via calon_id tunggal → sukses 1 baris
        $this->post(route('osis.public.vote.store'), [
            'calon_id' => $calonL->id,
        ]);

        $this->assertDatabaseCount('votings', 1);
        $this->assertEquals('sudah_memilih', $pemilih->fresh()->status);
    }

    /** @test */
    public function guru_public_cannot_vote_twice()
    {
        $pemilih = Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $calons = Calon::factory()->count(2)->create(['is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        // Vote pertama: 2 kandidat → sukses
        $this->post(route('osis.public.vote.store'), [
            'calon_ids' => $calons->pluck('id')->all(),
        ]);

        $this->assertDatabaseCount('votings', 2);
        $this->assertEquals('sudah_memilih', $pemilih->fresh()->status);

        // Verify ulang → sudah_memilih, redirect ke thanks (bukan form vote)
        $reVerify = $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $reVerify->assertRedirect(route('osis.public.thanks'));
        $this->assertTrue(session('already_voted'));

        // POST vote kedua (session verifikasi sudah tidak ada) → tolak, jumlah baris tetap 2
        $secondVote = $this->post(route('osis.public.vote.store'), [
            'calon_ids' => $calons->pluck('id')->all(),
        ]);

        $secondVote->assertRedirect(route('osis.public.landing'));
        $this->assertDatabaseCount('votings', 2);
    }

    /** @test */
    public function guru_vote_page_shows_checkbox_with_counter()
    {
        Pemilih::factory()->create([
            'nis' => '19870001',
            'user_type' => 'guru',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'guru',
            'identifier' => '19870001',
        ]);

        $response = $this->get(route('osis.public.vote'));

        $response->assertStatus(200);
        // Checkbox multi-select + counter + petunjuk 1-2 kandidat
        $response->assertSee('name="calon_ids[]"', false);
        $response->assertSee('id="vote-counter"', false);
        $response->assertSee('dipilih');
        $response->assertSee('Anda dapat memilih 1 atau 2');
        // Radio single-select tidak boleh dipakai untuk guru
        $response->assertDontSee('name="calon_id"', false);
    }

    /** @test */
    public function siswa_vote_page_still_shows_single_radio()
    {
        Pemilih::factory()->create([
            'nis' => '12345',
            'user_type' => 'siswa',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        Calon::factory()->create(['jenis_kelamin' => 'L', 'is_active' => true]);

        $this->post(route('osis.public.verify'), [
            'tipe' => 'siswa',
            'identifier' => '12345',
        ]);

        $response = $this->get(route('osis.public.vote'));

        $response->assertStatus(200);
        // Siswa: radio single-select + petunjuk gender tetap ada
        $response->assertSee('name="calon_id"', false);
        $response->assertDontSee('name="calon_ids[]"', false);
        $response->assertSee('hanya dapat memilih calon');
    }
}
