<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Barang;
use App\Models\Page;
use App\Models\InstagramSetting;
use App\Models\Calon;
use App\Models\Pemilih;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Get comprehensive statistics (with caching for performance)
        $stats = cache()->remember('dashboard_stats_' . $user->id, 300, function () {
            return $this->fetchCounts();
        });

        // Get recent activities (don't cache — needs to be fresh)
        $stats['recent_activities'] = $this->safe(fn() => AuditLog::with('user')->latest()->limit(10)->get(), collect());

        // Calculate module usage and user growth
        $moduleUsage = $this->calculateModuleUsage();
        $userGrowth = $this->calculateUserGrowth();

        return view('dashboards.admin', [
            'statistics' => $stats,
            'recentActivities' => $stats['recent_activities'],
            'moduleUsage' => $moduleUsage,
            'userGrowth' => $userGrowth,
        ]);
    }

    /**
     * API endpoint for live dashboard stats (polled by frontend).
     */
    public function stats(): JsonResponse
    {
        $siswaCount = $this->safe(fn() => Siswa::count(), 0);
        $guruCount = $this->safe(fn() => Guru::count(), 0);
        $userCount = $this->safe(fn() => User::count(), 0);
        $barangCount = $this->safe(fn() => Barang::count(), 0);

        // Calculate trends (current vs previous month)
        $siswaTrend = $this->calculateTrend(Siswa::class);
        $guruTrend = $this->calculateTrend(Guru::class);

        return response()->json([
            'total_siswa' => $siswaCount,
            'total_guru' => $guruCount,
            'total_users' => $userCount,
            'total_barang' => $barangCount,
            'siswa_trend' => $siswaTrend,
            'guru_trend' => $guruTrend,
        ]);
    }

    /**
     * Calculate month-over-month trend percentage for a model.
     */
    private function calculateTrend(string $model): ?float
    {
        try {
            $current = $model::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth])->count();
            $previous = $model::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth])->count();

            if ($previous === 0) {
                return $current > 0 ? 100.0 : 0.0;
            }

            return round((($current - $previous) / $previous) * 100, 1);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetch all model counts in a single try-catch block.
     */
    private function fetchCounts(): array
    {
        $models = [
            'total_users' => User::class,
            'total_roles' => Role::class,
            'total_permissions' => Permission::class,
            'total_siswa' => Siswa::class,
            'total_guru' => Guru::class,
            'total_barang' => Barang::class,
            'total_pages' => Page::class,
            'total_instagram_settings' => InstagramSetting::class,
        ];

        $counts = [];
        foreach ($models as $key => $model) {
            $counts[$key] = $this->safe(fn() => $model::count(), 0);
        }
        $counts['recent_activities'] = collect();

        return $counts;
    }

    /**
     * Execute a callback safely; return fallback on failure.
     */
    private function safe(callable $callback, mixed $fallback = null): mixed
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    /**
     * Calculate module usage percentage based on data count and recent activity
     */
    private function calculateModuleUsage()
    {
        // Get counts for each module (with caching for performance)
        $counts = cache()->remember('module_usage_counts', 300, function () {
            return [
                'users' => User::count(),
                'guru' => Guru::count(),
                'siswa' => Siswa::count(),
                'sarpras' => Barang::count(),
                'osis' => Calon::count() + Pemilih::count(),
                'pages' => Page::count(),
            ];
        });

        // Performance: count activities at database level instead of loading all into memory
        $activityCounts = cache()->remember('module_activity_counts_30days', 1800, function () {
            $counts = [
                'users' => 0,
                'guru' => 0,
                'siswa' => 0,
                'sarpras' => 0,
                'osis' => 0,
            ];

            try {
                $actions = AuditLog::where('created_at', '>=', now()->subDays(30))
                    ->pluck('action')
                    ->map(fn($action) => strtolower($action ?? ''));

                foreach ($actions as $action) {
                    if (str_contains($action, 'user')) {
                        $counts['users']++;
                    } elseif (str_contains($action, 'guru') || str_contains($action, 'teacher')) {
                        $counts['guru']++;
                    } elseif (str_contains($action, 'siswa') || str_contains($action, 'student')) {
                        $counts['siswa']++;
                    } elseif (str_contains($action, 'barang') || str_contains($action, 'sarpras') || str_contains($action, 'asset')) {
                        $counts['sarpras']++;
                    } elseif (str_contains($action, 'osis') || str_contains($action, 'calon') || str_contains($action, 'pemilih') || str_contains($action, 'voting')) {
                        $counts['osis']++;
                    }
                }
            } catch (\Exception $e) {
                // If audit logs fail, use default counts
            }

            return $counts;
        });

        // Calculate total for percentage calculation
        $totalData = array_sum($counts);
        $totalActivity = array_sum($activityCounts);

        // Calculate percentage based on data count (70%) and activity (30%)
        $modules = [
            'User Management' => [
                'data_count' => $counts['users'],
                'activity_count' => $activityCounts['users'],
                'color' => 'blue',
            ],
            'Guru Management' => [
                'data_count' => $counts['guru'],
                'activity_count' => $activityCounts['guru'],
                'color' => 'green',
            ],
            'Siswa Management' => [
                'data_count' => $counts['siswa'],
                'activity_count' => $activityCounts['siswa'],
                'color' => 'purple',
            ],
            'Sarpras Management' => [
                'data_count' => $counts['sarpras'],
                'activity_count' => $activityCounts['sarpras'],
                'color' => 'orange',
            ],
            'OSIS System' => [
                'data_count' => $counts['osis'],
                'activity_count' => $activityCounts['osis'],
                'color' => 'pink',
            ],
        ];

        // Calculate percentage for each module
        foreach ($modules as $name => &$module) {
            $dataPercentage = $totalData > 0 ? ($module['data_count'] / $totalData) * 70 : 0;
            $activityPercentage = $totalActivity > 0 ? ($module['activity_count'] / $totalActivity) * 30 : 0;

            $module['percentage'] = round($dataPercentage + $activityPercentage);

            // Ensure minimum 5% if there's any data
            if ($module['data_count'] > 0 && $module['percentage'] < 5) {
                $module['percentage'] = 5;
            }

            // Cap at 100%
            if ($module['percentage'] > 100) {
                $module['percentage'] = 100;
            }
        }

        return $modules;
    }

    /**
     * Calculate user growth for the last 6 months
     */
    private function calculateUserGrowth()
    {
        $months = [];
        $siswaData = [];
        $guruData = [];

        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Count new siswa for this month
            try {
                $siswaCount = Siswa::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            } catch (\Exception $e) {
                $siswaCount = 0;
            }

            // Count new guru for this month
            try {
                $guruCount = Guru::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            } catch (\Exception $e) {
                $guruCount = 0;
            }

            $months[] = $monthName;
            $siswaData[] = $siswaCount;
            $guruData[] = $guruCount;
        }

        // Find max value for percentage calculation
        $maxValue = max(array_merge($siswaData, $guruData));
        if ($maxValue == 0) {
            $maxValue = 1; // Prevent division by zero
        }

        // Calculate percentage for each month (relative to max)
        $chartData = [];
        for ($i = 0; $i < count($months); $i++) {
            $siswaPercentage = $maxValue > 0 ? ($siswaData[$i] / $maxValue) * 100 : 0;
            $guruPercentage = $maxValue > 0 ? ($guruData[$i] / $maxValue) * 100 : 0;

            $chartData[] = [
                'month' => $months[$i],
                'siswa' => [
                    'count' => $siswaData[$i],
                    'percentage' => round($siswaPercentage)
                ],
                'guru' => [
                    'count' => $guruData[$i],
                    'percentage' => round($guruPercentage)
                ]
            ];
        }

        return [
            'data' => $chartData,
            'max_value' => $maxValue,
            'total_siswa' => array_sum($siswaData),
            'total_guru' => array_sum($guruData)
        ];
    }
}
