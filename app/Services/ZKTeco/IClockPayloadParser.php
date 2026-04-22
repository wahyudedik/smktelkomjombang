<?php

namespace App\Services\ZKTeco;

use Carbon\Carbon;

class IClockPayloadParser
{
    public function parse(string $payload): array
    {
        $lines = preg_split("/\\r\\n|\\n|\\r/", $payload) ?: [];
        $events = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            if (str_contains($line, 'table=') || str_contains($line, 'Stamp=') || str_contains($line, 'OpStamp=')) {
                continue;
            }

            $event = $this->parseLine($line);
            if ($event) {
                $events[] = $event;
            }
        }

        return $events;
    }

    private function parseLine(string $line): ?array
    {
        if (preg_match('/PIN=(?<pin>[^\\t\\s]+)\\tDateTime=(?<dt>\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2})(?:\\t(?<rest>.*))?$/', $line, $m)) {
            return $this->eventFromParts(
                pin: (string) $m['pin'],
                dateTime: (string) $m['dt'],
                rest: $m['rest'] ?? '',
                raw: $line
            );
        }

        $parts = explode("\t", $line);
        if (count($parts) < 2) {
            return null;
        }

        $pin = trim((string) $parts[0]);
        $dateTime = trim((string) $parts[1]);

        if (!preg_match('/^\\d{4}-\\d{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}$/', $dateTime)) {
            return null;
        }

        $rest = implode("\t", array_slice($parts, 2));

        return $this->eventFromParts(
            pin: $pin,
            dateTime: $dateTime,
            rest: $rest,
            raw: $line
        );
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
            if (preg_match('/Verified=(?<v>[^\\t\\s]+)/', $rest, $m)) {
                $verifyMode = (string) $m['v'];
            }
            if (preg_match('/Status=(?<s>[^\\t\\s]+)/', $rest, $m)) {
                $inOutMode = (string) $m['s'];
            }

            $restParts = explode("\t", $rest);
            if (count($restParts) >= 2) {
                $inOutMode ??= trim((string) $restParts[0]) ?: null;
                $verifyMode ??= trim((string) $restParts[1]) ?: null;
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
