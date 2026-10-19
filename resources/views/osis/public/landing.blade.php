<x-osis-public.layout title="Pemilihan OSIS">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Judul -->
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Pemilihan OSIS</h1>
            <p class="text-slate-600">Verifikasi identitas Anda untuk menggunakan hak pilih secara online.
                Cukup masukkan NIS/NISN (siswa) atau NIP (guru).</p>
        </div>

        <!-- Info pemilihan aktif -->
        @if ($election)
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3 text-sm">
                        <p class="font-semibold text-green-900">{{ $election->title }}</p>
                        @if ($election->description)
                            <p class="text-green-700 mt-0.5">{{ $election->description }}</p>
                        @endif
                        <p class="text-green-700 mt-1">
                            {{ $election->start_date?->format('d/m/Y H:i') }} —
                            {{ $election->end_date?->format('d/m/Y H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-amber-800">
                        Pemilihan OSIS belum dimulai atau sudah berakhir. Silakan kembali lagi nanti.
                    </p>
                </div>
            </div>
        @endif

        <!-- Alert error -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Card form verifikasi -->
        @if ($election)
            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Verifikasi Identitas</h2>
                <p class="text-sm text-slate-600 mb-6">Pilih tipe pemilih, lalu masukkan nomor identitas Anda.</p>

                <form method="POST" action="{{ route('osis.public.verify') }}" class="space-y-6">
                    @csrf

                    <!-- Tipe pemilih -->
                    <div>
                        <label class="block text-sm font-medium text-slate-900 mb-3">Saya adalah</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="flex items-center justify-center p-4 border-2 border-slate-200 hover:border-slate-300 rounded-xl cursor-pointer transition-colors peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                <input type="radio" name="tipe" value="siswa" id="tipe-siswa"
                                    class="sr-only peer" {{ old('tipe', 'siswa') !== 'guru' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900">Siswa</span>
                                </div>
                            </label>
                            <label
                                class="flex items-center justify-center p-4 border-2 border-slate-200 hover:border-slate-300 rounded-xl cursor-pointer transition-colors peer-checked:border-blue-600 peer-checked:bg-blue-50">
                                <input type="radio" name="tipe" value="guru" id="tipe-guru"
                                    class="sr-only peer" {{ old('tipe') === 'guru' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-slate-900">Guru</span>
                                </div>
                            </label>
                        </div>
                        @error('tipe')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Identifier -->
                    <div>
                        <label for="identifier" class="block text-sm font-medium text-slate-900 mb-1"
                            id="identifier-label">NIS atau NISN</label>
                        <p class="text-xs text-slate-500 mb-2" id="identifier-hint">Masukkan salah satu — NIS atau
                            NISN.</p>
                        <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}"
                            maxlength="32" autocomplete="off" placeholder="Contoh: 4009/353.067"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('identifier')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verifikasi & Lanjutkan
                    </button>
                </form>
            </div>
        @endif
    </div>

    <script>
        (function () {
            var labelEl = document.getElementById('identifier-label');
            var hintEl = document.getElementById('identifier-hint');
            var inputEl = document.getElementById('identifier');

            function sync() {
                var tipe = (document.querySelector('input[name="tipe"]:checked') || {}).value || 'siswa';
                if (tipe === 'guru') {
                    labelEl.textContent = 'NIP';
                    hintEl.textContent = 'Masukkan NIP Anda (Nomor Induk Pegawai).';
                    inputEl.placeholder = 'Contoh: 198001012005011001';
                    inputEl.setAttribute('inputmode', 'numeric');
                } else {
                    labelEl.textContent = 'NIS atau NISN';
                    hintEl.textContent = 'Masukkan salah satu — NIS atau NISN.';
                    inputEl.placeholder = 'Contoh: 4009/353.067';
                    inputEl.removeAttribute('inputmode');
                }
            }

            document.querySelectorAll('input[name="tipe"]').forEach(function (radio) {
                radio.addEventListener('change', sync);
            });
            sync();
        })();
    </script>
</x-osis-public.layout>
