<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use App\Models\AttendanceIdentity;
use App\Models\User;
use App\Observers\AttendanceIdentityObserver;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register User Observer (currently empty, but kept for future use)
        User::observe(UserObserver::class);
        AttendanceIdentity::observe(AttendanceIdentityObserver::class);

        // ========================================
        // Custom Rate Limiters
        // ========================================
        // General API rate limit: 60 requests/minute by IP
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        // Webhook rate limit: 120 requests/minute by IP (generous for external services)
        RateLimiter::for('webhook', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        // Device API rate limit: 120 requests/minute by IP (ZKTeco device communication)
        RateLimiter::for('device-api', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        // Voting rate limit: 5 requests/minute by authenticated user (anti-fraud)
        RateLimiter::for('voting', fn (Request $request) => Limit::perMinute(5)->by(
            $request->user()?->id ?: $request->ip()
        ));

        // Bulk operation rate limit: 5 requests/minute by authenticated user
        RateLimiter::for('bulk', fn (Request $request) => Limit::perMinute(5)->by(
            $request->user()?->id ?: $request->ip()
        ));

        // Email verification rate limit: 3 requests/minute by IP (prevent email spam)
        RateLimiter::for('email-verify', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));

        // Public E-OSIS voting rate limit: 10 requests/minute by IP
        // (anti brute-force identifier NIS/NISN/NIP pada halaman publik tanpa login)
        RateLimiter::for('osis-public', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));

        // Login rate limit: 120 requests/minute by IP (shared IP lab sekolah) + 10/minute by email (anti-brute-force)
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(120)->by('login-ip|'.$request->ip()),
                Limit::perMinute(10)->by('login-email|'.Str::lower((string) $request->input('email'))),
            ];
        });

        // Attendance self-service PIN rate limit (anti brute-force /absensi/saya):
        // - 5 request/menit per IP — membatasi serangan dari satu klien
        // - 10 request/menit per PIN yang dicoba — membatasi brute-force satu PIN
        // Ditambah lockout counter per PIN di PublicAttendanceController
        // (lihat PublicAttendanceController::pinFailCacheKey() — 10 kegagalan → blokir 10 menit).
        // Key PIN di-hash agar PIN tidak tersimpan plaintext di cache store.
        RateLimiter::for('attendance-pin', function (Request $request) {
            $pinKey = hash('sha256', (string) $request->input('pin', ''));

            return [
                Limit::perMinute(5)->by('attendance-pin|'.$request->ip()),
                Limit::perMinute(10)->by('attendance-pin-attempt|'.$pinKey),
            ];
        });
    }
}
