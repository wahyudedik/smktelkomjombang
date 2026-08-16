<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Report Bulanan</h1>
                <p class="text-slate-600 mt-1">{{ $month->translatedFormat('F Y') }}</p>
            </div>
            <a href="{{ route('admin.absensi.report.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Pengguna</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Rata-rata Kehadiran</p>
                <p class="text-2xl font-bold mt-1 {{ $stats['avg_attendance'] >= 80 ? 'text-green-600' : ($stats['avg_attendance'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $stats['avg_attendance'] }}%
                </p>
            </div>
        </div>

        <!-- Tabel Rekap -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Total Hari</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Tidak Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($report as $index => $item)
                            @php
                                $persentase = $item['persentase'];
                                $badgeColor = $persentase >= 80 ? 'green' : ($persentase >= 60 ? 'yellow' : 'red');
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $item['kind'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $item['nama'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $item['pin'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-center">{{ $item['total_days'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-center font-medium">{{ $item['hadir'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-center font-medium">{{ $item['tidak_hadir'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800 rounded-full text-xs font-medium">
                                        {{ $persentase }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.absensi.report.user-detail', $item['identity']) }}?start_date={{ $month->startOfMonth()->toDateString() }}&end_date={{ $month->endOfMonth()->toDateString() }}"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
