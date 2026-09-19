<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk enroll biometric (fingerprint/face/RFID) dari web
 * Menggunakan IClockCommandQueue untuk queue command ke device
 */
class BiometricEnrollmentService
{
    private ?AttendanceDevice $device = null;
    private $connection;
    private IClockCommandQueue $commandQueue;

    public function __construct(?IClockCommandQueue $commandQueue = null)
    {
        $this->commandQueue = $commandQueue ?? new IClockCommandQueue();
    }

    /**
     * Connect ke device ZKTeco
     */
    public function connect(AttendanceDevice $device): bool
    {
        try {
            $this->device = $device;

            // Buka socket connection ke device
            $this->connection = @fsockopen(
                $device->ip_address,
                $device->port ?? 4370,
                $errno,
                $errstr,
                5
            );

            if (!$this->connection) {
                Log::error("Failed to connect to device {$device->serial_number}: {$errstr}");
                return false;
            }

            Log::info("Connected to device {$device->serial_number}");
            return true;
        } catch (\Exception $e) {
            Log::error("Connection error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Disconnect dari device
     */
    public function disconnect(): bool
    {
        if ($this->connection) {
            fclose($this->connection);
            $this->connection = null;
            return true;
        }
        return false;
    }

    /**
     * Get semua user dari device
     */
    public function getUsers(): array
    {
        if (!$this->connection) {
            return [];
        }

        try {
            // Command untuk get user list
            $command = $this->buildCommand('GET_USER_LIST');
            fwrite($this->connection, $command);

            $response = '';
            while (!feof($this->connection)) {
                $response .= fgets($this->connection, 128);
            }

            return $this->parseUserList($response);
        } catch (\Exception $e) {
            Log::error("Get users error: {$e->getMessage()}");
            return [];
        }
    }

    /**
     * Enroll fingerprint untuk user via command queue
     *
     * Command di-queue ke database. Device akan pull command saat poll /iclock/getrequest.
     *
     * @param string $pin PIN user di device
     * @param string $name Nama user
     * @param int $fingerIndex Index jari (0-9)
     */
    public function enrollFingerprint(string $pin, string $name, int $fingerIndex = 0): array
    {
        try {
            $devices = AttendanceDevice::query()
                ->where('is_active', true)
                ->get();

            if ($devices->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada device aktif yang ditemukan',
                ];
            }

            $queuedCount = 0;
            foreach ($devices as $device) {
                $command = "DATA UPDATE USERINFO PIN={$pin} Name={$name} FingerIdx={$fingerIndex} EnrollFP=1";

                DB::table('attendance_commands')->insert([
                    'attendance_device_id' => $device->id,
                    'kind' => 'enroll_fingerprint',
                    'device_pin' => $pin,
                    'command' => $command,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queuedCount++;
            }

            Log::info("Fingerprint enrollment queued for PIN {$pin} on {$queuedCount} device(s)");

            return [
                'success' => true,
                'message' => "Enrollment fingerprint untuk PIN {$pin} di-queue ke {$queuedCount} device. Silakan scan jari di device.",
                'pin' => $pin,
                'finger_index' => $fingerIndex,
                'queued_devices' => $queuedCount,
            ];
        } catch (\Exception $e) {
            Log::error("Fingerprint enrollment error: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => "Error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Enroll face untuk user via command queue
     *
     * @param string $pin PIN user di device
     * @param string $name Nama user
     */
    public function enrollFace(string $pin, string $name): array
    {
        try {
            $devices = AttendanceDevice::query()
                ->where('is_active', true)
                ->get();

            if ($devices->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada device aktif yang ditemukan',
                ];
            }

            $queuedCount = 0;
            foreach ($devices as $device) {
                $command = "DATA UPDATE USERINFO PIN={$pin} Name={$name} EnrollFace=1";

                DB::table('attendance_commands')->insert([
                    'attendance_device_id' => $device->id,
                    'kind' => 'enroll_face',
                    'device_pin' => $pin,
                    'command' => $command,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queuedCount++;
            }

            Log::info("Face enrollment queued for PIN {$pin} on {$queuedCount} device(s)");

            return [
                'success' => true,
                'message' => "Enrollment face untuk PIN {$pin} di-queue ke {$queuedCount} device. Silakan posisikan wajah di depan kamera device.",
                'pin' => $pin,
                'queued_devices' => $queuedCount,
            ];
        } catch (\Exception $e) {
            Log::error("Face enrollment error: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => "Error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Enroll RFID card untuk user via command queue
     *
     * @param string $pin PIN user di device
     * @param string $name Nama user
     * @param string $cardNumber Nomor kartu RFID
     */
    public function enrollRFID(string $pin, string $name, string $cardNumber): array
    {
        try {
            $devices = AttendanceDevice::query()
                ->where('is_active', true)
                ->get();

            if ($devices->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada device aktif yang ditemukan',
                ];
            }

            $queuedCount = 0;
            foreach ($devices as $device) {
                $command = "DATA UPDATE USERINFO PIN={$pin} Name={$name} Card={$cardNumber}";

                DB::table('attendance_commands')->insert([
                    'attendance_device_id' => $device->id,
                    'kind' => 'enroll_rfid',
                    'device_pin' => $pin,
                    'command' => $command,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queuedCount++;
            }

            Log::info("RFID enrollment queued for PIN {$pin} on {$queuedCount} device(s)");

            return [
                'success' => true,
                'message' => "Enrollment RFID untuk PIN {$pin} di-queue ke {$queuedCount} device.",
                'pin' => $pin,
                'card_number' => $cardNumber,
                'queued_devices' => $queuedCount,
            ];
        } catch (\Exception $e) {
            Log::error("RFID enrollment error: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => "Error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Delete fingerprint user via command queue
     */
    public function deleteFingerprint(string $pin, int $fingerIndex = -1): array
    {
        try {
            $command = $fingerIndex === -1
                ? "DATA DELETE USER PIN={$pin} ENROLLFP=1"
                : "DATA DELETE USER PIN={$pin} ENROLLFP=1 FingerIdx={$fingerIndex}";

            // Use the existing IClockCommandQueue for delete
            $queuedCount = $this->commandQueue->enqueueDeleteUserByPin($pin);

            Log::info("Fingerprint deletion queued for PIN {$pin}");

            return [
                'success' => true,
                'message' => "Fingerprint untuk PIN {$pin} berhasil di-queue untuk penghapusan",
                'pin' => $pin,
                'queued_devices' => $queuedCount,
            ];
        } catch (\Exception $e) {
            Log::error("Fingerprint deletion error: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => "Error: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Get device info
     */
    public function getDeviceInfo(): array
    {
        if (!$this->device) {
            return [];
        }

        try {
            return [
                'serial_number' => $this->device->serial_number,
                'ip_address' => $this->device->ip_address,
                'port' => $this->device->port,
                'name' => $this->device->name,
                'is_active' => $this->device->is_active,
                'last_seen_at' => $this->device->last_seen_at,
            ];
        } catch (\Exception $e) {
            Log::error("Get device info error: {$e->getMessage()}");
            return [];
        }
    }

    /**
     * Test connection ke device via TCP socket
     *
     * Mencoba membuka koneksi TCP ke device pada port yang dikonfigurasi.
     * Jika berhasil, berarti device dapat diakses dari jaringan.
     *
     * Mendukung 2 signature:
     * - testConnection(AttendanceDevice $device): array  (recommended)
     * - testConnection(string $ipAddress, int $port): bool  (backward compat)
     *
     * @param AttendanceDevice|string $device Device atau IP address
     * @param int $port Port (hanya digunakan jika $device adalah string)
     * @return array|bool
     */
    public static function testConnection(AttendanceDevice|string $device, int $port = 4370): array|bool
    {
        // Backward compatible: string IP + port → return bool
        if (is_string($device)) {
            $ipAddress = $device;
        } else {
            $ipAddress = $device->ip_address;
            $port = $device->port ?? 4370;
        }

        $fp = @fsockopen($ipAddress, $port, $errno, $errstr, 5);
        if ($fp) {
            fclose($fp);
            Log::info("Connection test successful to {$ipAddress}:{$port}");

            // Return based on input type
            if (is_string(func_get_arg(0))) {
                return true;
            }
            return [
                'success' => true,
                'message' => 'Connection successful',
            ];
        }

        Log::warning("Connection test failed to {$ipAddress}:{$port}: {$errstr} ({$errno})");

        if (is_string(func_get_arg(0))) {
            return false;
        }
        return [
            'success' => false,
            'message' => "Connection failed: {$errstr} ({$errno})",
        ];
    }

    /**
     * Build command untuk device
     */
    private function buildCommand(string $command): string
    {
        // Format command sesuai ZKTeco protocol
        return $command . "\r\n";
    }

    /**
     * Parse user list dari response
     */
    private function parseUserList(string $response): array
    {
        $users = [];
        $lines = explode("\n", $response);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Parse format: PIN,NAME,ROLE
            $parts = explode(',', $line);
            if (count($parts) >= 2) {
                $users[] = [
                    'pin' => $parts[0],
                    'name' => $parts[1],
                    'role' => $parts[2] ?? 'user',
                ];
            }
        }

        return $users;
    }
}
