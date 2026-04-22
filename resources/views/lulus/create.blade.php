<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.tambah_data_kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.lulus.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="siswa_id" class="block text-sm font-medium text-gray-700">Pilih Siswa
                                    *</label>
                                <select name="siswa_id" id="siswa_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_student_from_list') }}</option>
                                    @foreach ($siswas ?? [] as $siswa)
                                        <option value="{{ $siswa->id }}" data-nama="{{ $siswa->nama_lengkap }}"
                                            data-nis="{{ $siswa->nis }}" data-nisn="{{ $siswa->nisn }}"
                                            data-kelas="{{ $siswa->kelas }}" data-jurusan="{{ $siswa->jurusan }}"
                                            data-email="{{ $siswa->email }}"
                                            data-jenis-kelamin="{{ $siswa->jenis_kelamin }}"
                                            {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                            {{ $siswa->nama_lengkap }} - NIS: {{ $siswa->nis }} - NISN:
                                            {{ $siswa->nisn }} - {{ $siswa->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('siswa_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">{{ __('common.select_student_hint') }}</p>
                            </div>

                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap
                                    *</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                    required readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Terisi otomatis setelah memilih siswa</p>
                            </div>

                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700">NISN *</label>
                                <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                    required readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nisn')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Terisi otomatis setelah memilih siswa</p>
                            </div>

                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
                                <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                    readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Terisi otomatis setelah memilih siswa</p>
                            </div>

                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <select name="jurusan" id="jurusan"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major }}"
                                            {{ old('jurusan') == $major ? 'selected' : '' }}>
                                            {{ $major }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran
                                    *</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($tahunAjaran as $tahun)
                                        <option value="{{ $tahun }}"
                                            {{ old('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Status</option>
                                    <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus
                                    </option>
                                    <option value="tidak_lulus" {{ old('status') == 'tidak_lulus' ? 'selected' : '' }}>
                                        Tidak Lulus</option>
                                    <option value="mengulang" {{ old('status') == 'mengulang' ? 'selected' : '' }}>
                                        Mengulang</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Education Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pendidikan Lanjutan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tempat_kuliah" class="block text-sm font-medium text-gray-700">Tempat
                                        Kuliah</label>
                                    <input type="text" name="tempat_kuliah" id="tempat_kuliah"
                                        value="{{ old('tempat_kuliah') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tempat_kuliah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jurusan_kuliah" class="block text-sm font-medium text-gray-700">Jurusan
                                        Kuliah</label>
                                    <input type="text" name="jurusan_kuliah" id="jurusan_kuliah"
                                        value="{{ old('jurusan_kuliah') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('jurusan_kuliah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Work Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pekerjaan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tempat_kerja" class="block text-sm font-medium text-gray-700">Tempat
                                        Kerja</label>
                                    <input type="text" name="tempat_kerja" id="tempat_kerja"
                                        value="{{ old('tempat_kerja') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tempat_kerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jabatan_kerja" class="block text-sm font-medium text-gray-700">Jabatan
                                        Kerja</label>
                                    <input type="text" name="jabatan_kerja" id="jabatan_kerja"
                                        value="{{ old('jabatan_kerja') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('jabatan_kerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kontak</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="no_hp" class="block text-sm font-medium text-gray-700">No.
                                        HP</label>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('no_hp')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_wa" class="block text-sm font-medium text-gray-700">No.
                                        WhatsApp</label>
                                    <input type="text" name="no_wa" id="no_wa" value="{{ old('no_wa') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('no_wa')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat"
                                        class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tambahan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                                    <input type="file" name="foto" id="foto" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    @error('foto')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lulus" class="block text-sm font-medium text-gray-700">Tanggal
                                        Lulus</label>
                                    <input type="date" name="tanggal_lulus" id="tanggal_lulus"
                                        value="{{ old('tanggal_lulus') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tanggal_lulus')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700">Prestasi</label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('prestasi') }}</textarea>
                                    @error('prestasi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="border-t pt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.lulus.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siswaSelect = document.getElementById('siswa_id');
            const namaInput = document.getElementById('nama');
            const nisnInput = document.getElementById('nisn');
            const nisInput = document.getElementById('nis');
            const jurusanSelect = document.getElementById('jurusan');

            if (siswaSelect && namaInput && nisnInput && nisInput) {
                siswaSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        namaInput.value = selectedOption.getAttribute('data-nama') || '';
                        nisnInput.value = selectedOption.getAttribute('data-nisn') || '';
                        nisInput.value = selectedOption.getAttribute('data-nis') || '';

                        // Set jurusan if available
                        const jurusanValue = selectedOption.getAttribute('data-jurusan');
                        if (jurusanValue && jurusanSelect) {
                            jurusanSelect.value = jurusanValue;
                        }
                    } else {
                        namaInput.value = '';
                        nisnInput.value = '';
                        nisInput.value = '';
                    }
                });

                // Trigger on page load if value is already selected
                if (siswaSelect.value) {
                    siswaSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    </script>
</x-app-layout>
