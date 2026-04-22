<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Data Kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Import Data Kelulusan</h3>
                        <p class="text-gray-600">Upload file Excel untuk mengimpor data kelulusan secara massal</p>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h4 class="text-lg font-semibold text-blue-900 mb-4">Panduan Import Data</h4>
                        <div class="space-y-3 text-blue-800">
                            <div>
                                <h5 class="font-medium">Format File:</h5>
                                <p>File harus dalam format Excel (.xlsx, .xls) atau CSV (.csv)</p>
                            </div>
                            <div>
                                <h5 class="font-medium">Kolom yang Diperlukan:</h5>
                                <ul class="list-disc list-inside ml-4 space-y-1">
                                    <li><strong>nama</strong> - Nama lengkap siswa (wajib)</li>
                                    <li><strong>nisn</strong> - Nomor Induk Siswa Nasional (wajib)</li>
                                    <li><strong>nis</strong> - Nomor Induk Siswa (opsional)</li>
                                    <li><strong>jurusan</strong> - Jurusan siswa (opsional)</li>
                                    <li><strong>tahun_ajaran</strong> - Tahun ajaran (wajib)</li>
                                    <li><strong>status</strong> - Status kelulusan: lulus, tidak_lulus, mengulang
                                        (wajib)</li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium">Kolom Opsional:</h5>
                                <ul class="list-disc list-inside ml-4 space-y-1">
                                    <li>tempat_kuliah, jurusan_kuliah</li>
                                    <li>tempat_kerja, jabatan_kerja</li>
                                    <li>no_hp, no_wa, alamat</li>
                                    <li>prestasi, catatan</li>
                                    <li>tanggal_lulus (format: YYYY-MM-DD)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                        <h4 class="text-lg font-semibold text-green-900 mb-4">Template File</h4>
                        <p class="text-green-800 mb-4">Download template file Excel untuk memudahkan pengisian data:</p>
                        <a href="{{ route('admin.lulus.downloadTemplate') }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                            Download Template Excel
                        </a>
                    </div>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('admin.lulus.processImport') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="file" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Pilih file untuk diupload
                                    </span>
                                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv"
                                        required
                                        class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </label>
                                <p class="mt-1 text-xs text-gray-500">
                                    Excel (.xlsx, .xls) atau CSV (.csv) maksimal 2MB
                                </p>
                            </div>
                        </div>

                        @error('file')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Import Options -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Opsi Import</h4>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="skip_duplicates" id="skip_duplicates" value="1"
                                        checked
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="skip_duplicates" class="ml-2 block text-sm text-gray-900">
                                        Lewati data duplikat (berdasarkan NISN)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="update_existing" id="update_existing" value="1"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="update_existing" class="ml-2 block text-sm text-gray-900">
                                        Update data yang sudah ada
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.lulus.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Import Data
                            </button>
                        </div>
                    </form>

                    <!-- Sample Data -->
                    <div class="mt-8 bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Contoh Data</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            nisn</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            nis</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            jurusan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            tahun_ajaran</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ahmad Rizki</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1234567890</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024001</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IPA</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">lulus</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Siti Nurhaliza
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0987654321</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024002</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IPS</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">lulus</td>
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
