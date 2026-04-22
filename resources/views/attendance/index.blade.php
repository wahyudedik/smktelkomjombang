<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Absensi</h1>
                <p class="text-slate-600 mt-1">Rekap harian dan log scan dari perangkat</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.logs') }}" class="btn btn-secondary">Logs</a>
                <a href="{{ route('admin.absensi.devices.index') }}" class="btn btn-secondary">Devices</a>
                <a href="{{ route('admin.absensi.mapping.index') }}" class="btn btn-secondary">Mapping</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" class="flex flex-col md:flex-row md:items-end gap-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="mt-1 block w-56 rounded-md border-slate-300">
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                    <a class="btn btn-secondary" href="{{ route('admin.absensi.index') }}">Hari ini</a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Device Terdaftar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $devices->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Rekap ({{ $date }})</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $attendances->total() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Log Terbaru</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $latestLogs->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Rekap Harian</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Pulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($attendances as $attendance)
                            @php
                                $identity = $attendance->identity;
                                $nama =
                                    $identity?->guru?->nama_lengkap ??
                                    ($identity?->siswa?->nama_lengkap ?? ($identity?->user?->name ?? '-'));
                                $jenis = $identity?->kind ?? '-';
                                $pin = $identity?->device_pin ?? '-';
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $jenis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $pin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $attendance->first_in_at?->format('H:i:s') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $attendance->last_out_at?->format('H:i:s') ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $attendance->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada data
                                    rekap</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $attendances->links() }}
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Log Terbaru</h2>
                <a href="{{ route('admin.absensi.logs') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Device</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Mode</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($latestLogs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->log_time?->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->device?->serial_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $log->device_pin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->verify_mode ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada log
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
