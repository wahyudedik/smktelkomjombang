<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.edit_graduation_data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.lulus.update', $kelulusan) }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">{{ __('common.full_name_label') }}
                                    *</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $kelulusan->nama) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700">{{ __('common.nisn_label') }} *</label>
                                <input type="text" name="nisn" id="nisn"
                                    value="{{ old('nisn', $kelulusan->nisn) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nisn')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700">{{ __('common.nis_label') }}</label>
                                <input type="text" name="nis" id="nis"
                                    value="{{ old('nis', $kelulusan->nis) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700">{{ __('common.major') }}</label>
                                <select name="jurusan" id="jurusan"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_major') }}</option>
                                    @foreach ($majors as $major)
                                        <option value="{{ $major }}"
                                            {{ old('jurusan', $kelulusan->jurusan) == $major ? 'selected' : '' }}>
                                            {{ $major }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">{{ __('common.academic_year') }}
                                    *</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_academic_year') }}</option>
                                    @foreach ($tahunAjaran as $tahun)
                                        <option value="{{ $tahun }}"
                                            {{ old('tahun_ajaran', $kelulusan->tahun_ajaran) == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('common.status') }} *</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('common.select_status') }}</option>
                                    <option value="lulus"
                                        {{ old('status', $kelulusan->status) == 'lulus' ? 'selected' : '' }}>{{ __('common.graduated') }}
                                    </option>
                                    <option value="tidak_lulus"
                                        {{ old('status', $kelulusan->status) == 'tidak_lulus' ? 'selected' : '' }}>
                                        {{ __('common.not_graduated') }}</option>
                                    <option value="mengulang"
                                        {{ old('status', $kelulusan->status) == 'mengulang' ? 'selected' : '' }}>
                                        {{ __('common.repeat') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Education Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.continuing_education_info') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tempat_kuliah" class="block text-sm font-medium text-gray-700">{{ __('common.university_place') }}</label>
                                    <input type="text" name="tempat_kuliah" id="tempat_kuliah"
                                        value="{{ old('tempat_kuliah', $kelulusan->tempat_kuliah) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tempat_kuliah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jurusan_kuliah" class="block text-sm font-medium text-gray-700">{{ __('common.university_major') }}</label>
                                    <input type="text" name="jurusan_kuliah" id="jurusan_kuliah"
                                        value="{{ old('jurusan_kuliah', $kelulusan->jurusan_kuliah) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('jurusan_kuliah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Work Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.work_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tempat_kerja" class="block text-sm font-medium text-gray-700">{{ __('common.workplace') }}</label>
                                    <input type="text" name="tempat_kerja" id="tempat_kerja"
                                        value="{{ old('tempat_kerja', $kelulusan->tempat_kerja) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tempat_kerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jabatan_kerja" class="block text-sm font-medium text-gray-700">{{ __('common.job_position') }}</label>
                                    <input type="text" name="jabatan_kerja" id="jabatan_kerja"
                                        value="{{ old('jabatan_kerja', $kelulusan->jabatan_kerja) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('jabatan_kerja')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.contact_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="no_hp" class="block text-sm font-medium text-gray-700">{{ __('common.phone_number_label') }}</label>
                                    <input type="text" name="no_hp" id="no_hp"
                                        value="{{ old('no_hp', $kelulusan->no_hp) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('no_hp')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_wa" class="block text-sm font-medium text-gray-700">{{ __('common.whatsapp_number') }}</label>
                                    <input type="text" name="no_wa" id="no_wa"
                                        value="{{ old('no_wa', $kelulusan->no_wa) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('no_wa')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat"
                                        class="block text-sm font-medium text-gray-700">{{ __('common.address_label') }}</label>
                                    <textarea name="alamat" id="alamat" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alamat', $kelulusan->alamat) }}</textarea>
                                    @error('alamat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.additional_info') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="foto" class="block text-sm font-medium text-gray-700">{{ __('common.photo') }}</label>
                                    @if ($kelulusan->foto)
                                        <div class="mb-2">
                                            <img src="{{ $kelulusan->photo_url }}" alt="{{ $kelulusan->nama }}"
                                                class="h-20 w-20 rounded-full object-cover">
                                            <p class="text-sm text-gray-500 mt-1">{{ __('common.current_photo') }}</p>
                                        </div>
                                    @endif
                                    <input type="file" name="foto" id="foto" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    @error('foto')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lulus" class="block text-sm font-medium text-gray-700">{{ __('common.graduation_date') }}</label>
                                    <input type="date" name="tanggal_lulus" id="tanggal_lulus"
                                        value="{{ old('tanggal_lulus', $kelulusan->tanggal_lulus ? $kelulusan->tanggal_lulus->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tanggal_lulus')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700">{{ __('common.achievement') }}</label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('prestasi', $kelulusan->prestasi) }}</textarea>
                                    @error('prestasi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700">{{ __('common.notes') }}</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan', $kelulusan->catatan) }}</textarea>
                                    @error('catatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="border-t pt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.lulus.show', $kelulusan) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.update_graduation_data') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
