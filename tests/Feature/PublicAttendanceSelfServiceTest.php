<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicAttendanceController;
use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Regression test untuk hardening endpoint publik /absensi/saya:
 * - session-binding (data hanya dilayani dari sesi, bukan dari PIN di request)
 * - rate limit per-IP + per-PIN (named limiter `attendance-pin`)
 * - lockout sementara setelah N percobaan PIN gagal
 * - tanpa kebocoran PIN/data di response
 * - route logout untuk memutus sesi
 */
class PublicAttendanceSelfServiceTest extends TestCase
{
    use RefreshDatabase;

    /** PIN valid yang dipakai test (dibuat unik agar tidak muncul di HTML lain). */
    private const VALID_PIN = '918273645';

    /** PIN salah yang dipakai test. */
    private const WRONG_PIN = '123123123';

    private function createIdentity(?string $pin = self::VALID_PIN): AttendanceIdentity
    {
        $user = User::factory()->create(['name' => 'Budi Absensi Uji']);

        return AttendanceIdentity::create([
            'kind' => 'user',
            'user_id' => $user->id,
            'device_pin' => $pin,
            'is_active' => true,
        ]);
    }

    private function createAttendanceFor(AttendanceIdentity $identity): Attendance
    {
        return Attendance::create([
            'attendance_identity_id' => $identity->id,
            'date' => now()->subDay()->toDateString(),
            'first_in_at' => now()->subDay()->setTime(7, 0),
            'last_out_at' => now()->subDay()->setTime(15, 0),
            'status' => 'present',
        ]);
    }

    public function test_valid_pin_binds_identity_to_session_and_redirects(): void
    {
        Cache::flush();
        $identity = $this->createIdentity();

        $response = $this->post('/absensi/saya', ['pin' => self::VALID_PIN]);

        // Setelah PIN tervalidasi: redirect ke GET, identitas terikat di sesi.
        $response->assertRedirect(route('public.attendance.index'));
        $response->assertSessionHas(PublicAttendanceController::SESSION_KEY, $identity->id);
    }

    public function test_get_without_session_shows_pin_form_without_any_data(): void
    {
        $identity = $this->createIdentity();
        $this->createAttendanceFor($identity);

        $response = $this->get('/absensi/saya');

        $response->assertOk();

        // Hanya form PIN — tidak ada nama, tidak ada data absensi, tidak ada PIN.
        $response->assertSee('name="pin"', false);
        $response->assertDontSee($identity->user->name);
        $this->assertStringNotContainsString(self::VALID_PIN, $response->getContent());
    }

    public function test_get_with_session_shows_attendance_data_without_plaintext_pin(): void
    {
        $identity = $this->createIdentity();
        $attendance = $this->createAttendanceFor($identity);

        $response = $this->withSession([PublicAttendanceController::SESSION_KEY => $identity->id])
            ->get('/absensi/saya');

        $response->assertOk();

        // Data pemilik sesi tampil (nama boleh tampil — miliknya sendiri).
        $response->assertSee($identity->user->name);
        $response->assertSee('absensi-table', false);

        // PIN tidak boleh muncul dalam bentuk apa pun di HTML.
        $this->assertStringNotContainsString(self::VALID_PIN, $response->getContent());
        $response->assertDontSee('PIN:');

        // Form PIN tidak tampil saat sesi aktif.
        $response->assertDontSee('name="pin"', false);

        // Pastikan baris absensi benar-benar dirender dari data sesi.
        $this->assertNotNull($attendance->first_in_at);
    }

    public function test_wrong_pin_shows_generic_error_and_increases_fail_counter(): void
    {
        Cache::flush();
        $this->createIdentity(); // identitas valid ada, tapi PIN yang dikirim salah

        $response = $this->post('/absensi/saya', ['pin' => self::WRONG_PIN]);

        $response->assertOk();

        // Pesan generik — tidak membocorkan apakah PIN pernah terdaftar.
        $response->assertSee('PIN tidak ditemukan.');
        $response->assertSessionMissing(PublicAttendanceController::SESSION_KEY);

        // Fail count naik untuk PIN tersebut.
        $this->assertSame(1, (int) Cache::get(PublicAttendanceController::pinFailCacheKey(self::WRONG_PIN)));

        // Percobaan kedua menaikkan counter lagi.
        $this->post('/absensi/saya', ['pin' => self::WRONG_PIN])->assertOk();
        $this->assertSame(2, (int) Cache::get(PublicAttendanceController::pinFailCacheKey(self::WRONG_PIN)));
    }

    public function test_failed_attempts_trigger_lockout_after_max_failures(): void
    {
        Cache::flush();

        // Simulasikan 9 kegagalan sebelumnya (counter persist di cache).
        Cache::put(
            PublicAttendanceController::pinFailCacheKey(self::WRONG_PIN),
            PublicAttendanceController::MAX_FAILED_ATTEMPTS - 1,
            now()->addMinutes(10)
        );

        // Percobaan ke-10 mencapai threshold → blokir sementara.
        $response = $this->post('/absensi/saya', ['pin' => self::WRONG_PIN]);
        $response->assertOk();
        $response->assertSee('Terlalu banyak percobaan');
        $this->assertNotNull(Cache::get(PublicAttendanceController::lockoutCacheKey(self::WRONG_PIN)));

        // Request berikutnya dengan PIN yang sama tetap ditolak saat lockout aktif.
        $blocked = $this->post('/absensi/saya', ['pin' => self::WRONG_PIN]);
        $blocked->assertOk();
        $blocked->assertSee('Terlalu banyak percobaan');
        $blocked->assertSessionMissing(PublicAttendanceController::SESSION_KEY);
    }

    public function test_post_route_uses_named_throttle_attendance_pin(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($r) => $r->uri() === 'absensi/saya' && in_array('POST', $r->methods()));

        $this->assertNotNull($route, 'Route POST /absensi/saya tidak ditemukan.');
        $this->assertContains('throttle:attendance-pin', $route->gatherMiddleware());
    }

    public function test_per_ip_rate_limit_returns_429_after_repeated_attempts(): void
    {
        Cache::flush();

        // Limiter: 5 request/menit per IP → request ke-6 diblokir throttle.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/absensi/saya', ['pin' => self::WRONG_PIN])->assertOk();
        }

        $this->post('/absensi/saya', ['pin' => self::WRONG_PIN])->assertStatus(429);
    }

    public function test_logout_clears_session_and_returns_to_pin_form(): void
    {
        $identity = $this->createIdentity();

        $response = $this->withSession([PublicAttendanceController::SESSION_KEY => $identity->id])
            ->post('/absensi/saya/logout');

        $response->assertRedirect(route('public.attendance.index'));
        $response->assertSessionMissing(PublicAttendanceController::SESSION_KEY);

        // GET berikutnya kembali menampilkan form PIN.
        $this->get('/absensi/saya')->assertOk()->assertSee('name="pin"', false);
    }
}
