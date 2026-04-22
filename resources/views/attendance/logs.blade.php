<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Logs Absensi</h1>
                <p class="text-slate-600 mt-1">Data scan mentah dari perangkat</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Rekap</a>
                <a href="{{ route('admin.absensi.devices.index') }}" class="btn btn-secondary">Devices</a>
                <a href="{{ route('admin.absensi.mapping.index') }}" class="btn btn-secondary">Mapping</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Device</label>
                    <select name="device" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">Semua</option>
                        @foreach ($devices as $d)
                            <option value="{{ $d->serial_number }}" @selected(request('device') === $d->serial_number)>{{ $d->serial_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN</label>
                    <input type="text" name="pin" value="{{ request('pin') }}"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div class="flex items-end gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-secondary" href="{{ route('admin.absensi.logs') }}">Reset</a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
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
                                Verify</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                In/Out</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->log_time?->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->device?->serial_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $log->device_pin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->verify_mode ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $log->in_out_mode ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada log
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
