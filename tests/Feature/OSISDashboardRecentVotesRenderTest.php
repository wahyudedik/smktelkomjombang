<?php

declare(strict_types=1);

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
 * Regression test untuk crash 500 di GET /admin/osis (admin.osis.index)
 * setelah tabel `votings` berisi vote campuran (siswa + guru).
 *
 * Bug production: resources/views/osis/index.blade.php:294 mengakses
 * `$vote->pemilih->nama` tanpa null-check — vote siswa memiliki
 * `pemilih_id = null` sehingga ErrorException "Attempt to read property
 * 'nama' on null". Relasi pemilih null TIDAK memicu query ke pemilihs/
 * siswas (FK null → Eloquent tidak lazy-load), sesuai bukti query error
 * production yang tidak menampilkan query ke tabel `siswas`.
 *
 * Fix (view-only): null-safe render Recent Voting —
 * - vote siswa (siswa_id terisi): $vote->siswa?->nama_lengkap ?? 'Siswa'
 * - vote guru (pemilih_id terisi): $vote->pemilih?->nama ?? fallback 'Guru'
 * - calon: $vote->calon?->full_candidate_name ?? '—'
 */
class OSISDashboardRecentVotesRenderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected OsisElection $election;

    protected Calon $calon;

    protected function setUp(): void
    {
        parent::setUp();

        // Permission osis.view wajib ADA di DB — CheckPermission menangkap
        // PermissionDoesNotExist dan menolak 403, jadi permission harus
        // di-seed sebelum diberikan ke user admin.
        $this->getOrCreatePermission('osis.view');

        $this->admin = User::factory()->create([
            'email' => 'admin.osis.index@test.com',
        ]);
        $this->admin->syncRoles([$this->getOrCreateRole('admin')]);
        $this->admin->givePermissionTo('osis.view');
        $this->admin->updateQuietly(['user_type' => 'admin']);

        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026/2027',
            'description' => 'Test dashboard recent votes',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'allowed_classes' => null,
        ]);

        $this->calon = Calon::factory()->create([
            'nama_ketua' => 'Ketua Dashboard Test',
            'nama_wakil' => 'Wakil Dashboard Test',
            'jenis_kelamin' => 'L',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * @test
     */
    public function admin_osis_index_render_200_dengan_vote_siswa_dan_guru_campuran(): void
    {
        // Vote siswa: siswa_id terisi, pemilih_id null
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Siswa Regression Test',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'L',
        ]);

        Voting::factory()->create([
            'calon_id' => $this->calon->id,
            'siswa_id' => $siswa->id,
            'pemilih_id' => null,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        // Vote guru: pemilih_id terisi, siswa_id null, pemilih TANPA baris siswas
        $guruUser = User::factory()->create(['email' => 'guru.regression@test.com']);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        $pemilihGuru = Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'nama' => 'Guru Regression Test',
            'status' => 'sudah_memilih',
            'is_active' => true,
        ]);

        Voting::factory()->create([
            'calon_id' => $this->calon->id,
            'siswa_id' => null,
            'pemilih_id' => $pemilihGuru->id,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        // Pastikan pemilih guru benar-benar TANPA baris siswa terkait
        // (ini yang membuat $vote->siswa null untuk vote guru)
        $this->assertNull(Siswa::where('user_id', $guruUser->id)->first());

        $response = $this->actingAs($this->admin)->get(route('admin.osis.index'));

        // Sebelum fix: 500 ErrorException "Attempt to read property 'nama' on null"
        $response->assertStatus(200);

        // Kedua nama voter tampil di bagian Recent Voting (fallback label 'Guru'
        // hanya dipakai jika pemilihs.nama juga kosong — factory selalu mengisi)
        $response->assertSee('Siswa Regression Test');
        $response->assertSee('Guru Regression Test');
    }

    /**
     * @test
     */
    public function admin_osis_index_render_aman_dengan_vote_guru_saja(): void
    {
        // Vote guru tunggal (pemilih_id terisi, siswa_id null) — kasus yang
        // sebelumnya crash karena $vote->pemilih->nama diakses saat relasi
        // pemilih null pada vote siswa; di sini pemilih ada, tapi $vote->siswa
        // tetap null dan tidak boleh diakses tanpa guard.
        $guruUser = User::factory()->create(['email' => 'guru.only@test.com']);
        $guruUser->syncRoles([$this->getOrCreateRole('guru')]);
        $guruUser->updateQuietly(['user_type' => 'guru']);

        $pemilihGuru = Pemilih::factory()->create([
            'user_id' => $guruUser->id,
            'user_type' => 'guru',
            'nama' => 'Guru Only Vote',
            'status' => 'sudah_memilih',
            'is_active' => true,
        ]);

        Voting::factory()->create([
            'calon_id' => $this->calon->id,
            'siswa_id' => null,
            'pemilih_id' => $pemilihGuru->id,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.osis.index'));

        $response->assertStatus(200);
        $response->assertSee('Guru Only Vote');
    }

    /**
     * @test
     */
    public function admin_osis_index_render_aman_dengan_vote_siswa_saja(): void
    {
        // Vote siswa tunggal (pemilih_id null) — kasus crash asli production
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Siswa Only Vote',
            'kelas' => 'XI TKJ 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
        ]);

        Voting::factory()->create([
            'calon_id' => $this->calon->id,
            'siswa_id' => $siswa->id,
            'pemilih_id' => null,
            'election_id' => $this->election->id,
            'is_valid' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.osis.index'));

        // Sebelum fix: 500 "Attempt to read property 'nama' on null"
        $response->assertStatus(200);
        $response->assertSee('Siswa Only Vote');
    }
}
