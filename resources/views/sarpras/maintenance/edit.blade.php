@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Maintenance</h1>
                <p class="text-slate-600 mt-1">Update maintenance record information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.sarpras.maintenance.show', $maintenance) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
                <a href="{{ route('admin.sarpras.maintenance.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Maintenance Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.sarpras.maintenance.update', $maintenance) }}"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Type -->
                        <div>
                            <label for="jenis_item" class="form-label">Item Type</label>
                            <select id="jenis_item" name="jenis_item" required
                                class="form-input @error('jenis_item') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Select item type</option>
                                <option value="barang"
                                    {{ old('jenis_item', $maintenance->jenis_item) === 'barang' ? 'selected' : '' }}>
                                    Barang</option>
                                <option value="ruang"
                                    {{ old('jenis_item', $maintenance->jenis_item) === 'ruang' ? 'selected' : '' }}>
                                    Ruang
                                </option>
                            </select>
                            @error('jenis_item')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item ID -->
                        <div>
                            <label for="item_id" class="form-label">Item</label>
                            <select id="item_id" name="item_id" required
                                class="form-input @error('item_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Select item</option>
                                @if ($maintenance->jenis_item === 'barang')
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}"
                                            {{ old('item_id', $maintenance->item_id) == $barang->id ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                @elseif($maintenance->jenis_item === 'ruang')
                                    @foreach ($ruangs as $ruang)
                                        <option value="{{ $ruang->id }}"
                                            {{ old('item_id', $maintenance->item_id) == $ruang->id ? 'selected' : '' }}>
                                            {{ $ruang->nama_ruang }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('item_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maintenance Type -->
                        <div>
                            <label for="jenis_maintenance" class="form-label">Maintenance Type</label>
                            <select id="jenis_maintenance" name="jenis_maintenance" required
                                class="form-input @error('jenis_maintenance') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Select maintenance type</option>
                                <option value="rutin"
                                    {{ old('jenis_maintenance', $maintenance->jenis_maintenance) === 'rutin' ? 'selected' : '' }}>
                                    Rutin</option>
                                <option value="perbaikan"
                                    {{ old('jenis_maintenance', $maintenance->jenis_maintenance) === 'perbaikan' ? 'selected' : '' }}>
                                    Perbaikan</option>
                                <option value="pembersihan"
                                    {{ old('jenis_maintenance', $maintenance->jenis_maintenance) === 'pembersihan' ? 'selected' : '' }}>
                                    Pembersihan</option>
                                <option value="inspeksi"
                                    {{ old('jenis_maintenance', $maintenance->jenis_maintenance) === 'inspeksi' ? 'selected' : '' }}>
                                    Inspeksi</option>
                            </select>
                            @error('jenis_maintenance')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" required
                                class="form-input @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Select status</option>
                                <option value="dijadwalkan"
                                    {{ old('status', $maintenance->status) === 'dijadwalkan' ? 'selected' : '' }}>
                                    Dijadwalkan</option>
                                <option value="sedang_dikerjakan"
                                    {{ old('status', $maintenance->status) === 'sedang_dikerjakan' ? 'selected' : '' }}>
                                    Sedang Dikerjakan</option>
                                <option value="dalam_proses"
                                    {{ old('status', $maintenance->status) === 'dalam_proses' ? 'selected' : '' }}>
                                    Dalam Proses</option>
                                <option value="selesai"
                                    {{ old('status', $maintenance->status) === 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan"
                                    {{ old('status', $maintenance->status) === 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Cost -->
                        <div>
                            <label for="biaya" class="form-label">Cost (Rp)</label>
                            <input type="number" id="biaya" name="biaya"
                                value="{{ old('biaya', $maintenance->biaya) }}"
                                class="form-input @error('biaya') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter maintenance cost" min="0">
                            @error('biaya')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Technician -->
                        <div>
                            <label for="teknisi" class="form-label">Technician</label>
                            <input type="text" id="teknisi" name="teknisi"
                                value="{{ old('teknisi', $maintenance->teknisi) }}"
                                class="form-input @error('teknisi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter technician name">
                            @error('teknisi')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maintenance Date -->
                        <div>
                            <label for="tanggal_maintenance" class="form-label">Maintenance Date</label>
                            <input type="date" id="tanggal_maintenance" name="tanggal_maintenance"
                                value="{{ old('tanggal_maintenance', $maintenance->tanggal_maintenance?->format('Y-m-d')) }}"
                                class="form-input @error('tanggal_maintenance') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('tanggal_maintenance')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Completion Date -->
                        <div>
                            <label for="tanggal_selesai" class="form-label">Completion Date</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $maintenance->tanggal_selesai?->format('Y-m-d')) }}"
                                class="form-input @error('tanggal_selesai') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('tanggal_selesai')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Problem Description -->
                    <div>
                        <label for="deskripsi_masalah" class="form-label">Problem Description</label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4"
                            class="form-input @error('deskripsi_masalah') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter problem description">{{ old('deskripsi_masalah', $maintenance->deskripsi_masalah) }}</textarea>
                        @error('deskripsi_masalah')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Repair Action -->
                    <div>
                        <label for="tindakan_perbaikan" class="form-label">Repair Action</label>
                        <textarea id="tindakan_perbaikan" name="tindakan_perbaikan" rows="4"
                            class="form-input @error('tindakan_perbaikan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter repair action taken">{{ old('tindakan_perbaikan', $maintenance->tindakan_perbaikan) }}</textarea>
                        @error('tindakan_perbaikan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="catatan" class="form-label">Notes</label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="form-input @error('catatan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter maintenance notes">{{ old('catatan', $maintenance->catatan) }}</textarea>
                        @error('catatan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Before Photo -->
                    <div>
                        <label for="foto_sebelum" class="form-label">Before Photo</label>
                        <input type="file" id="foto_sebelum" name="foto_sebelum" accept="image/*"
                            class="form-input @error('foto_sebelum') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('foto_sebelum')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- After Photo -->
                    <div>
                        <label for="foto_sesudah" class="form-label">After Photo</label>
                        <input type="file" id="foto_sesudah" name="foto_sesudah" accept="image/*"
                            class="form-input @error('foto_sesudah') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('foto_sesudah')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Photos -->
                    @if ($maintenance->foto_sebelum || $maintenance->foto_sesudah)
                        <div>
                            <label class="form-label">Current Photos</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($maintenance->foto_sebelum)
                                    <div class="relative">
                                        <img src="{{ Storage::url($maintenance->foto_sebelum) }}" alt="Before photo"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <p class="text-sm text-slate-500 mt-1">Before Photo</p>
                                    </div>
                                @endif
                                @if ($maintenance->foto_sesudah)
                                    <div class="relative">
                                        <img src="{{ Storage::url($maintenance->foto_sesudah) }}" alt="After photo"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <p class="text-sm text-slate-500 mt-1">After Photo</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                        <a href="{{ route('admin.sarpras.maintenance.show', $maintenance) }}"
                            class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Maintenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Data untuk dropdown
        const barangs = @json($barangs);
        const ruangs = @json($ruangs);

        // Update item options based on item type
        document.getElementById('jenis_item').addEventListener('change', function() {
            const itemType = this.value;
            const itemSelect = document.getElementById('item_id');

            // Clear existing options
            itemSelect.innerHTML = '<option value="">Select item</option>';

            if (itemType === 'barang') {
                barangs.forEach(function(barang) {
                    itemSelect.innerHTML += '<option value="' + barang.id + '">' + barang.nama_barang +
                        '</option>';
                });
            } else if (itemType === 'ruang') {
                ruangs.forEach(function(ruang) {
                    itemSelect.innerHTML += '<option value="' + ruang.id + '">' + ruang.nama_ruang +
                        '</option>';
                });
            }
        });

        // Trigger change event on page load if jenis_item has a value
        document.addEventListener('DOMContentLoaded', function() {
            const itemTypeSelect = document.getElementById('jenis_item');
            const itemSelect = document.getElementById('item_id');
            const currentItemId = itemSelect.value; // Get current selected item ID

            if (itemTypeSelect.value) {
                // Trigger change event to populate dropdown
                itemTypeSelect.dispatchEvent(new Event('change'));

                // After a short delay, set the selected value
                setTimeout(function() {
                    if (currentItemId) {
                        itemSelect.value = currentItemId;
                    }
                }, 100);
            }
        });

        function removePhoto(photoName) {
            showConfirm(
                'Konfirmasi',
                'Apakah Anda yakin ingin menghapus foto ini?',
                'Ya, Hapus',
                'Batal'
            ).then((result) => {
                if (result.isConfirmed) {
                    // In a real implementation, this would make an AJAX call to remove the photo
                    console.log('Removing photo:', photoName);
                    showSuccess('Foto berhasil dihapus');
                }
            });
        }
    </script>
</x-app-layout>
