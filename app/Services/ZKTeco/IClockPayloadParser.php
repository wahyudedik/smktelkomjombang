<?php

namespace App\Services\ZKTeco;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IClockPayloadParser
{
    /**
     * Parse attendance payload dari device atau iClock Proxy.
     *
     * Mendukung beberapa format:
     * 1. ZKTeco standard: PIN=xxx\tDateTime=YYYY-MM-DD HH:MM:SS\tVerified=xx\tStatus=xx
     * 2. Tab-separated: PIN\tDateTime\tInOutMode\tVerifyMode
     * 3. Comma-separated: PIN,DateTime,VerifyMode,InOutMode
     * 4. iClock Proxy format: bisa berupa salah satu dari di atas
     */
    public function parse(string $payload): array
    {
        if (trim($payload) === '') {
            return [];
        }

        $lines = preg_split("/\\r\\n|\\n|\\r/", $payload) ?: [];
        $events = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Skip metadata lines (bukan attendance data)
            if ($this->isMetadataLine($line)) {
                continue;
            }

            $event = $this->parseLine($line);
            if ($event) {
                $events[] = $event;
            }
        }

        if (count($events) === 0 && count($lines) > 0) {
            Log::debug('IClockPayloadParser: no events parsed from payload', [
                'lines_count' => count($lines),
                'first_line' => $lines[0] ?? '',
                'payload_preview' => substr($payload, 0, 200),
            ]);
        }

        return $events;
    }

    /**
     * Cek apakah baris adalah metadata (bukan attendance data)
     */
    private function isMetadataLine(string $line): bool
    {
        // Skip baris yang merupakan metadata/proxy control
        $metadataPatterns = [
            '/^table=/',
            '/^Stamp=/',
            '/^OpStamp=/',
            '/^GET OPTION FROM/',
            '/^CHECKOPTION/',
            '/^STARTUP/',
            '/^Systime/',
            '/^VERSION/',
            '/^INFO=/',
            '/^ACK/',
            '/^NACK/',
            '/^CONNECT/',
        ];

        foreach ($metadataPatterns as $pattern) {
            if (preg_match($pattern, $line)) {
                return true;
            }
        }

        return false;
    }

    private function parseLine(string $line): ?array
    {
        // Format 1: ZKTeco standard dengan prefix
        // PIN=xxx\tDateTime=YYYY-MM-DD HH:MM:SS\tVerified=xx\tStatus=xx
        if (preg_match('/PIN=(?<pin>[^\\t\\s,]+)\\s*[\\t,]\\s*DateTime=(?<dt>\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2})(?:\\s*[\\t,](?<rest>.*))?$/i', $line, $m)) {
            return $this->eventFromParts(
                pin: (string) $m['pin'],
                dateTime: (string) $m['dt'],
                rest: $m['rest'] ?? '',
                raw: $line
            );
        }

        // Format 2: Tab-separated (device langsung atau iClock Proxy)
        // PIN\tDateTime\tInOutMode\tVerifyMode
        // atau
        // PIN\tDateTime\tVerified=xx\tStatus=xx
        $parts = preg_split('/[\\t,]/', $line) ?: [];
        $parts = array_map('trim', $parts);

        if (count($parts) >= 2) {
            $pin = $parts[0];
            $dateTime = $parts[1];

            // Validasi PIN (harus numerik atau alfanumerik, tidak kosong)
            if ($pin === '' || preg_match('/^[\\s\\t]+$/', $pin)) {
                return null;
            }

            // Validasi DateTime: format YYYY-MM-DD HH:MM:SS atau DD/MM/YYYY HH:MM:SS
            $parsedDt = $this->parseDateTime($dateTime);
            if ($parsedDt === null) {
                return null;
            }

            $rest = implode("\t", array_slice($parts, 2));

            return $this->eventFromParts(
                pin: $pin,
                dateTime: $parsedDt,
                rest: $rest,
                raw: $line
            );
        }

        return null;
    }

    /**
     * Parse datetime dari berbagai format yang mungkin dikirim device
     */
    private function parseDateTime(string $dateTime): ?string
    {
        $dateTime = trim($dateTime);

        // Format: YYYY-MM-DD HH:MM:SS
        if (preg_match('/^\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}$/', $dateTime)) {
            return $dateTime;
        }

        // Format: YYYY/MM/DD HH:MM:SS
        if (preg_match('/^(\\d{4})\\/(\\d{2})\\/(\\d{2}) (\\d{2}:\\d{2}:\\d{2})$/', $dateTime, $m)) {
            return "{$m[1]}-{$m[2]}-{$m[3]} {$m[4]}";
        }

        // Format: DD-MM-YYYY HH:MM:SS
        if (preg_match('/^(\\d{2})-(\\d{2})-(\\d{4}) (\\d{2}:\\d{2}:\\d{2})$/', $dateTime, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]} {$m[4]}";
        }

        // Format: DD/MM/YYYY HH:MM:SS
        if (preg_match('/^(\\d{2})\\/(\\d{2})\\/(\\d{4}) (\\d{2}:\\d{2}:\\d{2})$/', $dateTime, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]} {$m[4]}";
        }

        // Format: MM/DD/YYYY HH:MM:SS (US format)
        if (preg_match('/^(\\d{2})\\/(\\d{2})\\/(\\d{4}) (\\d{2}:\\d{2}:\\d{2})$/', $dateTime, $m)) {
            // Coba YYYY-MM-DD dulu, jika gagal coba MM-DD-YYYY
            $try1 = "{$m[3]}-{$m[1]}-{$m[2]} {$m[4]}";
            try {
                Carbon::createFromFormat('Y-m-d H:i:s', $try1);
                return $try1;
            } catch (\Throwable) {
                // ignore
            }
        }

        // Coba Carbon parse sebagai fallback
        try {
            $carbon = Carbon::parse($dateTime);
            return $carbon->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }

    private function eventFromParts(string $pin, string $dateTime, string $rest, string $raw): ?array
    {
        try {
            $ts = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
        } catch (\Throwable) {
            return null;
        }

        $verifyMode = null;
        $inOutMode = null;

        if ($rest !== '') {
            // Parse named fields: Verified=xx, Status=xx
            if (preg_match('/Verified=(?<v>[^\\t\\s,]+)/i', $rest, $m)) {
                $verifyMode = (string) $m['v'];
            }
            if (preg_match('/Status=(?<s>[^\\t\\s,]+)/i', $rest, $m)) {
                $inOutMode = (string) $m['s'];
            }

            // Fallback: positional fields
            $restParts = array_map('trim', preg_split('/[\\t,]/', $rest) ?: []);
            if (count($restParts) >= 2) {
                $inOutMode ??= $restParts[0] !== '' ? $restParts[0] : null;
                $verifyMode ??= $restParts[1] !== '' ? $restParts[1] : null;
            } elseif (count($restParts) === 1) {
                // Satu field saja — coba tebak
                $val = $restParts[0];
                if (is_numeric($val)) {
                    $verifyMode = $val;
                }
            }
        }

        return [
            'device_pin' => $pin,
            'log_time' => $ts,
            'verify_mode' => $verifyMode,
            'in_out_mode' => $inOutMode,
            'raw' => $raw,
        ];
    }
}
