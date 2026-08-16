<?php

namespace App\Http\Controllers;

use App\Models\AttendanceExcuse;
use App\Models\AttendanceIdentity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class AttendanceExcuseController extends BaseController
{
    /**
     * List semua izin/sakit dengan filter
     */
    public function index(Request $request)
    {
        $this->requireAdminOrPermission('attendance.excuses.view');

        $query = AttendanceExcuse::with(['identity.guru', 'identity.siswa', 'identity.user', 'approvedBy', 'creator']);

        // Filter berdasarkan jenis
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter berdasarkan tanggal spesifik
        if ($request->filled('date')) {
            $query->forDate($request->date);
        }

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('identity', function ($q) use ($search) {
                $q->whereHas('guru', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$search}%"))
                  ->orWhereHas('siswa', fn($q2) => $q2->where('nama_lengkap', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $excuses = $query->latest()->paginate(20)->withQueryString();

        return view('attendance.excuses.index', compact('excuses'));
    }

    /**
     * Form tambah izin/sakit
     */
    public function create()
    {
        $this->requireAdminOrPermission('attendance.excuses.create');

        $identities = AttendanceIdentity::where('is_active', true)
            ->with(['guru', 'siswa', 'user'])
            ->get()
            ->map(fn($i) => [
                'id' => $i->id,
                'label' => ($i->user?->name ?? $i->guru?->nama_lengkap ?? $i->siswa?->nama_lengkap ?? '-') . ' (' . $i->kind . ' - PIN: ' . $i->device_pin . ')',
            ]);

        return view('attendance.excuses.create', compact('identities'));
    }

    /**
     * Simpan izin/sakit baru
     */
    public function store(Request $request)
    {
        $this->requireAdminOrPermission('attendance.excuses.create');

        $validated = $request->validate([
            'attendance_identity_id' => 'required|exists:attendance_identities,id',
            'type'                   => 'required|in:izin,sakit,cuti,dinas,alpha',
            'date'                   => 'required|date',
            'reason'                 => 'required|string|min:3|max:1000',
            'attachment'             => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ], [
            'attendance_identity_id.required' => 'Pilih pengguna',
            'attendance_identity_id.exists'   => 'Pengguna tidak ditemukan',
            'type.required'                   => 'Jenis izin wajib diisi',
            'type.in'                         => 'Jenis izin tidak valid',
            'date.required'                   => 'Tanggal wajib diisi',
            'reason.required'                 => 'Alasan wajib diisi',
            'reason.min'                      => 'Alasan minimal 3 karakter',
            'reason.max'                      => 'Alasan maksimal 1000 karakter',
            'attachment.max'                  => 'Ukuran file maksimal 5MB',
            'attachment.mimes'                => 'Format file harus JPG, PNG, atau PDF',
        ]);

        // Cek duplikat
        $exists = AttendanceExcuse::where('attendance_identity_id', $validated['attendance_identity_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Sudah ada izin/sakit untuk tanggal ini'])->withInput();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('attendance/excuses', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        AttendanceExcuse::create($validated);

        return redirect()->route('admin.absensi.excuses.index')
            ->with('success', 'Izin/sakit berhasil ditambahkan');
    }

    /**
     * Detail izin/sakit
     */
    public function show(AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.view');

        $excuse->load(['identity.guru', 'identity.siswa', 'identity.user', 'approvedBy', 'creator']);

        return view('attendance.excuses.show', compact('excuse'));
    }

    /**
     * Form edit
     */
    public function edit(AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.create');

        $identities = AttendanceIdentity::where('is_active', true)
            ->with(['guru', 'siswa', 'user'])
            ->get()
            ->map(fn($i) => [
                'id' => $i->id,
                'label' => ($i->user?->name ?? $i->guru?->nama_lengkap ?? $i->siswa?->nama_lengkap ?? '-') . ' (' . $i->kind . ' - PIN: ' . $i->device_pin . ')',
            ]);

        return view('attendance.excuses.edit', compact('excuse', 'identities'));
    }

    /**
     * Update izin/sakit
     */
    public function update(Request $request, AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.create');

        $validated = $request->validate([
            'attendance_identity_id' => 'required|exists:attendance_identities,id',
            'type'                   => 'required|in:izin,sakit,cuti,dinas,alpha',
            'date'                   => 'required|date',
            'reason'                 => 'required|string|min:3|max:1000',
            'attachment'             => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ], [
            'attendance_identity_id.required' => 'Pilih pengguna',
            'type.required'                   => 'Jenis izin wajib diisi',
            'date.required'                   => 'Tanggal wajib diisi',
            'reason.required'                 => 'Alasan wajib diisi',
        ]);

        // Cek duplikat (kecuali record sendiri)
        $exists = AttendanceExcuse::where('attendance_identity_id', $validated['attendance_identity_id'])
            ->whereDate('date', $validated['date'])
            ->where('id', '!=', $excuse->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Sudah ada izin/sakit untuk tanggal ini'])->withInput();
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Hapus file lama
            if ($excuse->attachment_path) {
                Storage::disk('public')->delete($excuse->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('attendance/excuses', 'public');
        }

        // Reset status jika data diubah
        $validated['status'] = 'pending';
        $validated['approved_by'] = null;
        $validated['approved_at'] = null;
        $validated['rejection_reason'] = null;

        $excuse->update($validated);

        return redirect()->route('admin.absensi.excuses.show', $excuse)
            ->with('success', 'Izin/sakit berhasil diupdate');
    }

    /**
     * Hapus izin/sakit
     */
    public function destroy(AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.create');

        // Hapus file attachment
        if ($excuse->attachment_path) {
            Storage::disk('public')->delete($excuse->attachment_path);
        }

        $excuse->delete();

        return redirect()->route('admin.absensi.excuses.index')
            ->with('success', 'Izin/sakit berhasil dihapus');
    }

    /**
     * Approve izin/sakit
     */
    public function approve(AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.approve');

        $excuse->approve(auth()->id());

        return redirect()->back()
            ->with('success', 'Izin/sakit berhasil disetujui');
    }

    /**
     * Reject izin/sakit
     */
    public function reject(Request $request, AttendanceExcuse $excuse)
    {
        $this->requireAdminOrPermission('attendance.excuses.approve');

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:3|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi',
        ]);

        $excuse->reject(auth()->id(), $validated['rejection_reason']);

        return redirect()->back()
            ->with('success', 'Izin/sakit berhasil ditolak');
    }

    /**
     * Check permission (admin atau spesifik permission)
     */
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
