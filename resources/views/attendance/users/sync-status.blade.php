<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Status Sync User</h1>
                <p class="text-slate-600 mt-1">PIN: {{ $identity->device_pin }}</p>
            </div>
            <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Total Command</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $status['total_commands'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $status['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Sent</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $status['sent'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Done</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $status['done'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Failed</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $status['failed'] }}</p>
            </div>
        </div>

        <!-- Daftar Command -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Riwayat Command</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Command</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Sent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Executed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Result</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($status['commands'] as $cmd)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $cmd['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $cmd['kind'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700 font-mono">{{ $cmd['command'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($cmd['status'] === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                    @elseif ($cmd['status'] === 'sent')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Sent</span>
                                    @elseif ($cmd['status'] === 'done')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Done</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Failed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $cmd['sent_at'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $cmd['executed_at'] ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $cmd['result_code'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada command</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</x-app-layout>
