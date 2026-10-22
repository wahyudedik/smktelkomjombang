<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test untuk fix theme_override pada route deprecated
 * GET /telkom dan GET /maudu (LandingController::telkom()/::maudu()).
 *
 * Sebelum fix: current_theme() jatuh ke DEFAULT_THEME, sehingga route /telkom
 * bisa me-render konten konfigurasi maudu (dan sebaliknya). Sesudah fix:
 * config('app.theme_override') di-set per request sehingga theme_config(),
 * theme_info(), theme_image(), theme_view(), dan cache key buildData() resolve
 * ke tema yang benar — terlepas dari nilai DEFAULT_THEME.
 */
class LandingThemeOverrideTest extends TestCase
{
    use RefreshDatabase;

    /** Marker unik dari config/themes/telkom.php. */
    private const TELKOM_NAME = 'SMK Telekomunikasi Darul Ulum';

    /** Marker unik dari config/themes/maudu.php. */
    private const MAUDU_NAME = 'MA Unggulan Darul Ulum Rejoso';

    public function test_telkom_route_sets_theme_override_and_renders_telkom_content(): void
    {
        // Sengaja set default ke maudu — konten telkom hanya muncul jika
        // theme_override di-set oleh LandingController::telkom().
        config(['app.default_theme' => 'maudu']);

        $response = $this->get('/telkom');

        $response->assertOk();
        $this->assertSame('telkom', config('app.theme_override'));
        $response->assertViewIs('telkom');
        $response->assertSee(self::TELKOM_NAME);
        $response->assertDontSee(self::MAUDU_NAME);
    }

    public function test_maudu_route_sets_theme_override_and_renders_maudu_content(): void
    {
        // Sengaja set default ke telkom — konten maudu hanya muncul jika
        // theme_override di-set oleh LandingController::maudu().
        config(['app.default_theme' => 'telkom']);

        $response = $this->get('/maudu');

        $response->assertOk();
        $this->assertSame('maudu', config('app.theme_override'));
        $response->assertViewIs('maudu');
        $response->assertSee(self::MAUDU_NAME);
        $response->assertDontSee(self::TELKOM_NAME);
    }

    public function test_default_route_uses_default_theme_maudu_without_override(): void
    {
        config(['app.default_theme' => 'maudu']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('maudu');
        $response->assertSee(self::MAUDU_NAME);

        // Route default tidak menyetel theme_override — murni DEFAULT_THEME.
        $this->assertNull(config('app.theme_override'));
    }

    public function test_default_route_uses_default_theme_telkom_without_override(): void
    {
        config(['app.default_theme' => 'telkom']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('telkom');
        $response->assertSee(self::TELKOM_NAME);

        // Route default tidak menyetel theme_override — murni DEFAULT_THEME.
        $this->assertNull(config('app.theme_override'));
    }
}
