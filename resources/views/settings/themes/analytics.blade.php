<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Theme Analytics</h1>
                                <p class="text-gray-600 mt-2">Track halaman mana yang paling banyak dikunjungi per tema</p>
                            </div>
                            <a href="{{ route('admin.themes.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    @if (!$analyticsData['has_data'])
                        <!-- No Data State -->
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Data Analytics</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                                Theme analytics akan mulai terkumpul setelah tabel <code
                                    class="bg-gray-100 px-1 rounded">theme_analytics</code> dibuat dan halaman landing
                                mulai dilacak.
                            </p>
                            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4 max-w-lg mx-auto text-left">
                                <p class="text-sm font-medium text-gray-700 mb-2">Untuk mengaktifkan analytics:</p>
                                <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                                    <li>Jalankan migration: <code
                                            class="bg-gray-100 px-1 rounded">php artisan migrate</code></li>
                                    <li>Analytics akan mulai terkumpul otomatis</li>
                                    <li>Data ditampilkan di halaman ini</li>
                                </ol>
                            </div>
                        </div>
                    @else
                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            @foreach ($analyticsData['themes'] as $themeKey => $stats)
                                <div
                                    class="border rounded-lg p-4 {{ $themeKey === ($activeTheme ?? config('app.default_theme', 'telkom')) ? 'border-green-400 bg-green-50' : 'border-gray-200' }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-bold text-gray-900">
                                            {{ $themes[$themeKey]['short_name'] ?? $themeKey }}
                                        </h4>
                                        @if ($themeKey === ($activeTheme ?? config('app.default_theme', 'telkom')))
                                            <span
                                                class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Aktif</span>
                                        @endif
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Page Views</span>
                                            <span class="font-semibold text-gray-900"
                                                x-text="formatNumber({{ $stats['total_views'] }})">{{ number_format($stats['total_views']) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Unique Visitors</span>
                                            <span class="font-semibold text-gray-900"
                                                x-text="formatNumber({{ $stats['unique_visitors'] }})">{{ number_format($stats['unique_visitors']) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Avg. Time</span>
                                            <span class="font-semibold text-gray-900">{{ $stats['avg_time_on_page'] }}s</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Bounce Rate</span>
                                            <span class="font-semibold text-gray-900">{{ $stats['bounce_rate'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Daily Views Chart -->
                        @if (count($analyticsData['daily_views']) > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Page Views per Hari (30 Hari Terakhir)</h3>
                                <div class="bg-gray-50 rounded-lg p-4" style="height: 350px;">
                                    <canvas id="dailyViewsChart"></canvas>
                                </div>
                            </div>
                        @endif

                        <!-- Comparison Bar -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Perbandingan Total Views</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                @php
                                    $maxViews = max(array_column($analyticsData['themes'], 'total_views'));
                                @endphp
                                @foreach ($analyticsData['themes'] as $themeKey => $stats)
                                    @php
                                        $percentage = $maxViews > 0 ? ($stats['total_views'] / $maxViews * 100) : 0;
                                    @endphp
                                    <div class="mb-4 last:mb-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $themes[$themeKey]['short_name'] ?? $themeKey }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ number_format($stats['total_views']) }} views
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="h-3 rounded-full transition-all duration-500
                                                {{ $themeKey === 'telkom' ? 'bg-blue-500' : 'bg-purple-500' }}"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        @if ($analyticsData['has_data'] && count($analyticsData['daily_views']) > 0)
            // Daily Views Chart
            const dailyData = @json($analyticsData['daily_views']);
            const labels = dailyData.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            });

            const telkomViews = dailyData.map(d => d.telkom_views || 0);
            const mauduViews = dailyData.map(d => d.maudu_views || 0);

            const ctx = document.getElementById('dailyViewsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Telkom',
                            data: telkomViews,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 2,
                        },
                        {
                            label: 'MAUDU',
                            data: mauduViews,
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        @endif
    </script>
    @endpush
</x-app-layout>
