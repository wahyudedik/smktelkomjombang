<?php

namespace Database\Seeders;

use App\Models\AttendanceSetting;
use Illuminate\Database\Seeder;

class AttendanceSettingSeeder extends Seeder
{
    /**
     * Default settings mapped from config/attendance.php
     *
     * Each entry: key => ['value' => ..., 'type' => ...]
     *
     * SEED = MATERIALISASI DEFAULT, BUKAN OVERRIDE DIAM-DIAM:
     * - `run()` memakai firstOrCreate → hanya mengisi key yang BELUM ADA di
     *   tabel `attendance_settings`; value yang sudah ada (hasil set admin
     *   lewat UI Attendance Settings) TIDAK pernah ditimpa saat deploy.
     * - Nilai hardcoded di bawah dipakai hanya sebagai fallback materialisasi.
     * - Khusus group Overtime, value di-resolve dari `config('attendance.*')`
     *   (yang membaca env `ATTENDANCE_OVERTIME_*`) — sehingga seed SELALU
     *   konsisten dengan default config, bukan menimpa env produksi.
     *   (Ketidakcocokan ini pernah menyebabkan `ATTENDANCE_OVERTIME_ENABLED=true`
     *   di production ter-override oleh seed `false` → lembur nonaktif.)
     */
    private array $defaults = [
        // ── ZKTeco iClock ──────────────────────────────
        'iclock_secret'            => ['value' => '',                                    'type' => 'string'],
        'require_user_identity'    => ['value' => 'true',                                'type' => 'boolean'],
        'require_user_verified'    => ['value' => 'false',                               'type' => 'boolean'],

        // ── Sync Settings ──────────────────────────────
        'sync_enabled'             => ['value' => 'true',                                'type' => 'boolean'],
        'sync_interval'            => ['value' => '5',                                   'type' => 'integer'],
        'sync_batch_size'          => ['value' => '100',                                 'type' => 'integer'],

        // ── Work Hours ─────────────────────────────────
        'work_start'               => ['value' => '07:00',                               'type' => 'time'],
        'work_end'                 => ['value' => '15:00',                               'type' => 'time'],
        'late_threshold'           => ['value' => '07:30',                               'type' => 'time'],
        'late_grace_minutes'       => ['value' => '0',                                   'type' => 'integer'],
        'alpha_mark_time'          => ['value' => '23:00',                               'type' => 'time'],

        // ── Overtime ───────────────────────────────────
        'overtime_enabled'         => ['value' => 'false',                               'type' => 'boolean'],
        'overtime_start'           => ['value' => '16:00',                               'type' => 'time'],
        'overtime_rate_per_hour'   => ['value' => '0',                                   'type' => 'integer'],

        // ── Export ─────────────────────────────────────
        'export_format'            => ['value' => 'xlsx',                                'type' => 'string'],
        'export_logo'              => ['value' => '',                                    'type' => 'string'],
        'export_institution'       => ['value' => 'SMK Telekomunikasi Darul Ulum',       'type' => 'string'],

        // ── Notification ───────────────────────────────
        'notify_enabled'           => ['value' => 'true',                                'type' => 'boolean'],
        'notify_daily_summary_time' => ['value' => '16:00',                              'type' => 'time'],
        'notify_alpha_mark_time'   => ['value' => '23:00',                               'type' => 'time'],
        'notify_late'              => ['value' => 'true',                                'type' => 'boolean'],
        'notify_alpha'             => ['value' => 'true',                                'type' => 'boolean'],
        'notify_targets'           => ['value' => 'admin',                               'type' => 'string'],

        // ── Report ─────────────────────────────────────
        'work_days_per_week'       => ['value' => '6',                                   'type' => 'integer'],
        'work_days_per_month'      => ['value' => '25',                                  'type' => 'integer'],
        'report_show_photo'        => ['value' => 'true',                                'type' => 'boolean'],
        'report_show_charts'       => ['value' => 'true',                                'type' => 'boolean'],
        'report_date_format'       => ['value' => 'd/m/Y',                               'type' => 'string'],
        'report_time_format'       => ['value' => 'H:i',                                 'type' => 'string'],

        // ── Biometric ──────────────────────────────────
        'biometric_mode'           => ['value' => 'fingerprint',                         'type' => 'string'],
        'multi_template'           => ['value' => 'true',                                'type' => 'boolean'],

        // ── Cleanup ────────────────────────────────────
        'cleanup_enabled'          => ['value' => 'true',                                'type' => 'boolean'],
        'cleanup_retention_days'   => ['value' => '365',                                 'type' => 'integer'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = array_replace($this->defaults, $this->overtimeDefaults());

        foreach ($defaults as $key => $config) {
            // firstOrCreate: materialisasi default untuk key yang belum ada;
            // TIDAK menimpa value yang sudah di-set admin (updateOrCreate
            // sebelumnya mereset override admin setiap kali seeder dijalankan).
            AttendanceSetting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $config['value'],
                    'type'  => $config['type'],
                ]
            );
        }

        $this->command->info("✅ " . count($defaults) . " attendance settings berhasil di-seed.");
    }

    /**
     * Resolve default group Overtime dari config/attendance.php (env-aware).
     *
     * Mengapa khusus overtime: config default `overtime_enabled` = false,
     * tapi env `ATTENDANCE_OVERTIME_ENABLED` di production = true. Dengan
     * resolve dari config, seed mengikuti nilai efektif (env jika diset,
     * default config jika tidak) sehingga tidak pernah "menimpa diam-diam".
     *
     * @return array<string, array{value: string, type: string}>
     */
    private function overtimeDefaults(): array
    {
        return [
            'overtime_enabled'       => [
                'value' => ((bool) config('attendance.overtime_enabled', false)) ? 'true' : 'false',
                'type'  => 'boolean',
            ],
            'overtime_start'         => [
                'value' => (string) config('attendance.overtime_start', '16:00'),
                'type'  => 'time',
            ],
            'overtime_rate_per_hour' => [
                'value' => (string) (int) config('attendance.overtime_rate_per_hour', 0),
                'type'  => 'integer',
            ],
        ];
    }
}
