<?php

namespace App\Helpers;

use App\Models\AttendanceSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('attendance_config')) {
    /**
     * Get attendance configuration value with priority: DB → env/config → default.
     *
     * @param  string  $key      Dot-notation key (e.g. 'late_threshold', 'notify.enabled')
     * @param  mixed  $default  Fallback value if not found
     * @return mixed
     */
    function attendance_config(string $key, mixed $default = null): mixed
    {
        // Flatten nested config keys for DB lookup
        // e.g. 'notify.enabled' → 'notify_enabled'
        $dbKey = str_replace('.', '_', $key);

        return Cache::remember(
            "attendance_config:{$dbKey}",
            300, // 5 minutes
            function () use ($dbKey, $key, $default) {
                // 1. Try DB first
                $setting = AttendanceSetting::where('key', $dbKey)->first();
                if ($setting) {
                    return $setting->castValue();
                }

                // 2. Fallback to config/attendance.php (which uses env())
                $configValue = config("attendance.{$key}", $default);

                return $configValue;
            }
        );
    }
}
