<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    <i class="fas fa-file-upload mr-2 text-blue-600"></i>
                    Bulk Import Data
                </h1>
                <p class="text-slate-600 mt-1">Import data in bulk using Excel/CSV files</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Users Card -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Users</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_users']) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Siswa Card -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Siswa</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_siswa']) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Guru Card -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Guru</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_guru']) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Barang Card -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Barang</p>
                            <p class="text-2xl font-bold text-slate-900 mt-1">
                                {{ number_format($stats['total_barang']) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Modules -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Users Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Users</h3>
                                <p class="text-sm text-blue-100">Import user accounts</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="users">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'users') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Siswa Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Siswa</h3>
                                <p class="text-sm text-purple-100">Import student data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="siswa">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-purple-50 file:text-purple-700
                                    hover:file:bg-purple-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'siswa') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Guru Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Guru</h3>
                                <p class="text-sm text-green-100">Import teacher data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="guru">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-green-50 file:text-green-700
                                    hover:file:bg-green-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'guru') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Barang Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Barang</h3>
                                <p class="text-sm text-orange-100">Import asset data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="barang">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-orange-50 file:text-orange-700
                                    hover:file:bg-orange-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'barang') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Calon Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Calon OSIS</h3>
                                <p class="text-sm text-pink-100">Import candidate data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="calon">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-pink-50 file:text-pink-700
                                    hover:file:bg-pink-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'calon') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Pemilih Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-vote-yea text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Pemilih</h3>
                                <p class="text-sm text-indigo-100">Import voter data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="pemilih">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'pemilih') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kelulusan Import -->
                <div
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Kelulusan</h3>
                                <p class="text-sm text-teal-100">Import graduate data</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form class="import-form" data-module="kelulusan">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-file-excel mr-1 text-green-600"></i>
                                    Select Excel/CSV File
                                </label>
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-teal-50 file:text-teal-700
                                    hover:file:bg-teal-100">
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.superadmin.bulk-import.template', 'kelulusan') }}"
                                    class="flex-1 btn btn-secondary text-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Template
                                </a>
                                <button type="submit" class="flex-1 btn btn-primary">
                                    <i class="fas fa-upload mr-2"></i>
                                    Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Import Instructions
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Download the template for the module you want to import</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Fill in the data following the template format exactly</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Supported formats: Excel (.xlsx, .xls) or CSV (.csv)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Maximum file size: 5MB</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Make sure all required fields are filled</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-3 mt-0.5"></i>
                        <span>Check for duplicate data (NISN, NIP, email, etc.)</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const importForms = document.querySelectorAll('.import-form');

                importForms.forEach(form => {
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const module = this.dataset.module;
                        const fileInput = this.querySelector('input[type="file"]');
                        const submitBtn = this.querySelector('button[type="submit"]');

                        if (!fileInput.files.length) {
                            showAlert('Peringatan', 'Silakan pilih file untuk diimport', 'warning');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('module', module);
                        formData.append('file', fileInput.files[0]);
                        formData.append('_token', '{{ csrf_token() }}');

                        // Disable button and show loading
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';

                        try {
                            const response = await fetch(
                                '{{ route('admin.superadmin.bulk-import.process') }}', {
                                    method: 'POST',
                                    body: formData
                                });

                            const result = await response.json();

                            if (result.success) {
                                showSuccess('Import Berhasil!',
                                    `${result.message}<br><small class="text-gray-600">Imported: ${result.data.imported} records</small>`
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(result.message || 'Import failed');
                            }
                        } catch (error) {
                            showError('Import Gagal', error.message);
                        } finally {
                            // Re-enable button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            fileInput.value = '';
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
