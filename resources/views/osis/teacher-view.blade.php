<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.osis_teacher_view_title') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.teacher_view_description') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.osis.results') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('common.view_results') }}
                </a>
                <a href="{{ route('admin.osis.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if ($calons->count() > 0)
            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">{{ __('common.teacher_info_title') }}</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            {{ __('common.teacher_info_description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Candidates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($calons as $calon)
                    <div
                        class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $calon->full_candidate_name }}</h3>
                                    <p class="text-blue-100 text-sm">{{ $calon->pencalonan_type_display }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $calon->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $calon->gender_display }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Photos -->
                            <div class="flex justify-center space-x-4 mb-4">
                                @if ($calon->foto_ketua)
                                    <div class="text-center">
                                        <img src="{{ $calon->ketua_photo_url }}" alt="{{ $calon->nama_ketua }}"
                                            class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-slate-200">
                                        <p class="text-xs text-slate-600">{{ __('common.ketua') }}</p>
                                    </div>
                                @endif
                                @if ($calon->foto_wakil && $calon->nama_wakil)
                                    <div class="text-center">
                                        <img src="{{ $calon->wakil_photo_url }}" alt="{{ $calon->nama_wakil }}"
                                            class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-slate-200">
                                        <p class="text-xs text-slate-600">{{ __('common.wakil') }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Visi Misi Preview -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-slate-900 mb-2">{{ __('common.vision_mission') }}</h4>
                                <p class="text-sm text-slate-600 line-clamp-3">
                                    {{ Str::limit($calon->visi_misi, 150) }}
                                </p>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    {{ $calon->votings_count ?? 0 }} {{ __('common.votes') }}
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $calon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $calon->is_active ? __('common.status_active') : __('common.status_inactive') }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.osis.calon.show', $calon) }}"
                                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                        {{ __('common.view_details') }}
                                    </a>
                                    @if (Auth::user()->hasRole('superadmin'))
                                        <a href="{{ route('admin.osis.calon.edit', $calon) }}"
                                            class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                            {{ __('common.edit') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="mt-8 bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.candidate_summary') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $calons->count() }}</div>
                        <div class="text-sm text-blue-700">{{ __('common.total_calon') }}</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $calons->where('is_active', true)->count() }}
                        </div>
                        <div class="text-sm text-green-700">{{ __('common.active_candidates') }}</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $calons->sum('votings_count') }}</div>
                        <div class="text-sm text-purple-700">{{ __('common.total_suara') }}</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-2">{{ __('common.no_candidates') }}</h3>
                <p class="text-slate-600 mb-6">{{ __('common.no_candidates_message') }}</p>
                @if (Auth::user()->hasRole('superadmin'))
                    <a href="{{ route('admin.osis.calon.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('common.add_first_candidate') }}
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'osis_teacher_view_success_' + '{{ md5(session('success') . time()) }}';
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
                const errorKey = 'osis_teacher_view_error_' + '{{ md5(session('error') . time()) }}';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            const infoKey = 'osis_teacher_view_info_' + '{{ md5(session('info') . time()) }}';
            if (!sessionStorage.getItem(infoKey)) {
                showAlert('Info', '{{ session('info') }}', 'info');
                sessionStorage.setItem(infoKey, 'shown');
            }
        </script>
    @endif
</x-app-layout>
