<x-layouts.guest>
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 py-8 px-4 sm:px-6 lg:px-8">

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
        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                {{-- Success Header --}}
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-8 sm:px-8 text-center">
                    {{-- Check Icon --}}
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Check-In Berhasil!</h2>
                    <p class="text-green-100 text-sm">Data kunjungan Anda telah tercatat.</p>
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
                           class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-center">
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
</x-layouts.guest>
