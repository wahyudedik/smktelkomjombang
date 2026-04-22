<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Surat Masuk</h2>
                        <a href="{{ route('admin.letters.in.create') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Catat Surat Masuk
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nomor Surat</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal Terima</th>
                                    <th class="px-4 py-3 font-semibold">Pengirim</th>
                                    <th class="px-4 py-3 font-semibold">Perihal</th>
                                    <th class="px-4 py-3 font-semibold">File</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($letters as $letter)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $letter->reference_number }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">{{ $letter->letter_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">{{ $letter->sender }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ Str::limit($letter->subject, 30) }}</td>
                                        <td class="px-4 py-3">
                                            @if ($letter->file_path)
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank"
                                                    class="text-blue-600 hover:underline text-sm">
                                                    <i class="fas fa-paperclip mr-1"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="{{ route('admin.letters.in.show', $letter->id) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada surat
                                            masuk.</td>
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
