<x-osis-public.layout title="Hasil Pemilihan - Pemilihan OSIS">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Judul -->
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Hasil Pemilihan OSIS</h1>
            @if ($election)
                <p class="text-slate-600">{{ $election->title }} —
                    <span class="font-medium">{{ $election->status_display }}</span>
                </p>
            @else
                <p class="text-slate-600">Belum ada data pemilihan.</p>
            @endif
        </div>

        @if (!$election)
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <p class="text-slate-500">Belum ada pemilihan OSIS yang tersedia.</p>
            </div>
        @else
            <!-- Ringkasan partisipasi (SELALU ditampilkan — anonim) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
                    <p class="text-sm font-medium text-slate-600 mb-1">Total Pemilih</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalPemilih }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
                    <p class="text-sm font-medium text-slate-600 mb-1">Sudah Memilih</p>
                    <p class="text-3xl font-bold text-green-600">{{ $sudahMemilih }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
                    <p class="text-sm font-medium text-slate-600 mb-1">Partisipasi</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $votingPercentage }}%</p>
                </div>
            </div>

            @if ($resultsHidden)
                <!-- Hasil tersembunyi (pemilihan masih berjalan & admin belum mengizinkan) -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-amber-900">Perolehan Suara Belum Diumumkan</h3>
                            <p class="text-amber-700 text-sm mt-1">
                                Hasil perolehan suara per calon akan diumumkan setelah pemilihan ditutup.
                                Ringkasan partisipasi di atas tidak bersifat rahasia dan diperbarui secara berkala.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Perolehan suara per calon -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Perolehan Suara</h3>

                    @php
                        $maxVotes = $calons->max('votings_count') ?: 0;
                    @endphp

                    <div class="space-y-5">
                        @forelse ($calons as $index => $calon)
                            @php
                                $percentage = $totalVotes > 0 ? round(($calon->votings_count / $totalVotes) * 100, 1) : 0;
                                $barWidth = $maxVotes > 0 ? round(($calon->votings_count / $maxVotes) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <span
                                            class="flex-shrink-0 w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-700">{{ $index + 1 }}</span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900 truncate">
                                                {{ $calon->full_candidate_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $calon->pencalonan_type_display }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0 ml-4">
                                        <span class="font-bold text-slate-900">{{ $calon->votings_count }}</span>
                                        <span class="text-sm text-slate-500">suara ({{ $percentage }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all"
                                        style="width: {{ $barWidth }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 text-center py-4">Belum ada kandidat terdaftar.</p>
                        @endforelse
                    </div>

                    @if ($totalVotes > 0)
                        <p class="text-xs text-slate-500 mt-4 pt-4 border-t border-slate-100">
                            Total suara sah tercatat: <strong>{{ $totalVotes }}</strong> —
                            Pemilih belum memberikan suara: <strong>{{ $belumMemilih }}</strong>
                        </p>
                    @endif
                </div>
            @endif
        @endif

        <div class="text-center mt-8">
            <a href="{{ route('osis.public.landing') }}"
                class="inline-flex items-center px-6 py-3 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</x-osis-public.layout>
