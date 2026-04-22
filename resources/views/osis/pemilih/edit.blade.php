<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.edit_pemilih_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ $pemilih->nama }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.osis.pemilih.show', $pemilih) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ __('common.view_details') }}
                </a>
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
            <form method="POST" action="{{ route('admin.osis.pemilih.update', $pemilih) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.basic_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="form-label">{{ __('common.full_name') }}</label>
                            <input type="text" id="nama" name="nama"
                                value="{{ old('nama', $pemilih->nama) }}"
                                class="form-input @error('nama') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_full_name') }}" required>
                            @error('nama')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nis" class="form-label">{{ __('common.nis_student_number') }}</label>
                            <input type="text" id="nis" name="nis" value="{{ old('nis', $pemilih->nis) }}"
                                class="form-input @error('nis') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_nis') }}" required>
                            @error('nis')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kelas" class="form-label">{{ __('common.class_label') }}</label>
                            <input type="text" id="kelas" name="kelas"
                                value="{{ old('kelas', $pemilih->kelas) }}"
                                class="form-input @error('kelas') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_class') }}" required>
                            @error('kelas')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="form-label">{{ __('common.select_gender') }}</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">{{ __('common.select_gender') }}</option>
                                <option value="L"
                                    {{ old('jenis_kelamin', $pemilih->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                    {{ __('common.laki_laki') }}</option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $pemilih->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                    {{ __('common.perempuan') }}</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label">{{ __('common.email_label') }}</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $pemilih->email) }}"
                                class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.email_label') }}">
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
                            <input type="text" id="nomor_hp" name="nomor_hp"
                                value="{{ old('nomor_hp', $pemilih->nomor_hp) }}"
                                class="form-input @error('nomor_hp') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_phone_number') }}">
                            @error('nomor_hp')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat" class="form-label">{{ __('common.address_label') }}</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="form-input @error('alamat') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.address_label') }}">{{ old('alamat', $pemilih->alamat) }}</textarea>
                            @error('alamat')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Settings -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.status_settings') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $pemilih->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-slate-700">{{ __('common.active_in_election') }}</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="status_sudah_memilih" name="status_sudah_memilih"
                                value="1"
                                {{ old('status_sudah_memilih', $pemilih->status === 'sudah_memilih') ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="status_sudah_memilih" class="ml-2 text-sm text-slate-700">{{ __('common.has_voted') }}</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.osis.pemilih.show', $pemilih) }}" class="btn btn-secondary">
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
                const successKey = 'pemilih_edit_success_' + '{{ md5(session('success') . time()) }}';
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
                const errorKey = 'pemilih_edit_error_' + '{{ md5(session('error') . time()) }}';
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
                const errorsKey = 'pemilih_edit_errors_' + '{{ md5(json_encode($errors->all()) . time()) }}';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError('{{ $errors->first() }}');
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif
</x-app-layout>
