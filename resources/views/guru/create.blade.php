<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.tambah_data_guru') }}
            </h2>
            <a href="{{ route('admin.guru.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('common.back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.guru.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.personal_info') }}</h3>

                                <!-- NIP -->
                                <div>
                                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.nip') }}
                                        *</label>
                                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nip') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('nip')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.full_name_label') }} *</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                        value="{{ old('nama_lengkap') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_lengkap') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('nama_lengkap')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gelar -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="gelar_depan"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.title_prefix') }}</label>
                                        <input type="text" name="gelar_depan" id="gelar_depan"
                                            value="{{ old('gelar_depan') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gelar_depan') border-red-500 @else border-gray-300 @enderror">
                                        @error('gelar_depan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="gelar_belakang"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.title_suffix') }}</label>
                                        <input type="text" name="gelar_belakang" id="gelar_belakang"
                                            value="{{ old('gelar_belakang') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gelar_belakang') border-red-500 @else border-gray-300 @enderror">
                                        @error('gelar_belakang')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.gender_label') }} *</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="L"
                                                {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('common.laki_laki') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="P"
                                                {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('common.perempuan') }}</span>
                                        </label>
                                    </div>
                                    @error('jenis_kelamin')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal & Tempat Lahir -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_lahir"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.birth_date') }} *</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            value="{{ old('tanggal_lahir') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_lahir') border-red-500 @else border-gray-300 @enderror"
                                            required>
                                        @error('tanggal_lahir')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="tempat_lahir"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.birth_place') }} *</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                                            value="{{ old('tempat_lahir') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tempat_lahir') border-red-500 @else border-gray-300 @enderror"
                                            required>
                                        @error('tempat_lahir')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.address_label') }} *</label>
                                    <textarea name="alamat" id="alamat" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat') border-red-500 @else border-gray-300 @enderror"
                                        required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kontak -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.phone_number_label') }}</label>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            value="{{ old('no_telepon') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telepon') border-red-500 @else border-gray-300 @enderror">
                                        @error('no_telepon')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.whatsapp_number') }}</label>
                                        <input type="text" name="no_wa" id="no_wa"
                                            value="{{ old('no_wa') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_wa') border-red-500 @else border-gray-300 @enderror">
                                        @error('no_wa')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.email_label') }}</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Foto -->
                                <div>
                                    <label for="foto"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.photo') }}</label>
                                    <input type="file" name="foto" id="foto" accept="image/*"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('foto') border-red-500 @else border-gray-300 @enderror">
                                    <p class="text-gray-500 text-xs mt-1">{{ __('common.max_size_formats') }}
                                    </p>
                                    @error('foto')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.professional_information') }}</h3>

                                <!-- Status Kepegawaian -->
                                <div>
                                    <label for="status_kepegawaian"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.employment_status') }}
                                        *</label>
                                    <select name="status_kepegawaian" id="status_kepegawaian"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_kepegawaian') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                        <option value="">{{ __('common.select_status') }}</option>
                                        <option value="PNS"
                                            {{ old('status_kepegawaian') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="CPNS"
                                            {{ old('status_kepegawaian') == 'CPNS' ? 'selected' : '' }}>CPNS</option>
                                        <option value="GTT"
                                            {{ old('status_kepegawaian') == 'GTT' ? 'selected' : '' }}>GTT</option>
                                        <option value="GTY"
                                            {{ old('status_kepegawaian') == 'GTY' ? 'selected' : '' }}>GTY</option>
                                        <option value="Honorer"
                                            {{ old('status_kepegawaian') == 'Honorer' ? 'selected' : '' }}>Honorer
                                        </option>
                                    </select>
                                    @error('status_kepegawaian')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jabatan -->
                                <div>
                                    <label for="jabatan"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.position') }}</label>
                                    <input type="text" name="jabatan" id="jabatan"
                                        value="{{ old('jabatan') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jabatan') border-red-500 @else border-gray-300 @enderror">
                                    @error('jabatan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Masuk -->
                                <div>
                                    <label for="tanggal_masuk"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.entry_date') }} *</label>
                                    <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                        value="{{ old('tanggal_masuk') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_masuk') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('tanggal_mahir')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Keluar -->
                                <div>
                                    <label for="tanggal_keluar"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.exit_date') }}</label>
                                    <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                        value="{{ old('tanggal_keluar') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_keluar') border-red-500 @else border-gray-300 @enderror">
                                    @error('tanggal_keluar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status Aktif -->
                                <div>
                                    <label for="status_aktif"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.active_status') }} *</label>
                                    <select name="status_aktif" id="status_aktif"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_aktif') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                        <option value="">{{ __('common.select_status') }}</option>
                                        <option value="aktif" {{ old('status_aktif') == 'aktif' ? 'selected' : '' }}>
                                            {{ __('common.active') }}</option>
                                        <option value="tidak_aktif"
                                            {{ old('status_aktif') == 'tidak_aktif' ? 'selected' : '' }}>{{ __('common.inactive') }}
                                        </option>
                                        <option value="pensiun"
                                            {{ old('status_aktif') == 'pensiun' ? 'selected' : '' }}>{{ __('common.retired') }}</option>
                                        <option value="meninggal"
                                            {{ old('status_aktif') == 'meninggal' ? 'selected' : '' }}>{{ __('common.deceased') }}
                                        </option>
                                    </select>
                                    @error('status_aktif')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pendidikan -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="pendidikan_terakhir"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.education_level') }}
                                            *</label>
                                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir"
                                            value="{{ old('pendidikan_terakhir') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pendidikan_terakhir') border-red-500 @else border-gray-300 @enderror"
                                            required>
                                        @error('pendidikan_terakhir')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="tahun_lulus"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.education_year') }} *</label>
                                        <input type="text" name="tahun_lulus" id="tahun_lulus"
                                            value="{{ old('tahun_lulus') }}" inputmode="numeric" pattern="[0-9]{4}"
                                            maxlength="4" minlength="4"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_lulus') border-red-500 @else border-gray-300 @enderror"
                                            placeholder="YYYY" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">
                                        <small class="text-gray-500">{{ __('common.year_only_example') }}</small>
                                        @error('tahun_lulus')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Universitas -->
                                <div>
                                    <label for="universitas"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.university') }} *</label>
                                    <input type="text" name="universitas" id="universitas"
                                        value="{{ old('universitas') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('universitas') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('universitas')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sertifikasi -->
                                <div>
                                    <label for="sertifikasi"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.certification') }}</label>
                                    <textarea name="sertifikasi" id="sertifikasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sertifikasi') border-red-500 @else border-gray-300 @enderror">{{ old('sertifikasi') }}</textarea>
                                    @error('sertifikasi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Mata Pelajaran -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">{{ __('common.subjects') }} *</label>
                                        <button type="button" onclick="openMataPelajaranModal()"
                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            {{ __('common.add') }}
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-md p-2">
                                        @if (count($subjects) > 0)
                                            @foreach ($subjects as $subject)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="mata_pelajaran[]"
                                                        value="{{ $subject }}"
                                                        {{ in_array($subject, old('mata_pelajaran', [])) ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span
                                                        class="ml-2 text-sm text-gray-700">{{ $subject }}</span>
                                                </label>
                                            @endforeach
                                        @else
                                            <div class="col-span-2 text-center text-gray-500 py-4">
                                                {{ __('common.no_subjects_available') }}
                                            </div>
                                        @endif
                                    </div>
                                    @error('mata_pelajaran')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('common.select_subjects_taught') }}
                                    </p>
                                </div>

                                <!-- Prestasi -->
                                <div>
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.achievement') }}</label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prestasi') border-red-500 @else border-gray-300 @enderror">{{ old('prestasi') }}</textarea>
                                    @error('prestasi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.notes') }}</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan') border-red-500 @else border-gray-300 @enderror">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- User Account -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700">{{ __('common.user_account') }}</label>
                                        <button type="button" onclick="openUserModal()"
                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah
                                        </button>
                                    </div>
                                    <select name="user_id" id="user_id"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @else border-gray-300 @enderror">
                                        <option value="">{{ __('common.select_user_account_optional') }}</option>
                                        @if ($users->count() > 0)
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>{{ __('common.no_users_available') }}</option>
                                        @endif
                                    </select>
                                    @error('user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('common.only_unused_users') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.guru.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.save_teacher_data') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Pelajaran -->
    <div id="mataPelajaranModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.manage_subjects') }}</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.add_new_subject') }}</label>
                        <div class="space-y-3">
                            <input type="text" id="newMataPelajaran" placeholder="{{ __('common.subject_name') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addMataPelajaran()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add_subject') }}
                            </button>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.subjects_list') }}</h4>
                        <div id="mataPelajaranList" class="space-y-2 max-h-40 overflow-y-auto">
                            <!-- List will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeMataPelajaranModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal User Account -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.manage_user_account') }}</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.add_new_user') }}</label>
                        <div class="space-y-3">
                            <input type="text" id="newUserName" placeholder="{{ __('common.full_name_label') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="email" id="newUserEmail" placeholder="{{ __('common.email_label') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="password" id="newUserPassword" placeholder="{{ __('common.password') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addUser()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add_user') }}
                            </button>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.users_list') }}</h4>
                        <div id="userList" class="space-y-2 max-h-40 overflow-y-auto">
                            <!-- List will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeUserModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mata Pelajaran Functions
        function openMataPelajaranModal() {
            document.getElementById('mataPelajaranModal').classList.remove('hidden');
            loadMataPelajaranList();
        }

        function closeMataPelajaranModal() {
            document.getElementById('mataPelajaranModal').classList.add('hidden');
        }

        function addMataPelajaran() {
            const newMataPelajaran = document.getElementById('newMataPelajaran').value;

            if (!newMataPelajaran.trim()) {
                showError('{{ __('common.subject_name_required') }}');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '{{ __('common.loading') }}';
            button.disabled = true;

            fetch('{{ route('admin.guru.addSubject') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        nama: newMataPelajaran
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Add to checkbox list
                        const container = document.querySelector('.grid.grid-cols-2.gap-2');
                        const label = document.createElement('label');
                        label.className = 'flex items-center';
                        label.innerHTML = `
                            <input type="checkbox" name="mata_pelajaran[]" value="${data.data.nama}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">${data.data.nama}</span>
                        `;
                        container.appendChild(label);

                        // Update list in modal
                        loadMataPelajaranList();

                        document.getElementById('newMataPelajaran').value = '';
                        showSuccess('{{ __('common.subject_added_successfully') }}');
                    } else {
                        showError(data.message || '{{ __('common.error_adding_subject') }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Handle validation errors
                    if (error.errors) {
                        let errorMessages = [];
                        for (let field in error.errors) {
                            errorMessages.push(...error.errors[field]);
                        }
                        showError(errorMessages.join('<br>'));
                    } else if (error.message) {
                        showError(error.message);
                    } else {
                        showError('{{ __('common.error_adding_subject') }}');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        function loadMataPelajaranList() {
            // This would typically fetch from an API endpoint
            // For now, we'll just show a placeholder
            document.getElementById('mataPelajaranList').innerHTML = '<p class="text-gray-500 text-sm">{{ __('common.loading') }}</p>';
        }

        // User Functions
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            loadUserList();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function addUser() {
            const name = document.getElementById('newUserName').value;
            const email = document.getElementById('newUserEmail').value;
            const password = document.getElementById('newUserPassword').value;
            // Validation
            if (!name.trim()) {
                showError('{{ __('common.full_name_required') }}');
                return;
            }
            if (!email.trim()) {
                showError('{{ __('common.email_required') }}');
                return;
            }
            if (!password.trim()) {
                showError('{{ __('common.password_required') }}');
                return;
            }
            if (password.length < 8) {
                showError('{{ __('common.password_min_length') }}');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '{{ __('common.loading') }}';
            button.disabled = true;

            fetch('{{ route('admin.superadmin.users.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        roles: ['guru'] // Use role instead of user_type
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Add to select dropdown
                        const select = document.getElementById('user_id');
                        const option = document.createElement('option');
                        option.value = data.data.id;
                        option.textContent = `${data.data.name} (${data.data.email})`;
                        select.appendChild(option);

                        // Update list in modal
                        loadUserList();

                        // Clear form
                        document.getElementById('newUserName').value = '';
                        document.getElementById('newUserEmail').value = '';
                        document.getElementById('newUserPassword').value = '';

                        showSuccess('{{ __('common.user_added_successfully') }}');
                    } else {
                        showError(data.message || '{{ __('common.error_adding_user') }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Handle validation errors
                    if (error.errors) {
                        let errorMessages = [];
                        for (let field in error.errors) {
                            errorMessages.push(...error.errors[field]);
                        }
                        showError(errorMessages.join('<br>'));
                    } else if (error.message) {
                        showError(error.message);
                    } else {
                        showError('{{ __('common.error_adding_user') }}');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        function loadUserList() {
            // This would typically fetch from an API endpoint
            // For now, we'll just show a placeholder
            document.getElementById('userList').innerHTML = '<p class="text-gray-500 text-sm">{{ __('common.loading') }}</p>';
        }
    </script>
</x-app-layout>
