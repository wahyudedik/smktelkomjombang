<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceExcuse;
use App\Models\AttendanceIdentity;
use App\Models\User;
use App\Notifications\AttendanceNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Command untuk mengirim notifikasi absensi
 *
 * Dua mode:
 * 1. --summary: Kirim rekap harian ke admin (jam 16:00)
 * 2. --late: Kirim notifikasi keterlambatan ke admin saat ada yang terlambat
 * 3. --excuse: Kirim notifikasi izin/sakit perlu persetujuan
 *
 * Usage:
 *   php artisan attendance:notify --summary
 *   php artisan attendance:notify --late
 *   php artisan attendance:notify --excuse
 */
class AttendanceNotifyCommand extends Command
{
    protected $signature = 'attendance:notify
                            {--summary : Kirim rekap harian ke admin}
                            {--late : Kirim notifikasi keterlambatan}
                            {--excuse : Kirim notifikasi izin/sakit pending}';

    protected $description = 'Kirim notifikasi absensi (rekap harian, keterlambatan, izin pending)';

    public function handle(): int
    {
        if (!config('attendance.notify.enabled', true)) {
            $this->info('Notifikasi absensi dinonaktifkan di config.');
            return Command::SUCCESS;
        }

        $date = Carbon::today();
        $adminUsers = $this->getAdminUsers();

        if ($adminUsers->isEmpty()) {
            $this->warn('Tidak ada admin yang ditemukan untuk mengirim notifikasi.');
            return Command::SUCCESS;
        }

        $sent = 0;

        if ($this->option('summary')) {
            $sent += $this->sendDailySummary($adminUsers, $date);
        }

        if ($this->option('late')) {
            $sent += $this->sendLateNotifications($adminUsers, $date);
        }

        if ($this->option('excuse')) {
            $sent += $this->sendExcuseNotifications($adminUsers);
        }

        // Jika tidak ada opsi spesifik, kirim semua
        if (!$this->option('summary') && !$this->option('late') && !$this->option('excuse')) {
            $sent += $this->sendDailySummary($adminUsers, $date);
            $sent += $this->sendLateNotifications($adminUsers, $date);
            $sent += $this->sendExcuseNotifications($adminUsers);
        }

        $this->info("✅ Total {$sent} notifikasi terkirim.");
        return Command::SUCCESS;
    }

    /**
     * Kirim rekap harian ke admin
     */
    private function sendDailySummary($adminUsers, Carbon $date): int
    {
        $this->info('📊 Mengirim rekap harian...');

        $attendances = Attendance::whereDate('date', $date)->get();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $alphaCount = $attendances->where('status', 'alpha')->count();
        $lateCount = $attendances->filter(function ($a) use ($date) {
            return $a->first_in_at && $a->first_in_at->format('H:i') > config('attendance.late_threshold', '07:30');
        })->count();

        $totalIdentities = AttendanceIdentity::where('is_active', true)->count();

        $data = [
            'type'    => AttendanceNotification::TYPE_DAILY_SUMMARY,
            'date'    => $date->format('d F Y'),
            'hadir'   => $presentCount,
            'alpha'   => $alphaCount,
            'late'    => $lateCount,
            'excused' => $excusedCount,
            'total'   => $totalIdentities,
        ];

        $sent = 0;
        foreach ($adminUsers as $admin) {
            $admin->notify(new AttendanceNotification($data));
            $sent++;
        }

        $this->info("   ✅ Rekap harian terkirim ke {$sent} admin.");
        return $sent;
    }

    /**
     * Kirim notifikasi keterlambatan
     */
    private function sendLateNotifications($adminUsers, Carbon $date): int
    {
        $this->info('⏰ Mengirim notifikasi keterlambatan...');

        $threshold = config('attendance.late_threshold', '07:30');
        $lateRecords = Attendance::with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereDate('date', $date)
            ->whereNotNull('first_in_at')
            ->get()
            ->filter(function ($attendance) use ($threshold) {
                return $attendance->first_in_at->format('H:i') > $threshold;
            });

        $sent = 0;
        foreach ($lateRecords as $record) {
            $nama = $record->identity->user?->name
                ?? $record->identity->guru?->nama_lengkap
                ?? $record->identity->siswa?->nama_lengkap
                ?? '-';

            $data = [
                'type'      => AttendanceNotification::TYPE_LATE,
                'nama'      => $nama,
                'jam_masuk' => $record->first_in_at->format('H:i:s'),
                'threshold' => $threshold,
                'date'      => $date->format('d F Y'),
            ];

            foreach ($adminUsers as $admin) {
                $admin->notify(new AttendanceNotification($data));
                $sent++;
            }
        }

        $this->info("   ✅ {$sent} notifikasi keterlambatan terkirim.");
        return $sent;
    }

    /**
     * Kirim notifikasi izin/sakit yang perlu persetujuan
     */
    private function sendExcuseNotifications($adminUsers): int
    {
        $this->info('📋 Mengirim notifikasi izin/sakit pending...');

        $pendingExcuses = AttendanceExcuse::with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $sent = 0;
        foreach ($pendingExcuses as $excuse) {
            $nama = $excuse->nama;

            $data = [
                'type'       => AttendanceNotification::TYPE_EXCUSE_PENDING,
                'nama'       => $nama,
                'type_label' => $excuse->type_label,
                'date'       => $excuse->date->format('d F Y'),
                'reason'     => $excuse->reason,
                'excuse_id'  => $excuse->id,
            ];

            foreach ($adminUsers as $admin) {
                $admin->notify(new AttendanceNotification($data));
                $sent++;
            }
        }

        $this->info("   ✅ {$sent} notifikasi izin/sakit terkirim.");
        return $sent;
    }

    /**
     * Ambil admin users yang memenuhi target notifikasi
     */
    private function getAdminUsers()
    {
        $targets = config('attendance.notify.targets', ['admin']);

        return User::whereHas('roles', function ($query) use ($targets) {
            $query->whereIn('name', $targets);
        })->get();
    }
}
