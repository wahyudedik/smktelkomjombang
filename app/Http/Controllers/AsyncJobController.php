<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExcelImportJob;
use App\Jobs\GenerateExcelExportJob;
use App\Models\AsyncJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AsyncJobController extends Controller
{
    /**
     * Display list of user's async jobs.
     */
    public function index(Request $request)
    {
        $jobs = AsyncJob::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.async-jobs.index', compact('jobs'));
    }

    /**
     * Get job status (AJAX polling).
     */
    public function status(AsyncJob $job): JsonResponse
    {
        // Ensure user can only check their own jobs
        if ($job->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $job->id,
            'status' => $job->status,
            'progress' => $this->getProgressText($job),
            'duration' => $job->duration,
            'result' => $job->result,
            'error_message' => $job->error_message,
            'is_downloadable' => $job->isDownloadable(),
            'download_url' => $job->isDownloadable()
                ? route('admin.async-jobs.download', $job->id)
                : null,
        ]);
    }

    /**
     * Download the export file.
     */
    public function download(AsyncJob $job)
    {
        // Ensure user can only download their own files
        if ($job->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$job->isDownloadable()) {
            abort(404, 'File tidak tersedia atau sudah expired.');
        }

        $filePath = storage_path('app/' . $job->result['path']);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filename = $job->result['filename'] ?? 'export.xlsx';

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Cancel a pending or running job.
     */
    public function cancel(AsyncJob $job): JsonResponse
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$job->isPending() && !$job->isRunning()) {
            return response()->json([
                'error' => 'Hanya job pending atau running yang bisa dibatalkan.',
            ], 422);
        }

        // Delete from queue if still pending
        if ($job->isPending()) {
            $job->markFailed('Dibatalkan oleh pengguna');
        } else {
            return response()->json([
                'error' => 'Job yang sedang berjalan tidak bisa dibatalkan secara langsung.',
            ], 422);
        }

        return response()->json(['success' => true, 'message' => 'Job berhasil dibatalkan.']);
    }

    /**
     * Dispatch import job.
     */
    public function dispatchImport(Request $request, string $module): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imports', $filename, 'local');

        $asyncJob = AsyncJob::create([
            'user_id' => Auth::id(),
            'type' => AsyncJob::TYPE_IMPORT,
            'module' => $module,
            'status' => AsyncJob::STATUS_PENDING,
            'payload' => [
                'original_filename' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ],
        ]);

        ProcessExcelImportJob::dispatch($asyncJob->id, $module, storage_path('app/' . $filePath))
            ->onQueue('imports');

        Log::info("Import job dispatched", [
            'async_job_id' => $asyncJob->id,
            'module' => $module,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Import sedang diproses di background. Anda akan mendapat notifikasi saat selesai.',
            'job_id' => $asyncJob->id,
            'status_url' => route('admin.async-jobs.status', $asyncJob->id),
        ]);
    }

    /**
     * Dispatch export job.
     */
    public function dispatchExport(Request $request, string $module): JsonResponse
    {
        $validated = $request->validate([
            'filters' => 'nullable|array',
        ]);

        $asyncJob = AsyncJob::create([
            'user_id' => Auth::id(),
            'type' => AsyncJob::TYPE_EXPORT,
            'module' => $module,
            'status' => AsyncJob::STATUS_PENDING,
            'payload' => [
                'filters' => $request->input('filters', []),
            ],
        ]);

        GenerateExcelExportJob::dispatch($asyncJob->id, $module, $request->input('filters', []))
            ->onQueue('exports');

        Log::info("Export job dispatched", [
            'async_job_id' => $asyncJob->id,
            'module' => $module,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Export sedang diproses di background. Anda akan mendapat notifikasi saat selesai.',
            'job_id' => $asyncJob->id,
            'status_url' => route('admin.async-jobs.status', $asyncJob->id),
        ]);
    }

    /**
     * Clean up old completed jobs (called by scheduler).
     */
    public function cleanup(): void
    {
        // Delete jobs older than 7 days
        AsyncJob::where('created_at', '<', now()->subDays(7))
            ->each(function ($job) {
                // Delete associated files
                if (isset($job->result['path'])) {
                    Storage::disk('local')->delete($job->result['path']);
                }
                $job->delete();
            });

        Log::info('Async jobs cleanup completed');
    }

    /**
     * Get human-readable progress text.
     */
    private function getProgressText(AsyncJob $job): string
    {
        return match ($job->status) {
            AsyncJob::STATUS_PENDING => 'Menunggu diproses...',
            AsyncJob::STATUS_RUNNING => 'Sedang diproses...',
            AsyncJob::STATUS_COMPLETED => 'Selesai',
            AsyncJob::STATUS_FAILED => 'Gagal: ' . ($job->error_message ?? 'Unknown error'),
            default => 'Unknown',
        };
    }
}
