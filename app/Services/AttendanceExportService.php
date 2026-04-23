<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Service untuk export rekap absensi ke Excel
 */
class AttendanceExportService
{
    /**
     * Export rekap harian ke array (untuk Excel)
     */
    public function exportDaily(string $date): array
    {
        $date = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereDate('date', $date)
            ->orderBy('first_in_at')
            ->get();

        $data = [];

        // Header
        $data[] = [
            'Tanggal',
            'Jenis',
            'Nama',
            'PIN',
            'Jam Masuk',
            'Jam Pulang',
            'Durasi',
            'Status',
        ];

        // Data
        foreach ($attendances as $attendance) {
            $identity = $attendance->identity;
            $nama = $identity->user?->name 
                ?? $identity->guru?->nama_lengkap 
                ?? $identity->siswa?->nama_lengkap 
                ?? '-';

            $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
            $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';

            $durasi = '-';
            if ($attendance->first_in_at && $attendance->last_out_at) {
                $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                $hours = intdiv($diff, 60);
                $minutes = $diff % 60;
                $durasi = "{$hours}h {$minutes}m";
            }

            $data[] = [
                $date->format('Y-m-d'),
                $identity->kind,
                $nama,
                $identity->device_pin,
                $firstIn,
                $lastOut,
                $durasi,
                $attendance->status,
            ];
        }

        return $data;
    }

    /**
     * Export rekap periode (harian/mingguan/bulanan) ke array
     */
    public function exportPeriod(string $startDate, string $endDate, string $groupBy = 'daily'): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->orderBy('first_in_at')
            ->get();

        $data = [];

        // Header
        $data[] = [
            'Tanggal',
            'Jenis',
            'Nama',
            'PIN',
            'Jam Masuk',
            'Jam Pulang',
            'Durasi',
            'Status',
        ];

        // Data
        foreach ($attendances as $attendance) {
            $identity = $attendance->identity;
            $nama = $identity->user?->name 
                ?? $identity->guru?->nama_lengkap 
                ?? $identity->siswa?->nama_lengkap 
                ?? '-';

            $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
            $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';

            $durasi = '-';
            if ($attendance->first_in_at && $attendance->last_out_at) {
                $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                $hours = intdiv($diff, 60);
                $minutes = $diff % 60;
                $durasi = "{$hours}h {$minutes}m";
            }

            $data[] = [
                $attendance->date->format('Y-m-d'),
                $identity->kind,
                $nama,
                $identity->device_pin,
                $firstIn,
                $lastOut,
                $durasi,
                $attendance->status,
            ];
        }

        return $data;
    }

    /**
     * Export summary per user
     */
    public function exportSummary(string $startDate, string $endDate): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $identities = AttendanceIdentity::query()
            ->with(['guru', 'siswa', 'user'])
            ->where('is_active', true)
            ->get();

        $data = [];

        // Header
        $data[] = [
            'Jenis',
            'Nama',
            'PIN',
            'Total Hari',
            'Hadir',
            'Tidak Hadir',
            'Persentase',
            'Rata-rata Jam Masuk',
            'Rata-rata Jam Pulang',
        ];

        // Data
        foreach ($identities as $identity) {
            $nama = $identity->user?->name 
                ?? $identity->guru?->nama_lengkap 
                ?? $identity->siswa?->nama_lengkap 
                ?? '-';

            $attendances = Attendance::query()
                ->where('attendance_identity_id', $identity->id)
                ->whereBetween('date', [$start, $end])
                ->get();

            $totalDays = $end->diffInDays($start) + 1;
            $hadir = $attendances->count();
            $tidakHadir = $totalDays - $hadir;
            $persentase = $totalDays > 0 ? round(($hadir / $totalDays) * 100, 2) : 0;

            // Rata-rata jam masuk
            $avgFirstIn = '-';
            $firstIns = $attendances->filter(fn($a) => $a->first_in_at)->pluck('first_in_at');
            if ($firstIns->count() > 0) {
                $avgSeconds = $firstIns->map(fn($t) => $t->secondsSinceMidnight())->avg();
                $avgFirstIn = Carbon::createFromTime(0, 0, 0)->addSeconds($avgSeconds)->format('H:i:s');
            }

            // Rata-rata jam pulang
            $avgLastOut = '-';
            $lastOuts = $attendances->filter(fn($a) => $a->last_out_at)->pluck('last_out_at');
            if ($lastOuts->count() > 0) {
                $avgSeconds = $lastOuts->map(fn($t) => $t->secondsSinceMidnight())->avg();
                $avgLastOut = Carbon::createFromTime(0, 0, 0)->addSeconds($avgSeconds)->format('H:i:s');
            }

            $data[] = [
                $identity->kind,
                $nama,
                $identity->device_pin,
                $totalDays,
                $hadir,
                $tidakHadir,
                "{$persentase}%",
                $avgFirstIn,
                $avgLastOut,
            ];
        }

        return $data;
    }

    /**
     * Export detail per user (semua hari dalam periode)
     */
    public function exportUserDetail(int $identityId, string $startDate, string $endDate): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $identity = AttendanceIdentity::with(['guru', 'siswa', 'user'])->findOrFail($identityId);

        $attendances = Attendance::query()
            ->where('attendance_identity_id', $identityId)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get();

        $nama = $identity->user?->name 
            ?? $identity->guru?->nama_lengkap 
            ?? $identity->siswa?->nama_lengkap 
            ?? '-';

        $data = [];

        // Header
        $data[] = [
            "Detail Absensi: {$nama} ({$identity->device_pin})",
        ];
        $data[] = [
            "Periode: {$start->format('Y-m-d')} s/d {$end->format('Y-m-d')}",
        ];
        $data[] = [];
        $data[] = [
            'Tanggal',
            'Hari',
            'Jam Masuk',
            'Jam Pulang',
            'Durasi',
            'Status',
        ];

        // Data
        foreach ($attendances as $attendance) {
            $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
            $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';

            $durasi = '-';
            if ($attendance->first_in_at && $attendance->last_out_at) {
                $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                $hours = intdiv($diff, 60);
                $minutes = $diff % 60;
                $durasi = "{$hours}h {$minutes}m";
            }

            $data[] = [
                $attendance->date->format('Y-m-d'),
                $attendance->date->format('l'),
                $firstIn,
                $lastOut,
                $durasi,
                $attendance->status,
            ];
        }

        return $data;
    }
}
