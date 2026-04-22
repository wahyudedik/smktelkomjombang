<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Import Data Guru') }}
            </h2>
            <a href="{{ route('admin.guru.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Upload Form -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Upload File Excel</h3>
                            <form action="{{ route('admin.guru.processImport') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="file" class="block text-sm font-medium text-gray-700">Pilih File
                                            Excel</label>
                                        <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('file') border-red-300 @enderror"
                                            required>
                                        @error('file')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            Import Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Instructions -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Petunjuk Import</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">Sebelum mengimport:</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Download template terlebih dahulu</li>
                                    <li>• Isi data sesuai format template</li>
                                    <li>• Pastikan NIP unik untuk setiap guru</li>
                                    <li>• Jenis kelamin: L (Laki-laki) atau P (Perempuan)</li>
                                    <li>• Mata pelajaran dipisahkan dengan koma</li>
                                    <li>• Format tanggal: YYYY-MM-DD</li>
                                </ul>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.guru.downloadTemplate') }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download Template
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Data -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Format Template</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NIP</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Lengkap</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Kelamin</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status Kepegawaian</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mata Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">196501011990031001
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Dr. Ahmad Rizki,
                                            M.Pd</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">L</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PNS</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Matematika, Fisika
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
