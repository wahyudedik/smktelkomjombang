<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Advanced Analytics Dashboard</h1>
                <p class="text-slate-600 mt-1">Comprehensive system analytics and insights</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Date Range Filter -->
                <form method="GET" action="{{ route('admin.analytics') }}" class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $analytics['date_range']['start'] }}"
                        class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-slate-500">to</span>
                    <input type="date" name="end_date" value="{{ $analytics['date_range']['end'] }}"
                        class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </form>

                <!-- Export Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-2 z-10">
                        <a href="{{ route('admin.analytics.export', ['format' => 'json', 'start_date' => $analytics['date_range']['start'], 'end_date' => $analytics['date_range']['end']]) }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Export as JSON</a>
                        <a href="{{ route('admin.analytics.export', ['format' => 'csv', 'start_date' => $analytics['date_range']['start'], 'end_date' => $analytics['date_range']['end']]) }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Export as CSV</a>
                    </div>
                </div>

                <a href="{{ route('admin.analytics') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Load Chart.js and define function BEFORE Alpine.js processes x-data -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Define analyticsDashboard function globally - MUST be before Alpine initializes
        window.analyticsDashboard = function() {
            return {
                charts: {},
                init() {
                    // Delay chart initialization to ensure DOM and Chart.js are ready
                    setTimeout(() => this.initCharts(), 100);
                },
                initCharts() {
                    // Check if Chart.js is loaded
                    if (typeof Chart === 'undefined') {
                        console.warn('Chart.js not loaded yet, retrying...');
                        setTimeout(() => this.initCharts(), 100);
                        return;
                    }

                    // User Invitations Chart
                    const userInvCtx = document.getElementById('userInvitationsChart');
                    if (userInvCtx) {
                        this.charts.userInvitations = new Chart(userInvCtx, {
                            type: 'line',
                            data: {
                                labels: @json(array_column($analytics['trends']['user_invitations'], 'date')),
                                datasets: [{
                                    label: 'New Users',
                                    data: @json(array_column($analytics['trends']['user_invitations'], 'count')),
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 5,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    // Module Usage Chart
                    const moduleUsageCtx = document.getElementById('moduleUsageChart');
                    if (moduleUsageCtx) {
                        const moduleData = @json($analytics['trends']['module_usage']);
                        this.charts.moduleUsage = new Chart(moduleUsageCtx, {
                            type: 'bar',
                            data: {
                                labels: moduleData.map(d => d.date),
                                datasets: [{
                                        label: 'Voting',
                                        data: moduleData.map(d => d.voting),
                                        backgroundColor: 'rgba(168, 85, 247, 0.8)'
                                    },
                                    {
                                        label: 'Graduation',
                                        data: moduleData.map(d => d.graduation),
                                        backgroundColor: 'rgba(34, 197, 94, 0.8)'
                                    },
                                    {
                                        label: 'Assets',
                                        data: moduleData.map(d => d.assets),
                                        backgroundColor: 'rgba(249, 115, 22, 0.8)'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 5,
                                scales: {
                                    x: {
                                        stacked: true
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    }
                                }
                            }
                        });
                    }

                    // Actions by Type Chart
                    @if (isset($analytics['audit_analytics']['actions_by_type']) &&
                            count($analytics['audit_analytics']['actions_by_type']) > 0)
                        const actionsTypeCtx = document.getElementById('actionsByTypeChart');
                        if (actionsTypeCtx) {
                            const actionsData = @json($analytics['audit_analytics']['actions_by_type']);
                            this.charts.actionsByType = new Chart(actionsTypeCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: Object.keys(actionsData),
                                    datasets: [{
                                        data: Object.values(actionsData),
                                        backgroundColor: [
                                            'rgba(59, 130, 246, 0.8)',
                                            'rgba(34, 197, 94, 0.8)',
                                            'rgba(249, 115, 22, 0.8)',
                                            'rgba(168, 85, 247, 0.8)',
                                            'rgba(239, 68, 68, 0.8)',
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    aspectRatio: 2,
                                    plugins: {
                                        legend: {
                                            position: 'bottom'
                                        }
                                    }
                                }
                            });
                        }
                    @endif

                    // Peak Hours Chart
                    @if (isset($analytics['audit_analytics']['peak_hours']) && count($analytics['audit_analytics']['peak_hours']) > 0)
                        const peakHoursCtx = document.getElementById('peakHoursChart');
                        if (peakHoursCtx) {
                            const peakData = @json($analytics['audit_analytics']['peak_hours']);
                            const hours = Array.from({
                                length: 24
                            }, (_, i) => i);
                            this.charts.peakHours = new Chart(peakHoursCtx, {
                                type: 'bar',
                                data: {
                                    labels: hours.map(h => h + ':00'),
                                    datasets: [{
                                        label: 'Activities',
                                        data: hours.map(h => peakData[h] ?? 0),
                                        backgroundColor: 'rgba(139, 92, 246, 0.8)'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    aspectRatio: 5,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    @endif

                    // Feature Usage Chart
                    @if (isset($analytics['engagement']['feature_usage']))
                        const featureUsageCtx = document.getElementById('featureUsageChart');
                        if (featureUsageCtx) {
                            const featureData = @json($analytics['engagement']['feature_usage']);
                            this.charts.featureUsage = new Chart(featureUsageCtx, {
                                type: 'bar',
                                data: {
                                    labels: Object.keys(featureData).map(k => k.replace(/_/g, ' ').replace(
                                        /\b\w/g, l => l.toUpperCase())),
                                    datasets: [{
                                        label: 'Usage Count',
                                        data: Object.values(featureData),
                                        backgroundColor: 'rgba(59, 130, 246, 0.8)'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true,
                                    aspectRatio: 5,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    @endif
                },
                updateChart(chartName) {
                    window.location.reload();
                }
            }
        };
    </script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="analyticsDashboard()">
        <!-- Key Metrics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Users</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            {{ number_format($analytics['overview']['total_users']) }}</p>
                        @if (isset($analytics['user_activity']['user_growth']))
                            <p class="text-xs text-slate-500 mt-1">
                                <span
                                    class="{{ $analytics['user_activity']['user_growth']['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $analytics['user_activity']['user_growth']['growth_rate'] >= 0 ? '↑' : '↓' }}
                                    {{ abs($analytics['user_activity']['user_growth']['growth_rate']) }}%
                                </span>
                                vs previous period
                            </p>
                        @endif
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Active Users</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            {{ number_format($analytics['user_activity']['active_users'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500 mt-1">In selected period</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Actions</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            {{ number_format($analytics['audit_analytics']['total_actions'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500 mt-1">System activities tracked</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">System Health</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ ucfirst($analytics['performance']['system_health']['system_status'] ?? 'Healthy') }}</p>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $analytics['performance']['system_health']['active_modules'] ?? 0 }}/{{ $analytics['performance']['system_health']['total_modules'] ?? 6 }}
                            modules active
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Metrics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Activity Insights -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    User Activity Insights
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Last 24 Hours</p>
                            <p class="text-xl font-bold text-slate-900">
                                {{ $analytics['user_activity']['invited_users_last_24h'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">This Week</p>
                            <p class="text-xl font-bold text-slate-900">
                                {{ $analytics['user_activity']['invited_users_this_week'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">This Month</p>
                            <p class="text-xl font-bold text-slate-900">
                                {{ $analytics['user_activity']['invited_users_this_month'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-500">Selected Range</p>
                            <p class="text-xl font-bold text-slate-900">
                                {{ $analytics['user_activity']['invited_users_in_range'] ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <h4 class="text-sm font-medium text-slate-700 mb-3">User Distribution by Role</h4>
                        <div class="space-y-2">
                            @foreach ($analytics['user_activity']['user_distribution'] as $role => $count)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        <span class="text-sm text-slate-700 capitalize">{{ $role }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-24 bg-slate-200 rounded-full h-2">
                                            @php
                                                $total = array_sum($analytics['user_activity']['user_distribution']);
                                                $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                            @endphp
                                            <div class="bg-blue-500 h-2 rounded-full"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span
                                            class="text-sm font-semibold text-slate-900 w-12 text-right">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (isset($analytics['user_activity']['user_growth']))
                        <div class="pt-4 border-t">
                            <h4 class="text-sm font-medium text-slate-700 mb-2">User Growth</h4>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <p class="text-xs text-slate-500">New Users</p>
                                    <p class="text-lg font-bold text-green-600">
                                        {{ $analytics['user_activity']['user_growth']['new_users'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Growth Rate</p>
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ $analytics['user_activity']['user_growth']['growth_rate'] }}%</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Total Users</p>
                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $analytics['user_activity']['user_growth']['end_count'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Engagement Metrics -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Engagement Metrics
                </h3>
                <div class="space-y-4">
                    @if (isset($analytics['engagement']['voting_engagement']))
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                            <h4 class="text-sm font-medium text-purple-900 mb-3">Voting Engagement</h4>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <p class="text-xs text-purple-700">Participation</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ $analytics['engagement']['voting_engagement']['participation_rate'] }}%</p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-700">Avg Votes/Voter</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ $analytics['engagement']['voting_engagement']['average_votes_per_voter'] }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-700">Candidates/Vote</p>
                                    <p class="text-2xl font-bold text-purple-900">
                                        {{ $analytics['engagement']['voting_engagement']['candidates_per_vote'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($analytics['engagement']['module_adoption']))
                        <div>
                            <h4 class="text-sm font-medium text-slate-700 mb-3">Module Adoption Rates</h4>
                            <div class="space-y-3">
                                @foreach ($analytics['engagement']['module_adoption'] as $module => $data)
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm text-slate-700 capitalize">{{ $module }}</span>
                                            <span
                                                class="text-sm font-semibold text-slate-900">{{ $data['adoption_rate'] }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full"
                                                style="width: {{ min($data['adoption_rate'], 100) }}%"></div>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $data['actual_voters'] ?? ($data['actual_applicants'] ?? 0) }} of
                                            {{ $data['users_with_access'] }} users
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (isset($analytics['engagement']['user_retention']))
                        <div class="pt-4 border-t">
                            <h4 class="text-sm font-medium text-slate-700 mb-2">User Retention</h4>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <p class="text-xs text-slate-500">New Users</p>
                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $analytics['engagement']['user_retention']['new_users'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Active Users</p>
                                    <p class="text-lg font-bold text-green-600">
                                        {{ $analytics['engagement']['user_retention']['active_users'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Retention</p>
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ $analytics['engagement']['user_retention']['retention_rate'] }}%</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Invitation Trend Chart -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">User Invitation Trend</h3>
                    <select @change="updateChart('userInvitations')"
                        class="text-sm border border-slate-300 rounded px-2 py-1">
                        <option value="30">Last 30 Days</option>
                        <option value="7">Last 7 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                </div>
                <div class="h-48">
                    <canvas id="userInvitationsChart"></canvas>
                </div>
            </div>

            <!-- Module Usage Trend Chart -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Module Activity Trend</h3>
                    <select @change="updateChart('moduleUsage')"
                        class="text-sm border border-slate-300 rounded px-2 py-1">
                        <option value="30">Last 30 Days</option>
                        <option value="7">Last 7 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                </div>
                <div class="h-48">
                    <canvas id="moduleUsageChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Audit Analytics & Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Audit Analytics -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Audit Analytics
                </h3>

                @if (isset($analytics['audit_analytics']['actions_by_type']) &&
                        count($analytics['audit_analytics']['actions_by_type']) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-slate-700 mb-3">Actions by Type</h4>
                        <div class="h-36">
                            <canvas id="actionsByTypeChart"></canvas>
                        </div>
                    </div>
                @endif

                @if (isset($analytics['audit_analytics']['most_active_users']) &&
                        count($analytics['audit_analytics']['most_active_users']) > 0)
                    <div class="pt-4 border-t">
                        <h4 class="text-sm font-medium text-slate-700 mb-3">Most Active Users</h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach (array_slice($analytics['audit_analytics']['most_active_users'], 0, 10) as $user)
                                <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded">
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $user['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $user['email'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-blue-600">{{ $user['activity_count'] }}</p>
                                        <p class="text-xs text-slate-500">actions</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($analytics['audit_analytics']['peak_hours']) && count($analytics['audit_analytics']['peak_hours']) > 0)
                    <div class="pt-4 border-t mt-4">
                        <h4 class="text-sm font-medium text-slate-700 mb-3">Peak Activity Hours</h4>
                        <div class="h-32">
                            <canvas id="peakHoursChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Performance Metrics
                </h3>

                @if (isset($analytics['performance']['module_efficiency']))
                    <div class="space-y-4 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-700 mb-3">Module Efficiency</h4>
                            @foreach ($analytics['performance']['module_efficiency'] as $module => $data)
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-slate-700 capitalize">{{ $module }}</span>
                                        <span
                                            class="text-sm font-semibold text-slate-900">{{ $data['efficiency'] ?? ($data['approval_rate'] ?? ($data['maintenance_rate'] ?? 0)) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full"
                                            style="width: {{ min($data['efficiency'] ?? ($data['approval_rate'] ?? ($data['maintenance_rate'] ?? 0)), 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($analytics['performance']['database_performance']['largest_tables']))
                    <div class="pt-4 border-t">
                        <h4 class="text-sm font-medium text-slate-700 mb-3">Largest Tables</h4>
                        <div class="space-y-2">
                            @foreach ($analytics['performance']['database_performance']['largest_tables'] as $table)
                                <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded">
                                    <span
                                        class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $table['table']) }}</span>
                                    <span
                                        class="text-sm font-bold text-slate-900">{{ number_format($table['count']) }}
                                        rows</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Feature Usage -->
        @if (isset($analytics['engagement']['feature_usage']))
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Feature Usage</h3>
                <div class="h-40">
                    <canvas id="featureUsageChart"></canvas>
                </div>
            </div>
        @endif

        <!-- Module Usage Details -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Module Usage Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- OSIS -->
                <div class="border border-slate-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-slate-700 mb-3 flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        E-OSIS
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Candidates</span>
                            <span
                                class="text-sm font-bold text-slate-900">{{ $analytics['module_usage']['e_osis']['total_candidates'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Voters</span>
                            <span
                                class="text-sm font-bold text-slate-900">{{ $analytics['module_usage']['e_osis']['total_voters'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Participation</span>
                            <span
                                class="text-sm font-bold text-green-600">{{ $analytics['module_usage']['e_osis']['voting_participation'] }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Graduation -->
                <div class="border border-slate-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-slate-700 mb-3 flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        E-Lulus
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Graduates</span>
                            <span
                                class="text-sm font-bold text-slate-900">{{ $analytics['module_usage']['e_lulus']['total_graduates'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Pending</span>
                            <span
                                class="text-sm font-bold text-orange-600">{{ $analytics['module_usage']['e_lulus']['pending_verification'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sarpras -->
                <div class="border border-slate-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-slate-700 mb-3 flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                        Sarpras
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Total Assets</span>
                            <span
                                class="text-sm font-bold text-slate-900">{{ $analytics['module_usage']['sarpras']['total_assets'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600">Maintenance Due</span>
                            <span
                                class="text-sm font-bold text-red-600">{{ $analytics['module_usage']['sarpras']['maintenance_due'] }}</span>
                        </div>
                        <div class="space-y-1 mt-2 pt-2 border-t">
                            @foreach ($analytics['module_usage']['sarpras']['assets_by_condition'] as $condition => $count)
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-600 capitalize">{{ $condition }}</span>
                                    <span class="font-semibold text-slate-900">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
</x-app-layout>
