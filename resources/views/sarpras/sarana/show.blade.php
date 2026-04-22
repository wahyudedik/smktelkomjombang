<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Sarana</h1>
                <p class="text-slate-600 mt-1">{{ $sarana->kode_inventaris }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.sarana.edit', $sarana) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.sarpras.sarana.printInvoice', $sarana) }}" target="_blank" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Invoice
                </a>
                <a href="{{ route('admin.sarpras.sarana.index') }}" class="btn btn-secondary">
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
                <!-- Sarana Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Informasi Sarana</h3>
                        <span class="font-mono text-sm font-semibold text-blue-600">
                            {{ $sarana->kode_inventaris }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Ruang</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $sarana->ruang->nama_ruang ?? '-' }}</p>
                            <p class="text-sm text-slate-500">{{ $sarana->ruang->kode_ruang ?? '' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Tanggal</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $sarana->formatted_tanggal }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Sumber Dana</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $sarana->sumber_dana ?? '-' }}</p>
                            @if ($sarana->kode_sumber_dana)
                                <p class="text-sm text-slate-500">{{ $sarana->kode_sumber_dana }}</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Total Jumlah</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $sarana->total_jumlah }}</p>
                        </div>
                    </div>

                    @if ($sarana->catatan)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Catatan</h4>
                            <p class="text-slate-900">{{ $sarana->catatan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Barang List -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Daftar Barang</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotal = 0;
                                @endphp
                                @foreach ($sarana->barang as $index => $barang)
                                    @php
                                        $hargaBeli = $barang->harga_beli ?? 0;
                                        $jumlah = $barang->pivot->jumlah;
                                        $totalItem = $hargaBeli * $jumlah;
                                        $grandTotal += $totalItem;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <p class="font-medium text-slate-900">{{ $barang->nama_barang }}</p>
                                        </td>
                                        <td>
                                            <span class="font-mono text-sm text-slate-600">{{ $barang->kode_barang }}</span>
                                        </td>
                                        <td>
                                            @if ($barang->kategori)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $barang->kategori->nama_kategori }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-semibold text-slate-900">{{ $jumlah }}</span>
                                        </td>
                                        <td>
                                            <span class="text-slate-900">Rp {{ number_format($hargaBeli, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="font-semibold text-slate-900">Rp {{ number_format($totalItem, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeColor = match ($barang->pivot->kondisi) {
                                                    'baik' => 'green',
                                                    'rusak' => 'red',
                                                    'hilang' => 'gray',
                                                    default => 'gray',
                                                };
                                                $kondisiText = match ($barang->pivot->kondisi) {
                                                    'baik' => 'Baik',
                                                    'rusak' => 'Rusak',
                                                    'hilang' => 'Hilang',
                                                    default => 'Tidak Diketahui',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $badgeColor }}">{{ $kondisiText }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-blue-50">
                                    <td colspan="6" class="text-right font-bold text-slate-900">Grand Total:</td>
                                    <td class="font-bold text-blue-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- History / Audit Trail -->
                @if ($auditLogs && $auditLogs->count() > 0)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">History Perubahan</h3>
                        <div class="space-y-4">
                            @foreach ($auditLogs as $log)
                                <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <div class="flex-shrink-0">
                                        @php
                                            $actionConfig = match ($log->action) {
                                                'create' => [
                                                    'color' => 'green',
                                                    'bg' => 'bg-green-100',
                                                    'text' => 'text-green-600',
                                                    'badge' => 'bg-green-100 text-green-800',
                                                    'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
                                                ],
                                                'update' => [
                                                    'color' => 'blue',
                                                    'bg' => 'bg-blue-100',
                                                    'text' => 'text-blue-600',
                                                    'badge' => 'bg-blue-100 text-blue-800',
                                                    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                                                ],
                                                'delete' => [
                                                    'color' => 'red',
                                                    'bg' => 'bg-red-100',
                                                    'text' => 'text-red-600',
                                                    'badge' => 'bg-red-100 text-red-800',
                                                    'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                                                ],
                                                default => [
                                                    'color' => 'gray',
                                                    'bg' => 'bg-gray-100',
                                                    'text' => 'text-gray-600',
                                                    'badge' => 'bg-gray-100 text-gray-800',
                                                    'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                                ],
                                            };
                                        @endphp
                                        <div class="w-10 h-10 {{ $actionConfig['bg'] }} rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 {{ $actionConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $actionConfig['icon'] }}" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-medium text-slate-900">
                                                    {{ ucfirst($log->action) }} 
                                                    @if ($log->user)
                                                        oleh <span class="text-blue-600">{{ $log->user->name }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $log->created_at->format('d M Y, H:i') }} 
                                                    ({{ $log->created_at->diffForHumans() }})
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $actionConfig['badge'] }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </div>
                                        
                                        @if ($log->action === 'update' && $log->old_values && $log->new_values)
                                            <div class="mt-3 space-y-2">
                                                @php
                                                    $changedFields = [];
                                                    foreach ($log->new_values as $key => $newValue) {
                                                        $oldValue = $log->old_values[$key] ?? null;
                                                        if ($oldValue != $newValue) {
                                                            $changedFields[$key] = [
                                                                'old' => $oldValue,
                                                                'new' => $newValue,
                                                            ];
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if (count($changedFields) > 0)
                                                    <div class="text-xs font-medium text-slate-700 mb-2">Perubahan:</div>
                                                    <div class="space-y-1">
                                                        @foreach ($changedFields as $field => $values)
                                                            <div class="flex items-start space-x-2 text-xs">
                                                                <span class="font-medium text-slate-600 capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="text-red-600 line-through">{{ $values['old'] ?? '-' }}</span>
                                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                        </svg>
                                                                        <span class="text-green-600 font-medium">{{ $values['new'] ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif ($log->action === 'create' && $log->new_values)
                                            <div class="mt-2 text-xs text-slate-600">
                                                <span class="font-medium">Data yang dibuat:</span>
                                                <ul class="list-disc list-inside mt-1 space-y-1">
                                                    @foreach (array_slice($log->new_values, 0, 5) as $key => $value)
                                                        <li>
                                                            <span class="capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                                            <span class="font-medium">{{ $value ?? '-' }}</span>
                                                        </li>
                                                    @endforeach
                                                    @if (count($log->new_values) > 5)
                                                        <li class="text-slate-400">... dan {{ count($log->new_values) - 5 }} field lainnya</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        @if ($log->ip_address)
                                            <div class="mt-2 text-xs text-slate-400">
                                                IP: {{ $log->ip_address }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">History Perubahan</h3>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-slate-500">Belum ada history perubahan</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.sarpras.sarana.edit', $sarana) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Edit Sarana</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.sarpras.sarana.printInvoice', $sarana) }}" target="_blank"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Cetak Invoice</span>
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
                            <span class="text-sm font-semibold text-slate-900">{{ $sarana->barang->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Total Jumlah</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $sarana->total_jumlah }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Dibuat</span>
                            <span class="text-sm text-slate-900">{{ $sarana->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Diperbarui</span>
                            <span class="text-sm text-slate-900">{{ $sarana->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

