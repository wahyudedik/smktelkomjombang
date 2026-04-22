<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Sarpras Dashboard</h1>
                <p class="text-slate-600 mt-1">Manage school facilities and infrastructure</p>
            </div>
            <div class="flex items-center space-x-3">
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
                            <p class="text-sm font-medium text-slate-600">Categories</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_kategori'] }}</p>
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
                            <p class="text-sm font-medium text-slate-600">Total Items</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_barang'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Rooms -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Rooms</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_ruang'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Maintenance -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Maintenance</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['total_maintenance'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sarana Statistics Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Sarana -->
            <div class="card {{ $stats['sarana_perlu_update'] > 0 ? 'border-l-4 border-yellow-500' : '' }}">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center relative">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    @if ($stats['sarana_perlu_update'] > 0)
                                    <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $stats['sarana_perlu_update'] }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-600">Total Sarana</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_sarana'] }}</p>
                                @if ($stats['sarana_perlu_update'] > 0)
                                <p class="text-xs text-yellow-600 mt-1">
                                    {{ $stats['sarana_perlu_update'] }} perlu update
                                </p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.sarpras.sarana.index') }}" 
                            class="btn btn-sm btn-indigo">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Sarana -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Total Nilai Sarana</p>
                            <p class="text-2xl font-bold text-emerald-600">
                                Rp {{ number_format($stats['total_sarana_nilai'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Good Condition Items -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Good Condition</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['barang_baik'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Damaged Items -->
            <div class="card {{ $stats['barang_rusak_perlu_maintenance'] > 0 ? 'border-l-4 border-red-500' : '' }}">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center relative">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                @if ($stats['barang_rusak_perlu_maintenance'] > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $stats['barang_rusak_perlu_maintenance'] }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-slate-600">Damaged</p>
                            <p class="text-2xl font-bold text-red-600">{{ $stats['barang_rusak'] }}</p>
                            @if ($stats['barang_rusak_perlu_maintenance'] > 0)
                            <p class="text-xs text-red-600 mt-1">
                                {{ $stats['barang_rusak_perlu_maintenance'] }} perlu maintenance
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Rooms -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Active Rooms</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['ruang_aktif'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Maintenance -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Completed</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['maintenance_selesai'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts & Notifications -->
        @if ($stats['barang_rusak_perlu_maintenance'] > 0 || $stats['sarana_perlu_update'] > 0 || $stats['barang_rusak_di_sarana'] > 0)
        <div class="mb-8">
            <div class="card border-l-4 border-yellow-400 bg-yellow-50">
                <div class="card-body">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3">Peringatan & Notifikasi</h3>
                            <div class="space-y-2">
                                @if ($stats['barang_rusak_perlu_maintenance'] > 0)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-yellow-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium text-slate-900">
                                                {{ $stats['barang_rusak_perlu_maintenance'] }} Barang Rusak Perlu Maintenance
                                            </p>
                                            <p class="text-sm text-slate-600">Barang dengan kondisi rusak yang belum memiliki maintenance aktif</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.sarpras.barang.index', ['kondisi' => 'rusak']) }}" 
                                       class="btn btn-sm btn-red">
                                        Lihat Detail
                                    </a>
                                </div>
                                @endif

                                @if ($stats['barang_rusak_di_sarana'] > 0)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-yellow-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium text-slate-900">
                                                {{ $stats['barang_rusak_di_sarana'] }} Barang Rusak di Sarana
                                            </p>
                                            <p class="text-sm text-slate-600">Barang dengan kondisi rusak dalam sarana yang perlu diperhatikan</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.sarpras.reports') }}" 
                                       class="btn btn-sm btn-orange">
                                        Lihat Laporan
                                    </a>
                                </div>
                                @endif

                                @if ($stats['sarana_perlu_update'] > 0)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-yellow-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium text-slate-900">
                                                {{ $stats['sarana_perlu_update'] }} Sarana Perlu Update
                                            </p>
                                            <p class="text-sm text-slate-600">Sarana yang belum diupdate lebih dari 6 bulan atau memiliki barang rusak</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.sarpras.sarana.index') }}" 
                                       class="btn btn-sm btn-blue">
                                        Lihat Sarana
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.sarpras.kategori.index') }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Categories
                        </a>
                        <a href="{{ route('admin.sarpras.barang.index') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Items
                        </a>
                        <a href="{{ route('admin.sarpras.ruang.index') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Rooms
                        </a>
                        <a href="{{ route('admin.sarpras.maintenance.index') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Maintenance
                        </a>
                        <a href="{{ route('admin.sarpras.sarana.index') }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Sarana
                        </a>
                        <a href="{{ route('admin.sarpras.reports') }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Reports
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Maintenance -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Maintenance</h3>
                        <a href="{{ route('admin.sarpras.maintenance.index') }}" 
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($recent_maintenance->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recent_maintenance as $maintenance)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-orange-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">
                                                {{ $maintenance->item_name }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $maintenance->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        @if ($maintenance->status == 'pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ ucfirst($maintenance->status) }}
                                            </span>
                                        @elseif($maintenance->status == 'in_progress')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($maintenance->status) }}
                                            </span>
                                        @elseif($maintenance->status == 'selesai')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ ucfirst($maintenance->status) }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($maintenance->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-slate-500">No recent maintenance records</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Sarana -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Sarana</h3>
                        <a href="{{ route('admin.sarpras.sarana.index') }}" 
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($recent_sarana->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recent_sarana as $sarana)
                                <a href="{{ route('admin.sarpras.sarana.show', $sarana) }}" 
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors block">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">
                                                {{ $sarana->kode_inventaris }}
                                            </p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <p class="text-xs text-slate-500">
                                                    {{ $sarana->ruang->nama_ruang ?? '-' }}
                                                </p>
                                                <span class="text-slate-300">•</span>
                                                <p class="text-xs text-slate-500">
                                                    {{ $sarana->barang->count() }} barang
                                                </p>
                                                <span class="text-slate-300">•</span>
                                                <p class="text-xs text-slate-500">
                                                    {{ $sarana->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-slate-500">
                                            {{ $sarana->sumber_dana ?? '-' }}
                                        </span>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-slate-500">No recent sarana records</p>
                            <a href="{{ route('admin.sarpras.sarana.create') }}" 
                                class="mt-4 inline-block btn btn-sm btn-primary">
                                Tambah Sarana Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
