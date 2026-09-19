<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\AttendanceLog;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceSync extends Command
{
    protected $signature = 'attendance:sync {--since= : Proses log sejak tanggal tertentu (YYYY-MM-DD)} {--limit=5000 : Batas log yang diproses per run}';

    protected $description = 'Proses log absensi menjadi rekap harian';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        if ($limit <= 0) {
            $limit = 5000;
        }

        $since = $this->option('since');
        $sinceDate = null;
        if (is_string($since) && $since !== '') {
            try {
                $sinceDate = Carbon::createFromFormat('Y-m-d', $since)->startOfDay();
            } catch (\Throwable) {
                $this->error('Format --since harus YYYY-MM-DD');
                return 1;
            }
        }

        $query = AttendanceLog::query()
            ->whereNull('processed_at')
            ->orderBy('id');

        if ($sinceDate) {
            $query->where('log_time', '>=', $sinceDate);
        }

        $logs = $query->limit($limit)->get();
        if ($logs->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($logs) {
            $pins = $logs->pluck('device_pin')->unique()->values()->all();

            $identityByPin = AttendanceIdentity::query()
                ->where('is_active', true)
                ->whereIn('device_pin', $pins)
                ->get()
                ->keyBy('device_pin');

            $deviceIds = [];

            foreach ($logs as $log) {
                $identity = $identityByPin->get($log->device_pin);
                if (!$identity) {
                    $log->forceFill(['processed_at' => now()])->save();
                    continue;
                }

                $date = $log->log_time->copy()->startOfDay();

                $attendance = Attendance::firstOrCreate(
                    [
                        'attendance_identity_id' => $identity->id,
                        'date' => $date,
                    ],
                    [
                        'status' => 'present',
                    ]
                );

                $firstIn = $attendance->first_in_at;
                $lastOut = $attendance->last_out_at;

                if (!$firstIn || $log->log_time->lt($firstIn)) {
                    $firstIn = $log->log_time;
                }

                if (!$lastOut || $log->log_time->gt($lastOut)) {
                    $lastOut = $log->log_time;
                }

                // Determine status based on late threshold
                $lateThreshold = config('attendance.late_threshold', '07:30');
                $thresholdTime = $date->copy()->setTimeFromTimeString($lateThreshold);
                $status = ($firstIn && $firstIn->gt($thresholdTime)) ? 'late' : 'present';

                // Overtime calculation
                $overtimeMinutes = 0;
                if (config('attendance.overtime_enabled', false) && $lastOut) {
                    $overtimeStart = config('attendance.overtime_start', '16:00');
                    $overtimeStartTime = $date->copy()->setTimeFromTimeString($overtimeStart);

                    if ($lastOut->gt($overtimeStartTime)) {
                        $overtimeMinutes = (int) $lastOut->diffInMinutes($overtimeStartTime);
                        Log::info("Overtime detected", [
                            'attendance_id' => $attendance->id,
                            'last_out' => $lastOut->toDateTimeString(),
                            'overtime_start' => $overtimeStartTime->toDateTimeString(),
                            'overtime_minutes' => $overtimeMinutes,
                        ]);
                    }
                }

                $attendance->forceFill([
                    'first_in_at' => $firstIn,
                    'last_out_at' => $lastOut,
                    'status' => $status,
                ])->save();

                // Store overtime info in notes if we have overtime
                if ($overtimeMinutes > 0 && method_exists($attendance, 'notes')) {
                    $attendance->forceFill([
                        'notes' => "Overtime: {$overtimeMinutes} menit",
                    ])->save();
                }

                $log->forceFill(['processed_at' => now()])->save();

                $deviceIds[$log->attendance_device_id] = true;
            }

            if (count($deviceIds) > 0) {
                AttendanceDevice::query()
                    ->whereIn('id', array_keys($deviceIds))
                    ->update(['last_processed_at' => now()]);
            }
        });

        return 0;
    }
}
