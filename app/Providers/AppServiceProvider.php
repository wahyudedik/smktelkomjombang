<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
    }
}
