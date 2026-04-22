<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.import_osis_voters') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.import_voters_description') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.osis.pemilih.downloadTemplate') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('common.download_template') }}
                </a>
                <a href="{{ route('admin.osis.pemilih.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">

            <!-- Instructions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('common.import_guide') }}</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <h4 class="font-medium mb-2">{{ __('common.langkah_import') }}</h4>
                            <ol class="list-decimal list-inside space-y-1">
                                <li>{{ __('common.download_template_first') }}</li>
                                <li>{{ __('common.fill_data_according_template') }}</li>
                                <li>{{ __('common.upload_filled_excel') }}</li>
                                <li>{{ __('common.sistem_akan_validasi') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Form -->
            <form method="POST" action="{{ route('admin.osis.pemilih.processImport') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <!-- File Upload -->
                <div>
                    <label for="file" class="form-label">{{ __('common.select_excel_file') }}</label>
                    <div id="dropzone"
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-slate-400 transition-colors cursor-pointer relative">
                        <div class="space-y-1 text-center">
                            <svg id="upload-icon" class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor"
                                fill="none" viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <span id="upload-text">{{ __('common.upload_file') }}</span>
                                <p class="pl-1">{{ __('common.atau_drag_drop') }}</p>
                            </div>
                            <p class="text-xs text-slate-500">{{ __('common.excel_format_info') }}</p>

                            <!-- File Preview -->
                            <div id="file-preview"
                                class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-green-800" id="file-name"></p>
                                        <p class="text-xs text-green-600" id="file-size"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div id="error-message" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm text-red-800" id="error-text"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="file" name="file" type="file" accept=".xlsx,.xls,.csv" required
                        class="sr-only @error('file') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('file')
                        <p class="form-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Format Info -->
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-slate-900 mb-2">Format File yang Diperlukan:</h4>
                    <div class="text-sm text-slate-600">
                        <p class="mb-2">File Excel harus memiliki kolom berikut:</p>
                        <ul class="list-disc list-inside space-y-1 ml-4">
                            <li><strong>nama</strong> - Nama lengkap pemilih (wajib)</li>
                            <li><strong>email</strong> - Email pemilih (wajib)</li>
                            <li><strong>user_type</strong> - guru atau siswa (opsional, default: siswa) - digunakan untuk tracking di tabel pemilihs</li>
                            <li><strong>jenis_kelamin</strong> - L atau P (opsional)</li>
                            <li><strong>kelas_jabatan</strong> - Kelas untuk siswa atau jabatan untuk guru (opsional)
                            </li>
                            <li><strong>status</strong> - active atau inactive (opsional, default: active)</li>
                        </ul>
                    </div>
                </div>

                <!-- Import Options -->
                <div class="border-t border-slate-200 pt-6">
                    <h4 class="text-sm font-medium text-slate-900 mb-3">{{ __('common.import_options') }}</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="skip_duplicates" name="skip_duplicates" value="1"
                                checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="skip_duplicates" class="ml-2 text-sm text-slate-700">
                                Skip data duplikat (berdasarkan email)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="validate_data" name="validate_data" value="1" checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="validate_data" class="ml-2 text-sm text-slate-700">
                                Validasi data sebelum import
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.osis.pemilih.index') }}" class="btn btn-secondary">
                        {{ __('common.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        {{ __('common.import_data') }}
                    </button>
                </div>
            </form>

            <!-- Success/Error Messages -->
            @if (session('success') || session('error'))
                <div class="mt-8">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-green-800">
                                    <p class="font-medium">{{ __('common.import_success') }}</p>
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-red-800">
                                    <p class="font-medium">{{ __('common.import_failed') }}</p>
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('file');
            const uploadIcon = document.getElementById('upload-icon');
            const uploadText = document.getElementById('upload-text');
            const filePreview = document.getElementById('file-preview');
            const errorMessage = document.getElementById('error-message');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const errorText = document.getElementById('error-text');

            // Click to upload
            dropzone.addEventListener('click', function() {
                fileInput.click();
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFile(file);
                }
            });

            // Drag and drop functionality
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropzone.classList.add('border-blue-400', 'bg-blue-50');
                dropzone.classList.remove('border-slate-300');
            });

            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropzone.classList.remove('border-blue-400', 'bg-blue-50');
                dropzone.classList.add('border-slate-300');
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropzone.classList.remove('border-blue-400', 'bg-blue-50');
                dropzone.classList.add('border-slate-300');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    fileInput.files = files;
                    handleFile(file);
                }
            });

            function showError(message) {
                errorText.textContent = message;
                errorMessage.classList.remove('hidden');
                filePreview.classList.add('hidden');

                // Reset dropzone style
                dropzone.classList.add('border-red-300', 'bg-red-50');
                dropzone.classList.remove('border-slate-300');

                setTimeout(() => {
                    errorMessage.classList.add('hidden');
                    dropzone.classList.remove('border-red-300', 'bg-red-50');
                    dropzone.classList.add('border-slate-300');
                }, 3000);
            }

            function handleFile(file) {
                console.log('File selected:', file.name, file.type, file.size); // Debug log

                // Hide previous messages
                errorMessage.classList.add('hidden');

                // Validate file type
                const allowedTypes = [
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                    'application/vnd.ms-excel', // .xls
                    'text/csv', // .csv
                    'application/csv', // .csv alternative
                    'text/plain' // .csv sometimes has this type
                ];

                const fileExtension = file.name.toLowerCase().split('.').pop();
                const allowedExtensions = ['xlsx', 'xls', 'csv'];

                if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                    showError('File harus berupa Excel (.xlsx, .xls) atau CSV');
                    fileInput.value = '';
                    return;
                }

                // Validate file size (2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    showError('File terlalu besar. Maksimal 2MB');
                    fileInput.value = '';
                    return;
                }

                // Show success preview
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.classList.remove('hidden');

                // Update dropzone style
                dropzone.classList.add('border-green-300', 'bg-green-50');
                dropzone.classList.remove('border-slate-300');

                // Update icon
                uploadIcon.innerHTML = `
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                `;
                uploadIcon.classList.add('text-green-500');
                uploadIcon.classList.remove('text-slate-400');
                uploadText.textContent = 'File siap diupload';
                uploadText.classList.add('text-green-600');
                uploadText.classList.remove('text-slate-600');

                console.log('File validation passed:', file.name); // Debug log
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
</x-app-layout>
