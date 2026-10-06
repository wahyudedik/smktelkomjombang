<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.osis_election') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.election_description') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {{-- Route admin.osis.index butuh role admin|superadmin|osis — siswa/guru diarahkan ke dashboard --}}
                @if (Auth::user()->hasAnyRole(['siswa', 'guru']))
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('common.back_to_osis') }}
                    </a>
                @else
                    <a href="{{ route('admin.osis.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('common.back_to_osis') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Voting Status -->
        <div class="mb-8">
            @if ($hasVoted)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-900">{{ __('common.you_already_voted') }}</h3>
                            <p class="text-green-700">{{ __('common.thanks_for_participation') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-900">{{ __('common.please_select_candidate') }}</h3>
                            <p class="text-blue-700">{{ __('common.select_best_candidate') }}</p>
                            <div class="mt-2 text-sm text-blue-600">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @if (!empty($showAll))
                                        Anda melihat semua calon
                                    @else
                                        Anda melihat kandidat sesuai jenis kelamin Anda{{ !empty($genderLabel) ? ' (' . e($genderLabel) . ')' : '' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if (!$hasVoted)
            <!-- Voting Form -->
            <form method="POST" action="{{ route('admin.osis.vote') }}" class="space-y-8">
                @csrf

                <!-- Candidates List -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-slate-900">{{ __('common.candidate_list') }}</h2>

                    @if (!empty($isGuru))
                        <!-- Hint multi-select guru: maksimal 2 pasangan calon + counter live -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-start text-amber-800">
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="ml-2 text-sm font-medium">Guru dapat memilih maksimal 2 pasangan
                                        calon. Centang 1 atau 2 pilihan, lalu kirim.</span>
                                </div>
                                <span id="vote-counter"
                                    class="self-start sm:self-auto text-sm font-semibold text-amber-800 bg-amber-100 px-3 py-1 rounded-full">0/2
                                    dipilih</span>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($calons as $candidate)
                            <div
                                class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                                <div class="flex items-center space-x-4 mb-4">
                                    @if (!empty($isGuru))
                                        {{-- Guru: checkbox multi-select maksimal 2 pasangan calon --}}
                                        <input type="checkbox" id="calon_{{ $candidate->id }}" name="calon_ids[]"
                                            value="{{ $candidate->id }}"
                                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-slate-300 calon-checkbox">
                                    @else
                                        {{-- Siswa: radio single-select (1 pasangan calon) — semantik TIDAK berubah --}}
                                        <input type="radio" id="calon_{{ $candidate->id }}" name="calon_id"
                                            value="{{ $candidate->id }}"
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
                                                <svg class="w-8 h-8 text-orange-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <h4 class="font-medium text-slate-900">{{ $candidate->nama_ketua }}</h4>
                                        <p class="text-sm text-slate-600">{{ __('common.ketua_osis') }}</p>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <h4 class="font-medium text-slate-900">{{ $candidate->nama_wakil }}</h4>
                                        <p class="text-sm text-slate-600">{{ __('common.wakil_ketua_osis') }}</p>
                                    </div>
                                </div>

                                <!-- Visi Misi -->
                                <div class="mt-4">
                                    <h4 class="font-medium text-slate-900 mb-2">Visi & Misi</h4>
                                    <div
                                        class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3 max-h-32 overflow-y-auto">
                                        {!! nl2br(e($candidate->visi_misi)) !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <p class="text-slate-500">{{ __('common.no_candidates') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Submit Button -->
                @if ($calons->count() > 0)
                    <div class="flex items-center justify-center pt-6 border-t border-slate-200">
                        <button type="button" class="btn btn-primary btn-lg" onclick="confirmVote()">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{-- Label menyesuaikan peran: guru multi-select vs siswa single-select --}}
                            {{ !empty($isGuru) ? 'Kirim Pilihan' : 'Kirim Suara' }}
                        </button>
                    </div>
                @endif
            </form>
        @else
            <!-- Already Voted Message -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-slate-900 mb-2">{{ __('common.thanks_for_participating') }}</h3>
                <p class="text-slate-600 mb-6">{{ __('common.successfully_voted') }}</p>
                <a href="{{ route('admin.osis.results') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('common.view_preliminary_results') }}
                </a>
            </div>
        @endif
    </div>

    <script>
        @if (!empty($isGuru))
            // Multi-select guru: maksimal 2 pasangan calon (client-side enforcement)
            // Helper global showConfirm/showError tersedia dari resources/js/app.js (SweetAlert2)
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
                                    'Guru dapat memilih maksimal 2 pasangan calon. Silakan hapus salah satu centangan terlebih dahulu.');
                            } else {
                                alert('Guru dapat memilih maksimal 2 pasangan calon.');
                            }
                        }
                        updateCounter();
                    });
                });

                updateCounter();
            })();
        @endif

        function confirmVote() {
            @if (!empty($isGuru))
            var selectedCount = document.querySelectorAll('.calon-checkbox:checked').length;
            if (selectedCount === 0) {
                if (typeof showError !== 'undefined') {
                    showError('Belum Ada Pilihan', 'Pilih minimal satu pasangan calon sebelum mengirim suara.');
                } else {
                    alert('Pilih minimal satu pasangan calon sebelum mengirim suara.');
                }
                return;
            }
            var voteConfirmText = selectedCount === 1
                ? '{{ __('common.confirm_voting_message') }}'
                : 'Anda memilih ' + selectedCount + ' pasangan calon. Kirim pilihan Anda sekarang?';
            @else
            var voteConfirmText = '{{ __('common.confirm_voting_message') }}';
            @endif
            showConfirm(
                '{{ __('common.confirm_voting') }}',
                voteConfirmText,
                '{{ __('common.yes_vote') }}',
                '{{ __('common.cancel') }}'
            ).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit();
                }
            });
        }
    </script>
</x-app-layout>
