<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    @if (Auth::user()->hasRole('superadmin'))
                        {{ __('common.superadmin_dashboard') }}
                    @elseif(Auth::user()->hasRole('admin'))
                        {{ __('common.admin_dashboard') }}
                    @elseif(Auth::user()->hasRole('guru'))
                        {{ __('common.guru_dashboard') }}
                    @elseif(Auth::user()->hasRole('siswa'))
                        {{ __('common.siswa_dashboard') }}
                    @elseif(Auth::user()->hasRole('sarpras'))
                        {{ __('common.sarpras_dashboard') }}
                    @else
                        {{ __('common.dashboard') }}
                    @endif
                </h1>
                <p class="text-slate-600 mt-1">{{ __('common.welcome_back') }}, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if (Auth::user()->hasRole('superadmin'))
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                        Superadmin
                    </span>
                @elseif(Auth::user()->hasRole('admin'))
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></span>
                        Admin
                    </span>
                @elseif(Auth::user()->hasRole('guru'))
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                        Guru
                    </span>
                @elseif(Auth::user()->hasRole('siswa'))
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <span class="w-2 h-2 bg-purple-400 rounded-full mr-1.5"></span>
                        Siswa
                    </span>
                @elseif(Auth::user()->hasRole('sarpras'))
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        <span class="w-2 h-2 bg-orange-400 rounded-full mr-1.5"></span>
                        Sarpras
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="dashboardStats()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                @if (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']))
                    <!-- Total Students -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-dark-300">
                                    {{ __('common.total_siswa') }}</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="stats.siswa">
                                    {{ $statistics['total_siswa'] ?? 0 }}</p>
                                <p class="text-xs mt-1"
                                    :class="stats.siswaTrend >= 0 ? 'text-green-600' : 'text-red-600'"
                                    x-show="stats.siswaTrend !== null">
                                    <span
                                        x-text="stats.siswaTrend >= 0 ? '+' + stats.siswaTrend : stats.siswaTrend"></span>%
                                    dari bulan lalu
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']))
                    <!-- Total Teachers -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-dark-300">
                                    {{ __('common.total_guru') }}</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="stats.guru">
                                    {{ $statistics['total_guru'] ?? 0 }}</p>
                                <p class="text-xs mt-1"
                                    :class="stats.guruTrend >= 0 ? 'text-green-600' : 'text-red-600'"
                                    x-show="stats.guruTrend !== null">
                                    <span
                                        x-text="stats.guruTrend >= 0 ? '+' + stats.guruTrend : stats.guruTrend"></span>%
                                    dari bulan lalu
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->hasAnyRole(['admin', 'superadmin']))
                    <!-- Active Users -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-dark-300">
                                    {{ __('common.active_users') }}</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="stats.users">
                                    {{ $statistics['total_users'] ?? 0 }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                    {{ __('common.active_users') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->hasRole('siswa'))
                    <!-- Student-specific widgets -->
                    <!-- My Profile Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600">{{ __('common.profile_status') }}</p>
                                <p class="text-2xl font-bold text-green-600">{{ __('common.complete') }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('common.profile_complete') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Progress -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600">{{ __('common.academic_progress') }}</p>
                                <p class="text-2xl font-bold text-blue-600">85%</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('common.learning_progress') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600">{{ __('common.upcoming_events') }}</p>
                                <p class="text-2xl font-bold text-purple-600">3</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('common.upcoming_events') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->hasAnyRole(['sarpras', 'admin', 'superadmin']))
                    <!-- Total Assets -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-600 dark:text-dark-300">
                                    {{ __('common.total_assets') }}</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white" x-text="stats.barang">
                                    {{ $statistics['total_barang'] ?? 0 }}</p>
                                <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                    {{ __('common.total_assets') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Charts and Analytics Section -->
            @if (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8" x-data x-init="$nextTick(() => initCharts())">
                    <!-- User Growth Chart (Chart.js) -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ __('common.user_growth') }}</h3>
                                <p class="text-xs text-slate-500 dark:text-dark-400 mt-1">6 bulan terakhir - Total:
                                    {{ $userGrowth['total_siswa'] }} siswa, {{ $userGrowth['total_guru'] }} guru</p>
                            </div>
                        </div>
                        <div class="relative" style="height: 280px;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>

                    <!-- Module Usage Chart (Chart.js Doughnut) -->
                    <div
                        class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
                            {{ __('common.module_usage') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-dark-400 mb-4">Berdasarkan jumlah data (70%) &
                            aktivitas 30 hari terakhir (30%)</p>
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="relative" style="width: 200px; height: 200px;">
                                <canvas id="moduleUsageChart"></canvas>
                            </div>
                            <div class="flex-1 space-y-2 w-full">
                                @foreach ($moduleUsage as $moduleName => $module)
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center">
                                            <span
                                                class="w-3 h-3 rounded-full mr-2 bg-{{ $module['color'] }}-500"></span>
                                            <span class="text-slate-600 dark:text-dark-300">{{ $moduleName }}</span>
                                        </div>
                                        <span
                                            class="font-medium text-slate-900 dark:text-white">{{ $module['percentage'] }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions and Recent Activity -->
            @if (Auth::user()->hasAnyRole(['admin', 'superadmin']))
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Quick Actions -->
                    <div class="lg:col-span-1" x-data="{ expanded: true }">
                        <div
                            class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6">
                            <div class="flex items-center justify-between mb-4 cursor-pointer"
                                @click="expanded = !expanded">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ __('common.quick_actions') }}</h3>
                                <svg class="w-5 h-5 text-slate-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': !expanded }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="space-y-3" x-show="expanded" x-collapse>
                                @if (Auth::user()->hasRole('superadmin') || Auth::user()->can('users.create'))
                                    <a href="{{ route('admin.superadmin.users.create') }}"
                                        class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-dark-700 transition-colors">
                                        <div
                                            class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ __('common.add_new_user') }}</span>
                                    </a>
                                @endif

                                @if (Auth::user()->hasRole('superadmin') || Auth::user()->can('guru.create'))
                                    <a href="{{ route('admin.guru.create') }}"
                                        class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-dark-700 transition-colors">
                                        <div
                                            class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ __('common.add_new_teacher') }}</span>
                                    </a>
                                @endif

                                @if (Auth::user()->hasRole('superadmin') || Auth::user()->can('siswa.create'))
                                    <a href="{{ route('admin.siswa.create') }}"
                                        class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-dark-700 transition-colors">
                                        <div
                                            class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ __('common.add_new_student') }}</span>
                                    </a>
                                @endif

                                @if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('sarpras') || Auth::user()->can('sarpras.create'))
                                    <a href="{{ route('admin.sarpras.barang.create') }}"
                                        class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-dark-700 transition-colors">
                                        <div
                                            class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ __('common.add_new_asset') }}</span>
                                    </a>
                                @endif

                                @if (Auth::user()->hasRole('superadmin'))
                                    <a href="{{ route('admin.superadmin.instagram-settings') }}"
                                        class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-dark-700 transition-colors">
                                        <div
                                            class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-pink-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323c-.875.807-2.026 1.297-3.323 1.297zm7.718-1.297c-.875.807-2.026 1.297-3.323 1.297s-2.448-.49-3.323-1.297c-.807-.875-1.297-2.026-1.297-3.323s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">Kelola
                                            Instagram</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="lg:col-span-2" x-data="{ searchQuery: '', expanded: true }">
                        <div
                            class="bg-white dark:bg-dark-800 rounded-xl shadow-sm border border-slate-200 dark:border-dark-700 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3 cursor-pointer" @click="expanded = !expanded">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ __('common.recent_activity') }}</h3>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200"
                                        :class="{ 'rotate-180': !expanded }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="relative" x-show="expanded">
                                        <input type="text" x-model="searchQuery" placeholder="Cari aktivitas..."
                                            class="w-40 sm:w-56 text-xs rounded-lg border-slate-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white pl-8 pr-3 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3" x-show="expanded" x-collapse>
                                @forelse($recentActivities ?? [] as $activity)
                                    @if ($activity)
                                        @php
                                            $action = strtolower($activity->action ?? '');
                                            $iconColor = 'blue';
                                            $iconBg = 'bg-blue-100';
                                            $icon = 'check';

                                            if (str_contains($action, 'create') || str_contains($action, 'add')) {
                                                $iconColor = 'green';
                                                $iconBg = 'bg-green-100';
                                                $icon = 'plus';
                                            } elseif (
                                                str_contains($action, 'update') ||
                                                str_contains($action, 'edit')
                                            ) {
                                                $iconColor = 'blue';
                                                $iconBg = 'bg-blue-100';
                                                $icon = 'pencil';
                                            } elseif (
                                                str_contains($action, 'delete') ||
                                                str_contains($action, 'remove')
                                            ) {
                                                $iconColor = 'red';
                                                $iconBg = 'bg-red-100';
                                                $icon = 'trash';
                                            } elseif (
                                                str_contains($action, 'login') ||
                                                str_contains($action, 'logout')
                                            ) {
                                                $iconColor = 'purple';
                                                $iconBg = 'bg-purple-100';
                                                $icon = 'user';
                                            } elseif (
                                                str_contains($action, 'view') ||
                                                str_contains($action, 'access')
                                            ) {
                                                $iconColor = 'indigo';
                                                $iconBg = 'bg-indigo-100';
                                                $icon = 'eye';
                                            }
                                        @endphp
                                        <div x-show="searchQuery === '' || '{{ strtolower($activity->action ?? '') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($activity->user->name ?? 'system') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($activity->description ?? '') }}'.includes(searchQuery.toLowerCase())"
                                            class="flex items-start space-x-3 py-2 hover:bg-slate-50 dark:hover:bg-dark-700 rounded-lg px-2 -mx-2 transition-colors">
                                            <div
                                                class="w-8 h-8 {{ $iconBg }} rounded-full flex items-center justify-center flex-shrink-0">
                                                @if ($icon == 'plus')
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                @elseif($icon == 'pencil')
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                @elseif($icon == 'trash')
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                @elseif($icon == 'user')
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                @elseif($icon == 'eye')
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-{{ $iconColor }}-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    @if ($activity->user)
                                                        <span
                                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $activity->user->name }}</span>
                                                    @else
                                                        <span
                                                            class="text-sm font-medium text-slate-900 dark:text-white">System</span>
                                                    @endif

                                                    @if ($activity->action)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $iconColor }}-100 text-{{ $iconColor }}-800">
                                                            {{ ucfirst($activity->action) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-600 dark:text-dark-300 mt-0.5">
                                                    {{ $activity->description ?? 'User activity logged' }}
                                                </p>
                                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $activity->created_at?->diffForHumans() ?? 'Just now' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-slate-900">
                                            {{ __('common.no_recent_activity') }}</h3>
                                        <p class="mt-1 text-sm text-slate-500">Activity will appear here as users
                                            interact
                                            with the system.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        @endif

        <!-- Student Profile Section -->
        @if (Auth::user()->hasRole('siswa'))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Student Profile Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.student_profile') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                <span
                                    class="text-2xl font-bold text-blue-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-900">{{ Auth::user()->name }}</h4>
                                <p class="text-sm text-slate-600">{{ Auth::user()->email }}</p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    Siswa
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div>
                                <p class="text-sm text-slate-500">Kelas</p>
                                <p class="text-sm font-medium text-slate-900">XII IPA 1</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">NIS</p>
                                <p class="text-sm font-medium text-slate-900">2023001</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">{{ __('common.tahun_ajaran') }}</p>
                                <p class="text-sm font-medium text-slate-900">2024/2025</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Status</p>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.academic_info') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Rata-rata Nilai</p>
                                    <p class="text-xs text-slate-500">Semester 1</p>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-green-600">85.5</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Kehadiran</p>
                                    <p class="text-xs text-slate-500">{{ __('common.this_month') }}</p>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-blue-600">95%</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ __('common.upcoming_exams') }}
                                    </p>
                                    <p class="text-xs text-slate-500">UTS Semester 2</p>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-orange-600">5 hari</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.upcoming_events') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">UTS Semester 2</p>
                                <p class="text-xs text-slate-500">20 Oktober 2024</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">{{ __('common.parent_meeting') }}</p>
                                <p class="text-xs text-slate-500">25 Oktober 2024</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">{{ __('common.science_fair') }}</p>
                                <p class="text-xs text-slate-500">30 Oktober 2024</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.quick_access') }}</h3>
                    <div class="space-y-3">
                        <a href="#"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.view_grades') }}</span>
                        </a>

                        <a href="#"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.download_report') }}</span>
                        </a>

                        <a href="#"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.schedule') }}</span>
                        </a>

                        <a href="#"
                            class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.classmates') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>

    @push('scripts')
        <script>
            // Alpine.js component: Dashboard Stats with live polling
            function dashboardStats() {
                return {
                    stats: {
                        siswa: {{ $statistics['total_siswa'] ?? 0 }},
                        guru: {{ $statistics['total_guru'] ?? 0 }},
                        users: {{ $statistics['total_users'] ?? 0 }},
                        barang: {{ $statistics['total_barang'] ?? 0 }},
                        siswaTrend: null,
                        guruTrend: null,
                    },
                    pollInterval: null,
                    init() {
                        // Poll every 60 seconds for live stats
                        this.pollInterval = setInterval(() => this.fetchStats(), 60000);
                        // Cleanup interval saat component di-destroy (Alpine.js v3 compatible)
                        const cleanup = () => clearInterval(this.pollInterval);
                        window.addEventListener('beforeunload', cleanup);
                        this.$el.addEventListener('cleanup', cleanup);
                    },
                    async fetchStats() {
                        try {
                            const response = await fetch('{{ route('admin.dashboard.api.stats') }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                // Animate number changes
                                this.animateNumber('siswa', this.stats.siswa, data.total_siswa);
                                this.animateNumber('guru', this.stats.guru, data.total_guru);
                                this.animateNumber('users', this.stats.users, data.total_users);
                                this.animateNumber('barang', this.stats.barang, data.total_barang);
                                if (data.siswa_trend !== undefined) this.stats.siswaTrend = data.siswa_trend;
                                if (data.guru_trend !== undefined) this.stats.guruTrend = data.guru_trend;
                            }
                        } catch (e) {
                            console.log('Stats poll error:', e);
                        }
                    },
                    animateNumber(key, from, to) {
                        if (from === to) return;
                        const duration = 500;
                        const start = performance.now();
                        const step = (timestamp) => {
                            const progress = Math.min((timestamp - start) / duration, 1);
                            this.stats[key] = Math.round(from + (to - from) * progress);
                            if (progress < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    }
                };
            }

            // Chart.js Initialization
            function initCharts() {
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#94a3b8' : '#64748b';
                const gridColor = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(0,0,0,0.05)';

                // User Growth Bar Chart
                const growthCtx = document.getElementById('userGrowthChart');
                if (growthCtx) {
                    new Chart(growthCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode(array_column($userGrowth['data'], 'month')) !!},
                            datasets: [{
                                    label: 'Siswa',
                                    data: {!! json_encode(array_map(fn($d) => $d['siswa']['count'], $userGrowth['data'])) !!},
                                    backgroundColor: '#3b82f6',
                                    borderColor: '#2563eb',
                                    borderWidth: 2,
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.7,
                                },
                                {
                                    label: 'Guru',
                                    data: {!! json_encode(array_map(fn($d) => $d['guru']['count'], $userGrowth['data'])) !!},
                                    backgroundColor: '#22c55e',
                                    borderColor: '#16a34a',
                                    borderWidth: 2,
                                    borderRadius: 6,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.7,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 800,
                                easing: 'easeOutQuart'
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: textColor,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#1e293b' : '#0f172a',
                                    titleColor: '#f8fafc',
                                    bodyColor: '#e2e8f0',
                                    cornerRadius: 8,
                                    padding: 12,
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor
                                    },
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 12
                                        },
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Module Usage Doughnut Chart
                const moduleCtx = document.getElementById('moduleUsageChart');
                if (moduleCtx) {
                    const colorMap = {
                        blue: {
                            bg: 'rgba(59, 130, 246, 0.8)',
                            border: '#3b82f6'
                        },
                        green: {
                            bg: 'rgba(34, 197, 94, 0.8)',
                            border: '#22c55e'
                        },
                        purple: {
                            bg: 'rgba(168, 85, 247, 0.8)',
                            border: '#a855f7'
                        },
                        orange: {
                            bg: 'rgba(249, 115, 22, 0.8)',
                            border: '#f97316'
                        },
                        pink: {
                            bg: 'rgba(236, 72, 153, 0.8)',
                            border: '#ec4899'
                        },
                    };
                    @php
                        $moduleLabels = array_keys($moduleUsage);
                        $moduleValues = array_column($moduleUsage, 'percentage');
                        $moduleColors = array_map(fn($m) => $m['color'], $moduleUsage);
                    @endphp
                    // Skip doughnut chart jika semua values 0 (Chart.js v4 bug)
                    const moduleValues = {!! json_encode($moduleValues) !!};
                    const hasModuleData = moduleValues.some(v => v > 0);

                    if (hasModuleData) {
                    new Chart(moduleCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($moduleLabels) !!},
                            datasets: [{
                                data: moduleValues,
                                backgroundColor: {!! json_encode(
                                    array_map(
                                        fn($c) => match ($c) {
                                            'blue' => '#3b82f6',
                                            'green' => '#22c55e',
                                            'purple' => '#a855f7',
                                            'orange' => '#f97316',
                                            'pink' => '#ec4899',
                                            default => '#94a3b8',
                                        },
                                        $moduleColors,
                                    ),
                                ) !!},
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                hoverOffset: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            animation: {
                                animateRotate: true,
                                duration: 1000
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: isDark ? '#1e293b' : '#0f172a',
                                    titleColor: '#f8fafc',
                                    bodyColor: '#e2e8f0',
                                    cornerRadius: 8,
                                    padding: 12,
                                    callbacks: {
                                        label: function(ctx) {
                                            return ctx.label + ': ' + ctx.parsed + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                    } // end if (hasModuleData)
                }
            }

            // darkModeToggle() didefinisikan di layouts/app.blade.php (shared)
            // Override versi dashboard untuk reload charts setelah toggle dark mode
            const _originalDarkModeToggle = darkModeToggle;
            darkModeToggle = function() {
                const base = _originalDarkModeToggle();
                const originalToggle = base.toggle.bind(base);
                base.toggle = function() {
                    originalToggle();
                    setTimeout(() => {
                        if (typeof Chart !== 'undefined') {
                            Object.values(Chart.instances || {}).forEach(instance => instance.destroy());
                        }
                        initCharts();
                    }, 100);
                };
                return base;
            };
        </script>
    @endpush
</x-app-layout>
