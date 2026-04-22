<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.lesson_schedule') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.jadwal-pelajaran.calendar') }}"
                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('common.calendar_view') }}
                </a>
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
                            <a href="{{ route('admin.jadwal-pelajaran.export', request()->all()) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-excel mr-2 text-green-600"></i>Excel (.xlsx)
                            </a>
                            <a href="{{ route('admin.jadwal-pelajaran.export.pdf', request()->all()) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-pdf mr-2 text-red-600"></i>PDF (.pdf)
                            </a>
                            <a href="{{ route('admin.jadwal-pelajaran.export.json', request()->all()) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                <i class="fas fa-code mr-2 text-blue-600"></i>JSON (.json)
                            </a>
                            <a href="{{ route('admin.jadwal-pelajaran.export.xml', request()->all()) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-code mr-2 text-purple-600"></i>XML (.xml)
                            </a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.jadwal-pelajaran.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.add_schedule') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('admin.jadwal-pelajaran.index') }}"
                            class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.class_label') }}</label>
                                <select name="kelas_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('common.all_classes') }}</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.teacher') }}</label>
                                <select name="guru_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('common.all_teachers') }}</option>
                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.day') }}</label>
                                <select name="hari"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('common.all_days') }}</option>
                                    @foreach ($hariList as $hari)
                                        <option value="{{ $hari }}"
                                            {{ request('hari') == $hari ? 'selected' : '' }}>
                                            {{ $hari }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.academic_year') }}</label>
                                <select name="tahun_ajaran"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('common.all_academic_years') }}</option>
                                    @foreach ($tahunAjaranList as $tahun)
                                        <option value="{{ $tahun }}"
                                            {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.semester') }}</label>
                                <select name="semester"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">{{ __('common.all_semesters') }}</option>
                                    @foreach ($semesterList as $semester)
                                        <option value="{{ $semester }}"
                                            {{ request('semester') == $semester ? 'selected' : '' }}>
                                            {{ $semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('common.filter') }}
                                </button>
                                <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                                    class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center">
                                    {{ __('common.reset') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Stats -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">{{ __('common.total_schedule') }}</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $jadwals->total() }}</div>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">{{ __('common.active_schedule') }}</div>
                            <div class="text-2xl font-bold text-green-700">
                                {{ \App\Models\JadwalPelajaran::where('status', 'aktif')->count() }}
                            </div>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">{{ __('common.registered_class') }}</div>
                            <div class="text-2xl font-bold text-purple-700">{{ $kelasList->count() }}</div>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">{{ __('common.teaching_teacher') }}</div>
                            <div class="text-2xl font-bold text-orange-700">{{ $guruList->count() }}</div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.day_time') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.subject') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.teacher') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.class_label') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.room') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.status') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('common.action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($jadwals as $jadwal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $jadwal->hari_badge_color }}-100 text-{{ $jadwal->hari_badge_color }}-800">
                                                        {{ $jadwal->hari }}
                                                    </span>
                                                    <div class="text-sm text-gray-900 mt-1">{{ $jadwal->time_range }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $jadwal->duration }} {{ __('common.minutes') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $jadwal->mataPelajaran->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $jadwal->tahun_ajaran }} -
                                                {{ $jadwal->semester }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $jadwal->guru->full_name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $jadwal->guru->nip ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $jadwal->kelas->nama ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $jadwal->ruang ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $jadwal->status_badge_color }}-100 text-{{ $jadwal->status_badge_color }}-800">
                                                {{ $jadwal->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.jadwal-pelajaran.show', $jadwal) }}"
                                                class="text-blue-600 hover:text-blue-900">{{ __('common.detail') }}</a>
                                            <a href="{{ route('admin.jadwal-pelajaran.edit', $jadwal) }}"
                                                class="text-indigo-600 hover:text-indigo-900">{{ __('common.edit') }}</a>
                                            <form action="{{ route('admin.jadwal-pelajaran.destroy', $jadwal) }}"
                                                method="POST" class="inline"
                                                data-confirm="{{ __('common.delete_schedule_confirmation') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    {{ __('common.delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('common.no_schedule_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $jadwals->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
