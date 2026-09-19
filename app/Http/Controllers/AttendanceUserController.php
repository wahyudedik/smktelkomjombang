<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceIdentityImport;
use App\Models\AttendanceIdentity;
use App\Models\AuditLog;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use App\Services\ZKTeco\UserSyncService;
use App\Traits\AttendanceAuthorization;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

/**
 * Controller untuk manage user absensi (CRUD PIN mapping)
 * Fitur: tambah, edit, hapus user dari device via web
 */
class AttendanceUserController extends BaseController
{
    use AttendanceAuthorization;
    public function __construct(
        private readonly UserSyncService $syncService,
    ) {}

    /**
     * Tampil daftar user absensi dengan form tambah
     */
    public function index()
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $users = AttendanceIdentity::query()
            ->with(['user', 'guru', 'siswa'])
            ->orderByDesc('updated_at')
            ->paginate(50);

        $availableUsers = User::query()
            ->orderBy('name')
            ->get();

        $availableGurus = Guru::query()
            ->orderBy('nama_lengkap')
            ->get();

        $availableSiswas = Siswa::query()
            ->orderBy('nama_lengkap')
            ->get();

        return view('attendance.users.index', compact(
            'users',
            'availableUsers',
            'availableGurus',
            'availableSiswas'
        ));
    }

    /**
     * Simpan user absensi baru
     */
    public function store(Request $request)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $validated = $request->validate([
            'kind' => 'required|in:user,guru,siswa',
            'user_id' => 'nullable|integer|exists:users,id',
            'guru_id' => 'nullable|integer|exists:gurus,id',
            'siswa_id' => 'nullable|integer|exists:siswas,id',
            'device_pin' => 'required|string|max:64|unique:attendance_identities,device_pin',
            'is_active' => 'nullable|boolean',
        ]);

        $kind = $validated['kind'];
        $attributes = ['kind' => $kind];

        // Validasi sesuai kind
        if ($kind === 'user' && !isset($validated['user_id'])) {
            return back()->withErrors(['user_id' => 'user_id wajib untuk kind=user'])->withInput();
        }
        if ($kind === 'guru' && !isset($validated['guru_id'])) {
            return back()->withErrors(['guru_id' => 'guru_id wajib untuk kind=guru'])->withInput();
        }
        if ($kind === 'siswa' && !isset($validated['siswa_id'])) {
            return back()->withErrors(['siswa_id' => 'siswa_id wajib untuk kind=siswa'])->withInput();
        }

        // Set ID sesuai kind
        if ($kind === 'user') {
            $attributes['user_id'] = $validated['user_id'];
        } elseif ($kind === 'guru') {
            $attributes['guru_id'] = $validated['guru_id'];
        } elseif ($kind === 'siswa') {
            $attributes['siswa_id'] = $validated['siswa_id'];
        }

        // Buat identity baru
        $identity = AttendanceIdentity::create([
            ...$attributes,
            'device_pin' => $validated['device_pin'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Audit trail logging
        AuditLog::createLog(
            action: 'attendance.create_identity',
            userId: auth()->id() ?? null,
            modelType: AttendanceIdentity::class,
            modelId: $identity->id,
            oldValues: null,
            newValues: $identity->only(['kind', 'device_pin', 'is_active', 'user_id', 'guru_id', 'siswa_id']),
            ipAddress: request()->ip(),
            userAgent: request()->userAgent()
        );

        // Auto-sync ke device jika aktif
        if ($identity->is_active) {
            $name = $identity->user?->name
                ?? $identity->guru?->nama_lengkap
                ?? $identity->siswa?->nama_lengkap
                ?? "User {$identity->device_pin}";

            $this->syncService->enqueueAddUser($identity->device_pin, $name);
        }

        return redirect()->route('admin.absensi.users.index')
            ->with('success', "User {$identity->device_pin} berhasil ditambahkan dan dijadwalkan untuk sync ke device");
    }

    /**
     * Tampil form edit user
     */
    public function edit(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $availableUsers = User::query()
            ->orderBy('name')
            ->get();

        $availableGurus = Guru::query()
            ->orderBy('nama_lengkap')
            ->get();

        $availableSiswas = Siswa::query()
            ->orderBy('nama_lengkap')
            ->get();

        return view('attendance.users.edit', compact(
            'identity',
            'availableUsers',
            'availableGurus',
            'availableSiswas'
        ));
    }

    /**
     * Update user absensi
     */
    public function update(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $validated = $request->validate([
            'device_pin' => 'required|string|max:64|unique:attendance_identities,device_pin,' . $identity->id,
            'is_active' => 'nullable|boolean',
        ]);

        $oldPin = $identity->device_pin;
        $newPin = $validated['device_pin'];
        $wasActive = $identity->is_active;
        $isNowActive = $validated['is_active'] ?? true;

        // Audit trail logging (before update)
        $oldValues = $identity->only(['device_pin', 'is_active']);

        // Update identity
        $identity->update([
            'device_pin' => $newPin,
            'is_active' => $isNowActive,
        ]);

        // Audit trail logging (after update)
        AuditLog::createLog(
            action: 'attendance.update_identity',
            userId: auth()->id() ?? null,
            modelType: AttendanceIdentity::class,
            modelId: $identity->id,
            oldValues: $oldValues,
            newValues: ['device_pin' => $newPin, 'is_active' => $isNowActive],
            ipAddress: request()->ip(),
            userAgent: request()->userAgent()
        );

        // Handle sync ke device
        if ($oldPin !== $newPin) {
            // Jika PIN berubah: hapus PIN lama, tambah PIN baru
            $this->syncService->enqueueDeleteUser($oldPin);

            if ($isNowActive) {
                $name = $identity->user?->name
                    ?? $identity->guru?->nama_lengkap
                    ?? $identity->siswa?->nama_lengkap
                    ?? "User {$newPin}";

                $this->syncService->enqueueAddUser($newPin, $name);
            }
        } elseif ($wasActive && !$isNowActive) {
            // Jika dinonaktifkan: hapus dari device
            $this->syncService->enqueueDeleteUser($newPin);
        } elseif (!$wasActive && $isNowActive) {
            // Jika diaktifkan: tambah ke device
            $name = $identity->user?->name
                ?? $identity->guru?->nama_lengkap
                ?? $identity->siswa?->nama_lengkap
                ?? "User {$newPin}";

            $this->syncService->enqueueAddUser($newPin, $name);
        }

        return redirect()->route('admin.absensi.users.index')
            ->with('success', "User {$newPin} berhasil diperbarui dan dijadwalkan untuk sync");
    }

    /**
     * Hapus user absensi
     */
    public function destroy(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $pin = $identity->device_pin;

        // Audit trail logging (before delete)
        AuditLog::createLog(
            action: 'attendance.delete_identity',
            userId: auth()->id() ?? null,
            modelType: AttendanceIdentity::class,
            modelId: $identity->id,
            oldValues: $identity->only(['kind', 'device_pin', 'is_active', 'user_id', 'guru_id', 'siswa_id']),
            newValues: null,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent()
        );

        // Hapus dari device
        $this->syncService->enqueueDeleteUser($pin);

        // Hapus dari database
        $identity->delete();

        return redirect()->route('admin.absensi.users.index')
            ->with('success', "User {$pin} berhasil dihapus dan dijadwalkan untuk dihapus dari device");
    }

    /**
     * Tampil status sync untuk user tertentu
     */
    public function syncStatus(AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $status = $this->syncService->getSyncStatus($identity->device_pin);

        return view('attendance.users.sync-status', compact('identity', 'status'));
    }

    /**
     * Trigger manual sync semua user ke device
     */
    public function syncAll()
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $count = $this->syncService->syncAllUsers();

        return redirect()->route('admin.absensi.users.index')
            ->with('success', "{$count} user dijadwalkan untuk sync ke device");
    }

    /**
     * Tampil form import identity dari Excel/CSV
     */
    public function importForm()
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        return view('attendance.users.import');
    }

    /**
     * Proses import identity dari Excel/CSV
     */
    public function import(Request $request)
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        $request->validate([
            'file' => 'required|file|max:10240|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'File wajib diupload',
            'file.max'      => 'Ukuran file maksimal 10MB',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv',
        ]);

        try {
            $import = new AttendanceIdentityImport();
            Excel::import($import, $request->file('file'));

            $errors = $import->errors();
            $errorCount = $errors->count();
            $successCount = $import->getSuccessCount();

            if ($errorCount > 0 && $successCount === 0) {
                return back()->withErrors([
                    'import' => "Import gagal. Errors: " . $errors->first(),
                ])->withInput();
            }

            $message = "Import selesai: {$successCount} identity berhasil ditambahkan";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} baris memiliki error (dilewati)";
            }

            return redirect()->route('admin.absensi.users.index')
                ->with('success', $message);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = collect($failures)->map(fn($f) => 'Baris ' . $f->row() . ': ' . implode(', ', $f->errors()))->implode('; ');

            return back()->withErrors([
                'import' => "Import gagal validasi: {$errorMessages}",
            ])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'import' => "Terjadi kesalahan saat import: {$e->getMessage()}",
            ])->withInput();
        }
    }

    /**
     * Auto-map semua siswa/guru yang belum punya AttendanceIdentity
     */
    public function autoMap(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->requireAdminOrPermission('attendance.users.manage');

        try {
            $exitCode = Artisan::call('attendance:auto-map-identities');
            $output = Artisan::output();

            return redirect()->route('admin.absensi.users.index')
                ->with('success', "Auto-map selesai!\n" . $output);
        } catch (\Exception $e) {
            return redirect()->route('admin.absensi.users.index')
                ->with('error', "Auto-map gagal: {$e->getMessage()}");
        }
    }

    /**
     * Download template CSV untuk import absensi
     */
    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-absensi.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['device_pin', 'kind', 'reference_name']);
            fputcsv($file, ['1001', 'siswa', 'Nama Siswa']);
            fputcsv($file, ['9001', 'guru', 'Nama Guru']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


