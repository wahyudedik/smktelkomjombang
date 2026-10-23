<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.tambah_pemilih') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_pemilih_description') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.osis.pemilih.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            {{--
                Form create manual pemilih 2 mode tipe (Alpine.js):
                - siswa (default) : NIS + kelas teks bebas
                - guru            : NIP (di kolom `nis`) + kelas otomatis 'Guru'

                Pasangan input `kelas` (dua input name sama, saling exclusive):
                yang non-aktif selalu `disabled` (statis sebelum Alpine load +
                x-bind:disabled sesudahnya) sehingga TIDAK ikut ter-submit DAN
                disembunyikan via `x-show` (+ display:none statis) agar tidak
                tampil nyasar pada mode yang salah — input mode guru memakai
                `readonly` (bukan disabled) agar value 'Guru' tetap terkirim
                ke server saat mode guru aktif.
            --}}
            <form method="POST" action="{{ route('admin.osis.pemilih.store') }}" class="space-y-6"
                x-data="pemilihForm('{{ old('user_type', 'siswa') }}')">
                @csrf

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.basic_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="user_type" class="form-label">Tipe Pemilih</label>
                            <select id="user_type" name="user_type" x-model="userType"
                                class="form-input @error('user_type') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                            </select>
                            @error('user_type')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">
                                <span class="block">Siswa: gunakan NIS / NISN</span>
                                <span class="block">Guru: gunakan NIP (Nomor Induk Pegawai)</span>
                            </p>
                        </div>

                        <div>
                            <label for="nama" class="form-label">{{ __('common.full_name') }}</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                class="form-input @error('nama') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_full_name') }}" required>
                            @error('nama')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nis" class="form-label">
                                <span x-show="userType !== 'guru'">{{ __('common.nis_student_number') }}</span>
                                <span x-show="userType === 'guru'" style="display: none;">NIP (Nomor Induk
                                    Pegawai)</span>
                            </label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis') }}"
                                class="form-input @error('nis') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_nis') }}" required>
                            @error('nis')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1" x-show="userType === 'guru'"
                                style="display: none;">
                                NIP (Nomor Induk Pegawai) — dipakai untuk verifikasi
                            </p>
                        </div>

                        <div>
                            <label for="kelas" class="form-label">{{ __('common.class_label') }}</label>
                            {{-- Mode Siswa: teks bebas (disembunyikan + dinonaktifkan saat mode guru) --}}
                            <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}"
                                x-show="userType !== 'guru'"
                                x-bind:disabled="userType === 'guru'"
                                class="form-input @error('kelas') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_class') }}" required>
                            {{-- Mode Guru: value 'Guru' — readonly agar TETAP ter-submit (disabled tidak submit);
                                 x-show + display:none statis agar TIDAK tampil nyasar di mode siswa --}}
                            <input type="text" id="kelas_guru" name="kelas" value="Guru" readonly disabled
                                x-show="userType === 'guru'" style="display: none;"
                                x-bind:disabled="userType !== 'guru'"
                                class="form-input bg-slate-50 text-slate-600">
                            @error('kelas')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="form-input @error('jenis_kelamin') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">{{ __('common.select_gender') }}</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>{{ __('common.laki_laki') }}
                                </option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>{{ __('common.perempuan') }}
                                </option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan email">
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.additional_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor_hp" class="form-label">{{ __('common.phone_number') }}</label>
                            <input type="text" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp') }}"
                                class="form-input @error('nomor_hp') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_phone_number') }}">
                            @error('nomor_hp')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="form-input @error('alamat') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Pengaturan Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-slate-700">Aktif dalam pemilihan</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="status_sudah_memilih" name="status_sudah_memilih"
                                value="1" {{ old('status_sudah_memilih', false) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="status_sudah_memilih" class="ml-2 text-sm text-slate-700">Sudah
                                memilih</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.osis.pemilih.index') }}" class="btn btn-secondary">
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
                        Simpan Pemilih
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'pemilih_create_success_' + '{{ md5(session('success') . time()) }}';
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
                const errorKey = 'pemilih_create_error_' + '{{ md5(session('error') . time()) }}';
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
                const errorsKey = 'pemilih_create_errors_' + '{{ md5(json_encode($errors->all()) . time()) }}';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError(@json($errors->first()));
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif

    <script>
        // Alpine component: toggle mode tipe pemilih (Siswa default / Guru).
        // Field `kelas` memakai pasangan input name sama — yang non-aktif selalu
        // `disabled` (statis saat render + x-bind:disabled sesudah Alpine init)
        // sehingga tidak ikut ter-submit; input mode guru `readonly` agar value
        // 'Guru' tetap terkirim ke server.
        function pemilihForm(userType) {
            return {
                userType: ['siswa', 'guru'].includes(userType) ? userType : 'siswa',
            };
        }
    </script>
</x-app-layout>
