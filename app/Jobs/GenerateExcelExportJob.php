<?php

namespace App\Jobs;

use App\Models\AsyncJob;
use App\Exports\UserExport;
use App\Exports\SiswaExport;
use App\Exports\GuruExport;
use App\Exports\BarangExport;
use App\Exports\CalonExport;
use App\Exports\PemilihExport;
use App\Exports\KelulusanExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateExcelExportJob implements ShouldQueue
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
        public array $filters = [],
    ) {
        $this->onQueue('exports');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $asyncJob = AsyncJob::findOrFail($this->asyncJobId);

        try {
            $asyncJob->markRunning();

            Log::info("Export job started", [
                'async_job_id' => $this->asyncJobId,
                'module' => $this->module,
            ]);

            $filename = $this->module . '-export-' . date('Y-m-d_His') . '.xlsx';
            $exportPath = 'exports/' . $filename;

            $export = $this->resolveExportClass();

            // Store the export file to local disk
            Excel::store($export, $exportPath, 'local');

            $result = [
                'module' => $this->module,
                'filename' => $filename,
                'path' => $exportPath,
                'download_url' => route('admin.async-jobs.download', ['id' => $this->asyncJobId]),
            ];

            $asyncJob->markCompleted($result);

            Log::info("Export job completed", [
                'async_job_id' => $this->asyncJobId,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error("Export job failed", [
                'async_job_id' => $this->asyncJobId,
                'module' => $this->module,
                'error' => $e->getMessage(),
            ]);

            $asyncJob->markFailed($e->getMessage());

            throw $e;
        }
    }

    /**
     * Resolve the export class based on module type.
     */
    private function resolveExportClass(): object
    {
        $modelMap = [
            'users' => fn() => new UserExport($this->getUserExportData()),
            'siswa' => fn() => new SiswaExport($this->getSiswaExportData()),
            'guru' => fn() => new GuruExport($this->getGuruExportData()),
            'barang' => fn() => new BarangExport($this->getBarangExportData()),
            'calon' => fn() => new CalonExport($this->getCalonExportData()),
            'pemilih' => fn() => new PemilihExport($this->getPemilihExportData()),
            'kelulusan' => fn() => new KelulusanExport($this->getKelulusanExportData()),
        ];

        if (!isset($modelMap[$this->module])) {
            throw new \InvalidArgumentException("Unsupported export module: {$this->module}");
        }

        return $modelMap[$this->module]();
    }

    /**
     * Get export data for users.
     */
    private function getUserExportData(): \Illuminate\Support\Collection
    {
        return \App\Models\User::when(!empty($this->filters['role']), fn($q, $role) => $q->where('role', $role))
            ->get();
    }

    /**
     * Get export data for siswa.
     */
    private function getSiswaExportData(): \Illuminate\Support\Collection
    {
        $query = \App\Models\Siswa::query();

        if (!empty($this->filters['kelas'])) {
            $query->where('kelas', $this->filters['kelas']);
        }
        if (!empty($this->filters['jurusan'])) {
            $query->where('jurusan', $this->filters['jurusan']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
    }

    /**
     * Get export data for guru.
     */
    private function getGuruExportData(): \Illuminate\Support\Collection
    {
        return \App\Models\Guru::when(!empty($this->filters['status_aktif']), fn($q, $status) => $q->where('status_aktif', $status))
            ->get();
    }

    /**
     * Get export data for barang.
     */
    private function getBarangExportData(): \Illuminate\Support\Collection
    {
        $query = \App\Models\Barang::with('kategori', 'ruang');

        if (!empty($this->filters['kategori_id'])) {
            $query->where('kategori_id', $this->filters['kategori_id']);
        }
        if (!empty($this->filters['kondisi'])) {
            $query->where('kondisi', $this->filters['kondisi']);
        }

        return $query->get();
    }

    /**
     * Get export data for calon.
     */
    private function getCalonExportData(): \Illuminate\Support\Collection
    {
        return \App\Models\Calon::get();
    }

    /**
     * Get export data for pemilih.
     */
    private function getPemilihExportData(): \Illuminate\Support\Collection
    {
        return \App\Models\Pemilih::get();
    }

    /**
     * Get export data for kelulusan.
     */
    private function getKelulusanExportData(): \Illuminate\Support\Collection
    {
        $query = \App\Models\Kelulusan::query();

        if (!empty($this->filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $this->filters['tahun_ajaran']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
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
