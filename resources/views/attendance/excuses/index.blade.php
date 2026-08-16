<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">Kelola data izin, sakit, cuti, dan dinas luar</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('admin.absensi.excuses.create') }}" class="btn btn-primary">+ Tambah Izin</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Notifikasi -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.absensi.excuses.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama..."
                        class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <select name="type" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                        <option value="">Semua</option>
                        <option value="izin" {{ request('type') === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('type') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="cuti" {{ request('type') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="dinas" {{ request('type') === 'dinas' ? 'selected' : '' }}>Dinas Luar</option>
                        <option value="alpha" {{ request('type') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full text-sm">Filter</button>
                </div>
            </form>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($excuses as $excuse)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $excuse->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $excuse->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $excuse->type_label }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700 max-w-xs truncate">{{ $excuse->reason }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 bg-{{ $excuse->status_color }}-100 text-{{ $excuse->status_color }}-800 rounded-full text-xs font-medium">{{ $excuse->status_label }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $excuse->creator?->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.absensi.excuses.show', $excuse) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                                    @if ($excuse->status === 'pending')
                                        <form method="POST" action="{{ route('admin.absensi.excuses.approve', $excuse) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium" onclick="return confirm('Setujui izin ini?')">Setuju</button>
                                        </form>
                                        <a href="{{ route('admin.absensi.excuses.edit', $excuse) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data izin/sakit</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $excuses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
