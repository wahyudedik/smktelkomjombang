<x-osis-public.layout title="Pilih Calon - Pemilihan OSIS">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Konfirmasi identitas -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-900">
                            Halo <strong>{{ $pemilih->nama }}</strong> — {{ $pemilih->kelas ?: 'Siswa' }}
                        </h3>
                        <p class="text-blue-700 text-sm mt-0.5">
                            @if ($isGuru)
                                Pastikan data di atas benar, lalu pilih <strong>1 atau 2</strong> pasangan calon.
                            @else
                                Pastikan data di atas benar, lalu pilih satu pasangan calon.
                            @endif
                        </p>
                        <div class="mt-2 text-sm text-blue-600">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @if (!$showAll)
                                    Anda melihat kandidat sesuai jenis kelamin Anda{{ $genderLabel ? ' (' . e($genderLabel) . ')' : '' }}
                                    — Siswa laki-laki hanya dapat memilih calon laki-laki, siswa perempuan hanya dapat
                                    memilih calon perempuan.
                                @else
                                    Anda melihat semua calon
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('osis.public.landing') }}"
                    class="self-start inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Keluar
                </a>
            </div>
        </div>

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

        <!-- Voting Form -->
        <form method="POST" action="{{ route('osis.public.vote.store') }}" id="voting-form" class="space-y-8">
            @csrf

            <!-- Candidates List -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-slate-900">Daftar Calon</h2>
                    <span class="text-sm text-slate-500">
                        @if ($isGuru)
                            Pilih <strong>1 atau 2</strong> pasangan calon
                        @else
                            Pilih <strong>satu</strong> pasangan calon
                        @endif
                    </span>
                </div>

                @if ($isGuru)
                    <!-- Hint multi-select guru: maksimal 2 pasangan calon + counter live -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-start text-amber-800">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="ml-2 text-sm font-medium">Anda dapat memilih 1 atau 2 pasangan calon.
                                    Centang 1 atau 2 pilihan, lalu kirim.</span>
                            </div>
                            <span id="vote-counter"
                                class="self-start sm:self-auto text-sm font-semibold text-amber-800 bg-amber-100 px-3 py-1 rounded-full">0/2
                                dipilih</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($calons as $candidate)
                        <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center space-x-4 mb-4">
                                @if ($isGuru)
                                    {{-- Guru: checkbox multi-select maksimal 2 pasangan calon --}}
                                    <input type="checkbox" id="calon_{{ $candidate->id }}" name="calon_ids[]"
                                        value="{{ $candidate->id }}"
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-slate-300 calon-checkbox">
                                @else
                                    {{-- Siswa: radio single-select (1 pasangan calon) — semantik TIDAK berubah --}}
                                    <input type="radio" id="calon_{{ $candidate->id }}" name="calon_id"
                                        value="{{ $candidate->id }}" required
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-slate-300">
                                @endif
                                <label for="calon_{{ $candidate->id }}" class="flex-1 cursor-pointer">
                                    <h3 class="text-lg font-semibold text-slate-900">
                                        {{ $candidate->full_candidate_name }}</h3>
                                    <p class="text-sm text-slate-600">{{ $candidate->pencalonan_type_display }}</p>
                                </label>
                            </div>

                            <!-- Candidate Photos -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <!-- Ketua -->
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        @if ($candidate->ketua_photo_url)
                                            <img src="{{ $candidate->ketua_photo_url }}"
                                                alt="{{ $candidate->nama_ketua }}"
                                                class="w-20 h-20 rounded-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <h4 class="font-medium text-slate-900">{{ $candidate->nama_ketua }}</h4>
                                    <p class="text-sm text-slate-600">Ketua OSIS</p>
                                </div>

                                <!-- Wakil -->
                                <div class="text-center">
                                    <div
                                        class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        @if ($candidate->wakil_photo_url)
                                            <img src="{{ $candidate->wakil_photo_url }}"
                                                alt="{{ $candidate->nama_wakil }}"
                                                class="w-20 h-20 rounded-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <h4 class="font-medium text-slate-900">{{ $candidate->nama_wakil }}</h4>
                                    <p class="text-sm text-slate-600">Wakil Ketua OSIS</p>
                                </div>
                            </div>

                            <!-- Visi Misi -->
                            <div class="mt-4">
                                <h4 class="font-medium text-slate-900 mb-2">Visi & Misi</h4>
                                <div class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3 max-h-32 overflow-y-auto">
                                    {!! nl2br(e($candidate->visi_misi)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 bg-white rounded-xl border border-slate-200">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <p class="text-slate-500">Belum ada kandidat yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Submit Button -->
            @if ($calons->count() > 0)
                <div class="flex items-center justify-center pt-6 border-t border-slate-200">
                    <button type="button" onclick="confirmVote()"
                        class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $isGuru ? 'Kirim Pilihan' : 'Kirim Suara' }}
                    </button>
                </div>
            @endif
        </form>
    </div>

    <script>
        @if ($isGuru)
            // Multi-select guru: maksimal 2 pasangan calon (client-side enforcement)
            // — pola sama dengan resources/views/osis/voting.blade.php (jalur login)
            (function () {
                var MAX_VOTES = 2;
                var counter = document.getElementById('vote-counter');

                function checkedCount() {
                    return document.querySelectorAll('.calon-checkbox:checked').length;
                }

                function updateCounter() {
                    if (counter) {
                        counter.textContent = checkedCount() + '/' + MAX_VOTES + ' dipilih';
                    }
                }

                document.querySelectorAll('.calon-checkbox').forEach(function (checkbox) {
                    checkbox.addEventListener('change', function () {
                        if (checkedCount() > MAX_VOTES) {
                            // Tolak centang ke-3: batalkan + pesan error konsisten dengan UI
                            this.checked = false;
                            if (typeof showError !== 'undefined') {
                                showError('Maksimal 2 Pilihan',
                                    'Anda dapat memilih maksimal 2 pasangan calon. Silakan hapus salah satu centangan terlebih dahulu.');
                            } else {
                                alert('Anda dapat memilih maksimal 2 pasangan calon.');
                            }
                        }
                        updateCounter();
                    });
                }

                updateCounter();
            })();
        @endif

        function confirmVote() {
            @if ($isGuru)
                // Validasi client-side SEBELUM modal konfirmasi: minimal 1 checkbox tercentang
                var selectedCount = document.querySelectorAll('.calon-checkbox:checked').length;
                if (selectedCount === 0) {
                    if (typeof showError !== 'undefined') {
                        showError('Belum Ada Pilihan', 'Silakan pilih minimal satu pasangan calon.');
                    } else {
                        alert('Silakan pilih minimal satu pasangan calon.');
                    }
                    return;
                }
                var confirmMessage = selectedCount === 1
                    ? 'Suara Anda bersifat RAHASIA dan hanya dapat digunakan satu kali. ' +
                        'Setelah dikirim, pilihan tidak dapat diubah lagi. Kirim suara Anda sekarang?'
                    : 'Anda memilih ' + selectedCount + ' pasangan calon. Suara Anda bersifat RAHASIA dan hanya dapat ' +
                        'digunakan satu kali. Setelah dikirim, pilihan tidak dapat diubah lagi. Kirim pilihan Anda sekarang?';
            @else
                // Validasi client-side SEBELUM modal konfirmasi: radio calon_id wajib tercentang
                var selectedRadio = document.querySelector('input[name="calon_id"]:checked');
                if (!selectedRadio) {
                    if (typeof showError !== 'undefined') {
                        showError('Belum Ada Pilihan', 'Silakan pilih satu pasangan calon terlebih dahulu.');
                    } else {
                        alert('Silakan pilih satu pasangan calon terlebih dahulu.');
                    }
                    return;
                }
                var confirmMessage = 'Suara Anda bersifat RAHASIA dan hanya dapat digunakan satu kali. ' +
                    'Setelah dikirim, pilihan tidak dapat diubah lagi. Kirim suara Anda sekarang?';
            @endif

            // Helper global showConfirm/showError tersedia dari resources/js/app.js (SweetAlert2)
            if (typeof showConfirm !== 'undefined') {
                showConfirm('Konfirmasi Pilihan', confirmMessage, 'Ya, Kirim Suara', 'Batal')
                    .then((result) => {
                        if (result.isConfirmed) {
                            // Submit form voting secara EKSPLISIT — jangan querySelector generik
                            var votingForm = document.getElementById('voting-form');
                            if (votingForm) {
                                votingForm.submit();
                            }
                        }
                    });
            } else if (confirm(confirmMessage)) {
                document.getElementById('voting-form').submit();
            }
        }
    </script>
</x-osis-public.layout>
