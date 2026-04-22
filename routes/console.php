<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Instagram posts sync
// Sync posts based on user-defined frequency (default: every 5 minutes)
Schedule::command('instagram:sync')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Schedule Instagram token refresh
// Instagram long-lived tokens expire in 60 days, refresh them every 30 days
Schedule::command('instagram:refresh-token')
    ->monthlyOn(1, '02:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground();

// Schedule Sarpras notifications (daily at 08:00)
// Send notifications for damaged items and sarana that need updates
Schedule::command('sarpras:send-notifications --daily')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('attendance:sync')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
