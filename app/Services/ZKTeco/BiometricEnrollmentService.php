<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk enroll biometric (fingerprint/face/RFID) dari web
 * Menggunakan IClockCommandQueue untuk queue command ke device
 *
 * Jalur komunikasi SATU-SATUNYA dengan device adalah HTTP iClock
 * (device poll /iclock/getrequest → ambil command dari tabel attendance_commands).
 * Socket legacy protokol ZKTeco lama (port 4370) sudah dipensiunkan.
 */
class BiometricEnrollmentService
{
    private IClockCommandQueue $commandQueue;

    public function __construct(?IClockCommandQueue $commandQueue = null)
    {
        $this->commandQueue = $commandQueue ?? new IClockCommandQueue();
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
     *
     * Hanya menghapus template fingerprint user, BUKAN user-nya.
     * Format command konsisten dengan method enroll di file ini
     * (DATA UPDATE USERINFO ... EnrollFP=1).
     *
     * @param string $pin         PIN user di device
     * @param int    $fingerIndex Index jari (0-9), atau -1 untuk hapus semua fingerprint user
     */
    public function deleteFingerprint(string $pin, int $fingerIndex = -1): array
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

            // Format perintah ADMS/iClock protocol (konsisten dengan method enroll):
            // - FingerIdx tertentu → hapus 1 template fingerprint (DelFP=1)
            // - FingerIdx = -1      → hapus semua fingerprint user (ClearAllFingerPrint=1)
            $command = $fingerIndex === -1
                ? "DATA UPDATE USERINFO PIN={$pin} ClearAllFingerPrint=1"
                : "DATA UPDATE USERINFO PIN={$pin} FingerIdx={$fingerIndex} DelFP=1";

            $queuedCount = 0;
            foreach ($devices as $device) {
                DB::table('attendance_commands')->insert([
                    'attendance_device_id' => $device->id,
                    'kind' => 'delete_fingerprint',
                    'device_pin' => $pin,
                    'command' => $command,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queuedCount++;
            }

            Log::info("Fingerprint deletion queued for PIN {$pin} on {$queuedCount} device(s)", [
                'finger_index' => $fingerIndex,
                'command' => $command,
            ]);

            return [
                'success' => true,
                'message' => "Fingerprint deletion untuk PIN {$pin} di-queue ke {$queuedCount} device",
                'pin' => $pin,
                'finger_index' => $fingerIndex,
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
     * Health-check device berbasis iClock (TANPA socket / port 4370).
     *
     * Device ZKTeco iClock melakukan polling HTTP berkala ke /iclock/cdata,
     * yang memperbarui last_seen_at pada record device. Status "online"
     * diturunkan dari aktivitas iClock tersebut — bukan dari probe TCP legacy,
     * sehingga tetap berfungsi di jaringan yang memblokir port 4370.
     *
     * @param AttendanceDevice $device        Device yang dicek
     * @param int              $thresholdMinutes Ambang waktu (menit) sejak
     *                                          last_seen_at untuk dianggap "online"
     * @return array{
     *     success: bool,
     *     status: 'online'|'offline',
     *     message: string,
     *     method: 'iclock',
     *     last_seen_at: string|null,
     *     minutes_since_last_seen: float|null,
     *     threshold_minutes: int
     * }
     */
    public static function testConnection(AttendanceDevice $device, int $thresholdMinutes = 5): array
    {
        $lastSeen = $device->last_seen_at;

        if ($lastSeen === null) {
            Log::info("iClock health check for {$device->serial_number}: never connected");

            return [
                'success' => false,
                'status' => 'offline',
                'message' => "Device {$device->name} belum pernah terhubung via iClock (tidak ada aktivitas)",
                'method' => 'iclock',
                'last_seen_at' => null,
                'minutes_since_last_seen' => null,
                'threshold_minutes' => $thresholdMinutes,
            ];
        }

        // Carbon 3: diffInMinutes bersifat signed — hitung dari lastSeen menuju now
        // agar positif untuk last_seen_at di masa lampau.
        $minutesSince = round(max(0, (float) $lastSeen->diffInMinutes(now())), 1);
        $isOnline = $minutesSince <= $thresholdMinutes;

        Log::info("iClock health check for {$device->serial_number}: " . ($isOnline ? 'online' : 'offline'), [
            'minutes_since_last_seen' => $minutesSince,
            'threshold_minutes' => $thresholdMinutes,
        ]);

        return [
            'success' => $isOnline,
            'status' => $isOnline ? 'online' : 'offline',
            'message' => $isOnline
                ? "Device {$device->name} online — terakhir terlihat via iClock {$minutesSince} menit lalu"
                : "Device {$device->name} offline/tidak aktif — terakhir terlihat via iClock {$minutesSince} menit lalu (ambang {$thresholdMinutes} menit)",
            'method' => 'iclock',
            'last_seen_at' => $lastSeen->toIso8601String(),
            'minutes_since_last_seen' => $minutesSince,
            'threshold_minutes' => $thresholdMinutes,
        ];
    }
}
