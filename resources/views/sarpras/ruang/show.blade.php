<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Ruang Sarpras</h1>
                <p class="text-slate-600 mt-1">{{ $ruang->nama_ruang }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.ruang.edit', $ruang) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.sarpras.ruang.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ruang Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Informasi Ruang</h3>
                        <div class="flex items-center space-x-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $ruang->kode_ruang }}
                            </span>
                            @if ($ruang->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Nama Ruang</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $ruang->nama_ruang }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Lokasi</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $ruang->lokasi ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Luas</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $ruang->formatted_luas }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Kapasitas</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $ruang->kapasitas ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($ruang->deskripsi)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Deskripsi</h4>
                            <p class="text-slate-900">{{ $ruang->deskripsi }}</p>
                        </div>
                    @endif
                </div>

                <!-- Photo -->
                @if ($ruang->photo_url)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Foto Ruang</h3>
                        <div class="flex justify-center">
                            <img src="{{ $ruang->photo_url }}" alt="{{ $ruang->nama_ruang }}"
                                class="max-w-full h-auto rounded-lg shadow-lg">
                        </div>
                    </div>
                @endif

                <!-- Facilities -->
                @if ($ruang->fasilitas_list && count($ruang->fasilitas_list) > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Fasilitas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($ruang->fasilitas_list as $fasilitas)
                                <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-sm text-slate-900">{{ $fasilitas }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Barang in this room -->
                @if ($ruang->barang && $ruang->barang->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Barang dalam Ruang Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($ruang->barang->take(6) as $barang)
                                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900">{{ $barang->nama_barang }}</p>
                                        <p class="text-sm text-slate-500">{{ $barang->kondisi_display }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($ruang->barang->count() > 6)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.sarpras.barang.index', ['ruang' => $ruang->id]) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat semua {{ $ruang->barang->count() }} barang
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Sarana in this room -->
                @if ($ruang->sarana && $ruang->sarana->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Sarana di Ruang Ini</h3>
                            <a href="{{ route('admin.sarpras.sarana.index', ['ruang_id' => $ruang->id]) }}"
                                class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Lihat semua
                            </a>
                        </div>
                        <div class="space-y-4">
                            @foreach ($ruang->sarana->take(5) as $sarana)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-slate-900 truncate">{{ $sarana->kode_inventaris }}</p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <p class="text-sm text-slate-500">
                                                        {{ $sarana->barang->count() }} barang
                                                    </p>
                                                    <span class="text-slate-300">•</span>
                                                    <p class="text-sm text-slate-500">
                                                        {{ $sarana->sumber_dana ?? '-' }}
                                                    </p>
                                                    <span class="text-slate-300">•</span>
                                                    <p class="text-sm text-slate-500">
                                                        {{ $sarana->tanggal->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('admin.sarpras.sarana.show', $sarana) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.sarpras.sarana.printInvoice', $sarana) }}" target="_blank"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Print Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($ruang->sarana->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.sarpras.sarana.index', ['ruang_id' => $ruang->id]) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat semua {{ $ruang->sarana->count() }} sarana
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.sarpras.ruang.edit', $ruang) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Edit Ruang</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.barang.create', ['ruang' => $ruang->id]) }}"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Tambah Barang</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.sarana.create', ['ruang_id' => $ruang->id]) }}"
                            class="flex items-center justify-between p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Tambah Sarana</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.ruang.index') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Daftar Ruang</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Barang</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $ruang->barang_count ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Sarana</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $ruang->sarana->count() ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Status</span>
                            <span class="badge {{ $ruang->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $ruang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Dibuat</span>
                            <span class="text-sm text-slate-900">{{ $ruang->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Diperbarui</span>
                            <span class="text-sm text-slate-900">{{ $ruang->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
