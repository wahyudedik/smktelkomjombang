<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Absensi — {{ $nama }}</h1>
                <p class="text-slate-600 mt-1">{{ $start->format('d F Y') }} — {{ $end->format('d F Y') }} | PIN: {{ $identity->device_pin }}</p>
            </div>
            <a href="{{ route('admin.absensi.report.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Info Pengguna -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center">
                        <span class="text-2xl font-bold text-slate-500">{{ substr($nama, 0, 1) }}</span>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $nama }}</h2>
                    <p class="text-sm text-slate-600">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">{{ $identity->kind }}</span>
                        PIN: {{ $identity->device_pin }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_days'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Hadir</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['hadir'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Tidak Hadir</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['tidak_hadir'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Persentase</p>
                <p class="text-2xl font-bold mt-1 {{ $stats['persentase'] >= 80 ? 'text-green-600' : ($stats['persentase'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $stats['persentase'] }}%
                </p>
            </div>
        </div>

        <!-- Tabel Detail Absensi -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-sm font-semibold text-slate-900">Riwayat Absensi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($attendances as $index => $attendance)
                            @php
                                $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                                $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                                $durasi = '-';
                                if ($attendance->first_in_at && $attendance->last_out_at) {
                                    $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                                    $hours = intdiv($diff, 60);
                                    $minutes = $diff % 60;
                                    $durasi = "{$hours}j {$minutes}m";
                                }

                                // Cek keterlambatan
                                $lateThreshold = \Carbon\Carbon::parse(config('attendance.late_threshold', '07:30'));
                                $isLate = false;
                                if ($attendance->first_in_at) {
                                    $checkTime = $attendance->first_in_at->copy()->setDate(2000, 1, 1);
                                    $isLate = $checkTime->gt($lateThreshold);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $attendance->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $attendance->date->translatedFormat('l') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $isLate ? 'text-red-600 font-medium' : 'text-slate-700' }}">
                                    {{ $firstIn }}
                                    @if ($isLate)
                                        <span class="text-xs text-red-500 ml-1">(terlambat)</span>
                                    @endif
                                </td>
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
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data absensi untuk periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $attendances->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
