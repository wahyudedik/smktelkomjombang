<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Barang;
use App\Models\Voting;
use App\Models\Kelulusan;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Maintenance;
use App\Models\AuditLog;
use App\Models\JadwalPelajaran;
use App\Models\Page;
use App\Models\InstagramSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request (default: last 30 days)
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $dateRange = [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];

        $analytics = [
            'overview' => $this->getOverviewStats(),
            'user_activity' => $this->getUserActivity($dateRange),
            'module_usage' => $this->getModuleUsage(),
            'trends' => $this->getTrendsData($dateRange),
            'audit_analytics' => $this->getAuditAnalytics($dateRange),
            'performance' => $this->getPerformanceMetrics($dateRange),
            'engagement' => $this->getEngagementMetrics($dateRange),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];

        return view('analytics.dashboard', compact('analytics'));
    }

    /**
     * API endpoint for real-time analytics data
     */
    public function getData(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $dateRange = [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];

        $type = $request->get('type', 'overview');

        $data = match ($type) {
            'trends' => $this->getTrendsData($dateRange),
            'audit' => $this->getAuditAnalytics($dateRange),
            'performance' => $this->getPerformanceMetrics($dateRange),
            'engagement' => $this->getEngagementMetrics($dateRange),
            'overview' => $this->getOverviewStats(),
            default => [],
        };

        return response()->json([
            'success' => true,
            'data' => $data,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'json');
        $type = $request->get('type', 'full');

        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $dateRange = [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];

        $analytics = [
            'overview' => $this->getOverviewStats(),
            'user_activity' => $this->getUserActivity($dateRange),
            'module_usage' => $this->getModuleUsage(),
            'trends' => $this->getTrendsData($dateRange),
            'audit_analytics' => $this->getAuditAnalytics($dateRange),
            'performance' => $this->getPerformanceMetrics($dateRange),
            'engagement' => $this->getEngagementMetrics($dateRange),
            'exported_at' => now()->toIso8601String(),
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];

        if ($format === 'json') {
            return response()->json($analytics, 200, [], JSON_PRETTY_PRINT)
                ->header('Content-Disposition', 'attachment; filename="analytics_' . now()->format('Y-m-d') . '.json"');
        }

        if ($format === 'csv') {
            // Simple CSV export for key metrics
            $csv = "Metric,Value\n";
            $csv .= "Total Users," . $analytics['overview']['total_users'] . "\n";
            $csv .= "Total Students," . $analytics['overview']['total_students'] . "\n";
            $csv .= "Total Teachers," . $analytics['overview']['total_teachers'] . "\n";
            $csv .= "Total Assets," . $analytics['overview']['total_assets'] . "\n";
            $csv .= "Total Votes," . $analytics['overview']['total_votes'] . "\n";
            $csv .= "Graduated Students," . $analytics['overview']['graduated_students'] . "\n";

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="analytics_' . now()->format('Y-m-d') . '.csv"');
        }

        return response()->json(['error' => 'Unsupported format'], 400);
    }

    private function getOverviewStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_students' => Siswa::count(),
            'total_teachers' => Guru::count(),
            'total_assets' => Barang::count(),
            'total_votes' => Voting::count(),
            'graduated_students' => Kelulusan::where('status', 'lulus')->count(),
        ];
    }

    private function getUserActivity(?array $dateRange = null): array
    {
        if (!$dateRange) {
            $dateRange = [
                'start' => Carbon::now()->subDays(30)->startOfDay(),
                'end' => Carbon::now()->endOfDay(),
            ];
        }

        $lastWeek = Carbon::now()->subWeek();
        $lastMonth = Carbon::now()->subMonth();
        $last24Hours = Carbon::now()->subDay();

        return [
            'invited_users_this_week' => User::where('created_at', '>=', $lastWeek)->count(),
            'invited_users_this_month' => User::where('created_at', '>=', $lastMonth)->count(),
            'invited_users_last_24h' => User::where('created_at', '>=', $last24Hours)->count(),
            'invited_users_in_range' => User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'user_distribution' => $this->getUserDistribution(),
            'active_users' => $this->getActiveUsers($dateRange),
            'user_growth' => $this->getUserGrowth($dateRange),
        ];
    }

    private function getModuleUsage(): array
    {
        return [
            'e_osis' => [
                'total_candidates' => Calon::count(),
                'total_voters' => Pemilih::count(),
                'voting_participation' => $this->getVotingParticipation()
            ],
            'e_lulus' => [
                'total_graduates' => Kelulusan::where('status', 'lulus')->count(),
                'pending_verification' => Kelulusan::where('status', 'pending')->count(),
            ],
            'sarpras' => [
                'total_assets' => Barang::count(),
                'maintenance_due' => Maintenance::where('tanggal_maintenance', '<=', now()->addDays(7))->count(),
                'assets_by_condition' => $this->getAssetsByCondition()
            ],
        ];
    }

    private function getTrendsData(?array $dateRange = null): array
    {
        if (!$dateRange) {
            $dateRange = [
                'start' => Carbon::now()->subDays(30)->startOfDay(),
                'end' => Carbon::now()->endOfDay(),
            ];
        }

        $daysDiff = $dateRange['start']->diffInDays($dateRange['end']);
        $daysCount = min($daysDiff, 365); // Max 365 days

        $days = collect(range(0, $daysCount - 1))->map(function ($day) use ($dateRange) {
            return $dateRange['start']->copy()->addDays($day);
        });

        return [
            'user_invitations' => $this->getUserInvitationTrend($days),
            'module_usage' => $this->getModuleUsageTrend($days),
            'audit_activity' => $this->getAuditActivityTrend($days),
            'page_views' => $this->getPageViewsTrend($days),
        ];
    }

    private function getUserDistribution(): array
    {
        $roles = ['superadmin', 'admin', 'guru', 'siswa', 'sarpras'];
        $distribution = [];

        foreach ($roles as $roleName) {
            try {
                $distribution[$roleName] = User::role($roleName)->count();
            } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
                // Role doesn't exist yet, return 0
                $distribution[$roleName] = 0;
            }
        }

        return $distribution;
    }

    private function getVotingParticipation(): float
    {
        $totalVoters = Pemilih::count();
        $totalVotes = Voting::count();

        return $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 2) : 0;
    }

    private function getAssetsByCondition(): array
    {
        return [
            'baik' => Barang::where('kondisi', 'baik')->count(),
            'rusak' => Barang::where('kondisi', 'rusak')->count(),
            'hilang' => Barang::where('kondisi', 'hilang')->count()
        ];
    }

    private function getUserInvitationTrend($days): array
    {
        return $days->map(function ($day) {
            return [
                'date' => $day->format('M d'),
                'count' => User::whereDate('created_at', $day)->count()
            ];
        })->toArray();
    }

    private function getModuleUsageTrend($days): array
    {
        return $days->map(function ($day) {
            return [
                'date' => $day->format('M d'),
                'voting' => Voting::whereDate('created_at', $day)->count(),
                'graduation' => Kelulusan::whereDate('created_at', $day)->count(),
                'assets' => Barang::whereDate('created_at', $day)->count(),
                'students' => Siswa::whereDate('created_at', $day)->count(),
                'teachers' => Guru::whereDate('created_at', $day)->count(),
            ];
        })->toArray();
    }

    /**
     * Get audit analytics
     */
    private function getAuditAnalytics(array $dateRange): array
    {
        $audits = AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);

        return [
            'total_actions' => $audits->count(),
            'actions_by_type' => $this->getActionsByType($dateRange),
            'actions_by_user' => $this->getActionsByUser($dateRange),
            'actions_by_model' => $this->getActionsByModel($dateRange),
            'most_active_users' => $this->getMostActiveUsers($dateRange),
            'peak_hours' => $this->getPeakActivityHours($dateRange),
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(array $dateRange): array
    {
        return [
            'database_performance' => $this->getDatabasePerformance(),
            'system_health' => $this->getSystemHealth(),
            'module_efficiency' => $this->getModuleEfficiency(),
            'response_time' => $this->getAverageResponseTime($dateRange),
        ];
    }

    /**
     * Get engagement metrics
     */
    private function getEngagementMetrics(array $dateRange): array
    {
        return [
            'voting_engagement' => $this->getVotingEngagement(),
            'module_adoption' => $this->getModuleAdoption(),
            'user_retention' => $this->getUserRetention($dateRange),
            'feature_usage' => $this->getFeatureUsage($dateRange),
        ];
    }

    /**
     * Get active users in date range
     */
    private function getActiveUsers(array $dateRange): int
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Get user growth trend
     */
    private function getUserGrowth(array $dateRange): array
    {
        $startCount = User::where('created_at', '<', $dateRange['start'])->count();
        $endCount = User::where('created_at', '<=', $dateRange['end'])->count();
        $newUsers = User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();

        return [
            'start_count' => $startCount,
            'end_count' => $endCount,
            'new_users' => $newUsers,
            'growth_rate' => $startCount > 0 ? round((($endCount - $startCount) / $startCount) * 100, 2) : 0,
        ];
    }

    /**
     * Get actions by type
     */
    private function getActionsByType(array $dateRange): array
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderByDesc('count')
            ->pluck('count', 'action')
            ->toArray();
    }

    /**
     * Get actions by user
     */
    private function getActionsByUser(array $dateRange): array
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->with('user:id,name,email')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'user_id' => $log->user_id,
                    'user_name' => $log->user->name ?? 'Unknown',
                    'count' => $log->count,
                ];
            })
            ->toArray();
    }

    /**
     * Get actions by model
     */
    private function getActionsByModel(array $dateRange): array
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('model_type', DB::raw('count(*) as count'))
            ->whereNotNull('model_type')
            ->groupBy('model_type')
            ->orderByDesc('count')
            ->pluck('count', 'model_type')
            ->toArray();
    }

    /**
     * Get most active users
     */
    private function getMostActiveUsers(array $dateRange, int $limit = 10): array
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select('user_id', DB::raw('count(*) as activity_count'))
            ->groupBy('user_id')
            ->with('user:id,name,email')
            ->orderByDesc('activity_count')
            ->limit($limit)
            ->get()
            ->map(function ($log) {
                return [
                    'user_id' => $log->user_id,
                    'name' => $log->user->name ?? 'Unknown',
                    'email' => $log->user->email ?? '',
                    'activity_count' => $log->activity_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get peak activity hours
     */
    private function getPeakActivityHours(array $dateRange): array
    {
        return AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
    }

    /**
     * Get audit activity trend
     */
    private function getAuditActivityTrend($days): array
    {
        // Return empty array if days collection is empty
        if ($days->isEmpty()) {
            return [];
        }

        // Cache for 15 minutes to improve performance
        $firstDay = $days->first();
        $lastDay = $days->last();
        $cacheKey = 'audit_activity_trend_' . ($firstDay ? $firstDay->format('Y-m-d') : '') . '_' . ($lastDay ? $lastDay->format('Y-m-d') : '');

        return cache()->remember($cacheKey, 900, function () use ($days) {
            return $days->map(function ($day) {
                return [
                    'date' => $day->format('M d'),
                    'count' => AuditLog::whereDate('created_at', $day)->count(),
                ];
            })->toArray();
        });
    }

    /**
     * Get page views trend (using audit logs for page views)
     */
    private function getPageViewsTrend($days): array
    {
        return $days->map(function ($day) {
            return [
                'date' => $day->format('M d'),
                'count' => Page::whereDate('created_at', $day)->count() +
                    AuditLog::whereDate('created_at', $day)
                    ->where('action', 'read')
                    ->where('model_type', 'App\Models\Page')
                    ->count(),
            ];
        })->toArray();
    }

    /**
     * Get database performance metrics
     */
    private function getDatabasePerformance(): array
    {
        return [
            'total_tables' => DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE()")[0]->count ?? 0,
            'largest_tables' => $this->getLargestTables(),
        ];
    }

    /**
     * Get largest tables
     */
    private function getLargestTables(int $limit = 5): array
    {
        $tables = ['users', 'audit_logs', 'siswas', 'gurus', 'barang', 'votings', 'kelulusans'];
        $result = [];

        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $result[] = [
                    'table' => $table,
                    'count' => $count,
                ];
            } catch (\Exception $e) {
                // Table doesn't exist
                continue;
            }
        }

        usort($result, fn($a, $b) => $b['count'] <=> $a['count']);

        return array_slice($result, 0, $limit);
    }

    /**
     * Get system health metrics
     */
    private function getSystemHealth(): array
    {
        return [
            'total_modules' => 6, // OSIS, Kelulusan, Sarpras, Instagram, Pages, Users
            'active_modules' => $this->getActiveModules(),
            'system_status' => 'healthy',
        ];
    }

    /**
     * Get active modules count
     */
    private function getActiveModules(): int
    {
        $modules = 0;

        if (Calon::count() > 0 || Voting::count() > 0) $modules++;
        if (Kelulusan::count() > 0) $modules++;
        if (Barang::count() > 0) $modules++;
        if (InstagramSetting::where('is_active', true)->exists()) $modules++;
        if (Page::count() > 0) $modules++;
        if (User::count() > 0) $modules++;

        return $modules;
    }

    /**
     * Get module efficiency
     */
    private function getModuleEfficiency(): array
    {
        $calonCount = Calon::count();
        $pemilihCount = Pemilih::count();
        $denominatorOsis = $calonCount * $pemilihCount;

        return [
            'osis' => [
                'total_candidates' => $calonCount,
                'total_votes' => Voting::count(),
                'efficiency' => $denominatorOsis > 0 ? round((Voting::count() / $denominatorOsis) * 100, 2) : 0,
            ],
            'lulus' => [
                'total_applications' => Kelulusan::count(),
                'approved' => Kelulusan::where('status', 'lulus')->count(),
                'approval_rate' => Kelulusan::count() > 0 ? round((Kelulusan::where('status', 'lulus')->count() / Kelulusan::count()) * 100, 2) : 0,
            ],
            'sarpras' => [
                'total_assets' => Barang::count(),
                'maintenance_due' => Maintenance::where('tanggal_maintenance', '<=', now()->addDays(7))->count(),
                'maintenance_rate' => Barang::count() > 0 ? round((Maintenance::count() / Barang::count()) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get average response time (simulated - would need APM in production)
     */
    private function getAverageResponseTime(array $dateRange): array
    {
        // This would typically come from APM tools like Laravel Telescope or New Relic
        return [
            'average_ms' => 120, // Placeholder
            'p95_ms' => 250, // Placeholder
            'p99_ms' => 500, // Placeholder
        ];
    }

    /**
     * Get voting engagement
     */
    private function getVotingEngagement(): array
    {
        $totalVoters = Pemilih::count();
        $totalVotes = Voting::count();
        $totalCandidates = Calon::count();

        return [
            'participation_rate' => $totalVoters > 0 ? round(($totalVotes / $totalVoters) * 100, 2) : 0,
            'average_votes_per_voter' => $totalVoters > 0 ? round($totalVotes / $totalVoters, 2) : 0,
            'candidates_per_vote' => $totalVotes > 0 ? round($totalCandidates / $totalVotes, 2) : 0,
        ];
    }

    /**
     * Get module adoption rates
     */
    private function getModuleAdoption(): array
    {
        $totalUsers = User::count();
        $siswaCount = 0;
        $guruCount = 0;

        try {
            $siswaCount = User::role('siswa')->count();
        } catch (\Exception $e) {
            $siswaCount = 0;
        }

        try {
            $guruCount = User::role('guru')->count();
        } catch (\Exception $e) {
            $guruCount = 0;
        }

        $usersWithAccessOsis = $siswaCount + $guruCount;

        return [
            'osis' => [
                'users_with_access' => $usersWithAccessOsis,
                'actual_voters' => Pemilih::count(),
                'adoption_rate' => $usersWithAccessOsis > 0 ? round((Pemilih::count() / $usersWithAccessOsis) * 100, 2) : 0,
            ],
            'lulus' => [
                'users_with_access' => $siswaCount,
                'actual_applicants' => Kelulusan::count(),
                'adoption_rate' => $siswaCount > 0 ? round((Kelulusan::count() / $siswaCount) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get user retention
     */
    private function getUserRetention(array $dateRange): array
    {
        $newUsers = User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
        $activeUsers = AuditLog::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->distinct('user_id')
            ->count('user_id');

        return [
            'new_users' => $newUsers,
            'active_users' => $activeUsers,
            'retention_rate' => $newUsers > 0 ? round(($activeUsers / $newUsers) * 100, 2) : 0,
        ];
    }

    /**
     * Get feature usage
     */
    private function getFeatureUsage(array $dateRange): array
    {
        return [
            'voting_feature' => Voting::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'graduation_feature' => Kelulusan::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'asset_management' => Barang::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'page_management' => Page::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            'user_management' => User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
        ];
    }
}
