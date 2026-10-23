<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.edit_calon_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ $calon->full_candidate_name }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.osis.calon.show', $calon) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ __('common.view_details') }}
                </a>
                <a href="{{ route('admin.osis.calon.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // Mode sumber default saat edit: user_type tersimpan ('siswa'/'guru') ATAU
        // 'manual' untuk data legacy (user_type null) — nama lama tetap tampil apa
        // adanya di text input tanpa mengubah data.
        $defaultKetua = old('sumber_ketua', $calon->user_type ?? 'manual');
        $defaultWakil = old('sumber_wakil', $calon->user_type ?? 'manual');
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            {{--
                Form edit calon 3-mode sumber data (Alpine.js) — sinkron dengan create.
                Field nama_ketua/nama_wakil memakai `name` yang sama di ketiga mode,
                namun field mode non-aktif selalu `disabled` (statis sesuai mode default
                sebelum Alpine load + x-bind:disabled sesudahnya) sehingga TIDAK ikut
                ter-submit dan required pada field tersembunyi tidak memblokir submit.
            --}}
            <form method="POST" action="{{ route('admin.osis.calon.update', $calon) }}" enctype="multipart/form-data"
                class="space-y-6"
                x-data="calonForm('{{ $defaultKetua }}', '{{ $defaultWakil }}')">
                @csrf
                @method('PUT')

                <!-- Ketua Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.chairman_info') }}</h3>

                    <!-- Sumber Data Ketua -->
                    <div class="mb-4">
                        <label for="sumber_ketua" class="form-label">Sumber Data Ketua</label>
                        <select name="sumber_ketua" id="sumber_ketua" x-model="sumberKetua"
                            class="form-input">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="manual">Manual (ketik nama)</option>
                        </select>
                        {{-- Penanda asal calon → kolom calons.user_type (null = manual/legacy) --}}
                        <input type="hidden" name="user_type_ketua"
                            :value="sumberKetua === 'siswa' ? 'siswa' : (sumberKetua === 'guru' ? 'guru' : '')">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mode: Siswa -->
                        <div x-show="sumberKetua === 'siswa'" style="{{ $defaultKetua !== 'siswa' ? 'display: none;' : '' }}">
                            <label for="nama_ketua" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }}</label>
                            <select name="nama_ketua" id="nama_ketua"
                                {{ $defaultKetua !== 'siswa' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberKetua !== 'siswa'"
                                x-bind:required="sumberKetua === 'siswa'"
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($siswas ?? collect())->isEmpty() ? 'Belum ada data siswa aktif' : __('common.select_student_name') }}</option>
                                @foreach ($siswas ?? [] as $siswa)
                                    <option value="{{ $siswa->nama_lengkap }}" data-nis="{{ $siswa->nis }}"
                                        data-kelas="{{ $siswa->kelas }}" data-email="{{ $siswa->email }}"
                                        data-jenis-kelamin="{{ $siswa->jenis_kelamin }}"
                                        {{ old('nama_ketua', $calon->nama_ketua) == $siswa->nama_lengkap ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} - {{ $siswa->nis }} - {{ $siswa->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">{{ __('common.select_from_active_students') }}</p>
                        </div>

                        <!-- Mode: Guru -->
                        <div x-show="sumberKetua === 'guru'" style="{{ $defaultKetua !== 'guru' ? 'display: none;' : '' }}">
                            <label for="nama_ketua_guru" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }}</label>
                            <select name="nama_ketua" id="nama_ketua_guru"
                                {{ $defaultKetua !== 'guru' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberKetua !== 'guru'"
                                x-bind:required="sumberKetua === 'guru'"
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($gurus ?? collect())->isEmpty() ? 'Belum ada data guru aktif' : 'Pilih nama guru' }}</option>
                                @foreach ($gurus ?? [] as $guru)
                                    <option value="{{ $guru->nama_lengkap }}" data-jenis-kelamin="{{ $guru->jenis_kelamin }}"
                                        {{ old('nama_ketua', $calon->nama_ketua) == $guru->nama_lengkap ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}{{ $guru->nip ? ' - ' . $guru->nip : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if (($gurus ?? collect())->isEmpty())
                                <p class="text-sm text-amber-600 mt-1">Belum ada data guru aktif di sistem. Pilih
                                    <strong>Manual (ketik nama)</strong> untuk memasukkan nama guru secara bebas.</p>
                            @else
                                <p class="text-sm text-slate-600 mt-1">Pilih dari daftar guru aktif</p>
                            @endif
                            <div class="mt-4">
                                <label class="form-label">{{ __('common.select_class') }} {{ __('common.ketua') }}</label>
                                <input type="text" value="Guru" readonly
                                    class="form-input bg-slate-50 text-slate-600">
                            </div>
                        </div>

                        <!-- Mode: Manual -->
                        <div x-show="sumberKetua === 'manual'" style="{{ $defaultKetua !== 'manual' ? 'display: none;' : '' }}">
                            <label for="nama_ketua_manual" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }}</label>
                            <input type="text" name="nama_ketua" id="nama_ketua_manual" maxlength="255"
                                {{ $defaultKetua !== 'manual' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberKetua !== 'manual'"
                                x-bind:required="sumberKetua === 'manual'"
                                value="{{ old('nama_ketua', $calon->nama_ketua) }}" placeholder="Ketik nama ketua"
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('nama_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">Nama bebas, boleh siswa atau guru — data siswa/guru
                                tidak harus ada di sistem.</p>
                        </div>

                        <!-- Kelas (display-only, mode siswa saja) -->
                        <div x-show="sumberKetua === 'siswa'" style="{{ $defaultKetua !== 'siswa' ? 'display: none;' : '' }}">
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
                            <label for="foto_ketua" class="form-label">{{ __('common.chairman_photo') }}</label>
                            <input type="file" id="foto_ketua" name="foto_ketua" accept="image/*"
                                class="form-input @error('foto_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if ($calon->ketua_photo_url)
                                <div class="mt-2">
                                    <img src="{{ $calon->ketua_photo_url }}" alt="{{ __('common.current_photo') }}"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1">{{ __('common.current_photo') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Wakil Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.vice_chairman_info') }}</h3>

                    <!-- Sumber Data Wakil -->
                    <div class="mb-4">
                        <label for="sumber_wakil" class="form-label">Sumber Data Wakil</label>
                        <select name="sumber_wakil" id="sumber_wakil" x-model="sumberWakil"
                            class="form-input">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="manual">Manual (ketik nama)</option>
                        </select>
                        {{-- Penanda asal wakil → fallback derive server-side bila ketua manual --}}
                        <input type="hidden" name="user_type_wakil"
                            :value="sumberWakil === 'siswa' ? 'siswa' : (sumberWakil === 'guru' ? 'guru' : '')">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mode: Siswa -->
                        <div x-show="sumberWakil === 'siswa'" style="{{ $defaultWakil !== 'siswa' ? 'display: none;' : '' }}">
                            <label for="nama_wakil" class="form-label">{{ __('common.vice_chairman_name') }}</label>
                            <select name="nama_wakil" id="nama_wakil"
                                {{ $defaultWakil !== 'siswa' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberWakil !== 'siswa'"
                                x-bind:required="sumberWakil === 'siswa'"
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($siswas ?? collect())->isEmpty() ? 'Belum ada data siswa aktif' : __('common.select_student_name') }}</option>
                                @foreach ($siswas ?? [] as $siswa)
                                    <option value="{{ $siswa->nama_lengkap }}" data-nis="{{ $siswa->nis }}"
                                        data-kelas="{{ $siswa->kelas }}" data-email="{{ $siswa->email }}"
                                        data-jenis-kelamin="{{ $siswa->jenis_kelamin }}"
                                        {{ old('nama_wakil', $calon->nama_wakil) == $siswa->nama_lengkap ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} - {{ $siswa->nis }} - {{ $siswa->kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">{{ __('common.select_from_active_students') }}</p>
                        </div>

                        <!-- Mode: Guru -->
                        <div x-show="sumberWakil === 'guru'" style="{{ $defaultWakil !== 'guru' ? 'display: none;' : '' }}">
                            <label for="nama_wakil_guru" class="form-label">{{ __('common.vice_chairman_name') }}</label>
                            <select name="nama_wakil" id="nama_wakil_guru"
                                {{ $defaultWakil !== 'guru' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberWakil !== 'guru'"
                                x-bind:required="sumberWakil === 'guru'"
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ ($gurus ?? collect())->isEmpty() ? 'Belum ada data guru aktif' : 'Pilih nama guru' }}</option>
                                @foreach ($gurus ?? [] as $guru)
                                    <option value="{{ $guru->nama_lengkap }}" data-jenis-kelamin="{{ $guru->jenis_kelamin }}"
                                        {{ old('nama_wakil', $calon->nama_wakil) == $guru->nama_lengkap ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}{{ $guru->nip ? ' - ' . $guru->nip : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if (($gurus ?? collect())->isEmpty())
                                <p class="text-sm text-amber-600 mt-1">Belum ada data guru aktif di sistem. Pilih
                                    <strong>Manual (ketik nama)</strong> untuk memasukkan nama guru secara bebas.</p>
                            @else
                                <p class="text-sm text-slate-600 mt-1">Pilih dari daftar guru aktif</p>
                            @endif
                            <div class="mt-4">
                                <label class="form-label">{{ __('common.vice_chairman_class') }}</label>
                                <input type="text" value="Guru" readonly
                                    class="form-input bg-slate-50 text-slate-600">
                            </div>
                        </div>

                        <!-- Mode: Manual -->
                        <div x-show="sumberWakil === 'manual'" style="{{ $defaultWakil !== 'manual' ? 'display: none;' : '' }}">
                            <label for="nama_wakil_manual" class="form-label">{{ __('common.vice_chairman_name') }}</label>
                            <input type="text" name="nama_wakil" id="nama_wakil_manual" maxlength="255"
                                {{ $defaultWakil !== 'manual' ? 'disabled' : 'required' }}
                                x-bind:disabled="sumberWakil !== 'manual'"
                                x-bind:required="sumberWakil === 'manual'"
                                value="{{ old('nama_wakil', $calon->nama_wakil) }}" placeholder="Ketik nama wakil"
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('nama_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">Nama bebas, boleh siswa atau guru — data siswa/guru
                                tidak harus ada di sistem.</p>
                        </div>

                        <!-- Kelas (display-only, mode siswa saja) -->
                        <div x-show="sumberWakil === 'siswa'" style="{{ $defaultWakil !== 'siswa' ? 'display: none;' : '' }}">
                            {{-- disabled: kelas melekat di data siswa, tidak ada kolom kelas_wakil di tabel calons (field display-only, JS auto-fill via id) --}}
                            <label for="kelas_wakil" class="form-label">{{ __('common.vice_chairman_class') }}</label>
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
                            <label for="foto_wakil" class="form-label">{{ __('common.vice_chairman_photo') }}</label>
                            <input type="file" id="foto_wakil" name="foto_wakil" accept="image/*"
                                class="form-input @error('foto_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('foto_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @if ($calon->wakil_photo_url)
                                <div class="mt-2">
                                    <img src="{{ $calon->wakil_photo_url }}" alt="{{ __('common.current_photo') }}"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1">{{ __('common.current_photo') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Visi Misi Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.vision_mission') }}</h3>
                    <div>
                        <label for="visi_misi" class="form-label">{{ __('common.vision_mission') }}</label>
                        <textarea id="visi_misi" name="visi_misi" rows="8"
                            class="form-input @error('visi_misi') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="{{ __('common.enter_vision_mission') }}">{{ old('visi_misi', $calon->visi_misi) }}</textarea>
                        @error('visi_misi')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Settings Section -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jenis_kelamin" class="form-label">{{ __('common.gender_required') }}</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="form-input @error('jenis_kelamin') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_gender') }}</option>
                                <option value="L"
                                    {{ old('jenis_kelamin', $calon->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                                    {{ __('common.laki_laki') }}</option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $calon->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                                    {{ __('common.perempuan') }}</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-600 mt-1">{{ __('common.select_gender_hint') }}</p>
                        </div>

                        <div>
                            <label for="jenis_pencalonan" class="form-label">{{ __('common.jenis_pencalonan') }}</label>
                            <select id="jenis_pencalonan" name="jenis_pencalonan" required
                                class="form-input @error('jenis_pencalonan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_status') }}</option>
                                <option value="ketua" {{ old('jenis_pencalonan', $calon->jenis_pencalonan) === 'ketua' ? 'selected' : '' }}>{{ __('common.ketua_osis') }}
                                </option>
                                <option value="wakil" {{ old('jenis_pencalonan', $calon->jenis_pencalonan) === 'wakil' ? 'selected' : '' }}>{{ __('common.wakil_ketua_osis') }}
                                </option>
                                <option value="pasangan" {{ old('jenis_pencalonan', $calon->jenis_pencalonan) === 'pasangan' ? 'selected' : '' }}>{{ __('common.pasangan_ketua_wakil') }}</option>
                            </select>
                            @error('jenis_pencalonan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $calon->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-slate-700">{{ __('common.active_in_election') }}</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.osis.calon.show', $calon) }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ __('common.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('common.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'calon_edit_success_' + '{{ md5(session('success') . time()) }}';
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
                const errorKey = 'calon_edit_error_' + '{{ md5(session('error') . time()) }}';
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
                const errorsKey = 'calon_edit_errors_' + '{{ md5(json_encode($errors->all()) . time()) }}';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError(@json($errors->first()));
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif

    <script>
        // Alpine component: toggle visibility 3 mode sumber (Siswa/Guru/Manual).
        // Field mode non-aktif di-`disabled` (statis sesuai mode default saat render
        // + x-bind:disabled sesudah Alpine init) sehingga tidak ikut ter-submit dan
        // `required` pada field tersembunyi tidak memblokir submit browser.
        function calonForm(sumberKetua, sumberWakil) {
            return {
                sumberKetua: ['siswa', 'guru', 'manual'].includes(sumberKetua) ? sumberKetua : 'manual',
                sumberWakil: ['siswa', 'guru', 'manual'].includes(sumberWakil) ? sumberWakil : 'manual',
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
