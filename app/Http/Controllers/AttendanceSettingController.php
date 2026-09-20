<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use App\Traits\AttendanceAuthorization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AttendanceSettingController extends Controller
{
    use AttendanceAuthorization;

    /**
     * Setting categories with their keys and metadata.
     */
    private array $categories = [
        'work_hours' => [
            'label'  => 'Jam Kerja & Keterlambatan',
            'icon'   => 'fas fa-clock',
            'fields' => [
                'work_start'          => ['label' => 'Jam Mulai Kerja',     'type' => 'time',     'desc' => 'Jam masuk standar'],
                'work_end'            => ['label' => 'Jam Selesai Kerja',   'type' => 'time',     'desc' => 'Jam pulang standar'],
                'late_threshold'      => ['label' => 'Batas Keterlambatan', 'type' => 'time',     'desc' => 'Jika masuk setelah jam ini = terlambat'],
                'late_grace_minutes'  => ['label' => 'Toleransi (menit)',   'type' => 'integer',  'desc' => 'Grace period keterlambatan dalam menit'],
                'alpha_mark_time'     => ['label' => 'Waktu Tandai Alpha',  'type' => 'time',     'desc' => 'Jam otomatis menandai alpha jika tidak ada log'],
            ],
        ],
        'sync' => [
            'label'  => 'Sync & Device',
            'icon'   => 'fas fa-sync-alt',
            'fields' => [
                'sync_enabled'          => ['label' => 'Aktifkan Sinkronisasi',   'type' => 'boolean', 'desc' => 'Aktifkan sync otomatis dari iClock device'],
                'sync_interval'         => ['label' => 'Interval Sync (menit)',   'type' => 'integer', 'desc' => 'Interval sinkronisasi untuk scheduled task'],
                'sync_batch_size'       => ['label' => 'Batch Size',              'type' => 'integer', 'desc' => 'Jumlah log diproses per batch'],
                'require_user_identity' => ['label' => 'Wajibkan User Identity',  'type' => 'boolean', 'desc' => 'USERID harus terdaftar saat push log'],
                'require_user_verified' => ['label' => 'Wajibkan User Verified',  'type' => 'boolean', 'desc' => 'User harus terverifikasi sebelum log diterima'],
                'iclock_secret'         => ['label' => 'iClock Secret Token',     'type' => 'password','desc' => 'Secret token autentikasi push log dari iClock'],
            ],
        ],
        'overtime' => [
            'label'  => 'Lembur',
            'icon'   => 'fas fa-hourglass-half',
            'fields' => [
                'overtime_enabled'       => ['label' => 'Aktifkan Lembur',     'type' => 'boolean', 'desc' => 'Aktifkan perhitungan lembur'],
                'overtime_start'         => ['label' => 'Jam Mulai Lembur',    'type' => 'time',    'desc' => 'Jam mulai perhitungan lembur'],
                'overtime_rate_per_hour' => ['label' => 'Rate Lembur (Rp/jam)','type' => 'integer', 'desc' => 'Tarif lembur per jam dalam Rupiah'],
            ],
        ],
        'export' => [
            'label'  => 'Export',
            'icon'   => 'fas fa-file-export',
            'fields' => [
                'export_format'      => ['label' => 'Format Export',       'type' => 'select',  'desc' => 'Format default export', 'options' => ['xlsx' => 'Excel (.xlsx)', 'csv' => 'CSV (.csv)']],
                'export_institution' => ['label' => 'Nama Instansi',       'type' => 'text',    'desc' => 'Nama instansi untuk header PDF'],
                'export_logo'        => ['label' => 'Path Logo Export',    'type' => 'text',    'desc' => 'Path logo untuk PDF export'],
            ],
        ],
        'notification' => [
            'label'  => 'Notifikasi',
            'icon'   => 'fas fa-bell',
            'fields' => [
                'notify_enabled'            => ['label' => 'Aktifkan Notifikasi',         'type' => 'boolean', 'desc' => 'Aktifkan notifikasi absensi'],
                'notify_daily_summary_time' => ['label' => 'Waktu Rekap Harian',          'type' => 'time',    'desc' => 'Waktu pengiriman rekap harian'],
                'notify_alpha_mark_time'    => ['label' => 'Waktu Tandai Alpha (Notif)',   'type' => 'time',    'desc' => 'Waktu penandaan alpha via notifikasi'],
                'notify_late'               => ['label' => 'Notifikasi Keterlambatan',     'type' => 'boolean', 'desc' => 'Kirim notifikasi saat ada keterlambatan'],
                'notify_alpha'              => ['label' => 'Notifikasi Alpha',             'type' => 'boolean', 'desc' => 'Kirim notifikasi saat ada alpha'],
                'notify_targets'            => ['label' => 'Target Notifikasi',            'type' => 'text',    'desc' => 'Role yang menerima notifikasi (koma-pisah)'],
            ],
        ],
        'report' => [
            'label'  => 'Report',
            'icon'   => 'fas fa-chart-bar',
            'fields' => [
                'work_days_per_week'  => ['label' => 'Hari Kerja/Minggu',    'type' => 'integer', 'desc' => 'Jumlah hari kerja per minggu'],
                'work_days_per_month' => ['label' => 'Hari Kerja/Bulan',     'type' => 'integer', 'desc' => 'Jumlah hari kerja per bulan (rata-rata)'],
                'report_show_photo'   => ['label' => 'Tampilkan Foto',       'type' => 'boolean', 'desc' => 'Tampilkan foto profil di report'],
                'report_show_charts'  => ['label' => 'Tampilkan Grafik',     'type' => 'boolean', 'desc' => 'Tampilkan grafik di report'],
                'report_date_format'  => ['label' => 'Format Tanggal',       'type' => 'text',    'desc' => 'Format tanggal di report (PHP format)'],
                'report_time_format'  => ['label' => 'Format Waktu',         'type' => 'text',    'desc' => 'Format waktu di report (PHP format)'],
            ],
        ],
        'biometric' => [
            'label'  => 'Biometric',
            'icon'   => 'fas fa-fingerprint',
            'fields' => [
                'biometric_mode' => ['label' => 'Mode Biometric',       'type' => 'select',  'desc' => 'Mode verifikasi biometric default', 'options' => [
                    'fingerprint' => 'Fingerprint',
                    'face'        => 'Face Recognition',
                    'rfid'        => 'RFID Card',
                    'password'    => 'Password',
                ]],
                'multi_template' => ['label' => 'Multi Template',      'type' => 'boolean', 'desc' => 'Izinkan multiple biometric template per user'],
            ],
        ],
        'cleanup' => [
            'label'  => 'Cleanup',
            'icon'   => 'fas fa-broom',
            'fields' => [
                'cleanup_enabled'         => ['label' => 'Aktifkan Auto-Cleanup',   'type' => 'boolean', 'desc' => 'Aktifkan penghapusan log lama otomatis'],
                'cleanup_retention_days'  => ['label' => 'Retensi Data (hari)',      'type' => 'integer', 'desc' => 'Simpan log selama X hari (0 = tidak pernah hapus)'],
            ],
        ],
    ];

    /**
     * Display attendance settings form.
     */
    public function index(): View
    {
        $this->requireAdminOrPermission('settings.manage');

        $settings = AttendanceSetting::pluck('value', 'key')->toArray();

        // Build settings with current values and config defaults
        $categories = $this->categories;
        foreach ($categories as $catKey => &$category) {
            foreach ($category['fields'] as $fieldKey => &$field) {
                $field['key'] = $fieldKey;

                // Get current value: DB → config default
                if (isset($settings[$fieldKey])) {
                    $field['value'] = $this->castSettingValue($settings[$fieldKey], $field['type']);
                } else {
                    $field['value'] = $this->getConfigDefault($fieldKey, $field['type']);
                }

                $field['in_db'] = isset($settings[$fieldKey]);
            }
        }

        return view('attendance.settings', compact('categories'));
    }

    /**
     * Update attendance settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $this->requireAdminOrPermission('settings.manage');

        $data = $request->input('settings', []);

        // Validate based on type
        $validated = [];
        foreach ($this->categories as $category) {
            foreach ($category['fields'] as $fieldKey => $field) {
                if (!array_key_exists($fieldKey, $data)) {
                    continue;
                }

                $value = $data[$fieldKey];

                // Validate by type
                $validated[$fieldKey] = match ($field['type']) {
                    'integer' => (string) (int) $value,
                    'boolean' => $value ? 'true' : 'false',
                    'time'    => (string) $value,
                    'select'  => (string) $value,
                    default   => (string) $value,
                };
            }
        }

        // Save to database
        AttendanceSetting::setMany($validated);

        // Clear all attendance config cache
        Cache::tags(['attendance_config'])->flush();

        return redirect()->route('admin.absensi.settings')
            ->with('success', 'Pengaturan absensi berhasil disimpan.');
    }

    /**
     * Reset specific settings to defaults (delete from DB → fallback to config).
     */
    public function reset(Request $request): RedirectResponse
    {
        $this->requireAdminOrPermission('settings.manage');

        $keys = $request->input('keys', []);

        if (empty($keys)) {
            // Reset all
            AttendanceSetting::query()->delete();
        } else {
            AttendanceSetting::deleteMany($keys);
        }

        // Clear all attendance config cache
        Cache::tags(['attendance_config'])->flush();

        $message = empty($keys)
            ? 'Semua pengaturan absensi di-reset ke default.'
            : count($keys) . ' pengaturan berhasil di-reset ke default.';

        return redirect()->route('admin.absensi.settings')
            ->with('success', $message);
    }

    /**
     * Export settings to JSON.
     */
    public function export(): JsonResponse
    {
        $this->requireAdminOrPermission('settings.manage');

        $settings = AttendanceSetting::getAll()->toArray();

        return response()->json([
            'version'  => '1.0',
            'exported' => now()->toIso8601String(),
            'settings' => $settings,
        ], 200, [
            'Content-Disposition' => 'attachment; filename="attendance-settings-' . now()->format('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Import settings from JSON.
     */
    public function import(Request $request): RedirectResponse
    {
        $this->requireAdminOrPermission('settings.manage');

        $request->validate([
            'settings_file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('settings_file');
        $json = json_decode(file_get_contents($file->getRealPath()), true);

        if (!isset($json['settings']) || !is_array($json['settings'])) {
            return redirect()->route('admin.absensi.settings')
                ->with('error', 'File JSON tidak valid. Pastikan memiliki key "settings".');
        }

        AttendanceSetting::setMany($json['settings']);

        // Clear all attendance config cache
        Cache::tags(['attendance_config'])->flush();

        $count = count($json['settings']);

        return redirect()->route('admin.absensi.settings')
            ->with('success', "{$count} pengaturan berhasil di-import.");
    }

    /**
     * Cast setting value based on type.
     */
    private function castSettingValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            default   => is_array($value) ? implode(', ', $value) : $value,
        };
    }

    /**
     * Get config default value for a key.
     */
    private function getConfigDefault(string $key, string $type): mixed
    {
        // Map flat keys to config/attendance.php nested structure
        $configMap = [
            'work_start'               => 'work_hours.start',
            'work_end'                 => 'work_hours.end',
            'late_threshold'           => 'late_threshold',
            'late_grace_minutes'       => 'late_grace_minutes',
            'alpha_mark_time'          => 'alpha_mark_time',
            'sync_enabled'             => 'sync_enabled',
            'sync_interval'            => 'sync_interval',
            'sync_batch_size'          => 'sync_batch_size',
            'require_user_identity'    => 'require_user_identity',
            'require_user_verified'    => 'require_user_verified',
            'iclock_secret'            => 'iclock_secret',
            'overtime_enabled'         => 'overtime_enabled',
            'overtime_start'           => 'overtime_start',
            'overtime_rate_per_hour'   => 'overtime_rate_per_hour',
            'export_format'            => 'export_format',
            'export_logo'              => 'export_logo',
            'export_institution'       => 'export_institution',
            'notify_enabled'           => 'notify.enabled',
            'notify_daily_summary_time' => 'notify.daily_summary_time',
            'notify_alpha_mark_time'   => 'notify.alpha_mark_time',
            'notify_late'              => 'notify.notify_late',
            'notify_alpha'             => 'notify.notify_alpha',
            'notify_targets'           => 'notify.targets',
            'work_days_per_week'       => 'report.work_days_per_week',
            'work_days_per_month'      => 'report.work_days_per_month',
            'report_show_photo'        => 'report.show_photo',
            'report_show_charts'       => 'report.show_charts',
            'report_date_format'       => 'report.date_format',
            'report_time_format'       => 'report.time_format',
            'biometric_mode'           => 'biometric_mode',
            'multi_template'           => 'multi_template',
            'cleanup_enabled'          => 'cleanup_enabled',
            'cleanup_retention_days'   => 'cleanup_retention_days',
        ];

        $configKey = $configMap[$key] ?? $key;
        $value = config("attendance.{$configKey}");

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            default   => $value,
        };
    }
}
