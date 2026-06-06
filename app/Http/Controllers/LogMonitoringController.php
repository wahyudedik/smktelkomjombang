<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogMonitoringController extends Controller
{
    /**
     * Display list of all log files
     */
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        $logFiles = [];

        if (File::isDirectory($logPath)) {
            $files = File::files($logPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getRealPath(),
                        'size' => $this->formatBytes($file->getSize()),
                        'size_bytes' => $file->getSize(),
                        'modified' => $file->getMTime(),
                        'modified_human' => date('Y-m-d H:i:s', $file->getMTime()),
                        'errors' => $this->countErrors($file->getRealPath(), $file->getSize()),
                        'warnings' => $this->countWarnings($file->getRealPath(), $file->getSize()),
                    ];
                }
            }
        }

        // Sort by modified time descending (newest first)
        usort($logFiles, function ($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        // Stats
        $totalSize = array_sum(array_column($logFiles, 'size_bytes'));
        $totalErrors = array_sum(array_column($logFiles, 'errors'));
        $totalWarnings = array_sum(array_column($logFiles, 'warnings'));

        $stats = [
            'total_files' => count($logFiles),
            'total_size' => $this->formatBytes($totalSize),
            'total_errors' => $totalErrors,
            'total_warnings' => $totalWarnings,
        ];

        return view('log-monitoring.index', compact('logFiles', 'stats'));
    }

    /**
     * Display single log file contents with filtering
     */
    public function show(Request $request, string $filename)
    {
        $logPath = storage_path('logs/' . $filename);

        // Security: prevent path traversal
        $realPath = realpath($logPath);
        $logsRealPath = realpath(storage_path('logs'));
        if (!$realPath || !$logsRealPath || !str_starts_with($realPath, $logsRealPath)) {
            abort(404);
        }

        if (!File::exists($logPath)) {
            abort(404);
        }

        $file = new \SplFileInfo($logPath);
        $fileSize = $file->getSize();
        $maxDisplaySize = 5 * 1024 * 1024; // 5MB max display

        // Read the log file
        $content = '';
        if ($fileSize <= $maxDisplaySize) {
            $content = File::get($logPath);
        } else {
            // Read last 5MB for large files
            $handle = fopen($logPath, 'r');
            fseek($handle, $fileSize - $maxDisplaySize);
            $content = fread($handle, $maxDisplaySize);
            fclose($handle);
            $content = "[... File terlalu besar, menampilkan 5MB terakhir ...]\n\n" . $content;
        }

        // Filter by level
        $level = $request->get('level', '');
        $search = $request->get('search', '');
        $lines = explode("\n", $content);

        if ($level || $search) {
            $filteredLines = [];
            foreach ($lines as $line) {
                $matchLevel = !$level || stripos($line, $level) !== false;
                $matchSearch = !$search || stripos($line, $search) !== false;
                if ($matchLevel && $matchSearch) {
                    $filteredLines[] = $line;
                }
            }
            $lines = $filteredLines;
        }

        // Count log levels
        $levelCounts = [
            'emergency' => 0,
            'alert' => 0,
            'critical' => 0,
            'error' => 0,
            'warning' => 0,
            'notice' => 0,
            'info' => 0,
            'debug' => 0,
        ];

        foreach ($lines as $line) {
            foreach (array_keys($levelCounts) as $lvl) {
                if (stripos($line, "level.{$lvl}") !== false || stripos($line, strtoupper($lvl)) !== false) {
                    $levelCounts[$lvl]++;
                }
            }
        }

        $logData = [
            'filename' => $filename,
            'path' => $logPath,
            'size' => $this->formatBytes($fileSize),
            'modified' => date('Y-m-d H:i:s', $file->getMTime()),
            'total_lines' => count($lines),
            'content' => $content,
            'lines' => $lines,
            'levelCounts' => $levelCounts,
        ];

        return view('log-monitoring.show', compact('logData', 'level', 'search'));
    }

    /**
     * Download a log file
     */
    public function download(string $filename)
    {
        $logPath = storage_path('logs/' . $filename);

        $realPath = realpath($logPath);
        $logsRealPath = realpath(storage_path('logs'));
        if (!$realPath || !$logsRealPath || !str_starts_with($realPath, $logsRealPath)) {
            abort(404);
        }

        if (!File::exists($logPath)) {
            abort(404);
        }

        return response()->download($logPath, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Delete a log file
     */
    public function destroy(string $filename)
    {
        $logPath = storage_path('logs/' . $filename);

        $realPath = realpath($logPath);
        $logsRealPath = realpath(storage_path('logs'));
        if (!$realPath || !$logsRealPath || !str_starts_with($realPath, $logsRealPath)) {
            abort(404);
        }

        if (!File::exists($logPath)) {
            abort(404);
        }

        // Don't allow deleting the active log
        $activeLog = config('app.log') ?: 'laravel.log';
        if ($filename === $activeLog) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus log yang sedang aktif.']);
        }

        File::delete($logPath);

        return redirect()->route('admin.log-monitoring.index')
            ->with('success', "Log file '{$filename}' berhasil dihapus.");
    }

    /**
     * Clear (truncate) a log file
     */
    public function clear(string $filename)
    {
        $logPath = storage_path('logs/' . $filename);

        $realPath = realpath($logPath);
        $logsRealPath = realpath(storage_path('logs'));
        if (!$realPath || !$logsRealPath || !str_starts_with($realPath, $logsRealPath)) {
            abort(404);
        }

        if (!File::exists($logPath)) {
            abort(404);
        }

        File::put($logPath, '');

        return redirect()->route('admin.log-monitoring.show', $filename)
            ->with('success', "Log file '{$filename}' berhasil dikosongkan.");
    }

    /**
     * Count error-level lines in a log file (reads only last 1MB for performance)
     */
    private function countErrors(string $filePath, int $fileSize = 0): int
    {
        try {
            $maxRead = 1024 * 1024; // 1MB max for counting
            $content = '';
            if ($fileSize > $maxRead) {
                $handle = fopen($filePath, 'r');
                if ($handle) {
                    fseek($handle, $fileSize - $maxRead);
                    $content = fread($handle, $maxRead);
                    fclose($handle);
                }
            } else {
                $content = File::get($filePath);
            }

            $lines = explode("\n", $content);
            $count = 0;
            foreach ($lines as $line) {
                if (stripos($line, 'level.error') !== false ||
                    stripos($line, 'level.critical') !== false ||
                    stripos($line, 'level.alert') !== false ||
                    stripos($line, 'level.emergency') !== false) {
                    $count++;
                }
            }
            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Count warning-level lines in a log file (reads only last 1MB for performance)
     */
    private function countWarnings(string $filePath, int $fileSize = 0): int
    {
        try {
            $maxRead = 1024 * 1024; // 1MB max for counting
            $content = '';
            if ($fileSize > $maxRead) {
                $handle = fopen($filePath, 'r');
                if ($handle) {
                    fseek($handle, $fileSize - $maxRead);
                    $content = fread($handle, $maxRead);
                    fclose($handle);
                }
            } else {
                $content = File::get($filePath);
            }

            $lines = explode("\n", $content);
            $count = 0;
            foreach ($lines as $line) {
                if (stripos($line, 'level.warning') !== false) {
                    $count++;
                }
            }
            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
