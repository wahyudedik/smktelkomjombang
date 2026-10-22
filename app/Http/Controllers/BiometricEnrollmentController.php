<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Services\ZKTeco\BiometricEnrollmentService;
use App\Traits\AttendanceAuthorization;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller untuk enroll biometric (fingerprint/face/RFID) dari web
 */
class BiometricEnrollmentController extends BaseController
{
    use AttendanceAuthorization;
    public function __construct(
        private readonly BiometricEnrollmentService $enrollmentService,
    ) {}

    /**
     * Tampil form enrollment
     */
    public function index()
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = AttendanceIdentity::query()
            ->with(['user', 'guru', 'siswa'])
            ->where('is_active', true)
            ->orderBy('device_pin')
            ->get();

        return view('attendance.biometric.index', compact('devices', 'users'));
    }

    /**
     * Tampil form enroll fingerprint
     */
    public function enrollFingerprintForm(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $name = $identity->user?->name
            ?? $identity->guru?->nama_lengkap
            ?? $identity->siswa?->nama_lengkap
            ?? "User {$identity->device_pin}";

        return view('attendance.biometric.enroll-fingerprint', compact(
            'identity',
            'devices',
            'name'
        ));
    }

    /**
     * Proses enroll fingerprint
     */
    public function enrollFingerprint(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $validated = $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
            'finger_index' => 'required|integer|min:0|max:9',
        ]);

        $device = AttendanceDevice::findOrFail($validated['device_id']);

        // Enroll fingerprint — langsung enqueue ke attendance_commands.
        // Tidak butuh koneksi socket: command akan dieksekusi device via iClock
        // saat polling /iclock/getrequest.
        $result = $this->enrollmentService->enrollFingerprint(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User",
            $validated['finger_index']
        );

        if ($result['success']) {
            return redirect()->route('admin.absensi.biometric.index')
                ->with('success', $result['message']);
        }

        return back()->withErrors(['enrollment' => $result['message']]);
    }

    /**
     * Tampil form enroll face
     */
    public function enrollFaceForm(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $name = $identity->user?->name
            ?? $identity->guru?->nama_lengkap
            ?? $identity->siswa?->nama_lengkap
            ?? "User {$identity->device_pin}";

        return view('attendance.biometric.enroll-face', compact(
            'identity',
            'devices',
            'name'
        ));
    }

    /**
     * Proses enroll face
     */
    public function enrollFace(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $validated = $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
        ]);

        $device = AttendanceDevice::findOrFail($validated['device_id']);

        // Enroll face — langsung enqueue ke attendance_commands (tanpa socket).
        $result = $this->enrollmentService->enrollFace(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User"
        );

        if ($result['success']) {
            return redirect()->route('admin.absensi.biometric.index')
                ->with('success', $result['message']);
        }

        return back()->withErrors(['enrollment' => $result['message']]);
    }

    /**
     * Tampil form enroll RFID
     */
    public function enrollRFIDForm(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $devices = AttendanceDevice::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $name = $identity->user?->name
            ?? $identity->guru?->nama_lengkap
            ?? $identity->siswa?->nama_lengkap
            ?? "User {$identity->device_pin}";

        return view('attendance.biometric.enroll-rfid', compact(
            'identity',
            'devices',
            'name'
        ));
    }

    /**
     * Proses enroll RFID
     */
    public function enrollRFID(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $validated = $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
            'card_number' => 'required|string|max:20',
        ]);

        $device = AttendanceDevice::findOrFail($validated['device_id']);

        // Enroll RFID — langsung enqueue ke attendance_commands (tanpa socket).
        $result = $this->enrollmentService->enrollRFID(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User",
            $validated['card_number']
        );

        if ($result['success']) {
            return redirect()->route('admin.absensi.biometric.index')
                ->with('success', $result['message']);
        }

        return back()->withErrors(['enrollment' => $result['message']]);
    }

    /**
     * Test connection ke device (health-check iClock, tanpa socket)
     *
     * Status device diturunkan dari last_seen_at (aktivitas polling iClock),
     * bukan dari probe TCP port 4370.
     */
    public function testConnection(Request $request)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $validated = $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
        ]);

        $device = AttendanceDevice::findOrFail($validated['device_id']);

        $result = BiometricEnrollmentService::testConnection($device);

        return response()->json([
            'success' => $result['success'],
            'status' => $result['status'],
            'message' => $result['message'],
            'method' => $result['method'],
            'last_seen_at' => $result['last_seen_at'],
            'minutes_since_last_seen' => $result['minutes_since_last_seen'],
            'threshold_minutes' => $result['threshold_minutes'],
        ]);
    }

}
