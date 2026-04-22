<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.detail_pemilih_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ $pemilih->nama }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.osis.pemilih.edit', $pemilih) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('common.edit') }}
                </a>
                <a href="{{ route('admin.osis.pemilih.index') }}" class="btn btn-secondary">
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
                <!-- Pemilih Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">{{ __('common.voter_information') }}</h3>
                        <div class="flex items-center space-x-2">
                            @if ($pemilih->is_active)
                                <span class="badge badge-success">{{ __('common.status_active') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('common.status_inactive') }}</span>
                            @endif

                            @if ($pemilih->has_voted)
                                <span class="badge badge-success">{{ __('common.has_voted') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('common.not_voted') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.full_name_label') }}</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $pemilih->nama }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.nis_label') }}</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $pemilih->nis }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.class_label') }}</h4>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $pemilih->kelas }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.email_label') }}</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $pemilih->email ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.phone_number_label') }}</h4>
                            <p class="text-lg font-semibold text-slate-900">{{ $pemilih->nomor_hp ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.voting_time') }}</h4>
                            <p class="text-lg font-semibold text-slate-900">
                                @if ($pemilih->voted_at)
                                    {{ $pemilih->voted_at->format('d M Y, H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($pemilih->alamat)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">{{ __('common.address_label') }}</h4>
                            <p class="text-slate-900">{{ $pemilih->alamat }}</p>
                        </div>
                    @endif
                </div>

                <!-- Voting History -->
                @if ($pemilih->has_voted && $pemilih->voting)
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.voting_history') }}</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-green-900">{{ __('common.voting_successful') }}</p>
                                    <p class="text-sm text-green-700">{{ __('common.voted_on') }}
                                        {{ $pemilih->voted_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.quick_actions') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.osis.pemilih.edit', $pemilih) }}"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">{{ __('common.edit_voter') }}</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.osis.pemilih.index') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">{{ __('common.voter_list') }}</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.status_information') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.account_status') }}</span>
                            <span class="badge {{ $pemilih->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $pemilih->is_active ? __('common.status_active') : __('common.status_inactive') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.voting_status') }}</span>
                            <span class="badge {{ $pemilih->has_voted ? 'badge-success' : 'badge-warning' }}">
                                {{ $pemilih->has_voted ? __('common.has_voted') : __('common.not_voted') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ __('common.registered') }}</span>
                            <span class="text-sm text-slate-900">{{ $pemilih->created_at->format('d M Y') }}</span>
                        </div>

                        @if ($pemilih->voted_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">{{ __('common.last_voted') }}</span>
                                <span class="text-sm text-slate-900">{{ $pemilih->voted_at->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'pemilih_show_success_' + '{{ md5(session('success') . time()) }}';
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
                const errorKey = 'pemilih_show_error_' + '{{ md5(session('error') . time()) }}';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    @endif
</x-app-layout>
