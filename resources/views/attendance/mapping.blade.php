<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mapping PIN</h1>
                <p class="text-slate-600 mt-1">Pemetaan PIN perangkat ke data user/guru/siswa</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Rekap</a>
                <a href="{{ route('admin.absensi.logs') }}" class="btn btn-secondary">Logs</a>
                <a href="{{ route('admin.absensi.devices.index') }}" class="btn btn-secondary">Devices</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="POST" action="{{ route('admin.absensi.mapping.store') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kind</label>
                    <select name="kind" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="user" @selected(old('kind') === 'user')>user</option>
                        <option value="guru" @selected(old('kind') === 'guru')>guru</option>
                        <option value="siswa" @selected(old('kind') === 'siswa')>siswa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">user_id</label>
                    <input type="number" name="user_id" value="{{ old('user_id') }}"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">guru_id</label>
                    <input type="number" name="guru_id" value="{{ old('guru_id') }}"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">siswa_id</label>
                    <input type="number" name="siswa_id" value="{{ old('siswa_id') }}"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN</label>
                    <input type="text" name="device_pin" value="{{ old('device_pin') }}"
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>
                <div class="md:col-span-5 flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Kind</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($mappings as $m)
                            @php
                                $nama =
                                    $m->guru?->nama_lengkap ?? ($m->siswa?->nama_lengkap ?? ($m->user?->name ?? '-'));
                                $ref = $m->user_id
                                    ? "user:{$m->user_id}"
                                    : ($m->guru_id
                                        ? "guru:{$m->guru_id}"
                                        : ($m->siswa_id
                                            ? "siswa:{$m->siswa_id}"
                                            : '-'));
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $m->kind }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">
                                    {{ $nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $ref }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $m->device_pin }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $m->is_active ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada
                                    mapping</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $mappings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
