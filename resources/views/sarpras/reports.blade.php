<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Sarpras Reports</h1>
                <p class="text-slate-600 mt-1">Analytics and reports for school facilities</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <a href="{{ route('admin.sarpras.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                            <p class="text-sm font-medium text-slate-600">Total Categories</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $analytics['total_categories'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

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
                            <p class="text-2xl font-bold text-slate-900">{{ $analytics['total_items'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

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
                            <p class="text-sm font-medium text-slate-600">Total Rooms</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $analytics['total_rooms'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Total Value</p>
                            <p class="text-2xl font-bold text-slate-900">Rp
                                {{ number_format($analytics['total_value'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Items by Category -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Items by Category</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        @foreach ($analytics['items_by_category'] as $category)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <span
                                        class="text-sm font-medium text-slate-900">{{ $category->nama_kategori }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-slate-600">{{ $category->barang_count }} items</span>
                                    <div class="w-20 bg-slate-200 rounded-full h-2">
                                        @php
                                            $totalItems = $analytics['total_items'];
                                            $percentage =
                                                $totalItems > 0
                                                    ? round(($category->barang_count / $totalItems) * 100, 1)
                                                    : 0;
                                        @endphp
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Maintenance Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Maintenance Status</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-900">Pending</span>
                            </div>
                            <span class="text-sm text-slate-600">{{ $analytics['maintenance_pending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-900">In Progress</span>
                            </div>
                            <span class="text-sm text-slate-600">{{ $analytics['maintenance_in_progress'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-900">Completed</span>
                            </div>
                            <span class="text-sm text-slate-600">{{ $analytics['maintenance_completed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-900">Cancelled</span>
                            </div>
                            <span class="text-sm text-slate-600">{{ $analytics['maintenance_cancelled'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Items by Condition -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Items by Condition</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Good</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-slate-900">{{ $analytics['items_good'] }}</span>
                                <div class="w-20 bg-slate-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full"
                                        style="width: {{ $analytics['items_good_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Needs Repair</span>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="text-sm font-medium text-slate-900">{{ $analytics['items_repair'] }}</span>
                                <div class="w-20 bg-slate-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full"
                                        style="width: {{ $analytics['items_repair_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Damaged</span>
                            <div class="flex items-center space-x-2">
                                <span
                                    class="text-sm font-medium text-slate-900">{{ $analytics['items_damaged'] }}</span>
                                <div class="w-20 bg-slate-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full"
                                        style="width: {{ $analytics['items_damaged_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Costs -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Maintenance Costs</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">This Month</span>
                            <span class="text-sm font-medium text-slate-900">Rp
                                {{ number_format($analytics['maintenance_cost_month'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">This Year</span>
                            <span class="text-sm font-medium text-slate-900">Rp
                                {{ number_format($analytics['maintenance_cost_year'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Spent</span>
                            <span class="text-sm font-medium text-slate-900">Rp
                                {{ number_format($analytics['maintenance_cost_total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-900">Average per Item</span>
                                <span class="text-sm font-medium text-slate-900">Rp
                                    {{ number_format($analytics['maintenance_cost_average'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card mt-8">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Recent Activities</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($analytics['recent_activities'] as $activity)
                        <div class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-900">
                                    <span class="font-medium">{{ $activity->user->name ?? 'Unknown User' }}</span>
                                    created maintenance for
                                    <span class="font-medium">{{ $activity->item_name }}</span>
                                </p>
                                <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-slate-500">No recent activities</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
