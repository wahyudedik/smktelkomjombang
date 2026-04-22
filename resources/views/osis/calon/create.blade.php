<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.tambah_calon') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_calon_description') }}</p>
            </div>
            <a href="{{ route('admin.osis.calon.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('common.back') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.osis.calon.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- Jenis Pencalonan -->
                <div>
                    <label for="jenis_pencalonan" class="form-label">{{ __('common.jenis_pencalonan') }} *</label>
                    <select name="jenis_pencalonan" id="jenis_pencalonan" required
                        class="form-input @error('jenis_pencalonan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">{{ __('common.select_status') }}</option>
                        <option value="ketua" {{ old('jenis_pencalonan') === 'ketua' ? 'selected' : '' }}>{{ __('common.ketua_osis') }}
                        </option>
                        <option value="wakil" {{ old('jenis_pencalonan') === 'wakil' ? 'selected' : '' }}>{{ __('common.wakil_ketua_osis') }}
                        </option>
                        <option value="pasangan" {{ old('jenis_pencalonan') === 'pasangan' ? 'selected' : '' }}>{{ __('common.pasangan_ketua_wakil') }}</option>
                    </select>
                    @error('jenis_pencalonan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                        class="form-input @error('jenis_kelamin') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">{{ __('common.select_status') }}</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>{{ __('common.laki_laki') }}</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>{{ __('common.perempuan') }}</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-slate-600 mt-1">Pilih jenis kelamin calon (untuk filter pemilihan berdasarkan
                        gender siswa)</p>
                </div>

                <!-- Ketua OSIS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">{{ __('common.data_ketua_osis') }}
                        </h3>

                        <div>
                            <label for="nama_ketua" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }} *</label>
                            <select name="nama_ketua" id="nama_ketua" required
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_student_name') }}</option>
                                @foreach ($siswas ?? [] as $siswa)
                                    <option value="{{ $siswa->nama_lengkap }}" data-nis="{{ $siswa->nis }}"
                                        data-kelas="{{ $siswa->kelas }}" data-email="{{ $siswa->email }}"
                                        data-jenis-kelamin="{{ $siswa->jenis_kelamin }}"
                                        {{ old('nama_ketua') == $siswa->nama_lengkap ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} - {{ $siswa->nis }} - {{ $siswa->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">{{ __('common.select_from_active_students') }}</p>
                        </div>

                        <div>
                            <label for="kelas_ketua" class="form-label">{{ __('common.select_class') }} {{ __('common.ketua') }} *</label>
                            <select name="kelas_ketua" id="kelas_ketua" required
                                class="form-input @error('kelas_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_ketua') == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="foto_ketua" class="form-label">Foto Ketua</label>
                            <input type="file" name="foto_ketua" id="foto_ketua" accept="image/*"
                                class="form-input @error('foto_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-200 pb-2">{{ __('common.data_wakil_osis') }}
                        </h3>

                        <div>
                            <label for="nama_wakil" class="form-label">{{ __('common.name') }} {{ __('common.wakil') }} *</label>
                            <select name="nama_wakil" id="nama_wakil" required
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_student_name') }}</option>
                                @foreach ($siswas ?? [] as $siswa)
                                    <option value="{{ $siswa->nama_lengkap }}" data-nis="{{ $siswa->nis }}"
                                        data-kelas="{{ $siswa->kelas }}" data-email="{{ $siswa->email }}"
                                        data-jenis-kelamin="{{ $siswa->jenis_kelamin }}"
                                        {{ old('nama_wakil') == $siswa->nama_lengkap ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} - {{ $siswa->nis }} - {{ $siswa->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">{{ __('common.select_from_active_students') }}</p>
                        </div>

                        <div>
                            <label for="kelas_wakil" class="form-label">{{ __('common.select_class') }} {{ __('common.wakil') }} *</label>
                            <select name="kelas_wakil" id="kelas_wakil" required
                                class="form-input @error('kelas_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_wakil') == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="foto_wakil" class="form-label">Foto Wakil</label>
                            <input type="file" name="foto_wakil" id="foto_wakil" accept="image/*"
                                class="form-input @error('foto_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div>
                    <label for="visi_misi" class="form-label">Visi & Misi *</label>
                    <textarea name="visi_misi" id="visi_misi" rows="6" required
                        class="form-input @error('visi_misi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Tuliskan visi dan misi calon OSIS">{{ old('visi_misi') }}</textarea>
                    @error('visi_misi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active" class="form-label">Status</label>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-slate-700">Aktif dalam pemilihan</label>
                    </div>
                    @error('is_active')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.osis.calon.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Calon
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'calon_create_success_' + '{{ md5(session('success') . time()) }}';
                if (!sessionStorage.getItem(successKey) && typeof showSuccess !== 'undefined') {
                    showSuccess('{{ session('success') }}');
                    sessionStorage.setItem(successKey, 'shown');
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorKey = 'calon_create_error_' + '{{ md5(session('error') . time()) }}';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorsKey = 'calon_create_errors_' + '{{ md5(json_encode($errors->all()) . time()) }}';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError('{{ $errors->first() }}');
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-fill kelas when nama_ketua is selected
            const namaKetuaSelect = document.getElementById('nama_ketua');
            const kelasKetuaSelect = document.getElementById('kelas_ketua');

            if (namaKetuaSelect && kelasKetuaSelect) {
                namaKetuaSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const kelas = selectedOption.getAttribute('data-kelas');
                        if (kelas) {
                            kelasKetuaSelect.value = kelas;
                        }
                    }
                });
            }

            // Auto-fill kelas when nama_wakil is selected
            const namaWakilSelect = document.getElementById('nama_wakil');
            const kelasWakilSelect = document.getElementById('kelas_wakil');

            if (namaWakilSelect && kelasWakilSelect) {
                namaWakilSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const kelas = selectedOption.getAttribute('data-kelas');
                        if (kelas) {
                            kelasWakilSelect.value = kelas;
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
