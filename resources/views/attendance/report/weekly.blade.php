<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Report Mingguan</h1>
                <p class="text-slate-600 mt-1">{{ $start->format('d F Y') }} — {{ $end->format('d F Y') }}</p>
            </div>
            <a href="{{ route('admin.absensi.report.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_days'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Record</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['total_records'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Rata-rata/Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['avg_per_day'] }}</p>
            </div>
        </div>

        <!-- Data per Hari -->
        @forelse ($attendances as $dateKey => $dailyRecords)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-900">
                        {{ \Carbon\Carbon::parse($dateKey)->translatedFormat('l, d F Y') }}
                        <span class="ml-2 text-xs font-normal text-slate-500">({{ $dailyRecords->count() }} record)</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach ($dailyRecords as $attendance)
                                @php
                                    $identity = $attendance->identity;
                                    $nama = $identity->user?->name ?? ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                                    $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                                    $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                                    $durasi = '-';
                                    if ($attendance->first_in_at && $attendance->last_out_at) {
                                        $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                                        $hours = intdiv($diff, 60);
                                        $minutes = $diff % 60;
                                        $durasi = "{$hours}j {$minutes}m";
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $identity->kind }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $identity->device_pin }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $firstIn }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $lastOut }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $durasi }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($attendance->status === 'present')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Hadir</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Tidak Hadir</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                <p class="text-sm text-slate-600">Tidak ada data absensi untuk periode ini</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
