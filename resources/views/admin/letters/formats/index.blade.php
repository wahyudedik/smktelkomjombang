<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Format Surat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Format Surat</h2>
                        <a href="{{ route('admin.letters.formats.create') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Format Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nama Format</th>
                                    <th class="px-4 py-3 font-semibold">Template</th>
                                    <th class="px-4 py-3 font-semibold">Reset Periode</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($formats as $format)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 text-slate-800 font-medium">{{ $format->name }}</td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <span class="font-mono text-sm bg-slate-100 rounded px-2 py-1 inline-block">
                                                {{ $format->format_template }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">{{ ucfirst($format->period_mode) }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $format->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $format->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="{{ route('admin.letters.formats.edit', ['format' => $format->id]) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('admin.letters.formats.destroy', ['format' => $format->id]) }}"
                                                method="POST" class="inline"
                                                data-confirm="Apakah Anda yakin ingin menghapus format surat '{{ $format->name }}'? Tindakan ini tidak dapat dibatalkan.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada format
                                            surat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
