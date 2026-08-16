<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Report Keterlambatan</h1>
                <p class="text-slate-600 mt-1">{{ $start->format('d F Y') }} — {{ $end->format('d F Y') }} | Batas: {{ $stats['threshold_time'] }}</p>
            </div>
            <a href="{{ route('admin.absensi.report.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Keterlambatan</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['total_latecomers'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Batas Jam</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['threshold_time'] }} WIB</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Periode</p>
                <p class="text-lg font-bold text-slate-900 mt-1">{{ $start->diffInDays($end) + 1 }} hari</p>
            </div>
        </div>

        <!-- Tabel Keterlambatan -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($attendances as $index => $attendance)
                            @php
                                $identity = $attendance->identity;
                                $nama = $identity->user?->name ?? ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                                $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                                $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                                $threshold = \Carbon\Carbon::parse($stats['threshold_time']);
                                $lateMinutes = $attendance->first_in_at ? $threshold->diffInMinutes($attendance->first_in_at->copy()->setDate(2000, 1, 1)) : 0;
                                $lateMinutes = max(0, $lateMinutes);
                                $lateHours = intdiv($lateMinutes, 60);
                                $lateMins = $lateMinutes % 60;
                                $lateStr = $lateHours > 0 ? "{$lateHours}j {$lateMins}m" : "{$lateMins}m";
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $attendance->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $identity->kind }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $identity->device_pin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ $firstIn }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $lastOut }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                        +{{ $lateStr }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-600">
                                    Tidak ada data keterlambatan untuk periode ini 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
