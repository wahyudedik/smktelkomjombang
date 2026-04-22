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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Get comprehensive statistics for all users (with caching for performance)
        $stats = cache()->remember('dashboard_stats_' . $user->id, 300, function () use ($user) {
            // Cache for 5 minutes to reduce database load
            return [
                'total_users' => User::count(),
                'total_roles' => Role::count(),
                'total_permissions' => Permission::count(),
                'total_siswa' => 0,
                'total_guru' => 0,
                'total_barang' => 0,
                'total_pages' => 0,
                'total_instagram_settings' => 0,
                'recent_activities' => collect(),
            ];
        });

        // Get recent activities (don't cache - needs to be fresh)
        try {
            $stats['recent_activities'] = AuditLog::with('user')
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $stats['recent_activities'] = collect();
        }

        // Get statistics for all users with error handling (cached counts)
        try {
            $stats['total_siswa'] = cache()->remember('count_siswa', 300, fn() => Siswa::count());
        } catch (\Exception $e) {
            $stats['total_siswa'] = 0;
        }

        try {
            $stats['total_guru'] = cache()->remember('count_guru', 300, fn() => Guru::count());
        } catch (\Exception $e) {
            $stats['total_guru'] = 0;
        }

        try {
            $stats['total_barang'] = cache()->remember('count_barang', 300, fn() => Barang::count());
        } catch (\Exception $e) {
            $stats['total_barang'] = 0;
        }

        try {
            $stats['total_pages'] = cache()->remember('count_pages', 300, fn() => Page::count());
        } catch (\Exception $e) {
            $stats['total_pages'] = 0;
        }

        try {
            $stats['total_instagram_settings'] = cache()->remember('count_instagram_settings', 300, fn() => InstagramSetting::count());
        } catch (\Exception $e) {
            $stats['total_instagram_settings'] = 0;
        }

        // Calculate module usage based on actual data and activity
        $moduleUsage = $this->calculateModuleUsage();

        // Calculate user growth data (last 6 months)
        $userGrowth = $this->calculateUserGrowth();

        // Always use the centralized admin dashboard
        return view('dashboards.admin', [
            'statistics' => $stats,
            'recentActivities' => $stats['recent_activities'],
            'moduleUsage' => $moduleUsage,
            'userGrowth' => $userGrowth
        ]);
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

        // Get activity counts for each module from audit logs (last 30 days)
        $activityCounts = [
            'users' => 0,
            'guru' => 0,
            'siswa' => 0,
            'sarpras' => 0,
            'osis' => 0,
        ];

        try {
            // Cache recent activities for better performance
            $recentActivities = cache()->remember('recent_activities_30days', 1800, function () {
                return AuditLog::where('created_at', '>=', now()->subDays(30))->get();
            });

            foreach ($recentActivities as $activity) {
                $action = strtolower($activity->action ?? '');

                if (str_contains($action, 'user')) {
                    $activityCounts['users']++;
                } elseif (str_contains($action, 'guru') || str_contains($action, 'teacher')) {
                    $activityCounts['guru']++;
                } elseif (str_contains($action, 'siswa') || str_contains($action, 'student')) {
                    $activityCounts['siswa']++;
                } elseif (str_contains($action, 'barang') || str_contains($action, 'sarpras') || str_contains($action, 'asset')) {
                    $activityCounts['sarpras']++;
                } elseif (str_contains($action, 'osis') || str_contains($action, 'calon') || str_contains($action, 'pemilih') || str_contains($action, 'voting')) {
                    $activityCounts['osis']++;
                }
            }
        } catch (\Exception $e) {
            // If audit logs fail, use default activity counts
        }

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
