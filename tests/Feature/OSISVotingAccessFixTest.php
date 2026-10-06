<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test perbaikan akses voting OSIS:
 *
 * 1. Middleware route voting memakai `verified.email` (EnsureEmailIsVerified
 *    CUSTOM — alias di bootstrap/app.php:24), bukan alias framework `verified`:
 *    - Siswa belum email-verified DAN belum admin-verified → redirect ke
 *      verification.notice (behavior middleware custom yang sebenarnya).
 *    - Siswa `is_verified_by_admin = true` walau `email_verified_at = null`
 *      → BOLEH akses halaman voting (inti perbaikan middleware).
 *
 * 2. voting() MENOLAK render form untuk siswa tanpa baris `siswas`
 *    (user_id belum ter-link) → redirect ke admin.dashboard + error jelas
 *    (perilaku BARU — sebelumnya form tetap dirender lalu submit gagal diam-diam).
 */
class OSISVotingAccessFixTest extends TestCase
{
    use RefreshDatabase;

    protected OsisElection $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->election = OsisElection::create([
            'title' => 'Pemilihan OSIS 2026/2027',
            'description' => 'Test election access fix',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
            'is_locked' => false,
            'max_votes_per_student' => 1,
            'allowed_classes' => null,
        ]);
    }

    /**
     * Helper: buat user role siswa dengan kontrol penuh atas field verifikasi.
     */
    private function createSiswaUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->syncRoles([$this->getOrCreateRole('siswa')]);

        return $user;
    }

    /** @test */
    public function siswa_belum_email_verified_dan_belum_admin_verified_redirect_ke_verification_notice(): void
    {
        $user = $this->createSiswaUser([
            'email_verified_at' => null,
            'is_verified_by_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.osis.voting'));

        // EnsureEmailIsVerified custom: belum keduanya → redirect ke verification.notice
        // (route 'verification.notice' = /verify-email, routes/auth.php)
        $response->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function siswa_admin_verified_tanpa_email_verified_bisa_akses_voting(): void
    {
        // INTI PERBAIKAN MIDDLEWARE: admin-verified TETAP bisa akses walau email belum verified
        $user = $this->createSiswaUser([
            'email_verified_at' => null,
            'is_verified_by_admin' => true,
        ]);

        // Baris siswa ter-link + kandidat aktif agar halaman voting render penuh
        Siswa::factory()->create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Siswa Admin Verified',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
            'has_voted_osis' => false,
        ]);
        Calon::factory()->create([
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'jenis_kelamin' => 'P',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('admin.osis.voting'));

        $response->assertStatus(200);
    }

    /** @test */
    public function siswa_tanpa_baris_siswas_voting_redirect_ke_dashboard_dengan_error(): void
    {
        // Perilaku BARU voting(): siswa tanpa baris `siswas` → JANGAN render form.
        // Redirect ke admin.dashboard dengan pesan jelas (bukan render + submit gagal diam-diam).
        $user = $this->createSiswaUser([
            'email_verified_at' => now(), // lulus middleware verified.email
        ]);
        // Tidak membuat baris Siswa untuk user ini

        $response = $this->actingAs($user)->get(route('admin.osis.voting'));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            'belum terdaftar',
            (string) session('error'),
            'Pesan error harus menjelaskan akun belum terdaftar sebagai siswa'
        );
        // Form voting TIDAK dirender (cek marker tombol submit halaman voting)
        $response->assertDontSee('Kirim Suara');
    }

    /** @test */
    public function siswa_terlink_dan_sudah_memilih_tetap_redirect_ke_results(): void
    {
        // Regresi perilaku lama yang TIDAK berubah: sudah vote → redirect results + info
        $user = $this->createSiswaUser(['email_verified_at' => now()]);
        $siswa = Siswa::factory()->create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Siswa Sudah Vote',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
            'has_voted_osis' => true,
            'voted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.osis.voting'));

        $response->assertRedirect(route('admin.osis.results'));
        $response->assertSessionHas('info');
        $this->assertTrue($siswa->fresh()->hasVotedOsis());
    }

    /** @test */
    public function siswa_terlink_belum_memilih_tetap_render_form_voting(): void
    {
        // Regresi: siswa ter-link + belum vote → form voting tetap render (200)
        $user = $this->createSiswaUser(['email_verified_at' => now()]);
        Siswa::factory()->create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Siswa Valid',
            'kelas' => 'X IPA 1',
            'status' => 'aktif',
            'jenis_kelamin' => 'P',
            'has_voted_osis' => false,
        ]);
        Calon::factory()->create([
            'nama_ketua' => 'Ketua Aman',
            'nama_wakil' => 'Wakil Aman',
            'jenis_kelamin' => 'P',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('admin.osis.voting'));

        $response->assertStatus(200);
        $response->assertSee('Ketua Aman');
    }
}
