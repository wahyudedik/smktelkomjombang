<?php

namespace App\Services\ZKTeco;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IClockIngestService
{
    public function __construct(
        private readonly IClockPayloadParser $parser,
    ) {}

    public function ingest(string $serialNumber, string $payload, ?string $ipAddress = null): int
    {
        $events = $this->parser->parse($payload);

        Log::info('IClockIngestService: parsed events', [
            'serial_number' => $serialNumber,
            'payload_size' => strlen($payload),
            'events_count' => count($events),
            'ip_address' => $ipAddress,
        ]);

        if (count($events) === 0) {
            $this->touchDevice($serialNumber, $ipAddress);
            return 0;
        }

        if (Config::get('attendance.require_user_identity')) {
            $pins = array_values(array_unique(array_map(fn($e) => $e['device_pin'], $events)));

            $allowedPinsQuery = AttendanceIdentity::query()
                ->where('kind', 'user')
                ->where('is_active', true)
                ->whereIn('device_pin', $pins);

            if (Config::get('attendance.require_user_verified')) {
                $allowedPinsQuery->whereHas('user', function ($q) {
                    $q->where('is_verified_by_admin', true);
                });
            }

            $allowedPins = $allowedPinsQuery->pluck('device_pin')->all();
            $allowed = array_flip($allowedPins);

            $beforeFilter = count($events);
            $events = array_values(array_filter($events, fn($e) => isset($allowed[$e['device_pin']])));

            Log::info('IClockIngestService: user identity filter', [
                'serial_number' => $serialNumber,
                'before_filter' => $beforeFilter,
                'after_filter' => count($events),
                'allowed_pins' => $allowedPins,
            ]);
        }

        if (count($events) === 0) {
            Log::info('IClockIngestService: no events after filtering', [
                'serial_number' => $serialNumber,
                'ip_address' => $ipAddress,
            ]);
            $this->touchDevice($serialNumber, $ipAddress);
            return 0;
        }

        return DB::transaction(function () use ($serialNumber, $events, $ipAddress) {
            $device = AttendanceDevice::firstOrCreate(
                ['serial_number' => $serialNumber],
                [
                    'name' => $serialNumber,
                    'ip_address' => $ipAddress,
                    'port' => null,
                    'comm_key' => null,
                    'is_active' => true,
                ]
            );

            $device->forceFill([
                'last_seen_at' => now(),
                'ip_address' => $ipAddress ?: $device->ip_address,
            ])->save();

            $inserted = 0;

            foreach ($events as $event) {
                $created = AttendanceLog::firstOrCreate(
                    [
                        'attendance_device_id' => $device->id,
                        'device_pin' => $event['device_pin'],
                        'log_time' => $event['log_time'],
                    ],
                    [
                        'verify_mode' => $event['verify_mode'],
                        'in_out_mode' => $event['in_out_mode'],
                        'raw' => $event['raw'],
                    ]
                );

                if ($created->wasRecentlyCreated) {
                    $inserted++;
                }
            }

            Log::info('IClockIngestService: completed', [
                'serial_number' => $serialNumber,
                'total_events' => count($events),
                'inserted' => $inserted,
            ]);

            return $inserted;
        });
    }

    private function touchDevice(string $serialNumber, ?string $ipAddress): void
    {
        $device = AttendanceDevice::firstOrCreate(
            ['serial_number' => $serialNumber],
            [
                'name' => $serialNumber,
                'ip_address' => $ipAddress,
                'port' => null,
                'comm_key' => null,
                'is_active' => true,
            ]
        );

        $device->forceFill([
            'last_seen_at' => now(),
            'ip_address' => $ipAddress ?: $device->ip_address,
        ])->save();
    }
}
