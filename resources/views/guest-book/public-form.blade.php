<x-guest-book-layout>
    <div class="min-h-screen guest-book-header py-8 px-4 sm:px-6 lg:px-8">

        {{-- Header: Logo & School Name --}}
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-block">
                <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png')) }}"
                     alt="{{ theme_config('name', config('app.name')) }}"
                     class="h-14 sm:h-16 mx-auto mb-3 drop-shadow-lg"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                {{-- Fallback if logo image fails to load --}}
                <div class="hidden items-center justify-center mx-auto mb-3">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>
                    </div>
                </div>
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-white drop-shadow-sm">
                {{ theme_config('name', config('app.name')) }}
            </h1>
        </div>

        {{-- Main Card --}}
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl border border-white/20 overflow-hidden backdrop-blur-sm">

                {{-- Card Header --}}
                <div class="guest-book-header px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-white">Form Check-In Tamu</h2>
                            <p class="text-white/70 text-sm">Registrasi Kunjungan</p>
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
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">1</span>
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('guest_name') border-red-500 @else border-gray-300 @enderror">
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('nik') border-red-500 @else border-gray-300 @enderror">
                                    @error('nik')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- No. WhatsApp --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        No. WhatsApp
                                    </label>
                                    <input type="text" name="phone" id="phone"
                                           value="{{ old('phone') }}"
                                           maxlength="20"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('phone') border-red-500 @else border-gray-300 @enderror">
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('email') border-red-500 @else border-gray-300 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Foto Tamu (4×6) === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                Foto Tamu <span class="text-gray-400 font-normal">(4×6)</span>
                            </legend>

                            <div class="space-y-4">
                                {{-- Camera Container --}}
                                <div id="camera-container" class="relative">
                                    {{-- Video Preview --}}
                                    <div id="video-wrapper" class="relative mx-auto" style="max-width: 240px;">
                                        <video id="camera-video" autoplay playsinline muted
                                               class="w-full rounded-lg border border-gray-300 bg-gray-100"
                                               style="aspect-ratio: 2/3; object-fit: cover;"></video>
                                        {{-- Aspect Ratio Guide Overlay --}}
                                        <div id="ratio-guide" class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                            <div class="w-[80%] h-[85%] border-2 border-dashed border-white/70 rounded-lg"></div>
                                        </div>
                                        {{-- Camera Placeholder --}}
                                        <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 rounded-lg">
                                            <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Kamera belum aktif</span>
                                        </div>
                                    </div>

                                    {{-- Photo Preview (hidden by default) --}}
                                    <div id="photo-preview-wrapper" class="hidden mx-auto" style="max-width: 240px;">
                                        <img id="photo-preview" class="w-full rounded-lg border border-gray-300" style="aspect-ratio: 2/3; object-fit: cover;" alt="Foto Tamu">
                                    </div>
                                </div>

                                {{-- Camera Buttons --}}
                                <div id="camera-buttons" class="flex items-center justify-center gap-2">
                                    <button type="button" id="btn-start-camera" onclick="initCamera()"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                        </svg>
                                        Aktifkan Kamera
                                    </button>
                                    <button type="button" id="btn-capture" onclick="capturePhoto()" class="hidden
                                            inline-flex items-center gap-1.5 px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors guest-book-btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Ambil Foto
                                    </button>
                                    <button type="button" id="btn-retake" onclick="retakePhoto()" class="hidden
                                            inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        Foto Ulang
                                    </button>
                                </div>

                                {{-- Fallback File Input (shown when camera not supported) --}}
                                <div id="camera-fallback" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto (4×6)</label>
                                    <input type="file" name="photo_file" id="photo-file" accept="image/*" capture="user"
                                           onchange="handleFileUpload(event)"
                                           class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:text-white file:cursor-pointer guest-book-btn">
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, rasio 4×6 (2:3)</p>
                                </div>

                                <input type="hidden" name="photo" id="photo-data" value="{{ old('photo') }}">
                                <p id="photo-error" class="text-xs text-gray-400 text-center hidden">Foto tidak berhasil diambil. Silakan coba lagi.</p>
                            </div>
                        </fieldset>

                        {{-- === Instansi / Keperluan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                Instansi & Keperluan
                            </legend>

                            <div class="space-y-4">
                                {{-- Instansi/Organisasi --}}
                                <div>
                                    <label for="organization" class="block text-sm font-medium text-gray-700 mb-1">
                                        Instansi / Asal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="organization" id="organization"
                                           value="{{ old('organization') }}"
                                           placeholder="Nama instansi / perusahaan"
                                           required
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('organization') border-red-500 @else border-gray-300 @enderror">
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('position') border-red-500 @else border-gray-300 @enderror">
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
                                            class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('visit_category') border-red-500 @else border-gray-300 @enderror">
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('visit_target') border-red-500 @else border-gray-300 @enderror">
                                    @error('visit_target')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Keperluan --}}
                                <div>
                                    <label for="visit_purpose" class="block text-sm font-medium text-gray-700 mb-1">
                                        Keperluan / Uraian Kunjungan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="visit_purpose" id="visit_purpose" rows="3"
                                              placeholder="Jelaskan keperluan kunjungan Anda..."
                                              required
                                              class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none guest-book-focus @error('visit_purpose') border-red-500 @else border-gray-300 @enderror">{{ old('visit_purpose') }}</textarea>
                                    @error('visit_purpose')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Kendaraan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">4</span>
                                Kendaraan <span class="text-gray-400 font-normal">(opsional)</span>
                            </legend>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Jenis Kendaraan --}}
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Kendaraan
                                    </label>
                                    <select name="vehicle_type" id="vehicle_type"
                                            class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('vehicle_type') border-red-500 @else border-gray-300 @enderror">
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
                                           class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 guest-book-focus @error('vehicle_plate') border-red-500 @else border-gray-300 @enderror">
                                    @error('vehicle_plate')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- === Tanda Tangan Digital === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">5</span>
                                Tanda Tangan Digital
                            </legend>

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                <canvas id="signature-pad" width="400" height="200" class="w-full cursor-crosshair border border-gray-200 rounded bg-white" style="touch-action: none;"></canvas>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" id="clear-signature" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                    <span class="text-xs text-gray-500">Tanda tangan di area di atas</span>
                                </div>
                                <input type="hidden" name="signature" id="signature-data">
                            </div>
                        </fieldset>

                        {{-- === Catatan === --}}
                        <fieldset class="mb-6">
                            <legend class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="guest-book-step w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">6</span>
                                Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                            </legend>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan Tambahan
                                </label>
                                <textarea name="notes" id="notes" rows="2"
                                          placeholder="Informasi tambahan yang perlu diketahui petugas..."
                                          class="w-full px-3 py-2.5 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none guest-book-focus @error('notes') border-red-500 @else border-gray-300 @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </fieldset>

                        {{-- === Submit Button === --}}
                        <div class="pt-2">
                            <button type="submit" id="submitBtn"
                                    class="w-full flex items-center justify-center gap-2 guest-book-btn text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
                <a href="{{ route('landing') }}" class="text-sm text-white/70 hover:text-white transition-colors inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Photo Camera Styles */
        #camera-video {
            transform: scaleX(-1);
        }
        #photo-preview {
            transform: scaleX(-1);
        }
        #btn-capture {
            display: none;
        }
        #btn-capture.active {
            display: inline-flex;
        }
        #btn-retake {
            display: none;
        }
        #btn-retake.active {
            display: inline-flex;
        }
    </style>

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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDraw(e) {
            isDrawing = true;
            const pos = getPos(e);
            lastX = pos.x;
            lastY = pos.y;
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
            e.preventDefault();
        }

        function stopDraw() {
            if (isDrawing) {
                isDrawing = false;
                document.getElementById('signature-data').value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);
        canvas.addEventListener('touchstart', startDraw);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDraw);

        document.getElementById('clear-signature').addEventListener('click', function() {
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            document.getElementById('signature-data').value = '';
        });
    });

    // =============================================
    // Camera / Photo Functionality
    // =============================================
    let cameraStream = null;

    async function initCamera() {
        const video = document.getElementById('camera-video');
        const placeholder = document.getElementById('camera-placeholder');
        const ratioGuide = document.getElementById('ratio-guide');
        const btnStart = document.getElementById('btn-start-camera');
        const btnCapture = document.getElementById('btn-capture');
        const photoError = document.getElementById('photo-error');

        photoError.classList.add('hidden');

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 600 }, height: { ideal: 900 } }
            });
            cameraStream = stream;
            video.srcObject = stream;
            video.play();

            placeholder.classList.add('hidden');
            ratioGuide.classList.remove('hidden');
            btnStart.classList.add('hidden');
            btnCapture.classList.add('active');
            btnCapture.classList.remove('hidden');
        } catch (err) {
            // Fallback: show file input
            document.getElementById('camera-fallback').classList.remove('hidden');
            btnStart.classList.add('hidden');
        }
    }

    function capturePhoto() {
        const video = document.getElementById('camera-video');
        const canvas = document.getElementById('capture-canvas') || createCaptureCanvas();
        const ctx = canvas.getContext('2d');
        const photoInput = document.getElementById('photo-data');
        const previewWrapper = document.getElementById('photo-preview-wrapper');
        const previewImg = document.getElementById('photo-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const ratioGuide = document.getElementById('ratio-guide');

        // Crop center 2:3 from video
        const ratio = 2 / 3;
        let sw = video.videoWidth;
        let sh = video.videoHeight;
        let cw = sh * ratio;
        let sx = (sw - cw) / 2;

        // If video is mirrored, adjust crop
        canvas.width = 600;
        canvas.height = 900;
        ctx.save();
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, sx, 0, cw, sh, 0, 0, 600, 900);
        ctx.restore();

        photoInput.value = canvas.toDataURL('image/jpeg', 0.85);

        // Stop camera
        stopCamera();

        // Show preview
        previewImg.src = canvas.toDataURL('image/jpeg', 0.85);
        previewWrapper.classList.remove('hidden');
        previewWrapper.classList.add('block');
        videoWrapper.classList.add('hidden');
        ratioGuide.classList.add('hidden');

        // Toggle buttons
        btnCapture.classList.add('hidden');
        btnCapture.classList.remove('active');
        btnRetake.classList.remove('hidden');
        btnRetake.classList.add('active');
    }

    function retakePhoto() {
        const photoInput = document.getElementById('photo-data');
        const previewWrapper = document.getElementById('photo-preview-wrapper');
        const videoWrapper = document.getElementById('video-wrapper');
        const btnRetake = document.getElementById('btn-retake');

        photoInput.value = '';
        previewWrapper.classList.add('hidden');
        previewWrapper.classList.remove('block');
        videoWrapper.classList.remove('hidden');
        btnRetake.classList.add('hidden');
        btnRetake.classList.remove('active');

        initCamera();
    }

    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ratio = 2 / 3;
                let sw = img.width;
                let sh = img.height;
                let cw = sh * ratio;
                let sx = (sw - cw) / 2;

                canvas.width = 600;
                canvas.height = 900;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, sx, 0, cw, sh, 0, 0, 600, 900);

                document.getElementById('photo-data').value = canvas.toDataURL('image/jpeg', 0.85);

                // Show preview
                const previewWrapper = document.getElementById('photo-preview-wrapper');
                const previewImg = document.getElementById('photo-preview');
                previewImg.src = canvas.toDataURL('image/jpeg', 0.85);
                previewWrapper.classList.remove('hidden');
                previewWrapper.classList.add('block');

                document.getElementById('camera-fallback').classList.add('hidden');
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
    }

    function createCaptureCanvas() {
        const canvas = document.createElement('canvas');
        canvas.id = 'capture-canvas';
        canvas.style.display = 'none';
        document.body.appendChild(canvas);
        return canvas;
    }
    </script>
    @endpush
</x-guest-book-layout>
