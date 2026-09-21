<x-layouts.guest>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">

        {{-- Header: Logo & School Name --}}
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png')) }}"
                     alt="{{ theme_config('name', config('app.name')) }}"
                     class="h-14 sm:h-16 mx-auto mb-3">
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                {{ theme_config('name', config('app.name')) }}
            </h1>
        </div>

        {{-- Main Card --}}
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-white">Form Check-In Tamu</h2>
                            <p class="text-blue-100 text-sm">Registrasi Kunjungan</p>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="px-6 py-6 sm:px-8">

                    {{-- Subtitle --}}
                    <p class="text-gray-500 text-sm mb-6 text-center">
                        Silakan mengisi data diri Anda untuk keperluan kunjungan.
                        <span class="text-red-500">*</span> wajib diisi.
                    </p>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('guest-book.store') }}" id="guestCheckinForm">
                        @csrf

                        {{-- === Data Diri === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                Data Diri
                            </legend>

                            <div class="space-y-4">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="guest_name" id="guest_name"
                                           value="{{ old('guest_name') }}"
                                           placeholder="Masukkan nama lengkap"
                                           required
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('guest_name') border-red-500 @else border-gray-300 @enderror">
                                    @error('guest_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NIK --}}
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                                        NIK / KTP
                                    </label>
                                    <input type="text" name="nik" id="nik"
                                           value="{{ old('nik') }}"
                                           maxlength="20"
                                           placeholder="Nomor induk kependudukan"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @else border-gray-300 @enderror">
                                    @error('nik')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- No. Telepon --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        No. Telepon
                                    </label>
                                    <input type="text" name="phone" id="phone"
                                           value="{{ old('phone') }}"
                                           maxlength="20"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @else border-gray-300 @enderror">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email') }}"
                                           placeholder="contoh@email.com"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Instansi / Keperluan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                Instansi & Keperluan
                            </legend>

                            <div class="space-y-4">
                                {{-- Instansi/Organisasi --}}
                                <div>
                                    <label for="organization" class="block text-sm font-medium text-gray-700 mb-1">
                                        Instansi / Organisasi
                                    </label>
                                    <input type="text" name="organization" id="organization"
                                           value="{{ old('organization') }}"
                                           placeholder="Nama instansi / perusahaan"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('organization') border-red-500 @else border-gray-300 @enderror">
                                    @error('organization')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jabatan
                                    </label>
                                    <input type="text" name="position" id="position"
                                           value="{{ old('position') }}"
                                           placeholder="Jabatan Anda"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @else border-gray-300 @enderror">
                                    @error('position')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kategori Kunjungan --}}
                                <div>
                                    <label for="visit_category" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kategori Kunjungan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="visit_category" id="visit_category" required
                                            class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('visit_category') border-red-500 @else border-gray-300 @enderror">
                                        <option value="">— Pilih Kategori —</option>
                                        <option value="ppdb" {{ old('visit_category') === 'ppdb' ? 'selected' : '' }}>PPDB</option>
                                        <option value="ortu_wali" {{ old('visit_category') === 'ortu_wali' ? 'selected' : '' }}>Orang Tua / Wali</option>
                                        <option value="konsultasi" {{ old('visit_category') === 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                                        <option value="dinas" {{ old('visit_category') === 'dinas' ? 'selected' : '' }}>Dinas</option>
                                        <option value="supplier" {{ old('visit_category') === 'supplier' ? 'selected' : '' }}>Supplier</option>
                                        <option value="acara" {{ old('visit_category') === 'acara' ? 'selected' : '' }}>Acara</option>
                                        <option value="lainnya" {{ old('visit_category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('visit_category')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Yang Dituju --}}
                                <div>
                                    <label for="visit_target" class="block text-sm font-medium text-gray-700 mb-1">
                                        Yang Dituju
                                    </label>
                                    <input type="text" name="visit_target" id="visit_target"
                                           value="{{ old('visit_target') }}"
                                           placeholder="Nama guru / pegawai yang dituju"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('visit_target') border-red-500 @else border-gray-300 @enderror">
                                    @error('visit_target')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Keperluan --}}
                                <div>
                                    <label for="visit_purpose" class="block text-sm font-medium text-gray-700 mb-1">
                                        Keperluan / Uraian Kunjungan
                                    </label>
                                    <textarea name="visit_purpose" id="visit_purpose" rows="3"
                                              placeholder="Jelaskan keperluan kunjungan Anda..."
                                              class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('visit_purpose') border-red-500 @else border-gray-300 @enderror">{{ old('visit_purpose') }}</textarea>
                                    @error('visit_purpose')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Kendaraan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                Kendaraan <span class="text-gray-400 font-normal">(opsional)</span>
                            </legend>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Jenis Kendaraan --}}
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Kendaraan
                                    </label>
                                    <select name="vehicle_type" id="vehicle_type"
                                            class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_type') border-red-500 @else border-gray-300 @enderror">
                                        <option value="">— Pilih —</option>
                                        <option value="Motor" {{ old('vehicle_type') === 'Motor' ? 'selected' : '' }}>Motor</option>
                                        <option value="Mobil" {{ old('vehicle_type') === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                        <option value="Lainnya" {{ old('vehicle_type') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        <option value="Tidak Ada" {{ old('vehicle_type') === 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                    </select>
                                    @error('vehicle_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Plat Nomor --}}
                                <div>
                                    <label for="vehicle_plate" class="block text-sm font-medium text-gray-700 mb-1">
                                        Plat Nomor
                                    </label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate"
                                           value="{{ old('vehicle_plate') }}"
                                           maxlength="20"
                                           placeholder="B 1234 XX"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vehicle_plate') border-red-500 @else border-gray-300 @enderror">
                                    @error('vehicle_plate')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Catatan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                            </legend>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan Tambahan
                                </label>
                                <textarea name="notes" id="notes" rows="2"
                                          placeholder="Informasi tambahan yang perlu diketahui petugas..."
                                          class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('notes') border-red-500 @else border-gray-300 @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </fieldset>

                        {{-- === Submit Button --}}
                        <div class="pt-2">
                            <button type="submit" id="submitBtn"
                                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="btnText">Check-In Sekarang</span>
                                <svg id="btnSpinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    {{-- Footer Info --}}
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400">
                            Dengan mengisi form ini, Anda menyetujui data Anda dicatat untuk keperluan kunjungan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Back to Home --}}
            <div class="text-center mt-6">
                <a href="{{ route('landing') }}" class="text-sm text-gray-500 hover:text-blue-600 transition-colors inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('guestCheckinForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        });
    </script>
    @endpush
</x-layouts.guest>
