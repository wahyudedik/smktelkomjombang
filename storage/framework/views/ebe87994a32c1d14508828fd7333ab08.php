<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Import Theme Settings</h1>
                                <p class="text-gray-600 mt-2">
                                    Import pengaturan dari file JSON ke tema
                                    <strong><?php echo e($themes[$theme]['name'] ?? $theme); ?></strong>
                                </p>
                            </div>
                            <a href="<?php echo e(route('admin.themes.index')); ?>"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <?php if(session('error')): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Info Banner -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Cara kerja:</strong> Upload file JSON yang telah di-export sebelumnya.
                                    File harus berisi format JSON yang valid dengan struktur settings yang sesuai.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Import Modes -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-bold text-yellow-800 mb-2">Mode Import:</h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>
                                <strong>Merge (Gabungkan):</strong> Settings baru ditambahkan, settings yang sudah ada
                                dengan key sama akan ditimpa.
                            </li>
                            <li>
                                <strong>Replace (Timpa Semua):</strong> SEMUA settings tema ini akan dihapus terlebih
                                dahulu, lalu diganti dengan data dari file import.
                            </li>
                        </ul>
                    </div>

                    <!-- Import Form -->
                    <form action="<?php echo e(route('admin.themes.import-process', $theme)); ?>" method="POST"
                        enctype="multipart/form-data" id="importForm">
                        <?php echo csrf_field(); ?>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File JSON Import</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition"
                                id="dropZone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="import_file"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Pilih file JSON</span>
                                            <input id="import_file" name="import_file" type="file"
                                                class="sr-only" accept=".json,application/json" required
                                                onchange="handleFileSelect(this)">
                                        </label>
                                        <p class="pl-1">atau drag & drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">JSON files, maks 5MB</p>
                                    <div id="fileInfo" class="text-sm text-green-600 font-medium hidden"></div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['import_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Mode Import</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                    <?php echo e(old('import_mode', 'merge') === 'merge' ? 'border-blue-400 bg-blue-50' : 'border-gray-200'); ?>">
                                    <input type="radio" name="import_mode" value="merge"
                                        <?php echo e(old('import_mode', 'merge') === 'merge' ? 'checked' : ''); ?>

                                        class="text-blue-600 focus:ring-blue-500"
                                        onchange="updateModeSelection(this)">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Merge (Gabungkan)</div>
                                        <div class="text-xs text-gray-500">Tambah settings baru, timpa yang sudah ada</div>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                    <?php echo e(old('import_mode') === 'replace' ? 'border-red-400 bg-red-50' : 'border-gray-200'); ?>">
                                    <input type="radio" name="import_mode" value="replace"
                                        <?php echo e(old('import_mode') === 'replace' ? 'checked' : ''); ?>

                                        class="text-red-600 focus:ring-red-500"
                                        onchange="updateModeSelection(this)">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Replace (Timpa Semua)</div>
                                        <div class="text-xs text-gray-500">Hapus semua settings lama, ganti dengan import</div>
                                    </div>
                                </label>
                            </div>
                            <?php $__errorArgs = ['import_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="<?php echo e(route('admin.themes.index')); ?>"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 transition">
                                Batal
                            </a>
                            <button type="submit" id="importBtn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition disabled:opacity-50"
                                disabled>
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function handleFileSelect(input) {
            const fileInfo = document.getElementById('fileInfo');
            const importBtn = document.getElementById('importBtn');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);

                if (!file.name.endsWith('.json')) {
                    Swal.fire('Peringatan', 'Hanya file JSON yang diperbolehkan.', 'warning');
                    input.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Peringatan', 'Ukuran file maksimal 5MB.', 'warning');
                    input.value = '';
                    return;
                }

                fileInfo.textContent = `📄 ${file.name} (${sizeMB} MB)`;
                fileInfo.classList.remove('hidden');
                importBtn.disabled = false;

                // Try to preview the file content
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const data = JSON.parse(e.target.result);
                        if (data.settings && Array.isArray(data.settings)) {
                            Swal.fire({
                                title: 'File Valid!',
                                html: `
                                    <div class="text-left">
                                        <p><strong>Theme:</strong> ${data.theme_name || 'N/A'}</p>
                                        <p><strong>Settings:</strong> ${data.settings.length} items</p>
                                        <p><strong>Exported:</strong> ${data.exported_at || 'N/A'}</p>
                                    </div>
                                `,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    } catch (e) {
                        // Not valid JSON, let server-side validation handle it
                    }
                };
                reader.readAsText(file);
            } else {
                fileInfo.classList.add('hidden');
                importBtn.disabled = true;
            }
        }

        function updateModeSelection(input) {
            document.querySelectorAll('input[name="import_mode"]').forEach(radio => {
                const label = radio.closest('label');
                label.classList.remove('border-blue-400', 'bg-blue-50', 'border-red-400', 'bg-red-50');
                label.classList.add('border-gray-200');
            });

            const label = input.closest('label');
            label.classList.remove('border-gray-200');
            if (input.value === 'merge') {
                label.classList.add('border-blue-400', 'bg-blue-50');
            } else {
                label.classList.add('border-red-400', 'bg-red-50');
            }
        }

        // Drag & drop
        const dropZone = document.getElementById('dropZone');
        ['dragenter', 'dragover'].forEach(event => {
            dropZone.addEventListener(event, e => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            });
        });
        ['dragleave', 'drop'].forEach(event => {
            dropZone.addEventListener(event, e => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });
        });
        dropZone.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            if (files.length) {
                document.getElementById('import_file').files = files;
                handleFileSelect(document.getElementById('import_file'));
            }
        });

        // Confirm on replace mode
        document.getElementById('importForm').addEventListener('submit', function(e) {
            if (document.querySelector('input[name="import_mode"]:checked').value === 'replace') {
                e.preventDefault();
                Swal.fire({
                    title: '⚠️ Mode Replace!',
                    html: 'Semua pengaturan tema ini akan <strong>DIHAPUS</strong> dan diganti dengan data import.<br><br>Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Replace!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\settings\themes\import.blade.php ENDPATH**/ ?>