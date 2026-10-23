<?php

use App\Models\AttendanceSetting;
use Carbon\CarbonInterface;
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

    if (!function_exists('attendance_status_map')) {
        /**
         * Single source of truth untuk label & warna status absensi.
         *
         * Key = nilai status canonical di tabel `attendances` (English):
         * - 'present'  → ditulis oleh AttendanceSync (scan masuk tepat waktu)
         * - 'late'     → ditulis oleh AttendanceSync (scan masuk melewati late_threshold)
         * - 'excused'  → ditulis oleh MarkAlphaCommand (izin/sakit/cuti yang disetujui)
         * - 'alpha'    → ditulis oleh MarkAlphaCommand (tidak hadir tanpa izin)
         * - 'absent'   → alias legacy (tidak pernah ditulis; dipakai sebagian stats lama)
         *
         * JANGAN menambah nilai status baru tanpa sinkron dengan penulis di
         * AttendanceSync / MarkAlphaCommand — nilai di DB adalah English.
         */
        function attendance_status_map(?string $status): array
        {
            $map = [
                'present' => [
                    'label'       => 'Hadir',
                    'color'       => '#2e7d32',
                    'bg'          => '#e8f5e9',
                    'badge_class' => 'bg-green-100 text-green-800',
                ],
                'late' => [
                    'label'       => 'Terlambat',
                    'color'       => '#e65100',
                    'bg'          => '#fff3e0',
                    'badge_class' => 'bg-orange-100 text-orange-800',
                ],
                'excused' => [
                    'label'       => 'Izin/Sakit',
                    'color'       => '#1565c0',
                    'bg'          => '#e3f2fd',
                    'badge_class' => 'bg-blue-100 text-blue-800',
                ],
                'alpha' => [
                    'label'       => 'Tidak Hadir',
                    'color'       => '#c62828',
                    'bg'          => '#ffebee',
                    'badge_class' => 'bg-red-100 text-red-800',
                ],
                // Alias legacy — tidak pernah ditulis ke DB, tapi tetap dipetakan
                // agar data luar/ekspor lama tidak jatuh ke fallback abu-abu.
                'absent' => [
                    'label'       => 'Tidak Hadir',
                    'color'       => '#c62828',
                    'bg'          => '#ffebee',
                    'badge_class' => 'bg-red-100 text-red-800',
                ],
            ];

            return $map[$status] ?? [
                'label'       => (string) $status,
                'color'       => '#666666',
                'bg'          => '#f5f5f5',
                'badge_class' => 'bg-slate-100 text-slate-600',
            ];
        }
    }

    if (!function_exists('attendance_status_label')) {
        /**
         * Label Indonesia untuk nilai status absensi (English di DB).
         *
         * Contoh: 'present' → 'Hadir', 'late' → 'Terlambat',
         *         'excused' → 'Izin/Sakit', 'alpha' → 'Tidak Hadir'.
         */
        function attendance_status_label(?string $status): string
        {
            return attendance_status_map($status)['label'];
        }
    }

    if (!function_exists('attendance_status_color')) {
        /**
         * Warna teks (hex) untuk badge status absensi.
         */
        function attendance_status_color(?string $status): string
        {
            return attendance_status_map($status)['color'];
        }
    }

    if (!function_exists('attendance_status_bg')) {
        /**
         * Warna latar (hex) untuk badge status absensi.
         */
        function attendance_status_bg(?string $status): string
        {
            return attendance_status_map($status)['bg'];
        }
    }

    if (!function_exists('attendance_status_badge_class')) {
        /**
         * Class Tailwind untuk badge status absensi (view admin).
         */
        function attendance_status_badge_class(?string $status): string
        {
            return attendance_status_map($status)['badge_class'];
        }
    }
}

if (!function_exists('attendance_overtime_minutes')) {
    /**
     * Hitung menit lembur sebuah hari absensi — SATU-SATUNYA sumber rumus lembur.
     *
     * Dipakai oleh AttendanceSync (sinkronisasi harian) dan
     * AttendanceBackfillOvertime (backfill data historis) agar hasilnya
     * identik, deterministik, dan konsisten. Rumusnya persis seperti
     * implementasi asli di AttendanceSync:
     *
     *  - bila overtime nonaktif / tanpa tanggal / tanpa last_out_at → 0
     *  - lembur = last_out_at − overtime_start (pada tanggal yang sama)
     *  - Carbon 3 diffInMinutes() SIGNED → dinormalisasi abs() + round()
     *    agar selalu menghasilkan menit POSITIF
     *
     * @param  CarbonInterface|null  $date     Tanggal absensi (hanya bagian tanggalnya yang dipakai)
     * @param  CarbonInterface|null  $lastOut  Waktu pulang terakhir (last_out_at)
     * @return int Menit lembur (0 bila tidak ada lembur / overtime nonaktif)
     */
    function attendance_overtime_minutes(?CarbonInterface $date, ?CarbonInterface $lastOut): int
    {
        if (!$date || !$lastOut || !attendance_config('overtime_enabled', false)) {
            return 0;
        }

        $overtimeStartTime = $date->copy()->setTimeFromTimeString(
            (string) attendance_config('overtime_start', '16:00')
        );

        if (!$lastOut->gt($overtimeStartTime)) {
            return 0;
        }

        // Carbon 3: diffInMinutes() signed by default → abs() agar selalu
        // menit POSITIF (kasus ini lastOut > overtimeStart, hasil mentah =
        // negatif). round() untuk aman dari float pecahan.
        return (int) round(abs($lastOut->diffInMinutes($overtimeStartTime)));
    }
}
