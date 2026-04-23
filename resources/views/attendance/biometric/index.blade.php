<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Enroll Biometric</h1>
                <p class="text-slate-600 mt-1">Enroll fingerprint, face, atau RFID dari web</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Rekap</a>
                <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary">Users</a>
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

        <!-- Device Status -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Status Device</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($devices as $device)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <p class="font-medium text-slate-900">{{ $device->name }}</p>
                        <p class="text-sm text-slate-600">{{ $device->serial_number }}</p>
                        <p class="text-sm text-slate-600">{{ $device->ip_address }}:{{ $device->port ?? 4370 }}</p>
                        <button type="button" class="btn btn-sm btn-secondary mt-2 test-connection" data-device-id="{{ $device->id }}">
                            Test Connection
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- User List -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Daftar User untuk Enroll</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.absensi.biometric.fingerprint.form', $u) }}" class="btn btn-sm btn-secondary">Fingerprint</a>
                                    <a href="{{ route('admin.absensi.biometric.face.form', $u) }}" class="btn btn-sm btn-secondary">Face</a>
                                    <a href="{{ route('admin.absensi.biometric.rfid.form', $u) }}" class="btn btn-sm btn-secondary">RFID</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.test-connection').forEach(btn => {
            btn.addEventListener('click', async function() {
                const deviceId = this.dataset.deviceId;
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'Testing...';

                try {
                    const response = await fetch('{{ route("admin.absensi.biometric.test-connection") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ device_id: deviceId }),
                    });

                    const data = await response.json();
                    if (data.success) {
                        alert('✓ ' + data.message);
                    } else {
                        alert('✗ ' + data.message);
                    }
                } catch (error) {
                    alert('Error: ' + error.message);
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Test Connection';
                }
            });
        });
    </script>
</x-app-layout>
