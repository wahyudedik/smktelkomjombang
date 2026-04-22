@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Ruang Sarpras</h1>
                <p class="text-slate-600 mt-1">{{ $ruang->nama_ruang }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.ruang.show', $ruang) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.sarpras.ruang.update', $ruang) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_ruang" class="form-label">Nama Ruang</label>
                            <input type="text" id="nama_ruang" name="nama_ruang"
                                value="{{ old('nama_ruang', $ruang->nama_ruang) }}"
                                class="form-input @error('nama_ruang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan nama ruang" required>
                            @error('nama_ruang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_ruang" class="form-label">Kode Ruang</label>
                            <input type="text" id="kode_ruang" name="kode_ruang"
                                value="{{ old('kode_ruang', $ruang->kode_ruang) }}"
                                class="form-input @error('kode_ruang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan kode ruang">
                            @error('kode_ruang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_ruang" class="form-label">Jenis Ruang</label>
                            <select id="jenis_ruang" name="jenis_ruang"
                                class="form-input @error('jenis_ruang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Jenis Ruang</option>
                                <option value="Kelas"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Kelas' ? 'selected' : '' }}>Kelas
                                </option>
                                <option value="Laboratorium"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Laboratorium' ? 'selected' : '' }}>
                                    Laboratorium</option>
                                <option value="Perpustakaan"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Perpustakaan' ? 'selected' : '' }}>
                                    Perpustakaan</option>
                                <option value="Kantor"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Kantor' ? 'selected' : '' }}>Kantor
                                </option>
                                <option value="Aula"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Aula' ? 'selected' : '' }}>Aula
                                </option>
                                <option value="Lainnya"
                                    {{ old('jenis_ruang', $ruang->jenis_ruang) == 'Lainnya' ? 'selected' : '' }}>
                                    Lainnya</option>
                            </select>
                            @error('jenis_ruang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kapasitas" class="form-label">Kapasitas</label>
                            <input type="number" id="kapasitas" name="kapasitas"
                                value="{{ old('kapasitas', $ruang->kapasitas) }}"
                                class="form-input @error('kapasitas') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan kapasitas ruang">
                            @error('kapasitas')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select id="kondisi" name="kondisi"
                                class="form-input @error('kondisi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik"
                                    {{ old('kondisi', $ruang->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak"
                                    {{ old('kondisi', $ruang->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="renovasi"
                                    {{ old('kondisi', $ruang->kondisi) == 'renovasi' ? 'selected' : '' }}>Renovasi
                                </option>
                            </select>
                            @error('kondisi')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status"
                                class="form-input @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="aktif"
                                    {{ old('status', $ruang->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif"
                                    {{ old('status', $ruang->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                                <option value="renovasi"
                                    {{ old('status', $ruang->status) == 'renovasi' ? 'selected' : '' }}>Renovasi
                                </option>
                            </select>
                            @error('status')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Detail Ruang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="luas_ruang" class="form-label">Luas (m²)</label>
                            <input type="number" id="luas_ruang" name="luas_ruang"
                                value="{{ old('luas_ruang', $ruang->luas_ruang) }}"
                                class="form-input @error('luas_ruang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan luas ruang" step="0.01" min="0">
                            @error('luas_ruang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gedung" class="form-label">Gedung</label>
                            <input type="text" id="gedung" name="gedung"
                                value="{{ old('gedung', $ruang->gedung) }}"
                                class="form-input @error('gedung') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan nama gedung">
                            @error('gedung')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lantai" class="form-label">Lantai</label>
                            <input type="text" id="lantai" name="lantai"
                                value="{{ old('lantai', $ruang->lantai) }}"
                                class="form-input @error('lantai') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan lantai">
                            @error('lantai')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="foto" class="form-label">Foto Ruang</label>
                            <input type="file" id="foto" name="foto" accept="image/*"
                                class="form-input @error('foto') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if ($ruang->foto)
                                <div class="mt-2">
                                    <img src="{{ $ruang->photo_url }}" alt="Current photo"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1">Foto saat ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Fasilitas</h3>
                    <div>
                        <label for="fasilitas" class="form-label">Daftar Fasilitas</label>
                        <textarea id="fasilitas" name="fasilitas" rows="4"
                            class="form-input @error('fasilitas') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan daftar fasilitas yang tersedia (pisahkan dengan koma)">{{ old('fasilitas', $ruang->facilities_list) }}</textarea>
                        @error('fasilitas')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi</h3>
                    <div>
                        <label for="deskripsi" class="form-label">Deskripsi Ruang</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="form-input @error('deskripsi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan deskripsi ruang">{{ old('deskripsi', $ruang->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Catatan</h3>
                    <div>
                        <label for="catatan" class="form-label">Catatan Tambahan</label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="form-input @error('catatan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan catatan tambahan">{{ old('catatan', $ruang->catatan) }}</textarea>
                        @error('catatan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Pengaturan</h3>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $ruang->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-slate-700">Aktif</label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.sarpras.ruang.show', $ruang) }}" class="btn btn-secondary">
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
