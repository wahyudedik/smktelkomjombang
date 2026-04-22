<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">System Health</h1>
                <p class="text-slate-600 mt-1">Monitor system status and performance metrics</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.system.health') }}" class="btn btn-primary">
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
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Overall Status -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">System Status</h2>
                    <p class="text-slate-600 mt-1">Last checked: {{ $health['timestamp'] }}</p>
                </div>
                <div class="text-right">
                    @if ($health['status'] === 'healthy')
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Healthy
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Degraded
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <p class="text-sm text-slate-600">Environment</p>
                    <p class="text-lg font-bold text-slate-900 capitalize">{{ $health['environment'] }}</p>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <p class="text-sm text-slate-600">Version</p>
                    <p class="text-lg font-bold text-slate-900">{{ $health['version'] }}</p>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <p class="text-sm text-slate-600">PHP Version</p>
                    <p class="text-lg font-bold text-slate-900">{{ $health['metrics']['system']['php_version'] }}</p>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <p class="text-sm text-slate-600">Laravel Version</p>
                    <p class="text-lg font-bold text-slate-900">{{ $health['metrics']['system']['laravel_version'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Health Checks -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach ($health['checks'] as $checkName => $checkData)
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 capitalize">
                            {{ str_replace('_', ' ', $checkName) }}</h3>
                        @if ($checkData['status'] === 'healthy')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Healthy
                            </span>
                        @elseif($checkData['status'] === 'warning')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Warning
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Unhealthy
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        @foreach ($checkData as $key => $value)
                            @if ($key !== 'status')
                                <div class="flex justify-between">
                                    <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                    <span class="font-medium text-slate-900">
                                        @if (is_numeric($value))
                                            {{ number_format($value, 2) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- System Metrics -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">System Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-slate-700 mb-3">System Metrics</h4>
                    <div class="space-y-2">
                        @foreach ($health['metrics']['system'] as $key => $value)
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="font-medium text-slate-900">
                                    @if (is_array($value))
                                        {{ implode(', ', array_map(fn($v) => number_format($v, 2), $value)) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-slate-700 mb-3">Application Metrics</h4>
                    <div class="space-y-2">
                        @foreach ($health['metrics']['application'] as $key => $value)
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="font-medium text-slate-900">
                                    @if (is_bool($value))
                                        <span class="{{ $value ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $value ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Memory Usage -->
            @if (isset($health['checks']['memory']))
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Memory Usage</h3>
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block text-slate-600">
                                    {{ $health['checks']['memory']['current_mb'] }} MB /
                                    {{ $health['checks']['memory']['limit_mb'] }} MB
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-slate-600">
                                    {{ $health['checks']['memory']['usage_percentage'] }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-200">
                            <div style="width:{{ $health['checks']['memory']['usage_percentage'] }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center 
                                        {{ $health['checks']['memory']['usage_percentage'] > 80 ? 'bg-red-500' : 'bg-green-500' }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Disk Space -->
            @if (isset($health['checks']['disk_space']))
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Disk Space</h3>
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block text-slate-600">
                                    {{ $health['checks']['disk_space']['used_gb'] }} GB /
                                    {{ $health['checks']['disk_space']['total_gb'] }} GB
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-slate-600">
                                    {{ $health['checks']['disk_space']['usage_percentage'] }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-200">
                            <div style="width:{{ $health['checks']['disk_space']['usage_percentage'] }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center 
                                        {{ $health['checks']['disk_space']['usage_percentage'] > 90 ? 'bg-red-500' : 'bg-green-500' }}">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
