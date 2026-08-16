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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Theme Settings</h1>
                                <p class="text-gray-600 mt-2">Kelola pengaturan visual dan konten untuk setiap tema
                                    landing page</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="<?php echo e(route('admin.themes.compare')); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Bandingkan
                                </a>
                                <a href="<?php echo e(route('admin.themes.analytics')); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Analytics
                                </a>
                                <a href="<?php echo e(route('admin.settings.index')); ?>"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

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
                                    <strong>Cara kerja:</strong> Pengaturan dibaca dari Database → Config File →
                                    Default.
                                    Jika ada di database, nilai database yang digunakan. Jika kosong, fallback ke config
                                    file.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Theme Indicator -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Tema Aktif:</strong> <span
                                        class="font-bold"><?php echo e(strtoupper($activeTheme ?? config('app.default_theme', 'telkom'))); ?></span>
                                    — Ditentukan oleh variabel <code
                                        class="bg-yellow-100 px-1 rounded">DEFAULT_THEME</code> di file <code
                                        class="bg-yellow-100 px-1 rounded">.env</code>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $themeKey => $themeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isActive = ($activeTheme ?? config('app.default_theme', 'telkom')) === $themeKey;
                                $settingsCount = $themeStats[$themeKey] ?? 0;
                            ?>
                            <div
                                class="border rounded-lg overflow-hidden <?php echo e($isActive ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-200'); ?>">
                                <!-- Card Header -->
                                <div class="p-6 <?php echo e($isActive ? 'bg-green-50' : 'bg-gray-50'); ?>">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <?php if($isActive): ?>
                                                    <span
                                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </span>
                                                <?php else: ?>
                                                    <span
                                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-300 text-white">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                                        </svg>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-xl font-bold text-gray-900"><?php echo e($themeInfo['name']); ?>

                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    <?php echo e($themeInfo['description'] ?? 'Landing page ' . $themeInfo['name']); ?>

                                                </p>
                                            </div>
                                        </div>
                                        <?php if($isActive): ?>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                AKTIF
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6 border-t">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-semibold text-gray-900"><?php echo e($settingsCount); ?></span>
                                            pengaturan tersimpan di database
                                        </div>
                                    </div>

                                    <!-- School Info -->
                                    <?php if(isset($themeInfo['school'])): ?>
                                        <div class="text-sm text-gray-500 mb-4">
                                            <p><strong>Sekolah:</strong> <?php echo e($themeInfo['school']); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Primary Actions -->
                                    <div class="flex items-center space-x-3 mb-4">
                                        <a href="<?php echo e(route('admin.themes.edit', $themeKey)); ?>"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Settings
                                        </a>

                                        <!-- P3-6.1: Preview Button -->
                                        <button onclick="previewTheme('<?php echo e($themeKey); ?>')"
                                            class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Preview
                                        </button>

                                        <!-- P3-6.3: Export Button -->
                                        <a href="<?php echo e(route('admin.themes.export', $themeKey)); ?>"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Export
                                        </a>
                                    </div>

                                    <!-- Secondary Actions -->
                                    <div class="flex items-center space-x-3">
                                        <!-- P3-6.3: Import Button -->
                                        <a href="<?php echo e(route('admin.themes.import', $themeKey)); ?>"
                                            class="inline-flex items-center px-3 py-1.5 bg-cyan-50 border border-cyan-300 rounded-md text-xs text-cyan-700 hover:bg-cyan-100 transition ease-in-out duration-150">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Import
                                        </a>

                                        <form action="<?php echo e(route('admin.themes.seed-defaults', $themeKey)); ?>"
                                            method="POST"
                                            onsubmit="return confirm('Import default settings dari config file? Data yang sudah ada tidak akan ditimpa.')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-50 border border-yellow-300 rounded-md text-xs text-yellow-700 hover:bg-yellow-100 transition ease-in-out duration-150">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Seed
                                            </button>
                                        </form>

                                        <form action="<?php echo e(route('admin.themes.reset-defaults', $themeKey)); ?>"
                                            method="POST"
                                            onsubmit="return confirm('⚠️ PERINGATAN: Semua pengaturan tema ini akan DIHAPUS dan diganti dengan default dari config file. Lanjutkan?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-300 rounded-md text-xs text-red-700 hover:bg-red-100 transition ease-in-out duration-150">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Reset
                                            </button>
                                        </form>

                                        <!-- P3-6.2: Clone Button -->
                                        <button
                                            onclick="cloneTheme('<?php echo e($themeKey); ?>', '<?php echo e($themeInfo['name']); ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-violet-50 border border-violet-300 rounded-md text-xs text-violet-700 hover:bg-violet-100 transition ease-in-out duration-150">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Clone
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- P3-6.1: Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden" onclick="closePreviewModal(event)">
        <div class="absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="relative w-full h-full flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white px-6 py-4 flex items-center justify-between border-b">
                <div class="flex items-center">
                    <h3 class="text-lg font-bold text-gray-900" id="previewTitle">Preview Tema</h3>
                    <span
                        class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        Live Preview
                    </span>
                </div>
                <div class="flex items-center space-x-3">
                    <a id="previewExternalLink" href="#" target="_blank"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md text-xs text-white hover:bg-blue-700 transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Buka di Tab Baru
                    </a>
                    <button onclick="closePreviewModal()"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md text-xs text-white hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Modal Body (iframe) -->
            <div class="flex-1 bg-gray-200">
                <iframe id="previewFrame" src="" class="w-full h-full border-0"
                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <!-- P3-6.2: Clone Modal -->
    <div id="cloneModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeCloneModal()"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Clone Tema</h3>
            <p class="text-sm text-gray-600 mb-4">
                Salin semua pengaturan dari <strong id="cloneSourceName"></strong> ke tema lain.
            </p>
            <form id="cloneForm" onsubmit="submitClone(event)">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Tema</label>
                    <select name="target_theme" id="cloneTarget"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        required>
                        <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($info['name']); ?> (<?php echo e($key); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeCloneModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-violet-600 text-white rounded-md text-sm hover:bg-violet-700 transition">
                        Clone Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // P3-6.1: Theme Preview
            function previewTheme(themeKey) {
                const modal = document.getElementById('previewModal');
                const frame = document.getElementById('previewFrame');
                const title = document.getElementById('previewTitle');
                const link = document.getElementById('previewExternalLink');

                // Fetch preview URL from API
                fetch(`/admin/settings/themes/${themeKey}/preview`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            title.textContent = `Preview: ${data.theme_name}`;
                            link.href = data.url;
                            frame.src = data.url;
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Gagal memuat preview tema.', 'error');
                    });
            }

            function closePreviewModal(event) {
                if (event && event.target !== event.currentTarget && !event.target.closest('button') && !event.target.closest(
                        'a')) return;
                const modal = document.getElementById('previewModal');
                const frame = document.getElementById('previewFrame');
                modal.classList.add('hidden');
                frame.src = '';
                document.body.style.overflow = '';
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePreviewModal();
                    closeCloneModal();
                }
            });

            // P3-6.2: Theme Clone
            let cloneSourceTheme = '';

            function cloneTheme(themeKey, themeName) {
                cloneSourceTheme = themeKey;
                document.getElementById('cloneSourceName').textContent = themeName;
                document.getElementById('cloneForm').action = `/admin/settings/themes/${themeKey}/clone`;

                // Pre-select the other theme as target
                const select = document.getElementById('cloneTarget');
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value !== themeKey) {
                        select.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('cloneModal').classList.remove('hidden');
            }

            function closeCloneModal() {
                document.getElementById('cloneModal').classList.add('hidden');
            }

            function submitClone(event) {
                event.preventDefault();
                const form = document.getElementById('cloneForm');
                const targetTheme = document.getElementById('cloneTarget').value;

                if (targetTheme === cloneSourceTheme) {
                    Swal.fire('Peringatan', 'Tema sumber dan target tidak boleh sama.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Clone Tema?',
                    text: `Semua pengaturan akan disalin ke tema "${targetTheme}". Settings yang sudah ada di target akan ditimpa.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#7c3aed',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Clone!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(form);
                        formData.append('target_theme', targetTheme);

                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', data.error || 'Gagal clone tema.', 'error');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error', 'Terjadi kesalahan saat clone.', 'error');
                            });
                    }
                });
            }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/settings/themes/index.blade.php ENDPATH**/ ?>