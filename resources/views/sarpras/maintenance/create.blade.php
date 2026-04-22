<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Maintenance Sarpras</h1>
                <p class="text-slate-600 mt-1">Tambah data maintenance dan perawatan sarana prasarana</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.maintenance.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.sarpras.maintenance.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jenis_maintenance" class="form-label">Jenis Maintenance</label>
                            <select id="jenis_maintenance" name="jenis_maintenance"
                                class="form-input @error('jenis_maintenance') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Jenis Maintenance</option>
                                <option value="rutin" {{ old('jenis_maintenance') == 'rutin' ? 'selected' : '' }}>Rutin
                                </option>
                                <option value="perbaikan"
                                    {{ old('jenis_maintenance') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="pembersihan"
                                    {{ old('jenis_maintenance') == 'pembersihan' ? 'selected' : '' }}>Pembersihan
                                </option>
                                <option value="inspeksi" {{ old('jenis_maintenance') == 'inspeksi' ? 'selected' : '' }}>
                                    Inspeksi</option>
                            </select>
                            @error('jenis_maintenance')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_maintenance" class="form-label">Tanggal Maintenance</label>
                            <input type="date" id="tanggal_maintenance" name="tanggal_maintenance"
                                value="{{ old('tanggal_maintenance') }}"
                                class="form-input @error('tanggal_maintenance') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            @error('tanggal_maintenance')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_item" class="form-label">Tipe Item</label>
                            <select id="jenis_item" name="jenis_item"
                                class="form-input @error('jenis_item') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Tipe Item</option>
                                <option value="barang" {{ old('jenis_item') == 'barang' ? 'selected' : '' }}>Barang
                                </option>
                                <option value="ruang" {{ old('jenis_item') == 'ruang' ? 'selected' : '' }}>Ruang
                                </option>
                            </select>
                            @error('jenis_item')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="item_id" class="form-label">Item</label>
                            <select id="item_id" name="item_id"
                                class="form-input @error('item_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Item</option>
                                <!-- Options will be populated by JavaScript based on item_type -->
                            </select>
                            @error('item_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Detail Maintenance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status"
                                class="form-input @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="dijadwalkan" {{ old('status') == 'dijadwalkan' ? 'selected' : '' }}>
                                    Dijadwalkan</option>
                                <option value="sedang_dikerjakan"
                                    {{ old('status') == 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan
                                </option>
                                <option value="dalam_proses" {{ old('status') == 'dalam_proses' ? 'selected' : '' }}>
                                    Dalam Proses</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="biaya" class="form-label">Biaya (Rp)</label>
                            <input type="number" id="biaya" name="biaya" value="{{ old('biaya') }}"
                                class="form-input @error('biaya') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan biaya maintenance" min="0">
                            @error('biaya')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="foto_sebelum" class="form-label">Foto Sebelum</label>
                            <input type="file" id="foto_sebelum" name="foto_sebelum" accept="image/*"
                                class="form-input @error('foto_sebelum') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_sebelum')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="foto_sesudah" class="form-label">Foto Sesudah</label>
                            <input type="file" id="foto_sesudah" name="foto_sesudah" accept="image/*"
                                class="form-input @error('foto_sesudah') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_sesudah')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi</h3>
                    <div>
                        <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah</label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4"
                            class="form-input @error('deskripsi_masalah') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan deskripsi masalah">{{ old('deskripsi_masalah') }}</textarea>
                        @error('deskripsi_masalah')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="tindakan_perbaikan" class="form-label">Tindakan Perbaikan</label>
                        <textarea id="tindakan_perbaikan" name="tindakan_perbaikan" rows="4"
                            class="form-input @error('tindakan_perbaikan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan tindakan perbaikan">{{ old('tindakan_perbaikan') }}</textarea>
                        @error('tindakan_perbaikan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.sarpras.maintenance.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data untuk dropdown
        const barangs = @json($barangs);
        const ruangs = @json($ruangs);

        document.getElementById('jenis_item').addEventListener('change', function() {
            const itemType = this.value;
            const itemSelect = document.getElementById('item_id');

            // Clear existing options
            itemSelect.innerHTML = '<option value="">Pilih Item</option>';

            if (itemType === 'barang') {
                barangs.forEach(function(barang) {
                    itemSelect.innerHTML += '<option value="' + barang.id + '">' + barang.nama_barang + '</option>';
                });
            } else if (itemType === 'ruang') {
                ruangs.forEach(function(ruang) {
                    itemSelect.innerHTML += '<option value="' + ruang.id + '">' + ruang.nama_ruang + '</option>';
                });
            }
        });

        // Trigger change event on page load if jenis_item has a value
        document.addEventListener('DOMContentLoaded', function() {
            const itemTypeSelect = document.getElementById('jenis_item');
            if (itemTypeSelect.value) {
                itemTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>
