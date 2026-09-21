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
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl border border-white/20 overflow-hidden backdrop-blur-sm">

                {{-- Success Header --}}
                <div class="guest-book-header px-6 py-8 sm:px-8 text-center">
                    {{-- Check Icon --}}
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Check-In Berhasil!</h2>
                    <p class="text-white/70 text-sm">Data kunjungan Anda telah tercatat.</p>
                </div>

                {{-- Card Body --}}
                <div class="px-6 py-6 sm:px-8">

                    {{-- Ticket Number --}}
                    <div class="text-center mb-6">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Tiket</p>
                        <div class="inline-block bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl px-6 py-3">
                            <span class="text-2xl sm:text-3xl font-mono font-bold text-gray-900 tracking-wider">
                                {{ $guest->ticket_number }}
                            </span>
                        </div>
                    </div>

                    {{-- Data Summary --}}
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-5 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Ringkasan Data</h3>
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-sm text-gray-500 flex-shrink-0">Nama</span>
                                <span class="text-sm font-medium text-gray-900 text-right">{{ $guest->guest_name }}</span>
                            </div>
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-sm text-gray-500 flex-shrink-0">Kategori</span>
                                <span class="text-sm font-medium text-gray-900 text-right">
                                    @php
                                        $categories = [
                                            'ppdb' => 'PPDB',
                                            'ortu_wali' => 'Orang Tua / Wali',
                                            'konsultasi' => 'Konsultasi',
                                            'dinas' => 'Dinas',
                                            'supplier' => 'Supplier',
                                            'acara' => 'Acara',
                                            'lainnya' => 'Lainnya',
                                        ];
                                    @endphp
                                    {{ $categories[$guest->visit_category] ?? $guest->visit_category }}
                                </span>
                            </div>
                            @if($guest->organization)
                                <div class="flex justify-between items-start gap-4">
                                    <span class="text-sm text-gray-500 flex-shrink-0">Instansi</span>
                                    <span class="text-sm font-medium text-gray-900 text-right">{{ $guest->organization }}</span>
                                </div>
                            @endif
                            @if($guest->visit_target)
                                <div class="flex justify-between items-start gap-4">
                                    <span class="text-sm text-gray-500 flex-shrink-0">Dituju</span>
                                    <span class="text-sm font-medium text-gray-900 text-right">{{ $guest->visit_target }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-sm text-gray-500 flex-shrink-0">Waktu Masuk</span>
                                <span class="text-sm font-medium text-gray-900 text-right">
                                    {{ $guest->check_in_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </span>
                            </div>
                            @if($guest->vehicle_type && $guest->vehicle_type !== 'Tidak Ada')
                                <div class="flex justify-between items-start gap-4">
                                    <span class="text-sm text-gray-500 flex-shrink-0">Kendaraan</span>
                                    <span class="text-sm font-medium text-gray-900 text-right">
                                        {{ $guest->vehicle_type }}{{ $guest->vehicle_plate ? ' — ' . $guest->vehicle_plate : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Instruction --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900 mb-1">Petunjuk</p>
                            <p class="text-sm text-blue-700">
                                Silakan tunjukkan nomor tiket ini kepada petugas di bagian resepsionis.
                            </p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        {{-- Check-in Tamu Berikutnya --}}
                        <a href="{{ route('guest-book.form') }}"
                           class="w-full flex items-center justify-center gap-2 guest-book-btn text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Check-In Tamu Berikutnya
                        </a>

                        {{-- Kembali ke Beranda --}}
                        <a href="{{ route('landing') }}"
                           class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-all duration-200 text-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-book-layout>
