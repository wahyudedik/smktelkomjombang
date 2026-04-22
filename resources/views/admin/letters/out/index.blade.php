<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Surat Keluar</h2>
                        <a href="{{ route('admin.letters.out.create') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Surat Keluar
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nomor Surat</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold">Kepada</th>
                                    <th class="px-4 py-3 font-semibold">Perihal</th>
                                    <th class="px-4 py-3 font-semibold">Dibuat Oleh</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($letters as $letter)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800">
                                            {{ $letter->letter_number }}
                                            @if (!$letter->file_path)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">{{ $letter->letter_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">{{ $letter->recipient }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ Str::limit($letter->subject, 30) }}</td>
                                        <td class="px-4 py-3 text-slate-600 text-sm">{{ $letter->creator->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            @if (!$letter->file_path)
                                                <a href="{{ route('admin.letters.out.upload', $letter->id) }}"
                                                    class="text-orange-600 hover:text-orange-800" title="Upload Scan">
                                                    <i class="fas fa-upload"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.letters.out.show', $letter->id) }}"
                                                class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada surat
                                            keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $letters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
