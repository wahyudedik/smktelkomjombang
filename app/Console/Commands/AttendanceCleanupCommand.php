<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Console\Command;

class AttendanceCleanupCommand extends Command
{
    protected $signature = 'attendance:cleanup';

    protected $description = 'Hapus attendance_logs dan attendances yang melebihi retention config (cleanup_retention_days) serta raw log ZKTeco yang kadaluarsa';

    public function handle(): int
    {
        if (!attendance_config('cleanup_enabled', false)) {
            $this->info('Cleanup dinonaktifkan di config attendance.cleanup_enabled. Lewati.');

            return 0;
        }

        // Retention dari config attendance.cleanup_retention_days (default 365 hari).
        // 0 = jangan pernah hapus record.
        $retentionDays = (int) attendance_config('cleanup_retention_days', 365);

        $deletedLogs = 0;
        $deletedAttendances = 0;

        if ($retentionDays > 0) {
            $cutoff = now()->subDays($retentionDays);

            // Hapus attendance_logs yang processed_at lebih tua dari retention
            $deletedLogs = AttendanceLog::where('processed_at', '<', $cutoff)
                ->delete();

            // Hapus attendances yang date lebih tua dari retention
            $deletedAttendances = Attendance::where('date', '<', $cutoff)
                ->delete();
        }

        $this->info('Attendance cleanup selesai:');
        $this->info("  - retention days: {$retentionDays}");
        $this->info("  - attendance_logs dihapus: {$deletedLogs} record");
        $this->info("  - attendances dihapus: {$deletedAttendances} record");

        // Cleanup raw ZKTeco log files > 30 hari
        // (file disimpan sebagai .log oleh ZKTecoIClockController::saveRawLog,
        //  glob *.txt tetap dicakup untuk kompatibilitas dengan file lama)
        $rawDir = storage_path('app/zkteco-raw');
        if (is_dir($rawDir)) {
            $files = array_merge(
                glob($rawDir . '/*.log') ?: [],
                glob($rawDir . '/*.txt') ?: []
            );
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
