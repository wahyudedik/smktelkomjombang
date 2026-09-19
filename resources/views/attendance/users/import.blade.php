<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Import Identity Absensi</h1>
                <p class="text-slate-600 mt-1">Bulk import PIN mapping dari file Excel/CSV</p>
            </div>
            <a href="{{ route('admin.absensi.users.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upload Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Upload File Excel</h3>
                    <form action="{{ route('admin.absensi.users.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700">Pilih File Excel/CSV</label>
                                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('file') border-red-300 @enderror"
                                    required>
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Import Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Petunjuk Import</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Format file Excel/CSV:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• <strong>device_pin</strong> — PIN yang akan didaftarkan di device (wajib, unik)</li>
                            <li>• <strong>kind</strong> — Jenis pengguna: <code>user</code>, <code>guru</code>, atau <code>siswa</code></li>
                            <li>• <strong>reference_name</strong> — Nama lengkap untuk lookup (harus sesuai data di database)</li>
                        </ul>
                    </div>

                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-900 mb-2">Perhatian:</h4>
                        <ul class="text-sm text-yellow-800 space-y-1">
                            <li>• PIN yang sudah ada akan dilewati (skip)</li>
                            <li>• Nama referensi harus sesuai exact dengan data di database</li>
                            <li>• Jika nama tidak ditemukan, baris tersebut akan dilewati</li>
                            <li>• Semua identity yang diimport akan aktif secara default</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Format -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Contoh Format</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">device_pin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">kind</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">reference_name</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">user</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin Utama</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">guru</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Budi Santoso, S.Pd</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">siswa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ahmad Rizki</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
