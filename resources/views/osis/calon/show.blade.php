<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.detail_calon_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ $calon->full_candidate_name }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.osis.calon.edit', $calon) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('common.edit') }}
                </a>
                <a href="{{ route('admin.osis.calon.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Candidate Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">{{ __('common.candidate_information') }}</h3>
                        <span class="badge {{ $calon->is_active ? 'badge-success' : 'badge-warning' }}">
                            {{ $calon->is_active ? __('common.status_active') : __('common.status_inactive') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ketua -->
                        <div class="text-center">
                            <div
                                class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                @if ($calon->ketua_photo_url)
                                    <img src="{{ $calon->ketua_photo_url }}" alt="{{ $calon->nama_ketua }}"
                                        class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900">{{ $calon->nama_ketua }}</h4>
                            <p class="text-sm text-slate-600">{{ __('common.ketua_osis') }}</p>
                        </div>

                        <!-- Wakil -->
                        <div class="text-center">
                            <div
                                class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                @if ($calon->wakil_photo_url)
                                    <img src="{{ $calon->wakil_photo_url }}" alt="{{ $calon->nama_wakil }}"
                                        class="w-24 h-24 rounded-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900">{{ $calon->nama_wakil }}</h4>
                            <p class="text-sm text-slate-600">{{ __('common.wakil_ketua_osis') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.vision_mission') }}</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($calon->visi_misi)) !!}
                    </div>
                </div>

                <!-- Voting Statistics -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.voting_statistics') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <p class="text-2xl font-bold text-slate-900">{{ $calon->total_votes }}</p>
                            <p class="text-sm text-slate-600">{{ __('common.total_votes_label') }}</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <p class="text-2xl font-bold text-slate-900">{{ $calon->vote_percentage }}%</p>
                            <p class="text-sm text-slate-600">{{ __('common.percentage') }}</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <p class="text-2xl font-bold text-slate-900">{{ $calon->pencalonan_type_display }}</p>
                            <p class="text-sm text-slate-600">{{ __('common.jenis_pencalonan') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.quick_actions') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.osis.calon.edit', $calon) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">{{ __('common.edit_calon') }}</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.osis.results') }}"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">{{ __('common.view_results') }}</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Recent Votes -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.recent_voting') }}</h3>
                    <div class="space-y-3">
                        @forelse($calon->votings()->latest()->limit(5)->get() as $vote)
                            <div class="flex items-center space-x-3 p-2 hover:bg-slate-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900">{{ $vote->pemilih->nama }}</p>
                                    <p class="text-xs text-slate-500">{{ $vote->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm">{{ __('common.no_votes_yet') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'calon_show_success_' + '{{ md5(session('success') . time()) }}';
                if (!sessionStorage.getItem(successKey) && typeof showSuccess !== 'undefined') {
                    showSuccess('{{ session('success') }}');
                    sessionStorage.setItem(successKey, 'shown');
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorKey = 'calon_show_error_' + '{{ md5(session('error') . time()) }}';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    @endif
</x-app-layout>
