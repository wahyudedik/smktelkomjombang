<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Services\ZKTeco\IClockCommandQueue;
use App\Services\ZKTeco\IClockIngestService;
use App\Services\ZKTeco\IClockPayloadParser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class ZKTecoIClockController extends BaseController
{
    public function __construct(
        private readonly IClockPayloadParser $parser,
    ) {}

    public function cdata(Request $request, IClockIngestService $ingest)
    {
        $this->requireToken($request);

        $serialNumber = $this->serialNumber($request);
        $table = $request->query('table', '');
        $isIClockProxy = str_contains((string) $request->userAgent(), 'iClock Proxy');

        // Ambil payload dari berbagai sumber
        $payload = (string) $request->getContent();

        if ($payload === '' && is_string($request->input('data'))) {
            $payload = (string) $request->input('data');
        }

        // Log detail untuk debugging
        Log::info('ZKTeco cdata received', [
            'serial_number' => $serialNumber,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_iclock_proxy' => $isIClockProxy,
            'table' => $table,
            'content_type' => $request->header('Content-Type'),
            'payload_size' => strlen($payload),
            'payload_preview' => substr($payload, 0, 200),
            'all_query_params' => $request->query(),
        ]);

        // Jika dari iClock Proxy dan table=ATTLOG, coba parse payload
        if ($isIClockProxy && $table === 'ATTLOG') {
            // Selalu touch device untuk mencatat last_seen
            $this->touchDevice($serialNumber, $request->ip());

            if ($payload !== '') {
                // Coba parse sebagai teks
                $events = $this->parser->parse($payload);

                if (count($events) > 0) {
                    Log::info('ZKTeco iClock Proxy ATTLOG parsed', [
                        'serial_number' => $serialNumber,
                        'events_count' => count($events),
                    ]);
                    $ingest->ingest($serialNumber, $payload, $request->ip());
                } else {
                    // Jika gagal parse, log raw payload untuk analisis
                    Log::warning('ZKTeco iClock Proxy ATTLOG parse failed', [
                        'serial_number' => $serialNumber,
                        'payload_size' => strlen($payload),
                        'payload_hex' => bin2hex(substr($payload, 0, 100)),
                        'payload_raw' => $payload,
                    ]);

                    // Simpan raw payload untuk debugging
                    $this->saveRawLog($serialNumber, 'ATTLOG_PROXY', $payload, $request->ip());
                }
            } else {
                Log::info('ZKTeco iClock Proxy ATTLOG empty body (announce)', [
                    'serial_number' => $serialNumber,
                ]);
            }
        } elseif ($table !== '' && $table !== 'ATTLOG') {
            // Table lain (OPERLOG, options, dll) - log saja
            Log::info('ZKTeco iClock Proxy non-ATTLOG table', [
                'serial_number' => $serialNumber,
                'table' => $table,
                'payload_size' => strlen($payload),
            ]);
        } else {
            // Format langsung dari device (bukan proxy)
            $ingest->ingest($serialNumber, $payload, $request->ip());
        }

        return response("OK");
    }

    /**
     * Simpan raw payload untuk debugging
     */
    private function saveRawLog(string $serialNumber, string $type, string $payload, ?string $ipAddress): void
    {
        try {
            $logDir = storage_path('app/zkteco-raw');
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $filename = $logDir . '/' . date('Y-m-d') . '_' . $serialNumber . '_' . $type . '_' . time() . '.log';
            file_put_contents($filename, $payload);
        } catch (\Throwable $e) {
            Log::error('Failed to save raw ZKTeco log', ['error' => $e->getMessage()]);
        }
    }

    public function getrequest(Request $request, IClockCommandQueue $queue)
    {
        $this->requireToken($request);

        $serialNumber = $this->serialNumber($request);
        $device = $this->touchDevice($serialNumber, $request->ip());
        $commands = $queue->pullCommandsForDevice($device);

        $lines = array_merge($this->defaultOptionsLines($device->serial_number), $commands);

        // Log untuk debugging
        Log::info('ZKTeco getrequest', [
            'serial_number' => $serialNumber,
            'device_id' => $device->id,
            'ip_address' => $request->ip(),
            'lines_count' => count($lines),
        ]);

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
            Log::warning('ZKTeco token validation skipped: no secret configured');
            return;
        }

        $token = (string) ($request->query('token')
            ?? $request->input('token')
            ?? $request->query('iclock_token')
            ?? $request->input('iclock_token')
            ?? '');

        Log::debug('ZKTeco token validation', [
            'expected' => substr($expected, 0, 10) . '...',
            'received' => substr($token, 0, 10) . '...',
            'match' => hash_equals($expected, $token),
        ]);

        if (!hash_equals($expected, $token)) {
            Log::warning('ZKTeco token mismatch', [
                'ip' => request()->ip(),
                'path' => request()->path(),
            ]);
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
