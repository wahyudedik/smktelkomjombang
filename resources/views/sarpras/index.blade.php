<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.sarpras_dashboard') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_school_facilities') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.sarpras.barang.import') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ __('common.import_barang') }}
                </a>
                <a href="{{ route('admin.sarpras.barang.export') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('common.export_barang') }}
                </a>
                <a href="{{ route('admin.sarpras.reports') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('common.reports') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Categories -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">{{ __('common.categories') }}</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_categories'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Items -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">{{ __('common.items') }}</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_items'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Rooms -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">{{ __('common.rooms') }}</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_rooms'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Maintenance -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">{{ __('common.pending_maintenance') }}</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['pending_maintenance'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Management Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.management') }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.sarpras.kategori.index') }}"
                            class="flex flex-col items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.categories') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.barang.index') }}"
                            class="flex flex-col items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.items') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.ruang.index') }}"
                            class="flex flex-col items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.rooms') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.maintenance.index') }}"
                            class="flex flex-col items-center p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.maintenance') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Import/Export Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.data_management') }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.sarpras.barang.import') }}"
                            class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.import_barang') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.barang.export') }}"
                            class="flex flex-col items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.export_barang') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.barang.downloadTemplate') }}"
                            class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.download_template') }}</span>
                        </a>

                        <a href="{{ route('admin.sarpras.reports') }}"
                            class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ __('common.reports') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Maintenance -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.recent_maintenance') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        @forelse($recent_maintenance as $maintenance)
                            <div
                                class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-900">
                                        <span
                                            class="font-medium">{{ $maintenance->item->nama ?? __('common.unknown') }}</span>
                                        - {{ ucfirst($maintenance->maintenance_type) }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        <span
                                            class="badge {{ $maintenance->status_badge_color }}">{{ ucfirst($maintenance->status) }}</span>
                                        • {{ $maintenance->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                                <p class="text-slate-500">{{ __('common.no_recent_maintenance') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Items by Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.items_by_status') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.good') }}</span>
                            <span class="text-sm font-medium text-slate-900">{{ $stats['items_good'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.needs_repair') }}</span>
                            <span class="text-sm font-medium text-slate-900">{{ $stats['items_repair'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.damaged') }}</span>
                            <span class="text-sm font-medium text-slate-900">{{ $stats['items_damaged'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance by Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.maintenance_status') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.pending') }}</span>
                            <span
                                class="text-sm font-medium text-slate-900">{{ $stats['maintenance_pending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.in_progress') }}</span>
                            <span
                                class="text-sm font-medium text-slate-900">{{ $stats['maintenance_in_progress'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.completed') }}</span>
                            <span
                                class="text-sm font-medium text-slate-900">{{ $stats['maintenance_completed'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ __('common.total_value') }}</h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total_value'] }}</p>
                        <p class="text-sm text-slate-600">{{ __('common.total_asset_value') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
