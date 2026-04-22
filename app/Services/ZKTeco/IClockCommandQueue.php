<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceCommand;
use App\Models\AttendanceDevice;
use Illuminate\Support\Facades\DB;

class IClockCommandQueue
{
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
}
