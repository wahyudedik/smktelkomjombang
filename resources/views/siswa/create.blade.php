<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.tambah_data_siswa') }}
            </h2>
            <a href="{{ route('admin.siswa.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('common.back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.siswa.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.personal_info') }}</h3>

                                <!-- NIS -->
                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS
                                        *</label>
                                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nis') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('nis')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NISN -->
                                <div>
                                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN
                                        *</label>
                                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nisn') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('nisn')
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
                                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No.
                                            Telepon</label>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            value="{{ old('no_telepon') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telepon') border-red-500 @else border-gray-300 @enderror">
                                        @error('no_telepon')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.whatsapp_number') }}</label>
                                        <input type="text" name="no_wa" id="no_wa" value="{{ old('no_wa') }}"
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
                                    <p class="text-gray-500 text-xs mt-1">Max size: 2MB, Formats: JPEG, PNG, JPG, GIF
                                    </p>
                                    @error('foto')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akademik</h3>

                                <!-- Kelas -->
                                <div>
                                    <label for="kelas"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.class_label') }}</label>
                                    <div class="flex gap-2">
                                        <select name="kelas" id="kelas"
                                            class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas') border-red-500 @else border-gray-300 @enderror">
                                            <option value="">{{ __('common.select_class') }}</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k }}"
                                                    {{ old('kelas') == $k ? 'selected' : '' }}>{{ $k }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openKelasModal()"
                                            class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('kelas')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jurusan -->
                                <div>
                                    <label for="jurusan"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.major') }}</label>
                                    <div class="flex gap-2">
                                        <select name="jurusan" id="jurusan"
                                            class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jurusan') border-red-500 @else border-gray-300 @enderror">
                                            <option value="">{{ __('common.select_major') }}</option>
                                            @foreach ($jurusan as $j)
                                                <option value="{{ $j }}"
                                                    {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openJurusanModal()"
                                            class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('jurusan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tahun Masuk -->
                                <div>
                                    <label for="tahun_masuk"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.entry_year') }} *</label>
                                    <input type="number" name="tahun_masuk" id="tahun_masuk"
                                        value="{{ old('tahun_masuk') }}" min="2000" max="{{ date('Y') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_masuk') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('tahun_masuk')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tahun Lulus -->
                                <div>
                                    <label for="tahun_lulus"
                                        class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                                    <input type="number" name="tahun_lulus" id="tahun_lulus"
                                        value="{{ old('tahun_lulus') }}" min="2000" max="{{ date('Y') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_lulus') border-red-500 @else border-gray-300 @enderror">
                                    @error('tahun_lulus')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.current_status') }} *</label>
                                    <select name="status" id="status"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                        <option value="">{{ __('common.select_status') }}</option>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>{{ __('common.active') }}
                                        </option>
                                        <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>{{ __('common.graduated') }}
                                        </option>
                                        <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>
                                            {{ __('common.transferred') }}</option>
                                        <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>
                                            {{ __('common.dropped_out') }}</option>
                                        <option value="meninggal"
                                            {{ old('status') == 'meninggal' ? 'selected' : '' }}>{{ __('common.deceased') }}</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Ekstrakurikuler -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">Ekstrakurikuler</label>
                                        <button type="button" onclick="openEkstrakurikulerModal()"
                                            class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            {{ __('common.add') }}
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-md p-2">
                                        @foreach ($ekstrakurikuler as $eks)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="ekstrakurikuler[]"
                                                    value="{{ $eks }}"
                                                    {{ in_array($eks, old('ekstrakurikuler', [])) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">{{ $eks }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('ekstrakurikuler')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Parent Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Orang Tua</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Ayah -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Ayah</h4>
                                    <div>
                                        <label for="nama_ayah"
                                            class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" id="nama_ayah"
                                            value="{{ old('nama_ayah') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_ayah') border-red-500 @else border-gray-300 @enderror">
                                        @error('nama_ayah')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ayah"
                                            class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah"
                                            value="{{ old('pekerjaan_ayah') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pekerjaan_ayah') border-red-500 @else border-gray-300 @enderror">
                                        @error('pekerjaan_ayah')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Ibu -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Ibu</h4>
                                    <div>
                                        <label for="nama_ibu"
                                            class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" id="nama_ibu"
                                            value="{{ old('nama_ibu') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_ibu') border-red-500 @else border-gray-300 @enderror">
                                        @error('nama_ibu')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ibu"
                                            class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu"
                                            value="{{ old('pekerjaan_ibu') }}"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pekerjaan_ibu') border-red-500 @else border-gray-300 @enderror">
                                        @error('pekerjaan_ibu')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak Orang Tua -->
                            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label for="no_telepon_ortu"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.parent_phone') }}</label>
                                    <input type="text" name="no_telepon_ortu" id="no_telepon_ortu"
                                        value="{{ old('no_telepon_ortu') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telepon_ortu') border-red-500 @else border-gray-300 @enderror">
                                    @error('no_telepon_ortu')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="alamat_ortu"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.parent_address') }}</label>
                                    <textarea name="alamat_ortu" id="alamat_ortu" rows="2"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat_ortu') border-red-500 @else border-gray-300 @enderror">{{ old('alamat_ortu') }}</textarea>
                                    @error('alamat_ortu')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.additional_information') }}</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Prestasi -->
                                <div>
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700 mb-1">Prestasi</label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prestasi') border-red-500 @else border-gray-300 @enderror">{{ old('prestasi') }}</textarea>
                                    @error('prestasi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan') border-red-500 @else border-gray-300 @enderror">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- User Account -->
                            <div class="mt-6">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User
                                    Account</label>
                                <div class="flex gap-2">
                                    <select name="user_id" id="user_id"
                                        class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @else border-gray-300 @enderror">
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
                                    <button type="button" onclick="openUserModal()"
                                        class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('user_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ __('common.user_hint') }}
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.siswa.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.add_student_data') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelas -->
    <div id="kelasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.manage_class_data') }}</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.add_new_class') }}</label>
                        <div class="flex gap-2">
                            <input type="text" id="newKelas" placeholder="{{ __('common.enter_class_name') }}"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addKelas()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add') }}
                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.class_list') }}</h4>
                        <div id="kelasList" class="space-y-2">
                            @foreach ($kelas as $k)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">{{ $k }}</span>
                                    <div class="flex gap-1">
                                        <button onclick="editKelas({{ json_encode($k) }})"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            {{ __('common.edit') }}
                                        </button>
                                        <button onclick="deleteKelas({{ json_encode($k) }})"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            {{ __('common.delete') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeKelasModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jurusan -->
    <div id="jurusanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.manage_major_data') }}</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.add_new_major') }}</label>
                        <div class="flex gap-2">
                            <input type="text" id="newJurusan" placeholder="{{ __('common.enter_major_name') }}"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addJurusan()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add') }}
                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.major_list') }}</h4>
                        <div id="jurusanList" class="space-y-2">
                            @foreach ($jurusan as $j)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">{{ $j }}</span>
                                    <div class="flex gap-1">
                                        <button onclick="editJurusan({{ json_encode($j) }})"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            {{ __('common.edit') }}
                                        </button>
                                        <button onclick="deleteJurusan({{ json_encode($j) }})"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            {{ __('common.delete') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeJurusanModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('common.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ekstrakurikuler -->
    <div id="ekstrakurikulerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.manage_extracurricular_data') }}</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.add_new_extracurricular') }}</label>
                        <div class="flex gap-2">
                            <input type="text" id="newEkstrakurikuler" placeholder="{{ __('common.enter_extracurricular_name') }}"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addEkstrakurikuler()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add') }}
                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.extracurricular_list') }}</h4>
                        <div id="ekstrakurikulerList" class="space-y-2">
                            @foreach ($ekstrakurikuler as $eks)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">{{ $eks }}</span>
                                    <div class="flex gap-1">
                                        <button onclick="editEkstrakurikuler({{ json_encode($eks) }})"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button onclick="deleteEkstrakurikuler({{ json_encode($eks) }})"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeEkstrakurikulerModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Tutup
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
                            <input type="text" id="newUserName" placeholder="{{ __('common.enter_full_name') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="email" id="newUserEmail" placeholder="{{ __('common.enter_email') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="password" id="newUserPassword" placeholder="{{ __('common.password') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="bg-blue-50 border border-blue-200 rounded-md px-3 py-2">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-user-graduate text-blue-600 mr-2"></i>
                                    <span class="font-medium">{{ __('common.role') }}:</span> {{ __('common.student') }}
                                </p>
                            </div>
                            <button onclick="addUser()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                {{ __('common.add_user') }}
                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('common.user_list') }}</h4>
                        <div id="userList" class="space-y-2">
                            @foreach ($users as $user)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <span class="text-sm font-medium">{{ $user->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">({{ $user->email }})</span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button
                                            onclick="editUser({{ $user->id }}, {{ json_encode($user->name) }}, {{ json_encode($user->email) }})"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            {{ __('common.edit') }}
                                        </button>
                                        <button onclick="deleteUser({{ $user->id }})"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            {{ __('common.delete') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
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
        // Modal functions
        function openKelasModal() {
            document.getElementById('kelasModal').classList.remove('hidden');
        }

        function closeKelasModal() {
            document.getElementById('kelasModal').classList.add('hidden');
        }

        function openJurusanModal() {
            document.getElementById('jurusanModal').classList.remove('hidden');
        }

        function closeJurusanModal() {
            document.getElementById('jurusanModal').classList.add('hidden');
        }

        function openEkstrakurikulerModal() {
            document.getElementById('ekstrakurikulerModal').classList.remove('hidden');
        }

        function closeEkstrakurikulerModal() {
            document.getElementById('ekstrakurikulerModal').classList.add('hidden');
        }

        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // Add functions
        function addKelas() {
            const newKelas = document.getElementById('newKelas').value;
            if (newKelas.trim()) {
                // Show loading
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '{{ __('common.loading') }}...';
                button.disabled = true;

                // Send AJAX request
                fetch('{{ route('admin.settings.data-management.kelas.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newKelas
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add to select dropdown
                            const select = document.getElementById('kelas');
                            const option = document.createElement('option');
                            option.value = data.data.nama;
                            option.textContent = data.data.nama;
                            select.appendChild(option);

                            // Add to list
                            const list = document.getElementById('kelasList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editKelas(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteKelas(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newKelas').value = '';
                            showSuccess('{{ __('common.class_added_success') }}');
                        } else {
                            showError(data.message || '{{ __('common.error_occurred_adding_class') }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('{{ __('common.error_occurred_adding_class') }}');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addJurusan() {
            const newJurusan = document.getElementById('newJurusan').value;
            if (newJurusan.trim()) {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '{{ __('common.loading') }}...';
                button.disabled = true;

                fetch('{{ route('admin.settings.data-management.jurusan.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newJurusan
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const select = document.getElementById('jurusan');
                            const option = document.createElement('option');
                            option.value = data.data.nama;
                            option.textContent = data.data.nama;
                            select.appendChild(option);

                            const list = document.getElementById('jurusanList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editJurusan(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteJurusan(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newJurusan').value = '';
                            showSuccess('{{ __('common.major_added_success') }}');
                        } else {
                            showError(data.message || '{{ __('common.error_occurred_adding_major') }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('{{ __('common.error_occurred_adding_major') }}');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addEkstrakurikuler() {
            const newEkstrakurikuler = document.getElementById('newEkstrakurikuler').value;
            if (newEkstrakurikuler.trim()) {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '{{ __('common.loading') }}...';
                button.disabled = true;

                fetch('{{ route('admin.settings.data-management.ekstrakurikuler.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newEkstrakurikuler
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const list = document.getElementById('ekstrakurikulerList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editEkstrakurikuler(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteEkstrakurikuler(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newEkstrakurikuler').value = '';
                            showSuccess('{{ __('common.extracurricular_added_success') }}');
                        } else {
                            showError(data.message || '{{ __('common.error_occurred_adding_extracurricular') }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('{{ __('common.error_occurred_adding_extracurricular') }}');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addUser() {
            const name = document.getElementById('newUserName').value;
            const email = document.getElementById('newUserEmail').value;
            const password = document.getElementById('newUserPassword').value;
            // Validation
            if (!name.trim()) {
                showError('Nama lengkap harus diisi');
                return;
            }
            if (!email.trim()) {
                showError('{{ __('common.email_required') }}');
                return;
            }
            if (!password.trim()) {
                showError('Password harus diisi');
                return;
            }
            if (password.length < 8) {
                showError('Password minimal 8 karakter');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Loading...';
            button.disabled = true;

            // Get siswa role ID
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
                        roles: ['siswa'] // Use role instead of user_type
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

                        // Add to list
                        const list = document.getElementById('userList');
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                        div.innerHTML = `
                            <div>
                                <span class="text-sm font-medium">${data.data.name}</span>
                                <span class="text-xs text-gray-500 ml-2">(${data.data.email})</span>
                            </div>
                            <div class="flex gap-1">
                                <button onclick="editUser(${data.data.id}, ${JSON.stringify(data.data.name)}, ${JSON.stringify(data.data.email)})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteUser(${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                        list.appendChild(div);

                        // Clear form
                        document.getElementById('newUserName').value = '';
                        document.getElementById('newUserEmail').value = '';
                        document.getElementById('newUserPassword').value = '';
                        showSuccess('User berhasil ditambahkan!');
                    } else {
                        showError(data.message || 'Terjadi kesalahan saat menambahkan user');
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
                        showError('Terjadi kesalahan saat menambahkan user');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        // Edit functions
        async function editKelas(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: '{{ __('common.edit_class') }}',
                input: 'text',
                inputLabel: '{{ __('common.class_name') }}',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.save') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama kelas tidak boleh kosong!';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                // Update in select
                const select = document.getElementById('kelas');
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === oldValue) {
                        option.value = newValue;
                        option.textContent = newValue;
                    }
                });

                // Update in list
                const list = document.getElementById('kelasList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        // Update onclick attributes
                        const editBtn = item.querySelector('button[onclick*="editKelas"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteKelas"]');
                        editBtn.setAttribute('onclick', `editKelas(${JSON.stringify(newValue)})`);
                        deleteBtn.setAttribute('onclick', `deleteKelas(${JSON.stringify(newValue)})`);
                    }
                });
            }
        }

        async function editJurusan(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: '{{ __('common.edit_major') }}',
                input: 'text',
                inputLabel: '{{ __('common.major_name') }}',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.save') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama jurusan tidak boleh kosong!';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                const select = document.getElementById('jurusan');
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === oldValue) {
                        option.value = newValue;
                        option.textContent = newValue;
                    }
                });

                const list = document.getElementById('jurusanList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        const editBtn = item.querySelector('button[onclick*="editJurusan"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteJurusan"]');
                        editBtn.setAttribute('onclick', `editJurusan(${JSON.stringify(newValue)})`);
                        deleteBtn.setAttribute('onclick', `deleteJurusan(${JSON.stringify(newValue)})`);
                    }
                });
            }
        }

        async function editEkstrakurikuler(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: 'Edit Ekstrakurikuler',
                input: 'text',
                inputLabel: '{{ __('common.extracurricular_name') }}',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.save') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                inputValidator: (value) => {
                    if (!value) {
                        return '{{ __('common.extracurricular_name_required') }}';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                const list = document.getElementById('ekstrakurikulerList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        const editBtn = item.querySelector('button[onclick*="editEkstrakurikuler"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteEkstrakurikuler"]');
                        editBtn.setAttribute('onclick', `editEkstrakurikuler('${newValue}')`);
                        deleteBtn.setAttribute('onclick', `deleteEkstrakurikuler('${newValue}')`);
                    }
                });
            }
        }

        async function editUser(id, name, email) {
            const {
                value: formValues
            } = await Swal.fire({
                title: '{{ __('common.edit_user') }}',
                html: `
                    <input id="swal-name" class="swal2-input" placeholder="{{ __('common.name') }}" value="${name}" required>
                    <input id="swal-email" class="swal2-input" placeholder="{{ __('common.email_label') }}" type="email" value="${email}" required>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '{{ __('common.save') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                preConfirm: () => {
                    const nameInput = document.getElementById('swal-name');
                    const emailInput = document.getElementById('swal-email');
                    if (!nameInput.value || !emailInput.value) {
                        Swal.showValidationMessage('{{ __('common.name_email_required') }}');
                        return false;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                        Swal.showValidationMessage('Format email tidak valid!');
                        return false;
                    }
                    return {
                        name: nameInput.value,
                        email: emailInput.value
                    };
                }
            });

            if (formValues) {
                const newName = formValues.name;
                const newEmail = formValues.email;
                if (newName !== name || newEmail !== email) {
                    // Update in select
                    const select = document.getElementById('user_id');
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value === id) {
                            option.textContent = `${newName} (${newEmail})`;
                        }
                    });

                    // Update in list
                    const list = document.getElementById('userList');
                    const items = list.querySelectorAll('div');
                    items.forEach(item => {
                        const span = item.querySelector('span');
                        if (span.textContent === name) {
                            span.textContent = newName;
                            const emailSpan = item.querySelector('.text-gray-500');
                            emailSpan.textContent = `(${newEmail})`;
                        }
                    });
                }
            }
        }

        // Delete functions
        function deleteKelas(value, id) {
            showConfirm('{{ __('common.delete_confirmation') }}', `{{ __('common.delete_class_confirmation') }}`.replace(':value', ${JSON.stringify(value)}), '{{ __('common.yes_delete') }}', '{{ __('common.cancel') }}').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/kelas/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            if (!result.ok) {
                                if (result.status === 404) {
                                    showError('{{ __('common.error') }}!', '{{ __('common.class_not_found') }}');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('{{ __('common.unauthorized') }}!',
                                        '{{ __('common.unauthorized_action') }}');
                                } else {
                                    showError('{{ __('common.error') }}!', result.data.message || '{{ __('common.failed_delete_class') }}');
                                }
                                return;
                            }

                            if (result.data.success) {
                                // Remove from select
                                const select = document.getElementById('kelas');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === value) {
                                        option.remove();
                                    }
                                });

                                // Remove from list
                                const list = document.getElementById('kelasList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('{{ __('common.class_deleted_success') }}');
                            } else {
                                showError(result.data.message || '{{ __('common.error_occurred_deleting_class') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('{{ __('common.error_occurred_deleting_class') }}: ' + error.message);
                        });
                }
            });
        }

        function deleteJurusan(value, id) {
            showConfirm('{{ __('common.delete_confirmation') }}', `{{ __('common.delete_major_confirmation') }}`.replace(':value', ${JSON.stringify(value)}), '{{ __('common.yes_delete') }}', '{{ __('common.cancel') }}').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/jurusan/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            if (!result.ok) {
                                if (result.status === 404) {
                                    showError('{{ __('common.error') }}!', '{{ __('common.major_not_found') }}');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('{{ __('common.unauthorized') }}!',
                                        '{{ __('common.unauthorized_action') }}');
                                } else {
                                    showError('{{ __('common.error') }}!', result.data.message || '{{ __('common.failed_delete_major') }}');
                                }
                                return;
                            }

                            if (result.data.success) {
                                const select = document.getElementById('jurusan');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === value) {
                                        option.remove();
                                    }
                                });

                                const list = document.getElementById('jurusanList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('{{ __('common.major_deleted_success') }}');
                            } else {
                                showError(result.data.message || '{{ __('common.error_occurred_deleting_major') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('{{ __('common.error_occurred_deleting_major') }}: ' + error.message);
                        });
                }
            });
        }

        function deleteEkstrakurikuler(value, id) {
            showConfirm('{{ __('common.delete_confirmation') }}', `{{ __('common.delete_extracurricular_confirmation') }}`.replace(':value', ${JSON.stringify(value)}), '{{ __('common.yes_delete') }}', '{{ __('common.cancel') }}').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/ekstrakurikuler/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            if (!result.ok) {
                                if (result.status === 404) {
                                    showError('{{ __('common.error') }}!', '{{ __('common.extracurricular_not_found') }}');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('{{ __('common.unauthorized') }}!',
                                        '{{ __('common.unauthorized_action') }}');
                                } else {
                                    showError('{{ __('common.error') }}!', result.data.message ||
                                        '{{ __('common.failed_delete_extracurricular') }}');
                                }
                                return;
                            }

                            if (result.data.success) {
                                const list = document.getElementById('ekstrakurikulerList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('{{ __('common.extracurricular_deleted_success') }}');
                            } else {
                                showError(result.data.message ||
                                    '{{ __('common.error_occurred_deleting_extracurricular') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('{{ __('common.error_occurred_deleting_extracurricular') }}: ' + error.message);
                        });
                }
            });
        }

        function deleteUser(id) {
            showConfirm('{{ __('common.delete_confirmation') }}', '{{ __('common.delete_user_confirmation') }}', '{{ __('common.yes_delete') }}', '{{ __('common.cancel') }}').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const select = document.getElementById('user_id');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === id) {
                                        option.remove();
                                    }
                                });

                                const list = document.getElementById('userList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const button = item.querySelector(
                                        `button[onclick*="deleteUser('${id}')"]`);
                                    if (button) {
                                        item.remove();
                                    }
                                });
                                showSuccess('{{ __('common.user_deleted_success') }}');
                            } else {
                                showError(data.message || '{{ __('common.error_occurred_deleting_user') }}');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('{{ __('common.error_occurred_deleting_user') }}');
                        });
                }
            });
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bg-gray-600')) {
                closeKelasModal();
                closeJurusanModal();
                closeEkstrakurikulerModal();
                closeUserModal();
            }
        });
    </script>
</x-app-layout>
