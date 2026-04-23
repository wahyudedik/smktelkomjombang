<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Kegiatan</h1>
                <p class="text-slate-600 mt-1">Kelola kegiatan sekolah yang tampil di landing page</p>
            </div>
            @can('events.create')
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kegiatan
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Filter -->
        <div class="card mb-6">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.events.index') }}" id="filterForm"
                    class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau kategori..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="document.getElementById('filterForm').submit();">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="category"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="document.getElementById('filterForm').submit();">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($events as $event)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500">{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>
                                    <td class="px-6 py-4">
                                        @if ($event->image)
                                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}"
                                                class="w-16 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-slate-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $event->title }}</div>
                                        @if ($event->description)
                                            <div class="text-slate-500 text-xs mt-1 line-clamp-1">{{ Str::limit($event->description, 60) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <div class="font-medium">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($event->category)
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                                {{ $event->category }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($event->status === 'active')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                                        @elseif ($event->status === 'inactive')
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Nonaktif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            @can('events.view')
                                                <a href="{{ route('admin.events.show', $event) }}"
                                                    class="p-1.5 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('events.edit')
                                                <a href="{{ route('admin.events.edit', $event) }}"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('events.delete')
                                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <i class="fas fa-calendar-times text-4xl text-slate-300 mb-3 block"></i>
                                        Belum ada kegiatan. <a href="{{ route('admin.events.create') }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($events->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
