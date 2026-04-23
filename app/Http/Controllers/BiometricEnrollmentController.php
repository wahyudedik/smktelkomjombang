<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Services\ZKTeco\BiometricEnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller untuk enroll biometric (fingerprint/face/RFID) dari web
 */
class BiometricEnrollmentController extends BaseController
{
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

        // Connect ke device
        if (!$this->enrollmentService->connect($device)) {
            return back()->withErrors(['device' => 'Gagal terhubung ke device']);
        }

        // Enroll fingerprint
        $result = $this->enrollmentService->enrollFingerprint(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User",
            $validated['finger_index']
        );

        $this->enrollmentService->disconnect();

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

        // Connect ke device
        if (!$this->enrollmentService->connect($device)) {
            return back()->withErrors(['device' => 'Gagal terhubung ke device']);
        }

        // Enroll face
        $result = $this->enrollmentService->enrollFace(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User"
        );

        $this->enrollmentService->disconnect();

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

        // Connect ke device
        if (!$this->enrollmentService->connect($device)) {
            return back()->withErrors(['device' => 'Gagal terhubung ke device']);
        }

        // Enroll RFID
        $result = $this->enrollmentService->enrollRFID(
            $identity->device_pin,
            $identity->user?->name ?? $identity->guru?->nama_lengkap ?? $identity->siswa?->nama_lengkap ?? "User",
            $validated['card_number']
        );

        $this->enrollmentService->disconnect();

        if ($result['success']) {
            return redirect()->route('admin.absensi.biometric.index')
                ->with('success', $result['message']);
        }

        return back()->withErrors(['enrollment' => $result['message']]);
    }

    /**
     * Test connection ke device
     */
    public function testConnection(Request $request)
    {
        $this->requireAdminOrPermission('attendance.biometric.manage');

        $validated = $request->validate([
            'device_id' => 'required|exists:attendance_devices,id',
        ]);

        $device = AttendanceDevice::findOrFail($validated['device_id']);

        $connected = BiometricEnrollmentService::testConnection(
            $device->ip_address,
            $device->port ?? 4370
        );

        if ($connected) {
            return response()->json([
                'success' => true,
                'message' => "Device {$device->name} terhubung",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Gagal terhubung ke device {$device->name}",
        ], 400);
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
