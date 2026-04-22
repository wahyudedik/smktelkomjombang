<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Barang Sarpras</h1>
                <p class="text-slate-600 mt-1">{{ $barang->nama_barang }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.barang.edit', $barang) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.sarpras.barang.index') }}" class="btn btn-secondary">
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
                <!-- Barang Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Informasi Barang</h3>
                        <div class="flex items-center space-x-2">
                            <span class="badge {{ $barang->kondisi_badge_color }}">
                                {{ $barang->kondisi_display }}
                            </span>
                            @if ($barang->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Nama Barang</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $barang->nama_barang }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Kode Barang</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $barang->kode_barang ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Kategori</h4>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $barang->kategori->nama_kategori }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Lokasi</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $barang->ruang->nama_ruang ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Harga</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $barang->formatted_harga }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Tahun Pembelian</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $barang->tahun_pembelian ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($barang->deskripsi)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Deskripsi</h4>
                            <p class="text-slate-900">{{ $barang->deskripsi }}</p>
                        </div>
                    @endif
                </div>

                <!-- Photo -->
                @if ($barang->photo_url)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Foto Barang</h3>
                        <div class="flex justify-center">
                            <img src="{{ $barang->photo_url }}" alt="{{ $barang->nama_barang }}"
                                class="max-w-full h-auto rounded-lg shadow-lg">
                        </div>
                    </div>
                @endif

                <!-- Maintenance History -->
                @if ($barang->maintenance && $barang->maintenance->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Riwayat Maintenance</h3>
                        <div class="space-y-4">
                            @foreach ($barang->maintenance->take(5) as $maintenance)
                                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900">{{ $maintenance->jenis_maintenance }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $maintenance->created_at->format('d M Y') }}</p>
                                    </div>
                                    <span class="badge {{ $maintenance->status_badge_color }}">
                                        {{ $maintenance->status_display }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if ($barang->maintenance->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.sarpras.maintenance.index', ['barang' => $barang->id]) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat semua {{ $barang->maintenance->count() }} maintenance
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Sarana that use this barang -->
                @if ($barang->sarana && $barang->sarana->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">Sarana yang Menggunakan Barang Ini</h3>
                            <a href="{{ route('admin.sarpras.sarana.index', ['barang_id' => $barang->id]) }}"
                                class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Lihat semua
                            </a>
                        </div>
                        <div class="space-y-4">
                            @foreach ($barang->sarana->take(5) as $sarana)
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
                                                        Ruang: {{ $sarana->ruang->nama_ruang ?? '-' }}
                                                    </p>
                                                    <span class="text-slate-300">•</span>
                                                    <p class="text-sm text-slate-500">
                                                        Jumlah: {{ $sarana->pivot->jumlah ?? 1 }}
                                                    </p>
                                                    <span class="text-slate-300">•</span>
                                                    <p class="text-sm text-slate-500">
                                                        Kondisi: {{ ucfirst($sarana->pivot->kondisi ?? 'baik') }}
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

                        @if ($barang->sarana->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.sarpras.sarana.index', ['barang_id' => $barang->id]) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat semua {{ $barang->sarana->count() }} sarana
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
                        <a href="{{ route('admin.sarpras.barang.edit', $barang) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Edit Barang</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.maintenance.create', ['barang' => $barang->id]) }}"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Tambah Maintenance</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.sarana.create', ['barang_id' => $barang->id, 'ruang_id' => $barang->ruang_id]) }}"
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

                        <a href="{{ route('admin.sarpras.barang.index') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Daftar Barang</span>
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
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Tambahan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Usia Barang</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $barang->age_display }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Sarana</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $barang->sarana->count() ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Status</span>
                            <span class="badge {{ $barang->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $barang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Dibuat</span>
                            <span class="text-sm text-slate-900">{{ $barang->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Diperbarui</span>
                            <span class="text-sm text-slate-900">{{ $barang->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
