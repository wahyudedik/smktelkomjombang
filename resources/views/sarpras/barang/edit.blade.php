<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Barang Sarpras</h1>
                <p class="text-slate-600 mt-1">{{ $barang->nama_barang }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.barang.show', $barang) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.sarpras.barang.update', $barang) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang', $barang->nama_barang) }}"
                                class="form-input @error('nama_barang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan nama barang" required>
                            @error('nama_barang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang"
                                value="{{ old('kode_barang', $barang->kode_barang) }}"
                                class="form-input @error('kode_barang') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan kode barang">
                            @error('kode_barang')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select id="kategori_id" name="kategori_id"
                                class="form-input @error('kategori_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ruang_id" class="form-label">Lokasi/Ruang</label>
                            <select id="ruang_id" name="ruang_id"
                                class="form-input @error('ruang_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">Pilih Ruang</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id', $barang->ruang_id) == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama_ruang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ruang_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Detail Barang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select id="kondisi" name="kondisi"
                                class="form-input @error('kondisi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik"
                                    {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak"
                                    {{ old('kondisi', $barang->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="hilang"
                                    {{ old('kondisi', $barang->kondisi) == 'hilang' ? 'selected' : '' }}>Hilang
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
                                <option value="tersedia"
                                    {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="dipinjam"
                                    {{ old('status', $barang->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                                </option>
                                <option value="rusak"
                                    {{ old('status', $barang->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="hilang"
                                    {{ old('status', $barang->status) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('status')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga_beli" class="form-label">Harga (Rp)</label>
                            <input type="number" id="harga_beli" name="harga_beli"
                                value="{{ old('harga_beli', $barang->harga_beli) }}"
                                class="form-input @error('harga_beli') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan harga barang">
                            @error('harga_beli')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                            <input type="date" id="tanggal_pembelian" name="tanggal_pembelian"
                                value="{{ old('tanggal_pembelian', $barang->tanggal_pembelian ? $barang->tanggal_pembelian->format('Y-m-d') : '') }}"
                                class="form-input @error('tanggal_pembelian') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan tanggal pembelian">
                            @error('tanggal_pembelian')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="foto" class="form-label">Foto Barang</label>
                            <input type="file" id="foto" name="foto" accept="image/*"
                                class="form-input @error('foto') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if ($barang->photo_url)
                                <div class="mt-2">
                                    <img src="{{ $barang->photo_url }}" alt="Current photo"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1">Foto saat ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi</h3>
                    <div>
                        <label for="deskripsi" class="form-label">Deskripsi Barang</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="form-input @error('deskripsi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan deskripsi barang">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                        @error('deskripsi')
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
                            {{ old('is_active', $barang->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-slate-700">Aktif</label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.sarpras.barang.show', $barang) }}" class="btn btn-secondary">
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
