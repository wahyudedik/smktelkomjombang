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

// Mark alpha for users who didn't show up (daily at 23:00)
// This checks if each active identity has an attendance record for today.
// If not, it checks for approved excuses. If neither, marks as 'alpha'.
Schedule::command('attendance:mark-alpha')
    ->dailyAt('23:00')
    ->withoutOverlapping()
    ->runInBackground();

// Send attendance notifications (daily summary at 16:00)
Schedule::command('attendance:notify --summary')
    ->dailyAt(config('attendance.notify.daily_summary_time', '16:00'))
    ->withoutOverlapping()
    ->runInBackground();

// Send pending excuse notifications (every hour during work hours)
Schedule::command('attendance:notify --excuse')
    ->hourly()
    ->between('08:00', '17:00')
    ->withoutOverlapping()
    ->runInBackground();

// Clean up old async jobs and their files (daily at 03:00)
// Removes jobs older than 7 days and their associated export files
Schedule::call(function () {
    $oldJobs = \App\Models\AsyncJob::where('created_at', '<', now()->subDays(7))->get();
    foreach ($oldJobs as $job) {
        if (isset($job->result['path'])) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($job->result['path']);
        }
        $job->delete();
    }
})->name('cleanup-old-jobs')
    ->dailyAt('03:00')
    ->withoutOverlapping();
