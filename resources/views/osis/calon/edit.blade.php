<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.edit_calon_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ $calon->full_candidate_name }}</p>
            </div>
            <div class="flex items-center space-x-2">
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.osis.calon.update', $calon) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Ketua Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.chairman_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_ketua" class="form-label">{{ __('common.name') }} {{ __('common.ketua') }}</label>
                            <select name="nama_ketua" id="nama_ketua" required
                                class="form-input @error('nama_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_student_name') }}</option>
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
                        </div>

                        <div>
                            <label for="kelas_ketua" class="form-label">{{ __('common.select_class') }} {{ __('common.ketua') }}</label>
                            <select name="kelas_ketua" id="kelas_ketua" required
                                class="form-input @error('kelas_ketua') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_ketua', $calon->kelas_ketua) == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_ketua')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ketua_photo" class="form-label">{{ __('common.chairman_photo') }}</label>
                            <input type="file" id="ketua_photo" name="ketua_photo" accept="image/*"
                                class="form-input @error('ketua_photo') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('ketua_photo')
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_wakil" class="form-label">{{ __('common.vice_chairman_name') }}</label>
                            <select name="nama_wakil" id="nama_wakil" required
                                class="form-input @error('nama_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_student_name') }}</option>
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
                        </div>

                        <div>
                            <label for="kelas_wakil" class="form-label">{{ __('common.vice_chairman_class') }}</label>
                            <select name="kelas_wakil" id="kelas_wakil" required
                                class="form-input @error('kelas_wakil') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_class') }}</option>
                                @foreach ($kelas ?? [] as $k)
                                    <option value="{{ $k }}"
                                        {{ old('kelas_wakil', $calon->kelas_wakil) == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_wakil')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="wakil_photo" class="form-label">{{ __('common.vice_chairman_photo') }}</label>
                            <input type="file" id="wakil_photo" name="wakil_photo" accept="image/*"
                                class="form-input @error('wakil_photo') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('wakil_photo')
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
                            <label for="pencalonan_type" class="form-label">{{ __('common.jenis_pencalonan') }}</label>
                            <select id="pencalonan_type" name="pencalonan_type"
                                class="form-input @error('pencalonan_type') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="individu"
                                    {{ old('pencalonan_type', $calon->pencalonan_type) == 'individu' ? 'selected' : '' }}>
                                    {{ __('common.individual') }}</option>
                                <option value="pasangan"
                                    {{ old('pencalonan_type', $calon->pencalonan_type) == 'pasangan' ? 'selected' : '' }}>
                                    {{ __('common.pasangan') }}</option>
                            </select>
                            @error('pencalonan_type')
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
                    showSuccess('{{ session('success') }}');
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
                    showError('{{ session('error') }}');
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
