<?php

namespace App\Providers;

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
    }
}
