<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class AttendanceController extends BaseController
{
    public function index()
    {
        $this->requireAccess('attendance.view');

        $date = request()->string('date')->toString();
        if ($date === '') {
            $date = now()->toDateString();
        }

        $attendances = Attendance::query()
            ->with(['identity.guru', 'identity.siswa', 'identity.user'])
            ->whereDate('date', $date)
            ->orderBy('first_in_at')
            ->paginate(25);

        $latestLogs = AttendanceLog::query()
            ->with('device')
            ->latest('log_time')
            ->limit(20)
            ->get();

        $devices = AttendanceDevice::query()
            ->orderByDesc('last_seen_at')
            ->get();

        return view('attendance.index', compact('attendances', 'latestLogs', 'devices', 'date'));
    }

    public function logs(Request $request)
    {
        $this->requireAccess('attendance.view');

        $query = AttendanceLog::query()
            ->with('device')
            ->latest('log_time');

        $device = $request->string('device')->toString();
        if ($device !== '') {
            $query->whereHas('device', function ($q) use ($device) {
                $q->where('serial_number', $device);
            });
        }

        $pin = $request->string('pin')->toString();
        if ($pin !== '') {
            $query->where('device_pin', $pin);
        }

        $date = $request->string('date')->toString();
        if ($date !== '') {
            $query->whereDate('log_time', $date);
        }

        $logs = $query->paginate(50)->withQueryString();
        $devices = AttendanceDevice::query()->orderBy('serial_number')->get();

        return view('attendance.logs', compact('logs', 'devices'));
    }

    public function devices()
    {
        $this->requireAdminOrPermission('attendance.devices.view');

        $devices = AttendanceDevice::query()
            ->orderByDesc('last_seen_at')
            ->paginate(25);

        return view('attendance.devices', compact('devices'));
    }

    public function updateDevice(Request $request, AttendanceDevice $device)
    {
        $this->requireAdminOrPermission('attendance.devices.edit');

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'comm_key' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $device->forceFill([
            'name' => $validated['name'] ?? $device->name,
            'ip_address' => $validated['ip_address'] ?? $device->ip_address,
            'port' => $validated['port'] ?? $device->port,
            'comm_key' => $validated['comm_key'] ?? $device->comm_key,
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : $device->is_active,
        ])->save();

        return redirect()->route('admin.absensi.devices.index')->with('success', 'Perangkat berhasil diperbarui');
    }

    public function mapping()
    {
        $this->requireAdminOrPermission('attendance.mapping.manage');

        $mappings = AttendanceIdentity::query()
            ->with(['guru', 'siswa', 'user'])
            ->orderByDesc('updated_at')
            ->paginate(50);

        return view('attendance.mapping', compact('mappings'));
    }

    public function storeMapping(Request $request)
    {
        $this->requireAdminOrPermission('attendance.mapping.manage');

        $validated = $request->validate([
            'kind' => 'required|in:user,guru,siswa',
            'user_id' => 'nullable|integer|exists:users,id',
            'guru_id' => 'nullable|integer|exists:gurus,id',
            'siswa_id' => 'nullable|integer|exists:siswas,id',
            'device_pin' => 'required|string|max:64',
            'is_active' => 'nullable|boolean',
        ]);

        $kind = $validated['kind'];
        $attributes = ['kind' => $kind];

        if ($kind === 'user') {
            if (!isset($validated['user_id'])) {
                return back()->withErrors(['user_id' => 'user_id wajib untuk kind=user'])->withInput();
            }
            $attributes['user_id'] = $validated['user_id'];
        }

        if ($kind === 'guru') {
            if (!isset($validated['guru_id'])) {
                return back()->withErrors(['guru_id' => 'guru_id wajib untuk kind=guru'])->withInput();
            }
            $attributes['guru_id'] = $validated['guru_id'];
        }

        if ($kind === 'siswa') {
            if (!isset($validated['siswa_id'])) {
                return back()->withErrors(['siswa_id' => 'siswa_id wajib untuk kind=siswa'])->withInput();
            }
            $attributes['siswa_id'] = $validated['siswa_id'];
        }

        $mapping = AttendanceIdentity::query()->where($attributes)->first();
        if (!$mapping) {
            $mapping = new AttendanceIdentity($attributes);
        }

        $mapping->forceFill([
            'device_pin' => $validated['device_pin'],
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
        ])->save();

        return redirect()->route('admin.absensi.mapping.index')->with('success', 'Mapping berhasil disimpan');
    }

    private function requireAccess(string $permission): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->hasAnyRole(['guru', 'admin', 'superadmin'])) {
            return;
        }

        if ($user->can($permission)) {
            return;
        }

        abort(403);
    }

    private function requireAdminOrPermission(string $permission): void
    {
        $user = Auth::user();

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
