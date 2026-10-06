<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LoginRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_post_route_uses_throttle_login_middleware(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($r) => $r->uri() === 'login' && in_array('POST', $r->methods()));

        $this->assertNotNull($route, 'POST /login route not found');
        $this->assertContains('throttle:login', $route->gatherMiddleware());
    }

    public function test_different_emails_from_same_ip_are_not_blocked_by_each_other(): void
    {
        Cache::flush();

        for ($i = 1; $i <= 12; $i++) {
            $email = "siswa{$i}@sekolah.test";
            User::factory()->create(['email' => $email]);

            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password',
            ]);

            // Harus mencapai validasi kredensial (redirect), bukan 429
            $response->assertStatus(302);
            $response->assertRedirect(route('admin.dashboard', absolute: false));

            // Logout agar request berikutnya tetap sebagai guest
            auth()->logout();
            $this->flushSession();
        }
    }

    public function test_same_email_is_rate_limited_after_10_attempts(): void
    {
        Cache::flush();

        $email = 'siswa-limited@sekolah.test';

        // 10 percobaan pertama dengan password salah → redirect back (bukan 429)
        for ($i = 1; $i <= 10; $i++) {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302);
        }

        // Percobaan ke-11 → harus 429
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(429);
    }
}
