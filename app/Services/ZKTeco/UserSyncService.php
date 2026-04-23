<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceCommand;
use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk sinkronisasi user dari web ke device ZKTeco
 * Mendukung add, update, dan delete user via ADMS command queue
 */
class UserSyncService
{
    /**
     * Tambah user baru ke device
     * Command: DATA APPEND USERINFO PIN=<PIN> NAME=<NAME>
     */
    public function enqueueAddUser(string $devicePin, string $userName): int
    {
        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (count($devices) === 0) {
            return 0;
        }

        $command = "DATA APPEND USERINFO PIN={$devicePin} NAME={$userName}";

        $rows = array_map(function (int $deviceId) use ($devicePin, $command) {
            return [
                'attendance_device_id' => $deviceId,
                'kind' => 'add_user',
                'device_pin' => $devicePin,
                'command' => $command,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $devices);

        return DB::table('attendance_commands')->insert($rows) ? count($rows) : 0;
    }

    /**
     * Update user di device
     * Command: DATA UPDATE USERINFO PIN=<PIN> NAME=<NAME>
     */
    public function enqueueUpdateUser(string $devicePin, string $userName): int
    {
        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (count($devices) === 0) {
            return 0;
        }

        $command = "DATA UPDATE USERINFO PIN={$devicePin} NAME={$userName}";

        $rows = array_map(function (int $deviceId) use ($devicePin, $command) {
            return [
                'attendance_device_id' => $deviceId,
                'kind' => 'update_user',
                'device_pin' => $devicePin,
                'command' => $command,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $devices);

        return DB::table('attendance_commands')->insert($rows) ? count($rows) : 0;
    }

    /**
     * Hapus user dari device
     * Command: DATA DELETE USERINFO PIN=<PIN>
     */
    public function enqueueDeleteUser(string $devicePin): int
    {
        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        if (count($devices) === 0) {
            return 0;
        }

        $command = "DATA DELETE USERINFO PIN={$devicePin}";

        $rows = array_map(function (int $deviceId) use ($devicePin, $command) {
            return [
                'attendance_device_id' => $deviceId,
                'kind' => 'delete_user',
                'device_pin' => $devicePin,
                'command' => $command,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $devices);

        return DB::table('attendance_commands')->insert($rows) ? count($rows) : 0;
    }

    /**
     * Sync semua user dari database ke device
     * Gunakan untuk initial setup atau recovery
     */
    public function syncAllUsers(): int
    {
        $identities = AttendanceIdentity::query()
            ->where('is_active', true)
            ->with(['user', 'guru', 'siswa'])
            ->get();

        $count = 0;

        foreach ($identities as $identity) {
            $name = $identity->user?->name 
                ?? $identity->guru?->nama_lengkap 
                ?? $identity->siswa?->nama_lengkap 
                ?? "User {$identity->device_pin}";

            $this->enqueueAddUser($identity->device_pin, $name);
            $count++;
        }

        return $count;
    }

    /**
     * Get status sync untuk user tertentu
     */
    public function getSyncStatus(string $devicePin): array
    {
        $commands = AttendanceCommand::query()
            ->where('device_pin', $devicePin)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return [
            'pin' => $devicePin,
            'total_commands' => $commands->count(),
            'pending' => $commands->where('status', 'pending')->count(),
            'sent' => $commands->where('status', 'sent')->count(),
            'done' => $commands->where('status', 'done')->count(),
            'failed' => $commands->where('status', 'failed')->count(),
            'commands' => $commands->map(fn($c) => [
                'id' => $c->id,
                'kind' => $c->kind,
                'command' => $c->command,
                'status' => $c->status,
                'sent_at' => $c->sent_at?->format('Y-m-d H:i:s'),
                'executed_at' => $c->executed_at?->format('Y-m-d H:i:s'),
                'result_code' => $c->result_code,
            ])->all(),
        ];
    }
}
