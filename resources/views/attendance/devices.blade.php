<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Devices Absensi</h1>
                <p class="text-slate-600 mt-1">Perangkat yang terdeteksi dari endpoint iClock</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Rekap</a>
                <a href="{{ route('admin.absensi.logs') }}" class="btn btn-secondary">Logs</a>
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

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Serial</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Port</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aktif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Last Seen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($devices as $device)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $device->serial_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <input type="text" name="name" value="{{ $device->name }}"
                                        form="device-{{ $device->id }}" class="w-48 rounded-md border-slate-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <input type="text" name="ip_address" value="{{ $device->ip_address }}"
                                        form="device-{{ $device->id }}" class="w-40 rounded-md border-slate-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <input type="number" name="port" value="{{ $device->port }}" min="1"
                                        form="device-{{ $device->id }}" class="w-24 rounded-md border-slate-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <select name="is_active" form="device-{{ $device->id }}"
                                        class="rounded-md border-slate-300">
                                        <option value="1" @selected($device->is_active)>Ya</option>
                                        <option value="0" @selected(!$device->is_active)>Tidak</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $device->last_seen_at?->format('Y-m-d H:i:s') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <form id="device-{{ $device->id }}" method="POST"
                                        action="{{ route('admin.absensi.devices.update', $device) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada device
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $devices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
