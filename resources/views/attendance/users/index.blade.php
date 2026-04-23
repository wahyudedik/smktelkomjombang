<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manage User Absensi</h1>
                <p class="text-slate-600 mt-1">Tambah, edit, hapus user dan sinkronisasi ke device</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Rekap</a>
                <a href="{{ route('admin.absensi.logs') }}" class="btn btn-secondary">Logs</a>
                <a href="{{ route('admin.absensi.devices.index') }}" class="btn btn-secondary">Devices</a>
                <a href="{{ route('admin.absensi.mapping.index') }}" class="btn btn-secondary">Mapping</a>
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

        <!-- Form Tambah User -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Tambah User Baru</h2>
            <form method="POST" action="{{ route('admin.absensi.users.store') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <select name="kind" id="kind" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="user">User</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih User --</option>
                        @foreach ($availableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: none;">
                    <label class="block text-sm font-medium text-slate-700">Guru</label>
                    <select name="guru_id" id="guru_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($availableGurus as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: none;">
                    <label class="block text-sm font-medium text-slate-700">Siswa</label>
                    <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($availableSiswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN Device</label>
                    <input type="text" name="device_pin" placeholder="Contoh: 1001" 
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>

        <!-- Tombol Sync All -->
        <div class="mb-6">
            <form method="POST" action="{{ route('admin.absensi.users.sync-all') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Sync Semua User ke Device</button>
            </form>
        </div>

        <!-- Daftar User -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($users as $u)
                            @php
                                $nama = $u->user?->name ?? ($u->guru?->nama_lengkap ?? ($u->siswa?->nama_lengkap ?? '-'));
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $u->kind }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $u->device_pin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($u->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.absensi.users.edit', $u) }}" class="btn btn-sm btn-secondary">Edit</a>
                                    <a href="{{ route('admin.absensi.users.sync-status', $u) }}" class="btn btn-sm btn-secondary">Status</a>
                                    <form method="POST" action="{{ route('admin.absensi.users.destroy', $u) }}" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('kind').addEventListener('change', function() {
            const kind = this.value;
            document.getElementById('user_id').parentElement.style.display = kind === 'user' ? 'block' : 'none';
            document.getElementById('guru_id').parentElement.style.display = kind === 'guru' ? 'block' : 'none';
            document.getElementById('siswa_id').parentElement.style.display = kind === 'siswa' ? 'block' : 'none';
        });
        // Trigger on load
        document.getElementById('kind').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
