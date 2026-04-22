<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Services\ZKTeco\IClockCommandQueue;
use App\Services\ZKTeco\IClockIngestService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ZKTecoIClockController extends BaseController
{
    public function cdata(Request $request, IClockIngestService $ingest)
    {
        $this->requireToken($request);

        $serialNumber = $this->serialNumber($request);
        $payload = (string) $request->getContent();

        if ($payload === '' && is_string($request->input('data'))) {
            $payload = (string) $request->input('data');
        }

        $ingest->ingest($serialNumber, $payload, $request->ip());

        return response("OK");
    }

    public function getrequest(Request $request, IClockCommandQueue $queue)
    {
        $this->requireToken($request);

        $device = $this->touchDevice($this->serialNumber($request), $request->ip());
        $commands = $queue->pullCommandsForDevice($device);

        $lines = array_merge($this->defaultOptionsLines($device->serial_number), $commands);

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function devicecmd(Request $request, IClockCommandQueue $queue)
    {
        $this->requireToken($request);

        $device = $this->touchDevice($this->serialNumber($request), $request->ip());

        $raw = (string) $request->getContent();
        if ($raw === '' && is_string($request->input('data'))) {
            $raw = (string) $request->input('data');
        }

        $commandId = $request->query('ID') ?? $request->input('ID') ?? null;
        $resultCode = $request->query('Return') ?? $request->input('Return') ?? ($request->query('Result') ?? $request->input('Result') ?? null);

        $parsedId = is_numeric($commandId) ? (int) $commandId : 0;
        if ($parsedId > 0) {
            $queue->recordResult($device, $parsedId, $resultCode !== null ? (string) $resultCode : null, $raw);
        }

        return response("OK");
    }

    private function requireToken(Request $request): void
    {
        $expected = (string) config('attendance.iclock_secret');
        if ($expected === '') {
            return;
        }

        $token = (string) ($request->query('token')
            ?? $request->input('token')
            ?? $request->query('iclock_token')
            ?? $request->input('iclock_token')
            ?? '');

        if (!hash_equals($expected, $token)) {
            abort(403);
        }
    }

    private function serialNumber(Request $request): string
    {
        return (string) ($request->query('SN') ?? $request->input('SN') ?? 'UNKNOWN');
    }

    private function touchDevice(string $serialNumber, ?string $ipAddress): AttendanceDevice
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

        return $device;
    }

    private function defaultOptionsLines(string $serialNumber): array
    {
        return [
            "GET OPTION FROM:{$serialNumber}",
            'Stamp=9999',
            'OpStamp=0',
            'ErrorDelay=30',
            'Delay=10',
            'TransTimes=00:00;23:59',
            'TransInterval=1',
            'TransFlag=1111111111',
            'Realtime=1',
        ];
    }
}
