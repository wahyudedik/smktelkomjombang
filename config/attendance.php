<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ZKTeco iClock Integration
    |--------------------------------------------------------------------------
    */

    // Secret token untuk autentikasi push log dari iClock device
    'iclock_secret' => env('ATTENDANCE_ICLOCK_SECRET', ''),

    // Wajibkan user identity saat push log (USERID harus terdaftar)
    'require_user_identity' => env('ATTENDANCE_REQUIRE_USER_IDENTITY', true),

    // Wajibkan user terverifikasi sebelum log diterima
    'require_user_verified' => env('ATTENDANCE_REQUIRE_USER_VERIFIED', false),

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */

    // Aktifkan sinkronisasi otomatis dari iClock device
    'sync_enabled' => env('ATTENDANCE_SYNC_ENABLED', true),

    // Interval sinkronisasi dalam menit (untuk scheduled task)
    'sync_interval' => env('ATTENDANCE_SYNC_INTERVAL', 5),

    // Batch size untuk sinkronisasi massal
    'sync_batch_size' => env('ATTENDANCE_SYNC_BATCH_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Work Hours
    |--------------------------------------------------------------------------
    */

    // Jam kerja standar
    'work_hours' => [
        'start' => env('ATTENDANCE_WORK_START', '07:00'),
        'end'   => env('ATTENDANCE_WORK_END', '15:00'),
    ],

    // Batas waktu keterlambatan (jika lebih dari jam ini = terlambat)
    'late_threshold' => env('ATTENDANCE_LATE_THRESHOLD', '07:30'),

    // Toleransi keterlambatan dalam menit (grace period)
    'late_grace_minutes' => env('ATTENDANCE_LATE_GRACE_MINUTES', 0),

    // Batas waktu untuk penandaan alpha otomatis (jika tidak ada log sama sekali)
    'alpha_mark_time' => env('ATTENDANCE_ALPHA_MARK_TIME', '23:00'),

    /*
    |--------------------------------------------------------------------------
    | Overtime Settings
    |--------------------------------------------------------------------------
    */

    // Aktifkan perhitungan lembur
    'overtime_enabled' => env('ATTENDANCE_OVERTIME_ENABLED', false),

    // Jam mulai lembur (setelah jam kerja berakhir)
    'overtime_start' => env('ATTENDANCE_OVERTIME_START', '16:00'),

    // Rate lembur per jam (dalam Rupiah)
    'overtime_rate_per_hour' => env('ATTENDANCE_OVERTIME_RATE', 0),

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    */

    // Format default export
    'export_format' => env('ATTENDANCE_EXPORT_FORMAT', 'xlsx'),

    // Logo untuk PDF export
    'export_logo' => env('ATTENDANCE_EXPORT_LOGO', ''),

    // Nama instansi untuk PDF export header
    'export_institution' => env('ATTENDANCE_EXPORT_INSTITUTION', 'SMK Telekomunikasi Darul Ulum'),

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */

    'notify' => [
        // Aktifkan notifikasi absensi
        'enabled'           => env('ATTENDANCE_NOTIFY_ENABLED', true),

        // Waktu pengiriman rekap harian
        'daily_summary_time' => env('ATTENDANCE_NOTIFY_SUMMARY_TIME', '16:00'),

        // Waktu penandaan alpha otomatis
        'alpha_mark_time'    => env('ATTENDANCE_NOTIFY_ALPHA_TIME', '23:00'),

        // Kirim notifikasi ke admin saat ada keterlambatan
        'notify_late'        => env('ATTENDANCE_NOTIFY_LATE', true),

        // Kirim notifikasi ke admin saat ada alpha
        'notify_alpha'       => env('ATTENDANCE_NOTIFY_ALPHA', true),

        // Target notifikasi (admin, principal, atau both)
        'targets'            => explode(',', env('ATTENDANCE_NOTIFY_TARGETS', 'admin')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Settings
    |--------------------------------------------------------------------------
    */

    'report' => [
        // Jumlah hari kerja per minggu
        'work_days_per_week' => env('ATTENDANCE_WORK_DAYS_PER_WEEK', 6),

        // Jumlah hari kerja per bulan (rata-rata)
        'work_days_per_month' => env('ATTENDANCE_WORK_DAYS_PER_MONTH', 25),

        // Tampilkan foto profil di report
        'show_photo' => env('ATTENDANCE_REPORT_SHOW_PHOTO', true),

        // Tampilkan grafik di report
        'show_charts' => env('ATTENDANCE_REPORT_SHOW_CHARTS', true),

        // Format tanggal di report
        'date_format' => env('ATTENDANCE_REPORT_DATE_FORMAT', 'd/m/Y'),

        // Format waktu di report
        'time_format' => env('ATTENDANCE_REPORT_TIME_FORMAT', 'H:i'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Biometric Settings
    |--------------------------------------------------------------------------
    */

    // Mode verifikasi biometric
    // 'fingerprint' | 'face' | 'rfid' | 'password'
    'biometric_mode' => env('ATTENDANCE_BIOMETRIC_MODE', 'fingerprint'),

    // Izinkan multiple biometric template per user
    'multi_template' => env('ATTENDANCE_MULTI_TEMPLATE', true),

    /*
    |--------------------------------------------------------------------------
    | Cleanup Settings
    |--------------------------------------------------------------------------
    */

    // Aktifkan auto-cleanup log lama
    'cleanup_enabled' => env('ATTENDANCE_CLEANUP_ENABLED', false),

    // Simpan log selama X hari (0 = tidak pernah hapus)
    'cleanup_retention_days' => env('ATTENDANCE_CLEANUP_RETENTION_DAYS', 365),

];
