<x-osis-public.layout title="Terima Kasih - Pemilihan OSIS">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        @if ($alreadyVoted)
            <!-- Sudah memilih (tanpa membocorkan pilihan) -->
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">Anda Sudah Menggunakan Hak Pilih Anda
            </h1>
            <p class="text-slate-600 mb-8">
                Terima kasih telah berpartisipasi dalam pemilihan OSIS.
                Setiap pemilih hanya dapat memberikan suara satu kali — pilihan Anda bersifat
                <strong>rahasia</strong> dan tidak dapat diubah kembali.
            </p>
        @else
            <!-- Vote sukses -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">Terima Kasih!</h1>
            <p class="text-slate-600 mb-8">
                Hak pilih Anda telah tercatat dalam pemilihan OSIS.
                Pilihan Anda bersifat <strong>rahasia</strong> dan hanya digunakan satu kali.
            </p>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @if ($showResults || $alreadyVoted)
                <a href="{{ route('osis.public.results') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Lihat Hasil Pemilihan
                </a>
            @endif
            <a href="{{ route('osis.public.landing') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 font-medium transition-colors w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-osis-public.layout>
