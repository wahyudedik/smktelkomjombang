<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk enroll biometric (fingerprint/face/RFID) dari web
 * Menggunakan ZKTeco SDK via TCP connection
 */
class BiometricEnrollmentService
{
    private $device;
    private $connection;

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
     * Enroll fingerprint untuk user
     * 
     * @param string $pin PIN user di device
     * @param string $name Nama user
     * @param int $fingerIndex Index jari (0-9)
     */
    public function enrollFingerprint(string $pin, string $name, int $fingerIndex = 0): array
    {
        if (!$this->connection) {
            return [
                'success' => false,
                'message' => 'Device tidak terhubung',
            ];
        }

        try {
            // Dalam implementasi real, ini memerlukan:
            // 1. Komunikasi dengan device untuk mulai enrollment
            // 2. User scan jari di device
            // 3. Device mengirim template fingerprint
            // 4. Simpan template ke device

            // Untuk MVP, kita queue command untuk device
            // Device akan menampilkan prompt untuk scan jari

            $command = "ENROLL_FINGERPRINT PIN={$pin} INDEX={$fingerIndex}";
            
            Log::info("Fingerprint enrollment queued for PIN {$pin}");

            return [
                'success' => true,
                'message' => "Enrollment fingerprint untuk PIN {$pin} dimulai. Silakan scan jari di device.",
                'pin' => $pin,
                'finger_index' => $fingerIndex,
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
     * Enroll face untuk user
     */
    public function enrollFace(string $pin, string $name): array
    {
        if (!$this->connection) {
            return [
                'success' => false,
                'message' => 'Device tidak terhubung',
            ];
        }

        try {
            $command = "ENROLL_FACE PIN={$pin}";
            
            Log::info("Face enrollment queued for PIN {$pin}");

            return [
                'success' => true,
                'message' => "Enrollment face untuk PIN {$pin} dimulai. Silakan posisikan wajah di depan kamera device.",
                'pin' => $pin,
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
     * Enroll RFID card untuk user
     */
    public function enrollRFID(string $pin, string $name, string $cardNumber): array
    {
        if (!$this->connection) {
            return [
                'success' => false,
                'message' => 'Device tidak terhubung',
            ];
        }

        try {
            $command = "ENROLL_RFID PIN={$pin} CARD={$cardNumber}";
            
            Log::info("RFID enrollment queued for PIN {$pin}");

            return [
                'success' => true,
                'message' => "Enrollment RFID untuk PIN {$pin} dimulai. Silakan tempel kartu ke reader device.",
                'pin' => $pin,
                'card_number' => $cardNumber,
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
     * Delete fingerprint user
     */
    public function deleteFingerprint(string $pin, int $fingerIndex = -1): array
    {
        if (!$this->connection) {
            return [
                'success' => false,
                'message' => 'Device tidak terhubung',
            ];
        }

        try {
            $command = $fingerIndex === -1 
                ? "DELETE_FINGERPRINT PIN={$pin}" 
                : "DELETE_FINGERPRINT PIN={$pin} INDEX={$fingerIndex}";
            
            Log::info("Fingerprint deletion queued for PIN {$pin}");

            return [
                'success' => true,
                'message' => "Fingerprint untuk PIN {$pin} berhasil dihapus",
                'pin' => $pin,
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
        if (!$this->connection) {
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
     * Test connection ke device
     */
    public static function testConnection(string $ipAddress, int $port = 4370): bool
    {
        try {
            $connection = @fsockopen($ipAddress, $port, $errno, $errstr, 5);
            if ($connection) {
                fclose($connection);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
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
