<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.e_graduation_data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <a href="{{ route('admin.lulus.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('common.add_graduation_data') }}
                    </a>
                    <a href="{{ route('admin.lulus.import') }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                        {{ __('common.import_data') }}
                    </a>
                    <a href="{{ route('admin.lulus.export', request()->query()) }}"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded ml-2">
                        {{ __('common.export_data') }}
                    </a>
                </div>
                <div class="flex-1">
                    <a href="{{ route('admin.lulus.check') }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('common.check_graduation_status') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.lulus.index') }}" id="filterForm"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">{{ __('common.search_label') }}</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="{{ __('common.search_name_nisn_nis') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">{{ __('common.status') }}</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">{{ __('common.academic_year') }}</label>
                            <select name="tahun_ajaran" id="tahun_ajaran"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value="">{{ __('common.all_academic_years') }}</option>
                                @foreach ($tahunAjaran as $tahun)
                                    <option value="{{ $tahun }}"
                                        {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-gray-700">{{ __('common.major') }}</label>
                            <select name="jurusan" id="jurusan"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value="">{{ __('common.all_majors') }}</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j }}"
                                        {{ request('jurusan') == $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <button type="submit"
                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.lulus.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                {{ __('common.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($kelulusans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.photo') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.name') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NISN</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.major') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.academic_year') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.status') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.activity') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($kelulusans as $kelulusan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($kelulusan->foto)
                                                    <img src="{{ $kelulusan->photo_url }}"
                                                        alt="{{ $kelulusan->nama }}"
                                                        class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span
                                                            class="text-gray-600 text-sm font-medium">{{ substr($kelulusan->nama, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $kelulusan->nama }}
                                                </div>
                                                @if ($kelulusan->nis)
                                                    <div class="text-sm text-gray-500">NIS: {{ $kelulusan->nis }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $kelulusan->nisn }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $kelulusan->major_display }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $kelulusan->graduation_year_display }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if ($kelulusan->status_badge_color == 'green') bg-green-100 text-green-800
                                                    @elseif($kelulusan->status_badge_color == 'red') bg-red-100 text-red-800
                                                    @elseif($kelulusan->status_badge_color == 'yellow') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $kelulusan->status_display }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $kelulusan->current_activity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.lulus.show', $kelulusan) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">{{ __('common.view') }}</a>
                                                    <a href="{{ route('admin.lulus.edit', $kelulusan) }}"
                                                        class="text-yellow-600 hover:text-yellow-900">{{ __('common.edit') }}</a>
                                                    <form method="POST"
                                                        action="{{ route('admin.lulus.destroy', $kelulusan) }}"
                                                        class="inline"
                                                        data-confirm="{{ __('common.delete_data_confirmation') }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $kelulusans->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">{{ __('common.no_graduation_data_found') }}</div>
                            <a href="{{ route('admin.lulus.create') }}"
                                class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.add_first_data') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
