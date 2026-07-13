<?php

namespace App\Jobs;

use App\Models\AsyncJob;
use App\Imports\UserImport;
use App\Imports\SiswaImport;
use App\Imports\GuruImport;
use App\Imports\BarangImport;
use App\Imports\CalonImport;
use App\Imports\PemilihImport;
use App\Imports\KelulusanImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessExcelImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $asyncJobId,
        public readonly string $module,
        public readonly string $filePath,
    ) {
        $this->onQueue('imports');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $asyncJob = AsyncJob::findOrFail($this->asyncJobId);

        try {
            $asyncJob->markRunning();

            Log::info("Import job started", [
                'async_job_id' => $this->asyncJobId,
                'module' => $this->module,
                'file' => $this->filePath,
            ]);

            $import = $this->resolveImportClass();
            Excel::import($import, $this->filePath);

            $result = [
                'module' => $this->module,
                'imported' => $import->getRowCount() ?? 0,
                'errors' => $import->errors() ?? [],
            ];

            $asyncJob->markCompleted($result);

            Log::info("Import job completed", [
                'async_job_id' => $this->asyncJobId,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error("Import job failed", [
                'async_job_id' => $this->asyncJobId,
                'module' => $this->module,
                'error' => $e->getMessage(),
            ]);

            $asyncJob->markFailed($e->getMessage());

            throw $e;
        }
    }

    /**
     * Resolve the import class based on module type.
     */
    private function resolveImportClass(): object
    {
        return match ($this->module) {
            'users' => new UserImport(),
            'siswa' => new SiswaImport(),
            'guru' => new GuruImport(),
            'barang' => new BarangImport(),
            'calon' => new CalonImport(),
            'pemilih' => new PemilihImport(),
            'kelulusan' => new KelulusanImport(),
            default => throw new \InvalidArgumentException("Unsupported module: {$this->module}"),
        };
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $asyncJob = AsyncJob::find($this->asyncJobId);
        if ($asyncJob && !$asyncJob->isFailed()) {
            $asyncJob->markFailed($exception->getMessage());
        }
    }
}
