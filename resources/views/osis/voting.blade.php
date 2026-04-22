<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.osis_election') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.election_description') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.osis.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back_to_osis') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Voting Status -->
        <div class="mb-8">
            @if ($pemilih->has_voted)
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
                            @if ($siswa->jenis_kelamin)
                                <div class="mt-2 text-sm text-blue-600">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('common.you_viewing_candidates') }} {{ $siswa->jenis_kelamin === 'L' ? __('common.laki_laki') : __('common.perempuan') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if (!$pemilih->has_voted)
            <!-- Voting Form -->
            <form method="POST" action="{{ route('admin.osis.vote') }}" class="space-y-8">
                @csrf

                <!-- Candidates List -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-slate-900">{{ __('common.candidate_list') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($calon as $candidate)
                            <div
                                class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                                <div class="flex items-center space-x-4 mb-4">
                                    <input type="radio" id="calon_{{ $candidate->id }}" name="calon_id"
                                        value="{{ $candidate->id }}"
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <label for="calon_{{ $candidate->id }}" class="flex-1 cursor-pointer">
                                        <h3 class="text-lg font-semibold text-slate-900">
                                            {{ $candidate->full_candidate_name }}</h3>
                                        <p class="text-sm text-slate-600">{{ $candidate->pencalonan_type_display }}</p>
                                    </label>
                                </div>

                                <!-- Candidate Photos -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
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
                                        <p class="text-xs text-slate-500">{{ $candidate->kelas_ketua }}</p>
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
                                        <p class="text-xs text-slate-500">{{ $candidate->kelas_wakil }}</p>
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
                @if ($calon->count() > 0)
                    <div class="flex items-center justify-center pt-6 border-t border-slate-200">
                        <button type="button" class="btn btn-primary btn-lg" onclick="confirmVote()">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kirim Pilihan
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
        function confirmVote() {
            showConfirm(
                '{{ __('common.confirm_voting') }}',
                '{{ __('common.confirm_voting_message') }}',
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
