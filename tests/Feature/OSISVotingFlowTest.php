<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Voting;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * Flow test OSIS voting (jalur login: admin.osis.*).
 *
 * ADAPTASI MULTI-ELECTION (fitur baru): semua jalur vote/results/voting page
 * kini mewajibkan `OsisElection::active()` — setUp membuat election aktif
 * (start di masa lalu, end di masa depan, is_active=true, is_locked=false).
 *
 * ADAPTASI LAINNYA (perilaku produksi saat ini, dikonfirmasi user):
 * - Vote siswa dicatat via `votings.siswa_id` (pemilih_id SELALU null di jalur siswa).
 * - Double-vote kini redirect + flash `info` (bukan `errors`) — lihat OSISController::processVote().
 * - Route `admin.osis.results` berada di grup `role:siswa|guru` (admin mengakses hasil
 *   via dashboard / admin.osis.teacher-view) — user admin ditambahkan role guru sebagai setup akses.
 * - Jalur vote login (siswa & guru) kini menegakkan `calons.is_active` DAN
 *   `pemilihs.is_active` (fix audit keamanan) — konsisten dengan voting publik.
 */
class OSISVotingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;

    protected User $siswa;

    protected Siswa $siswaData;

    protected OsisElection $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Election aktif yang mencakup waktu sekarang — wajib untuk semua jalur
        // voting/results sejak fitur multi-election OSIS.
        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026/2027',
            'description' => 'Test election for OSISVotingFlowTest',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'allowed_classes' => null, // semua kelas diizinkan
        ]);

        // Create superadmin (role admin + permission yang diminta route middleware
        // `permission:osis.*` di grup admin.osis)
        $this->superadmin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $adminRole = $this->getOrCreateRole('admin');
        $adminRole->givePermissionTo($this->getOrCreatePermission('osis.view'));
        $adminRole->givePermissionTo($this->getOrCreatePermission('osis.create'));
        $this->superadmin->syncRoles([$adminRole]);
        $this->superadmin->updateQuietly(['user_type' => 'admin']);

        // Create siswa user
        $this->siswa = User::factory()->create([
            'email' => 'siswa@test.com',
            'password' => Hash::make('password'),
        ]);
        $siswaRole = $this->getOrCreateRole('siswa');
        $this->siswa->syncRoles([$siswaRole]);
        $this->siswa->updateQuietly(['user_type' => 'siswa']);

        // Create siswa data — TER-LINK ke user via user_id. Controller resolve
        // Siswa::where('user_id', $user->id); tanpa linking ini semua vote/voting
        // page di-redirect (lihat OSISVotingAccessFixTest).
        $this->siswaData = Siswa::factory()->create([
            'user_id' => $this->siswa->id,
            'nama_lengkap' => 'Test Siswa',
            'nis' => '12345',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'L', // deterministik — kandidat segender dibuat 'L' di test vote
            'has_voted_osis' => false,
        ]);
    }

    /** @test */
    public function admin_can_create_calon()
    {
        // Payload mengikuti skema validasi storeCalon() saat ini
        // (field lama kelas_ketua/visi/misi sudah diganti jenis_kelamin + visi_misi).
        $calonData = [
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'jenis_kelamin' => 'L',
            'visi_misi' => 'Visi dan misi test',
            'jenis_pencalonan' => 'pasangan',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.osis.calon.store'), $calonData);

        $response->assertRedirect();
        $this->assertDatabaseHas('calons', [
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
        ]);
    }

    /** @test */
    public function admin_can_create_pemilih()
    {
        // Payload mengikuti skema validasi storePemilih() saat ini
        // (jenis_kelamin kini required|in:L,P).
        $pemilihData = [
            'nama' => 'Test Siswa',
            'nis' => '12345',
            'kelas' => 'X IPA 1',
            'jenis_kelamin' => 'L',
            'email' => 'siswa@test.com',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superadmin)
            ->post(route('admin.osis.pemilih.store'), $pemilihData);

        $response->assertRedirect();
        $this->assertDatabaseHas('pemilihs', [
            'nama' => 'Test Siswa',
            'nis' => '12345',
        ]);
    }

    /** @test */
    public function siswa_can_access_voting_page()
    {
        $response = $this->actingAs($this->siswa)
            ->get(route('admin.osis.voting'));

        $response->assertStatus(200);
    }

    /** @test */
    public function siswa_can_vote()
    {
        // Create calon segender dengan siswa (gender enforcement server-side di processVote)
        $calon = Calon::factory()->create([
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'jenis_kelamin' => 'L',
            'is_active' => true,
        ]);

        // Create pemilih (marker dual-tracking: ikut ter-sync ke 'sudah_memilih' setelah vote)
        Pemilih::factory()->create([
            'user_id' => $this->siswa->id,
            'user_type' => 'siswa',
            'nama' => 'Test Siswa',
            'nis' => '99991',
            'email' => $this->siswa->email,
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $voteData = [
            'calon_id' => $calon->id,
        ];

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), $voteData);

        $response->assertRedirect();
        // Vote siswa dicatat via siswa_id + election_id aktif
        // (pemilih_id SELALU null di jalur siswa — lihat OSISController::processVote()).
        $this->assertDatabaseHas('votings', [
            'calon_id' => $calon->id,
            'siswa_id' => $this->siswaData->id,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);
    }

    /** @test */
    public function siswa_cannot_vote_twice()
    {
        $calon = Calon::factory()->create([
            'jenis_kelamin' => 'L', // segender dengan $this->siswaData
            'is_active' => true,
        ]);
        Pemilih::factory()->create([
            'user_id' => $this->siswa->id,
            'user_type' => 'siswa',
            'email' => $this->siswa->email,
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        // First vote
        $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
            ]);

        // Second vote attempt — double-vote kini ditandai flash `info` +
        // redirect ke hasil (bukan fake success, bukan `errors`).
        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
            ]);

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('info');

        $voteCount = Voting::where('siswa_id', $this->siswaData->id)->count();
        $this->assertEquals(1, $voteCount);
    }

    /** @test */
    public function admin_can_view_voting_results()
    {
        // Route admin.osis.results kini berada di grup `role:siswa|guru` —
        // user admin mengakses hasil via dashboard / admin.osis.teacher-view.
        // Tambahkan role guru sebagai setup akses hasil untuk user admin ini.
        $this->superadmin->assignRole($this->getOrCreateRole('guru'));

        $calon = Calon::factory()->create(['is_active' => true]);
        $pemilih = Pemilih::factory()->create([
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        Voting::factory()->create([
            'calon_id' => $calon->id,
            'pemilih_id' => $pemilih->id,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.osis.results'));

        $response->assertStatus(200);
        $response->assertViewIs('osis.results');
    }

    /** @test */
    public function only_active_calon_can_receive_votes()
    {
        // Jalur vote GURU menegakkan `calon.is_active` via
        // Rule::exists + is_active=true → validation errors (lihat juga
        // siswa_cannot_vote_for_inactive_calon untuk jalur siswa, dan
        // PublicOsisVotingTest untuk voting publik).
        $guruUser = User::factory()->create(['email' => 'guru.vote@test.com']);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'status' => 'belum_memilih',
            'is_active' => true,
        ]);

        $inactiveCalon = Calon::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->actingAs($guruUser)
            ->post(route('admin.osis.vote'), [
                // Backward-compat: form guru boleh mengirim calon_id tunggal →
                // di-merge menjadi calon_ids, lalu divalidasi exists+is_active.
                'calon_id' => $inactiveCalon->id,
            ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('votings', [
            'calon_id' => $inactiveCalon->id,
        ]);
    }

    /** @test */
    public function inactive_pemilih_cannot_vote_via_login_flow()
    {
        // PERILAKU SETELAH FIX (audit keamanan): jalur vote login (admin.osis.vote)
        // kini men-gate berdasarkan `pemilihs.is_active` — konsisten dengan voting
        // publik (PublicOsisController::verify — lihat PublicOsisVotingTest::
        // inactive_voter_cannot_vote_via_public_flow). Siswa dengan record pemilih
        // non-aktif DITOLAK vote via login (redirect error + vote tidak tercatat).
        $calon = Calon::factory()->create([
            'jenis_kelamin' => 'L', // segender dengan $this->siswaData
            'is_active' => true,
        ]);
        $inactivePemilih = Pemilih::factory()->create([
            'user_id' => $this->siswa->id,
            'user_type' => 'siswa',
            'email' => $this->siswa->email,
            'is_active' => false,
            'status' => 'belum_memilih',
        ]);

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $calon->id,
                'pemilih_id' => $inactivePemilih->id,
            ]);

        // Ditolak: redirect ke dashboard + flash error (bukan sukses/fake success)
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('votings', [
            'calon_id' => $calon->id,
            'siswa_id' => $this->siswaData->id,
        ]);
        $this->assertFalse($this->siswaData->fresh()->has_voted_osis);
    }

    /** @test */
    public function siswa_cannot_vote_for_inactive_calon()
    {
        // PERILAKU SETELAH FIX (audit keamanan): jalur vote siswa login kini
        // menerapkan filter `calons.is_active` di validasi (Rule::exists +
        // where is_active=true → validation errors) — konsisten dengan jalur
        // guru (only_active_calon_can_receive_votes) & voting publik.
        $inactiveCalon = Calon::factory()->create([
            'jenis_kelamin' => 'L', // segender dengan $this->siswaData
            'is_active' => false,
        ]);
        Pemilih::factory()->create([
            'user_id' => $this->siswa->id,
            'user_type' => 'siswa',
            'email' => $this->siswa->email,
            'is_active' => true,
            'status' => 'belum_memilih',
        ]);

        $response = $this->actingAs($this->siswa)
            ->post(route('admin.osis.vote'), [
                'calon_id' => $inactiveCalon->id,
            ]);

        $response->assertSessionHasErrors('calon_id');
        $this->assertDatabaseMissing('votings', [
            'calon_id' => $inactiveCalon->id,
        ]);
        $this->assertFalse($this->siswaData->fresh()->has_voted_osis);
    }
}
