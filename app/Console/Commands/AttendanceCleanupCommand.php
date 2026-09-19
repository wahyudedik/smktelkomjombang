<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AttendanceCleanupCommand extends Command
{
    protected $signature = 'attendance:cleanup';

    protected $description = 'Hapus attendance_logs (>90 hari) dan attendances (>180 hari) yang sudah tidak aktif';

    public function handle(): int
    {
        if (!attendance_config('cleanup_enabled', false)) {
            $this->info('Cleanup dinonaktifkan di config attendance.cleanup_enabled. Lewati.');

            return 0;
        }

        $deletedLogs = 0;
        $deletedAttendances = 0;

        // Hapus attendance_logs yang processed_at > 90 hari
        $deletedLogs = AttendanceLog::where('processed_at', '<', now()->subDays(90))
            ->delete();

        // Hapus attendances yang date > 180 hari
        $deletedAttendances = Attendance::where('date', '<', now()->subDays(180))
            ->delete();

        $this->info('Attendance cleanup selesai:');
        $this->info("  - attendance_logs dihapus: {$deletedLogs} record (>90 hari)");
        $this->info("  - attendances dihapus: {$deletedAttendances} record (>180 hari)");

        // Cleanup raw ZKTeco log files > 30 hari
        $rawDir = storage_path('app/zkteco-raw');
        if (is_dir($rawDir)) {
            $files = glob($rawDir . '/*.txt');
            $deletedFiles = 0;
            foreach ($files as $file) {
                if (filemtime($file) < now()->subDays(30)->timestamp) {
                    unlink($file);
                    $deletedFiles++;
                }
            }
            $this->info("  - raw log files dihapus: {$deletedFiles} file (>30 hari)");
        } else {
            $this->info("  - raw log directory tidak ditemukan, skip cleanup");
        }

        return 0;
    }
}
