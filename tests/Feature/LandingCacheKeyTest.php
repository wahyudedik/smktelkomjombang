<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regression test untuk pemisahan cache data generik antar-tema (perf/landing).
 *
 * Sebelum: LandingController::buildData() memakai key per-tema
 * ("landing_{theme}_siswa_count", dst.) padahal semua query-nya
 * theme-independent → data identik di-cache N kali untuk N tema, dan
 * invalidation harus loop semua tema.
 *
 * Sesudah: key generik ("landing_siswa_count", dst.) — satu entri cache
 * dibagi semua tema, invalidation cukup satu Cache::forget() per key.
 */
class LandingCacheKeyTest extends TestCase
{
    use RefreshDatabase;

    /** Key generik yang diproduksi LandingController::buildData() sekarang. */
    private const GENERIC_KEYS = [
        'landing_siswa_count',
        'landing_kelulusan_percentage',
        'landing_testimonials',
        'landing_blogs_ordered_ids',
        'landing_partners',
        'landing_events',
    ];

    /** Suffix dataset yang dulu diproduksi dengan prefix tema (key legacy). */
    private const LEGACY_SUFFIXES = [
        'siswa_count',
        'kelulusan_percentage',
        'testimonials',
        'blogs_ordered_ids',
        'partners',
        'events',
        'blogs', // key legacy yang sudah tidak diproduksi sama sekali
    ];

    public function test_landing_populates_generic_cache_keys_without_theme_suffix(): void
    {
        config(['app.default_theme' => 'telkom']);

        $response = $this->get('/');
        $response->assertOk();

        // Semua dataset di-cache dengan key generik (tanpa suffix tema).
        foreach (self::GENERIC_KEYS as $key) {
            $this->assertTrue(Cache::has($key), "Key generik [{$key}] harus terisi setelah request landing.");
        }

        // Key lama per-tema tidak boleh diproduksi lagi (telkom & maudu).
        foreach (['telkom', 'maudu'] as $theme) {
            foreach (self::LEGACY_SUFFIXES as $suffix) {
                $legacyKey = "landing_{$theme}_{$suffix}";
                $this->assertFalse(Cache::has($legacyKey), "Key legacy [{$legacyKey}] tidak boleh diproduksi lagi.");
            }
        }
    }

    public function test_cache_entries_are_shared_between_themes(): void
    {
        Cache::flush();

        // Request tema telkom — cache generik terisi.
        $this->get('/telkom')->assertOk();
        $this->assertTrue(Cache::has('landing_siswa_count'));

        // Beri sentinel pada key generik — jika tema maudu membaca key yang
        // SAMA (bukan duplikat per-tema), nilai sentinel harus muncul di view.
        Cache::put('landing_siswa_count', 999);

        $response = $this->get('/maudu');
        $response->assertOk();
        $response->assertViewIs('maudu');
        $response->assertViewHas('siswaCount', 999);

        // Tidak ada duplikasi key per-tema yang tercipta dari kedua request.
        foreach (['telkom', 'maudu'] as $theme) {
            $this->assertFalse(
                Cache::has("landing_{$theme}_siswa_count"),
                "Tidak boleh ada duplikasi key [landing_{$theme}_siswa_count]."
            );
        }
    }

    public function test_event_crud_invalidates_generic_events_cache(): void
    {
        Storage::fake('public');

        // Isi cache lewat request landing publik.
        $this->get('/')->assertOk();
        $this->assertTrue(Cache::has('landing_events'));

        $admin = $this->createAdminWithPermissions(['events.create']);

        $response = $this->actingAs($admin)->post(route('admin.events.store'), [
            'title' => 'Seminar Cache Test',
            'description' => 'Memicu invalidation cache landing.',
            'date' => now()->addDays(7)->format('Y-m-d'),
            'category' => 'seminar',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertFalse(Cache::has('landing_events'), 'Store event harus menghapus key generik landing_events.');
    }

    public function test_berita_crud_invalidates_generic_blogs_cache(): void
    {
        // Isi cache lewat request landing publik.
        $this->get('/')->assertOk();
        $this->assertTrue(Cache::has('landing_blogs_ordered_ids'));

        $admin = $this->createAdminWithPermissions(['berita.create']);

        $response = $this->actingAs($admin)->post(route('admin.berita.store'), [
            'title' => 'Berita Cache Test',
            'content' => '<p>Konten berita untuk memicu invalidation cache.</p>',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertFalse(
            Cache::has('landing_blogs_ordered_ids'),
            'Store berita harus menghapus key generik landing_blogs_ordered_ids.'
        );
    }

    public function test_event_crud_does_not_touch_legacy_per_theme_keys(): void
    {
        Storage::fake('public');

        $this->get('/')->assertOk();

        // Simulasikan sisa key legacy di store (mis. dari deploy sebelumnya).
        Cache::put('landing_telkom_events', collect());
        Cache::put('landing_maudu_events', collect());

        $admin = $this->createAdminWithPermissions(['events.create']);

        $this->actingAs($admin)->post(route('admin.events.store'), [
            'title' => 'Event Legacy Key Test',
            'description' => 'Invalidation cukup menyentuh key generik.',
            'date' => now()->addDays(3)->format('Y-m-d'),
            'category' => 'seminar',
            'status' => 'active',
        ])->assertRedirect();

        // Key generik terhapus; key legacy lama dibiarkan kadaluarsa sendiri (TTL).
        $this->assertFalse(Cache::has('landing_events'));
    }

    /**
     * Buat user admin (role admin) dengan permission yang diminta.
     *
     * Permission dibuat eksplisit via getOrCreatePermission() dulu —
     * givePermissionTo(string) melempar PermissionDoesNotExist jika
     * permission belum ada di DB (Spatie versi ini tidak auto-create).
     */
    private function createAdminWithPermissions(array $permissions): User
    {
        $admin = User::factory()->create([
            'email' => 'admin-cache-test@test.com',
            'password' => Hash::make('password'),
        ]);

        $adminRole = $this->getOrCreateRole('admin');
        $admin->syncRoles([$adminRole]);

        foreach ($permissions as $permission) {
            $admin->givePermissionTo($this->getOrCreatePermission($permission));
        }

        return $admin;
    }
}
