<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.teacher_data') }}
            </h2>
            <div class="flex items-center space-x-2">
                @can('import', App\Models\Guru::class)
                    <a href="{{ route('admin.guru.import') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        {{ __('common.import') }}
                    </a>
                @endcan
                @can('export', App\Models\Guru::class)
                    <div class="relative inline-block" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('common.export') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('admin.guru.export') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-excel mr-2 text-green-600"></i>Excel (.xlsx)
                                </a>
                                <a href="{{ route('admin.guru.export.pdf') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-pdf mr-2 text-red-600"></i>PDF (.pdf)
                                </a>
                                <a href="{{ route('admin.guru.export.json') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                    <i class="fas fa-code mr-2 text-blue-600"></i>JSON (.json)
                                </a>
                                <a href="{{ route('admin.guru.export.xml') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-code mr-2 text-purple-600"></i>XML (.xml)
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('create', App\Models\Guru::class)
                    <a href="{{ route('admin.guru.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('common.add_teacher') }}
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.guru.index') }}" id="filterForm"
                            class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.search') }}</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="{{ __('common.search_name_or_nip') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.status') }}</label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">{{ __('common.all_status') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.employment_status') }}</label>
                                <select name="employment_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">{{ __('common.all_status') }}</option>
                                    @foreach ($employmentStatuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request('employment_status') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.subject') }}</label>
                                <select name="subject"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">{{ __('common.all_subjects') }}</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject }}"
                                            {{ request('subject') == $subject ? 'selected' : '' }}>
                                            {{ $subject }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('common.filter') }}
                                </button>
                                <a href="{{ route('admin.guru.index') }}"
                                    class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center">
                                    {{ __('common.reset') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Gurus Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_lengkap', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="hover:text-gray-700">
                                            {{ __('common.name') }}
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.nip') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.status') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.employment_status') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.subject') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($gurus as $guru)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if ($guru->foto)
                                                    <img class="h-10 w-10 rounded-full object-cover mr-3"
                                                        src="{{ $guru->photo_url }}"
                                                        alt="{{ $guru->nama_lengkap }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                        <span
                                                            class="text-gray-600 font-medium">{{ substr($guru->nama_lengkap, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $guru->full_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $guru->jabatan ?? 'Guru' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guru->nip }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($guru->status_badge_color === 'green') bg-green-100 text-green-800
                                                @elseif($guru->status_badge_color === 'red') bg-red-100 text-red-800
                                                @elseif($guru->status_badge_color === 'blue') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $guru->status_aktif)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($guru->employment_badge_color === 'green') bg-green-100 text-green-800
                                                @elseif($guru->employment_badge_color === 'blue') bg-blue-100 text-blue-800
                                                @elseif($guru->employment_badge_color === 'yellow') bg-yellow-100 text-yellow-800
                                                @elseif($guru->employment_badge_color === 'orange') bg-orange-100 text-orange-800
                                                @elseif($guru->employment_badge_color === 'red') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $guru->status_kepegawaian }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $guru->subjects_string }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @can('view', $guru)
                                                    <a href="{{ route('admin.guru.show', $guru) }}"
                                                        class="text-blue-600 hover:text-blue-900">{{ __('common.view') }}</a>
                                                @endcan
                                                @can('update', $guru)
                                                    <a href="{{ route('admin.guru.edit', $guru) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">{{ __('common.edit') }}</a>
                                                @endcan
                                                @can('delete', $guru)
                                                    <form method="POST"
                                                        action="{{ route('admin.guru.destroy', $guru) }}" class="inline"
                                                        data-confirm="{{ str_replace(':name', $guru->full_name, __('common.delete_teacher_confirmation')) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('common.no_teacher_data_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $gurus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            // Generate unique key for this success message
            const successKey = 'guru_alert_' + '{{ md5(session('success') . time()) }}';

            document.addEventListener('DOMContentLoaded', function() {
                // Check if this alert has already been shown
                if (!sessionStorage.getItem(successKey)) {
                    showSuccess('{{ session('success') }}');
                    // Mark this alert as shown
                    sessionStorage.setItem(successKey, 'shown');

                    // Clean up old alerts (keep only last 5)
                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            const errorKey = 'guru_alert_error_' + '{{ md5(session('error') . time()) }}';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(errorKey)) {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_error_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            const validationKey = 'guru_alert_validation_' + '{{ md5(json_encode($errors->all()) . time()) }}';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(validationKey)) {
                    showError('{!! implode('<br>', $errors->all()) !!}');
                    sessionStorage.setItem(validationKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_validation_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    @endif
</x-app-layout>
