<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Daftar Sarana</h1>
                <p class="text-slate-600 mt-1">Kelola data inventaris sarana sekolah</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.sarana.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Sarana
                </a>
                <a href="{{ route('admin.sarpras.sarana.exportExcel', request()->query()) }}" class="btn btn-success">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
                <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="btn btn-info">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Import Excel
                </button>
                <a href="{{ route('admin.sarpras.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.sarpras.sarana.index') }}" id="filterForm"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode inventaris, nama barang, ruang..." class="form-input">
                </div>
                <div class="flex gap-2">
                    <select name="kategori_id" class="form-input">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <select name="sumber_dana" class="form-input">
                        <option value="">Sumber Dana</option>
                        @foreach ($sumberDanas as $sd)
                            <option value="{{ $sd }}" {{ request('sumber_dana') == $sd ? 'selected' : '' }}>
                                {{ $sd }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.sarpras.sarana.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Sarana Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Inventaris</th>
                            <th>Nama Barang</th>
                            <th>Ruang</th>
                            <th>Kategori</th>
                            <th>Sumber Dana</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($saranas as $index => $sarana)
                            @php
                                $firstBarang = $sarana->barang->first();
                            @endphp
                            <tr>
                                <td>{{ $saranas->firstItem() + $index }}</td>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-blue-600">
                                        {{ $sarana->kode_inventaris }}
                                    </span>
                                </td>
                                <td>
                                    @if ($firstBarang)
                                        <div>
                                            <p class="font-medium text-slate-900">{{ $firstBarang->nama_barang }}</p>
                                            @if ($sarana->barang->count() > 1)
                                                <p class="text-xs text-slate-500">
                                                    +{{ $sarana->barang->count() - 1 }} barang lainnya
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ $sarana->ruang->nama_ruang ?? '-' }}</span>
                                </td>
                                <td>
                                    @if ($firstBarang && $firstBarang->kategori)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $firstBarang->kategori->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <p class="text-sm text-slate-900">{{ $sarana->sumber_dana ?? '-' }}</p>
                                        @if ($sarana->kode_sumber_dana)
                                            <p class="text-xs text-slate-500">{{ $sarana->kode_sumber_dana }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-slate-900">
                                        {{ $sarana->total_jumlah }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $kondisiCounts = [];
                                        foreach ($sarana->barang as $barang) {
                                            $kondisi = $barang->pivot->kondisi;
                                            $jumlah = $barang->pivot->jumlah;
                                            $kondisiCounts[$kondisi] = ($kondisiCounts[$kondisi] ?? 0) + $jumlah;
                                        }
                                        $kondisiDisplay = [];
                                        foreach ($kondisiCounts as $kondisi => $count) {
                                            $badgeColor = match ($kondisi) {
                                                'baik' => 'green',
                                                'rusak' => 'red',
                                                'hilang' => 'gray',
                                                default => 'gray',
                                            };
                                            $kondisiText = match ($kondisi) {
                                                'baik' => 'Baik',
                                                'rusak' => 'Rusak',
                                                'hilang' => 'Hilang',
                                                default => 'Tidak Diketahui',
                                            };
                                            $kondisiDisplay[] = "<span class='badge badge-{$badgeColor}'>{$kondisiText} ({$count})</span>";
                                        }
                                    @endphp
                                    <div class="flex flex-wrap gap-1">
                                        {!! implode('', $kondisiDisplay) !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.sarpras.sarana.show', $sarana) }}"
                                            class="btn btn-sm btn-secondary" title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.sarpras.sarana.edit', $sarana) }}"
                                            class="btn btn-sm btn-secondary" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.sarpras.sarana.printInvoice', $sarana) }}"
                                            class="btn btn-sm btn-primary" title="Cetak Invoice" target="_blank">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.sarpras.sarana.destroy', $sarana) }}" method="POST"
                                            class="inline delete-form"
                                            data-sarana="{{ $sarana->kode_inventaris }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-slate-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="text-slate-600 font-medium">Tidak ada data sarana</p>
                                        <p class="text-slate-400 text-sm mt-1">Mulai dengan menambahkan sarana baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($saranas->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $saranas->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Wait for Sweet Alert functions to be available
            function initSaranaScripts() {
                // Check if Sweet Alert functions are available
                if (typeof showSuccess === 'undefined' || typeof showError === 'undefined' || typeof showConfirm === 'undefined') {
                    // Retry after a short delay
                    setTimeout(initSaranaScripts, 100);
                    return;
                }

                // Show success/error messages with Sweet Alert
                @if (session('success'))
                    showSuccess('Berhasil!', '{{ session('success') }}');
                @endif

                @if (session('error'))
                    showError('Error!', '{{ session('error') }}');
                @endif

                @if ($errors->any())
                    showError('Terjadi Kesalahan!', '{!! implode('<br>', $errors->all()) !!}');
                @endif

                // Delete confirmation with Sweet Alert
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const saranaKode = this.getAttribute('data-sarana');
                        
                        showConfirm(
                            'Hapus Sarana?',
                            `Apakah Anda yakin ingin menghapus sarana dengan kode ${saranaKode}? Tindakan ini tidak dapat dibatalkan.`,
                            'Ya, Hapus',
                            'Batal'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSaranaScripts);
            } else {
                // DOM is already ready
                initSaranaScripts();
            }
        </script>
    @endpush

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Import Sarana dari Excel</h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-3">
                        Upload file Excel (.xlsx, .xls, atau .csv) yang berisi data sarana.
                    </p>
                    <a href="{{ route('admin.sarpras.sarana.downloadTemplate') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Template Excel
                    </a>
                </div>

                <form action="{{ route('admin.sarpras.sarana.importExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required class="form-input">
                        <p class="text-xs text-slate-500 mt-1">Format: .xlsx, .xls, atau .csv (Max: 10MB)</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="btn btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

