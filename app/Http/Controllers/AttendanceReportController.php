<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller untuk report absensi per periode
 */
class AttendanceReportController extends BaseController
{
    /**
     * Tampil form report
     */
    public function index()
    {
        $this->requireAdminOrPermission('attendance.report');

        return view('attendance.report.index');
    }

    /**
     * Report harian
     */
    public function daily(Request $request)
    {
        $this->requireAdminOrPermission('attendance.report');

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereDate('date', $date)
            ->orderBy('first_in_at')
            ->paginate(50);

        $stats = [
            'total' => $attendances->total(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
        ];

        return view('attendance.report.daily', compact('attendances', 'date', 'stats'));
    }

    /**
     * Report mingguan
     */
    public function weekly(Request $request)
    {
        $this->requireAdminOrPermission('attendance.report');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $start = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay();

        // Group by date
        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get()
            ->groupBy(fn($a) => $a->date->format('Y-m-d'));

        $stats = [
            'total_days' => $attendances->count(),
            'total_records' => Attendance::whereBetween('date', [$start, $end])->count(),
            'avg_per_day' => round(Attendance::whereBetween('date', [$start, $end])->count() / max($attendances->count(), 1), 2),
        ];

        return view('attendance.report.weekly', compact('attendances', 'start', 'end', 'stats'));
    }

    /**
     * Report bulanan
     */
    public function monthly(Request $request)
    {
        $this->requireAdminOrPermission('attendance.report');

        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = Carbon::createFromFormat('Y-m', $validated['month']);
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        // Group by user
        $identities = AttendanceIdentity::query()
            ->with(['guru', 'siswa', 'user'])
            ->where('is_active', true)
            ->get();

        $report = [];

        foreach ($identities as $identity) {
            $attendances = Attendance::query()
                ->where('attendance_identity_id', $identity->id)
                ->whereBetween('date', [$start, $end])
                ->get();

            $totalDays = $end->diffInDays($start) + 1;
            $hadir = $attendances->count();
            $tidakHadir = $totalDays - $hadir;

            $nama = $identity->user?->name 
                ?? $identity->guru?->nama_lengkap 
                ?? $identity->siswa?->nama_lengkap 
                ?? '-';

            $report[] = [
                'identity' => $identity,
                'nama' => $nama,
                'kind' => $identity->kind,
                'pin' => $identity->device_pin,
                'total_days' => $totalDays,
                'hadir' => $hadir,
                'tidak_hadir' => $tidakHadir,
                'persentase' => $totalDays > 0 ? round(($hadir / $totalDays) * 100, 2) : 0,
            ];
        }

        // Sort by persentase
        usort($report, fn($a, $b) => $b['persentase'] <=> $a['persentase']);

        $stats = [
            'total_users' => count($report),
            'avg_attendance' => round(array_sum(array_column($report, 'persentase')) / max(count($report), 1), 2),
        ];

        return view('attendance.report.monthly', compact('report', 'month', 'stats'));
    }

    /**
     * Report per user
     */
    public function userDetail(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.report');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $start = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay();

        $attendances = Attendance::query()
            ->where('attendance_identity_id', $identity->id)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->paginate(50);

        $nama = $identity->user?->name 
            ?? $identity->guru?->nama_lengkap 
            ?? $identity->siswa?->nama_lengkap 
            ?? '-';

        $stats = [
            'total_days' => $end->diffInDays($start) + 1,
            'hadir' => $attendances->count(),
            'tidak_hadir' => ($end->diffInDays($start) + 1) - $attendances->count(),
            'persentase' => round(($attendances->count() / max($end->diffInDays($start) + 1, 1)) * 100, 2),
        ];

        return view('attendance.report.user-detail', compact(
            'identity',
            'attendances',
            'nama',
            'start',
            'end',
            'stats'
        ));
    }

    /**
     * Report keterlambatan
     */
    public function latecomers(Request $request)
    {
        $this->requireAdminOrPermission('attendance.report');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'threshold_time' => 'required|date_format:H:i', // Jam batas (contoh: 07:30)
        ]);

        $start = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay();
        $thresholdTime = Carbon::createFromFormat('H:i', $validated['threshold_time']);

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereBetween('date', [$start, $end])
            ->whereNotNull('first_in_at')
            ->get()
            ->filter(function ($attendance) use ($thresholdTime) {
                $firstInTime = $attendance->first_in_at->copy()->setDate(2000, 1, 1);
                return $firstInTime->gt($thresholdTime);
            })
            ->sortBy(fn($a) => $a->first_in_at);

        $stats = [
            'total_latecomers' => $attendances->count(),
            'threshold_time' => $thresholdTime->format('H:i'),
        ];

        return view('attendance.report.latecomers', compact('attendances', 'start', 'end', 'stats'));
    }

    private function requireAdminOrPermission(string $permission): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->hasAnyRole(['admin', 'superadmin'])) {
            return;
        }

        if ($user->can($permission)) {
            return;
        }

        abort(403);
    }
}
