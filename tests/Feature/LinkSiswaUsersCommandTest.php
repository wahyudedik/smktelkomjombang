<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test artisan command `osis:link-siswa-users` (linking massal siswas.user_id).
 *
 * Match key NYATA (dari schema): `siswas.email` (nullable) ↔ `users.email`
 * (unique), case-insensitive, kandidat HANYA user role 'siswa'.
 *
 * Aturan anti-fraud yang diuji:
 * - UPDATE ONLY: tidak pernah membuat baris `siswas` baru.
 * - Idempoten: siswa yang sudah ter-link TIDAK berubah.
 * - Tanpa match → user_id tetap NULL, command sukses (exit 0).
 * - Konflik (user kandidat sudah ter-link ke siswa lain) → skip, tidak overwrite.
 * - Ambigu (>1 kandidat match) → skip, tidak memilih acak.
 */
class LinkSiswaUsersCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: buat user dengan role siswa + email tertentu.
     */
    private function createSiswaUser(string $email): User
    {
        $user = User::factory()->create(['email' => $email]);
        $user->syncRoles([$this->getOrCreateRole('siswa')]);

        return $user;
    }

    /** @test */
    public function siswa_tanpa_user_id_dan_user_email_match_terlink(): void
    {
        $user = $this->createSiswaUser('link.me@test.com');

        $siswa = Siswa::factory()->create([
            'user_id' => null,
            'email' => 'link.me@test.com',
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertSame($user->id, $siswa->fresh()->user_id, 'Siswa dengan email match harus ter-link ke user role siswa');
    }

    /** @test */
    public function siswa_yang_sudah_terlink_tidak_berubah_idempoten(): void
    {
        $linkedUser = $this->createSiswaUser('already.linked@test.com');
        $otherUser = $this->createSiswaUser('other@test.com');

        // Siswa A sudah ter-link ke user lain (user_id bukan milik email-nya sendiri)
        $siswaA = Siswa::factory()->create([
            'user_id' => $otherUser->id,
            'email' => 'already.linked@test.com',
        ]);
        // User dengan email match tersedia — command tidak boleh "memperbaiki" link yang sudah ada
        $this->assertNotNull($linkedUser->id);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertSame($otherUser->id, $siswaA->fresh()->user_id, 'Siswa yang sudah ter-link TIDAK boleh diubah (idempoten)');
    }

    /** @test */
    public function siswa_tanpa_match_user_id_tetap_null_dan_command_sukses(): void
    {
        $siswa = Siswa::factory()->create([
            'user_id' => null,
            'email' => 'tidak.ada.user@test.com',
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertNull($siswa->fresh()->user_id, 'Siswa tanpa kandidat user harus tetap user_id NULL');
    }

    /** @test */
    public function siswa_email_kosong_juga_dihitung_tanpa_match(): void
    {
        $siswa = Siswa::factory()->create([
            'user_id' => null,
            'email' => null,
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertNull($siswa->fresh()->user_id);
    }

    /** @test */
    public function konflik_user_sudah_terlink_ke_siswa_lain_skip_tanpa_overwrite(): void
    {
        // User X sudah ter-link ke siswa A
        $userX = $this->createSiswaUser('konflik@test.com');
        $siswaA = Siswa::factory()->create([
            'user_id' => $userX->id,
            'nama_lengkap' => 'Siswa A',
        ]);
        // Siswa B (user_id null) punya email yang sama dengan user X → KONFLIK
        $siswaB = Siswa::factory()->create([
            'user_id' => null,
            'email' => 'konflik@test.com',
            'nama_lengkap' => 'Siswa B',
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        // Siswa A tidak berubah, siswa B tidak di-link (tidak overwrite)
        $this->assertSame($userX->id, $siswaA->fresh()->user_id, 'Link siswa A tidak boleh diubah');
        $this->assertNull($siswaB->fresh()->user_id, 'Siswa B harus di-skip (konflik) — tidak menyalin link siswa A');
    }

    /** @test */
    public function ambigu_dua_user_email_huruf_besar_kecil_sama_skip(): void
    {
        // SQLite unique index case-sensitive → dua user dengan email beda case bisa ada.
        // Keduanya match LOWER(email) siswa → kandidat ambigu → SKIP (jangan pilih acak).
        $userUpper = $this->createSiswaUser('Ambig@Test.com');
        $userLower = $this->createSiswaUser('ambig@test.com');

        $siswa = Siswa::factory()->create([
            'user_id' => null,
            'email' => 'ambig@test.com',
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertNull($siswa->fresh()->user_id, 'Match ambigu harus di-skip, bukan dipilih acak');
        $this->assertNotNull($userUpper->id);
        $this->assertNotNull($userLower->id);
    }

    /** @test */
    public function command_update_only_tidak_membuat_baris_siswas_baru(): void
    {
        $this->createSiswaUser('update.only@test.com');

        // 1 siswa null + 1 siswa sudah ter-link
        Siswa::factory()->create(['user_id' => null, 'email' => 'update.only@test.com']);
        Siswa::factory()->create(['user_id' => $this->createSiswaUser('linked@test.com')->id]);

        $countBefore = Siswa::count();

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertSame($countBefore, Siswa::count(), 'Command UPDATE ONLY — jumlah baris siswas tidak boleh berubah');
    }

    /** @test */
    public function user_tanpa_role_siswa_tidak_dipakai_sebagai_kandidat(): void
    {
        // User dengan email match TANPA role siswa → bukan kandidat valid
        $nonSiswa = User::factory()->create(['email' => 'bukan.siswa@test.com']);
        // (tidak di-syncRoles)

        $siswa = Siswa::factory()->create([
            'user_id' => null,
            'email' => 'bukan.siswa@test.com',
        ]);

        $this->artisan('osis:link-siswa-users')
            ->assertExitCode(0);

        $this->assertNull($siswa->fresh()->user_id, 'User tanpa role siswa tidak boleh dijadikan kandidat link');
        // Cek langsung via query (User tidak punya relasi `siswas()` — hindari BadMethodCallException)
        $this->assertNull(Siswa::where('user_id', $nonSiswa->id)->first());
    }
}
