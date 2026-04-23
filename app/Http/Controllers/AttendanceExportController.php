<?php

namespace App\Http\Controllers;

use App\Models\AttendanceIdentity;
use App\Services\AttendanceExportService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

/**
 * Controller untuk export rekap absensi ke Excel
 */
class AttendanceExportController extends BaseController
{
    public function __construct(
        private readonly AttendanceExportService $exportService,
    ) {}

    /**
     * Tampil form export
     */
    public function index()
    {
        $this->requireAdminOrPermission('attendance.export');

        return view('attendance.export.index');
    }

    /**
     * Export rekap harian ke Excel
     */
    public function exportDaily(Request $request)
    {
        $this->requireAdminOrPermission('attendance.export');

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $data = $this->exportService->exportDaily($validated['date']);
        $fileName = "Rekap-Absensi-{$validated['date']}.xlsx";

        return Excel::download(
            new \App\Exports\AttendanceExport($data),
            $fileName,
            ExcelWriter::XLSX
        );
    }

    /**
     * Export rekap periode ke Excel
     */
    public function exportPeriod(Request $request)
    {
        $this->requireAdminOrPermission('attendance.export');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'group_by' => 'required|in:daily,weekly,monthly',
        ]);

        $data = $this->exportService->exportPeriod(
            $validated['start_date'],
            $validated['end_date'],
            $validated['group_by']
        );

        $fileName = "Rekap-Absensi-{$validated['start_date']}-{$validated['end_date']}.xlsx";

        return Excel::download(
            new \App\Exports\AttendanceExport($data),
            $fileName,
            ExcelWriter::XLSX
        );
    }

    /**
     * Export summary per user
     */
    public function exportSummary(Request $request)
    {
        $this->requireAdminOrPermission('attendance.export');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $data = $this->exportService->exportSummary(
            $validated['start_date'],
            $validated['end_date']
        );

        $fileName = "Summary-Absensi-{$validated['start_date']}-{$validated['end_date']}.xlsx";

        return Excel::download(
            new \App\Exports\AttendanceExport($data),
            $fileName,
            ExcelWriter::XLSX
        );
    }

    /**
     * Export detail user
     */
    public function exportUserDetail(Request $request, AttendanceIdentity $identity)
    {
        $this->requireAdminOrPermission('attendance.export');

        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $data = $this->exportService->exportUserDetail(
            $identity->id,
            $validated['start_date'],
            $validated['end_date']
        );

        $nama = $identity->user?->name 
            ?? $identity->guru?->nama_lengkap 
            ?? $identity->siswa?->nama_lengkap 
            ?? 'User';

        $fileName = "Detail-{$nama}-{$validated['start_date']}-{$validated['end_date']}.xlsx";

        return Excel::download(
            new \App\Exports\AttendanceExport($data),
            $fileName,
            ExcelWriter::XLSX
        );
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
