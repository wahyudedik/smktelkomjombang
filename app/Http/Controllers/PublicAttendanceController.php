<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PublicAttendanceController extends BaseController
{
    /**
     * Show PIN input form for self-service attendance check.
     */
    public function index()
    {
        return view(theme_view('attendance.public.check'));
    }

    /**
     * Validate PIN and display last 30 days of attendance records.
     */
    public function check(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:20',
        ]);

        $pin = $request->input('pin');

        // Find the identity by PIN (siswa, guru, or user)
        $identity = AttendanceIdentity::query()
            ->with(['guru', 'siswa', 'user'])
            ->where('device_pin', $pin)
            ->where('is_active', true)
            ->first();

        if (!$identity) {
            return view(theme_view('attendance.public.check'), [
                'error' => 'PIN tidak ditemukan atau tidak aktif. Silakan periksa kembali.',
            ]);
        }

        // Resolve the name based on identity kind
        $name = match (true) {
            $identity->guru !== null => $identity->guru->nama_lengkap,
            $identity->siswa !== null => $identity->siswa->nama_lengkap,
            $identity->user !== null => $identity->user->name,
            default => '-',
        };

        // Get attendance for the last 30 days
        $attendances = Attendance::query()
            ->where('attendance_identity_id', $identity->id)
            ->whereDate('date', '>=', now()->subDays(30))
            ->orderByDesc('date')
            ->orderByDesc('first_in_at')
            ->get();

        return view(theme_view('attendance.public.check'), [
            'identity' => $identity,
            'name' => $name,
            'attendances' => $attendances,
            'pin' => $pin,
        ]);
    }
}
