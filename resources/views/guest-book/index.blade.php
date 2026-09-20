<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-dark-800 border-b border-slate-200 dark:border-dark-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <x-admin.breadcrumb title="Buku Tamu" :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Buku Tamu'],
                    ]" />
                    <div class="flex flex-wrap items-center gap-2">
                        @can('buku-tamu.export')
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button @click="open = !open"
                                    class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-medium py-2 px-3 lg:px-4 rounded-lg text-sm shadow-sm transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Export</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('admin.buku-tamu.export', array_merge(request()->query(), ['type' => 'excel'])) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Export Excel</span>
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('admin.buku-tamu.export', array_merge(request()->query(), ['type' => 'pdf'])) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Export PDF</span>
                                    </a>
                                </div>
                            </div>
                        @endcan
                        @can('buku-tamu.create')
                            <a href="{{ route('admin.buku-tamu.create') }}"
                                class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white font-medium py-2 px-3 lg:px-4 rounded-lg text-sm shadow-sm transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Check-In Tamu</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 lg:p-6 text-gray-900">

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-2 sm:p-3 bg-blue-100 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-xs sm:text-sm font-medium text-gray-500">Hari Ini</p>
                                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $stats['total_today'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-2 sm:p-3 bg-green-100 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-xs sm:text-sm font-medium text-gray-500">Sedang Berkunjung</p>
                                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $stats['active_checkin'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-2 sm:p-3 bg-purple-100 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Semua</p>
                                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $stats['total_all'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 sm:p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 p-2 sm:p-3 bg-yellow-100 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-xs sm:text-sm font-medium text-gray-500">Rata-rata Durasi</p>
                                    <p class="text-xl sm:text-2xl font-semibold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-3 sm:p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.buku-tamu.index') }}" id="filterForm"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nama, Tiket, Instansi, Telepon..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Status</option>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Kategori Kunjungan</label>
                                <select name="visit_category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($visitCategories as $key => $label)
                                        <option value="{{ $key }}" {{ request('visit_category') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="sm:col-span-2 lg:col-span-5 flex flex-col sm:flex-row items-stretch sm:items-end gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 active:bg-slate-700 text-white font-medium py-2 px-4 rounded-lg text-sm shadow-sm transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    <span>Filter</span>
                                </button>
                                <a href="{{ route('admin.buku-tamu.index') }}"
                                    class="inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white font-medium py-2 px-4 rounded-lg text-sm shadow-sm transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Guest Book Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamu</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                                    <th class="hidden md:table-cell px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                    <th class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Keluar</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($guests as $guest)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            {{ $guest->ticket_number }}
                                        </td>
                                        <td class="hidden md:table-cell px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            @if ($guest->photo_url)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ $guest->photo_url }}"
                                                    alt="{{ $guest->guest_name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium text-sm">{{ substr($guest->guest_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $guest->guest_name }}</div>
                                            @if ($guest->phone)
                                                <div class="text-xs text-gray-500">{{ $guest->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guest->organization ?? '-' }}
                                        </td>
                                        <td class="hidden md:table-cell px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $visitCategories[$guest->visit_category] ?? $guest->visit_category }}
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            @if ($guest->status === 'check_in')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Sedang Berkunjung
                                                </span>
                                            @elseif ($guest->status === 'check_out')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Selesai
                                                </span>
                                            @elseif ($guest->status === 'dibatalkan')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $guest->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guest->check_in_at ? $guest->check_in_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="hidden lg:table-cell px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guest->check_out_at ? $guest->check_out_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-wrap items-center gap-1 sm:gap-1.5">
                                                @can('buku-tamu.view')
                                                    <a href="{{ route('admin.buku-tamu.show', $guest) }}"
                                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded-md transition-colors duration-150" title="Lihat Detail">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Lihat</span>
                                                    </a>
                                                @endcan
                                                @can('buku-tamu.update')
                                                    <a href="{{ route('admin.buku-tamu.edit', $guest) }}"
                                                        class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-2 py-1 rounded-md transition-colors duration-150" title="Edit">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Edit</span>
                                                    </a>
                                                @endcan
                                                @if ($guest->status === 'check_in')
                                                    @can('buku-tamu.checkout')
                                                        <form method="POST" action="{{ route('admin.buku-tamu.checkout', $guest) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="inline-flex items-center gap-1 text-orange-600 hover:text-orange-800 hover:bg-orange-50 px-2 py-1 rounded-md transition-colors duration-150"
                                                                title="Check-out"
                                                                onclick="return confirm('Check-out tamu ini?')">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                                </svg>
                                                                <span class="hidden sm:inline">Checkout</span>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                                @can('buku-tamu.delete')
                                                    <form method="POST" action="{{ route('admin.buku-tamu.destroy', $guest) }}" class="inline"
                                                        id="delete-form-{{ $guest->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded-md transition-colors duration-150"
                                                            title="Hapus"
                                                            onclick="confirmDelete({{ $guest->id }})">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span class="hidden sm:inline">Hapus</span>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-center text-gray-500 text-sm">
                                            Tidak ada data buku tamu ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $guests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showSuccess('Berhasil!', @json(session('success')));
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showError('Error!', @json(session('error')));
            });
        </script>
    @endif
</x-app-layout>
