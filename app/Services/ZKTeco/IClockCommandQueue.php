<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceCommand;
use App\Models\AttendanceDevice;
use Illuminate\Support\Facades\DB;

class IClockCommandQueue
{
    /**
     * Jumlah maksimum retry untuk command yang gagal/stale.
     */
    private const MAX_RETRY_COUNT = 3;

    /**
     * Default timeout (menit) command berstatus 'sent' sebelum dianggap
     * stale dan dikembalikan ke 'pending' untuk diproses ulang oleh device.
     */
    private const DEFAULT_STALE_SENT_TIMEOUT_MINUTES = 60;

    public function enqueueDeleteUserByPin(string $devicePin): int
    {
        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (count($devices) === 0) {
            return 0;
        }

        $rows = array_map(function (int $deviceId) use ($devicePin) {
            return [
                'attendance_device_id' => $deviceId,
                'kind' => 'delete_user',
                'device_pin' => $devicePin,
                'command' => "DATA DELETE USERINFO PIN={$devicePin}",
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $devices);

        return DB::table('attendance_commands')->insert($rows) ? count($rows) : 0;
    }

    public function pullCommandsForDevice(AttendanceDevice $device, int $limit = 20): array
    {
        $commands = AttendanceCommand::query()
            ->where('attendance_device_id', $device->id)
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($commands->count() === 0) {
            return [];
        }

        $ids = $commands->pluck('id')->all();

        AttendanceCommand::query()
            ->whereIn('id', $ids)
            ->update([
                'status' => 'sent',
                'sent_at' => now(),
                'updated_at' => now(),
            ]);

        return $commands
            ->map(fn(AttendanceCommand $c) => "C:{$c->id}:{$c->command}")
            ->all();
    }

    public function recordResult(AttendanceDevice $device, int $commandId, ?string $resultCode, ?string $raw): bool
    {
        $command = AttendanceCommand::query()
            ->where('id', $commandId)
            ->where('attendance_device_id', $device->id)
            ->first();

        if (!$command) {
            return false;
        }

        $normalized = $resultCode !== null ? trim((string) $resultCode) : null;
        $success = $normalized === '0' || $normalized === 'OK';

        $command->forceFill([
            'status' => $success ? 'done' : 'failed',
            'executed_at' => now(),
            'result_code' => $normalized === '' ? null : $normalized,
            'result_raw' => $raw === '' ? null : $raw,
        ])->save();

        return true;
    }

    /**
     * Recover command yang stuck di queue:
     * - 'sent' yang updated_at lebih tua dari timeout (device tidak pernah
     *   melaporkan hasil via /iclock/devicecmd) → kembali ke 'pending'.
     * - 'failed' yang retry_count masih di bawah MAX_RETRY_COUNT → kembali ke 'pending'.
     * - 'sent' stale / 'failed' yang retry habis → ditandai 'failed' (timeout)
     *   agar tidak loop selamanya dan terlihat di admin.
     *
     * Dipanggil dari scheduler (routes/console.php) setiap 10 menit.
     *
     * @return array{stale_sent: int, stale_sent_exhausted: int, failed_retried: int}
     */
    public function recoverStaleCommands(int $timeoutMinutes = self::DEFAULT_STALE_SENT_TIMEOUT_MINUTES): array
    {
        if ($timeoutMinutes <= 0) {
            $timeoutMinutes = self::DEFAULT_STALE_SENT_TIMEOUT_MINUTES;
        }

        $staleCutoff = now()->subMinutes($timeoutMinutes);

        $staleSentQuery = AttendanceCommand::query()
            ->where('status', 'sent')
            ->where(function ($query) use ($staleCutoff) {
                $query->where('updated_at', '<', $staleCutoff)
                    ->orWhere(function ($q) use ($staleCutoff) {
                        $q->whereNull('updated_at')->where('created_at', '<', $staleCutoff);
                    });
            });

        // 1a. Stale 'sent' yang masih punya sisa retry → kembalikan ke pending.
        $staleSent = (clone $staleSentQuery)
            ->where('retry_count', '<', self::MAX_RETRY_COUNT)
            ->update([
                'status' => 'pending',
                'sent_at' => null,
                'retry_count' => DB::raw('retry_count + 1'),
                'updated_at' => now(),
            ]);

        // 1b. Stale 'sent' yang retry habis → tandai failed (timeout).
        $staleSentExhausted = $staleSentQuery
            ->where('retry_count', '>=', self::MAX_RETRY_COUNT)
            ->update([
                'status' => 'failed',
                'executed_at' => now(),
                'result_code' => 'TIMEOUT',
                'result_raw' => 'Command stale (tidak pernah dilaporkan hasilnya oleh device) dan sudah mencapai batas retry',
                'updated_at' => now(),
            ]);

        // 2. 'failed' yang masih punya sisa retry → kembalikan ke pending.
        $failedRetried = AttendanceCommand::query()
            ->where('status', 'failed')
            ->where('retry_count', '<', self::MAX_RETRY_COUNT)
            ->update([
                'status' => 'pending',
                'retry_count' => DB::raw('retry_count + 1'),
                'updated_at' => now(),
            ]);

        return [
            'stale_sent' => $staleSent,
            'stale_sent_exhausted' => $staleSentExhausted,
            'failed_retried' => $failedRetried,
        ];
    }
}
