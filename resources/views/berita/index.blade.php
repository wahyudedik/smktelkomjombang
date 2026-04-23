<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Berita</h1>
                <p class="text-slate-600 mt-1">Kelola berita & artikel yang tampil di landing page</p>
            </div>
            @can('berita.create')
                <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Berita
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="card mb-6">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.berita.index') }}" id="filterForm"
                    class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul berita..."
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
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Reset</a>
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
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Penulis</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($beritas as $berita)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $loop->iteration + ($beritas->currentPage() - 1) * $beritas->perPage() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($berita->featured_image)
                                            <img src="{{ Storage::url($berita->featured_image) }}" alt="{{ $berita->title }}"
                                                class="w-16 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-newspaper text-slate-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 flex items-center gap-2">
                                            {{ $berita->title }}
                                            @if ($berita->is_featured)
                                                <span class="px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">Unggulan</span>
                                            @endif
                                        </div>
                                        @if ($berita->excerpt)
                                            <div class="text-slate-500 text-xs mt-1">{{ Str::limit($berita->excerpt, 70) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">
                                        {{ $berita->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">
                                        @if ($berita->published_at)
                                            {{ $berita->published_at->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($berita->status === 'published')
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Publish</span>
                                        @elseif ($berita->status === 'draft')
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            @can('berita.view')
                                                <a href="{{ route('admin.berita.show', $berita) }}"
                                                    class="p-1.5 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('berita.edit')
                                                <a href="{{ route('admin.berita.edit', $berita) }}"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('berita.delete')
                                                <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                                        <i class="fas fa-newspaper text-4xl text-slate-300 mb-3 block"></i>
                                        Belum ada berita.
                                        @can('berita.create')
                                            <a href="{{ route('admin.berita.create') }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($beritas->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200">
                        {{ $beritas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
