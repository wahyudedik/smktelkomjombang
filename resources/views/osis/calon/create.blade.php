<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
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
            {{--
                Form calon 2-mode sumber data (Alpine.js):
                - siswa  (default) : select nama dari $siswas + kelas auto-fill display-only
                - manual           : text input nama bebas (tanpa baris di siswas)

                Calon HANYA Siswa/Manual — guru TIDAK menjadi calon OSIS
                (validasi server-side `user_type_* => in:siswa` menolak 'guru').

                Field nama_ketua/nama_wakil memakai `name` yang sama di kedua mode,
                namun field mode non-aktif selalu `disabled` (statis sebelum Alpine
                load + x-bind:disabled sesudahnya) sehingga TIDAK ikut ter-submit
                dan required pada field tersembunyi tidak memblokir submit.
            --}}
            <form method="POST" action="{{ route('admin.osis.calon.store') }}" enctype="multipart/form-data"
                class="space-y-6"
                x-data="calonForm('{{ old('sumber_ketua', 'siswa') }}', '{{ old('sumber_wakil', 'siswa') }}')">
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

                        <!-- Sumber Data Ketua -->
                        <div>
                            <label for="sumber_ketua" class="form-label">Sumber Data Ketua</label>
                            <select name="sumber_ketua" id="sumber_ketua" x-model="sumberKetua"
                                class="form-input">
                                <option value="siswa">Siswa</option>
                                <option value="manual">Manual (ketik nama)</option>
                            </select>
                            {{-- Penanda asal calon → kolom calons.user_type (null = manual/legacy) --}}
                            <input type="hidden" name="user_type_ketua"
                                :value="sumberKetua === 'siswa' ? 'siswa' : ''">
                        </div>

                        <!-- Mode: Siswa -->
                        <div x-show="sumberKetua === 'siswa'">
                            <label for="nama_ketua" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }} *</label>
                            <select name="nama_ketua" id="nama_ketua" required
                                x-bind:disabled="sumberKetua !== 'siswa'"
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($siswas ?? collect())->isEmpty() ? 'Belum ada data siswa aktif' : __('common.select_student_name') }}</option>
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

                        <!-- Mode: Manual -->
                        <div x-show="sumberKetua === 'manual'" style="display: none;">
                            <label for="nama_ketua_manual" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }} *</label>
                            <input type="text" name="nama_ketua" id="nama_ketua_manual" maxlength="255" disabled
                                x-bind:disabled="sumberKetua !== 'manual'"
                                x-bind:required="sumberKetua === 'manual'"
                                value="{{ old('nama_ketua') }}" placeholder="Ketik nama ketua"
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('nama_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">Nama bebas — tidak harus ada di data siswa (ketik
                                manual).</p>
                        </div>

                        <div x-show="sumberKetua === 'siswa'">
                            {{-- disabled: kelas melekat di data siswa, tidak ada kolom kelas_ketua di tabel calons (field display-only, JS auto-fill via id) --}}
                            <label for="kelas_ketua" class="form-label">{{ __('common.select_class') }} {{ __('common.ketua') }}</label>
                            <select name="kelas_ketua" id="kelas_ketua" disabled
                                class="form-input">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_ketua') == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
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

                        <!-- Sumber Data Wakil -->
                        <div>
                            <label for="sumber_wakil" class="form-label">Sumber Data Wakil</label>
                            <select name="sumber_wakil" id="sumber_wakil" x-model="sumberWakil"
                                class="form-input">
                                <option value="siswa">Siswa</option>
                                <option value="manual">Manual (ketik nama)</option>
                            </select>
                            {{-- Penanda asal wakil → fallback derive server-side bila ketua manual --}}
                            <input type="hidden" name="user_type_wakil"
                                :value="sumberWakil === 'siswa' ? 'siswa' : ''">
                        </div>

                        <!-- Mode: Siswa -->
                        <div x-show="sumberWakil === 'siswa'">
                            <label for="nama_wakil" class="form-label">{{ __('common.name') }} {{ __('common.wakil') }} *</label>
                            <select name="nama_wakil" id="nama_wakil" required
                                x-bind:disabled="sumberWakil !== 'siswa'"
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($siswas ?? collect())->isEmpty() ? 'Belum ada data siswa aktif' : __('common.select_student_name') }}</option>
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

                        <!-- Mode: Manual -->
                        <div x-show="sumberWakil === 'manual'" style="display: none;">
                            <label for="nama_wakil_manual" class="form-label">{{ __('common.name') }} {{ __('common.wakil') }} *</label>
                            <input type="text" name="nama_wakil" id="nama_wakil_manual" maxlength="255" disabled
                                x-bind:disabled="sumberWakil !== 'manual'"
                                x-bind:required="sumberWakil === 'manual'"
                                value="{{ old('nama_wakil') }}" placeholder="Ketik nama wakil"
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('nama_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">Nama bebas — tidak harus ada di data siswa (ketik
                                manual).</p>
                        </div>

                        <div x-show="sumberWakil === 'siswa'">
                            {{-- disabled: kelas melekat di data siswa, tidak ada kolom kelas_wakil di tabel calons (field display-only, JS auto-fill via id) --}}
                            <label for="kelas_wakil" class="form-label">{{ __('common.select_class') }} {{ __('common.wakil') }}</label>
                            <select name="kelas_wakil" id="kelas_wakil" disabled
                                class="form-input">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_wakil') == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
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
                    showSuccess(@json(session('success')));
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
                    showError(@json(session('error')));
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
                    showError(@json($errors->first()));
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif

    <script>
        // Alpine component: toggle visibility 2 mode sumber (Siswa/Manual).
        // Guru bukan opsi calon — nilai legacy 'guru' (old input/data lama)
        // di-fallback ke default mode.
        // Field mode non-aktif di-`disabled` (statis saat render + x-bind:disabled
        // sesudah Alpine init) sehingga tidak ikut ter-submit dan `required` pada
        // field tersembunyi tidak memblokir submit browser.
        function calonForm(sumberKetua, sumberWakil) {
            return {
                sumberKetua: ['siswa', 'manual'].includes(sumberKetua) ? sumberKetua : 'siswa',
                sumberWakil: ['siswa', 'manual'].includes(sumberWakil) ? sumberWakil : 'siswa',
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Sync display-only kelas field from the selected student's data-kelas attribute.
            // Field kelas_* tidak disubmit ke server (tidak ada kolom di tabel calons).
            function syncKelas(namaSelect, kelasSelect) {
                if (!namaSelect || !kelasSelect) return;
                const selectedOption = namaSelect.options[namaSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const kelas = selectedOption.getAttribute('data-kelas');
                    if (kelas) {
                        kelasSelect.value = kelas;
                    }
                }
            }

            const namaKetuaSelect = document.getElementById('nama_ketua');
            const kelasKetuaSelect = document.getElementById('kelas_ketua');
            const namaWakilSelect = document.getElementById('nama_wakil');
            const kelasWakilSelect = document.getElementById('kelas_wakil');

            // Auto-fill kelas when nama is selected
            if (namaKetuaSelect && kelasKetuaSelect) {
                namaKetuaSelect.addEventListener('change', function() {
                    syncKelas(namaKetuaSelect, kelasKetuaSelect);
                });
            }
            if (namaWakilSelect && kelasWakilSelect) {
                namaWakilSelect.addEventListener('change', function() {
                    syncKelas(namaWakilSelect, kelasWakilSelect);
                });
            }

            // Sync saat halaman dimuat (agar nilai tampil langsung, bukan kosong)
            syncKelas(namaKetuaSelect, kelasKetuaSelect);
            syncKelas(namaWakilSelect, kelasWakilSelect);
        });
    </script>
</x-app-layout>
