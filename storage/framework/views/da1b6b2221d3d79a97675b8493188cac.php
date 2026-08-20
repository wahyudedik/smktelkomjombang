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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.sarpras_items_list')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.manage_sarpras_items')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Barang::class)): ?>
                    <a href="<?php echo e(route('admin.sarpras.barang.create')); ?>" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <?php echo e(__('common.add_item')); ?>

                    </a>
                <?php endif; ?>

                <!-- Import/Export Buttons -->
                <?php if(Auth::user()->can('import', App\Models\Barang::class) || Auth::user()->can('export', App\Models\Barang::class)): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <?php echo e(__('common.import_export')); ?>

                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                            <div class="py-1">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import', App\Models\Barang::class)): ?>
                                    <a href="<?php echo e(route('admin.sarpras.barang.import')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <?php echo e(__('common.import_data')); ?>

                                    </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export', App\Models\Barang::class)): ?>
                                    <div class="px-2 py-1">
                                        <p class="text-xs text-gray-500 font-semibold mb-1"><?php echo e(__('common.export_formats')); ?></p>
                                    </div>
                                    <a href="<?php echo e(route('admin.sarpras.barang.export')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-excel mr-2 text-green-600"></i>
                                        Excel (.xlsx)
                                    </a>
                                    <a href="<?php echo e(route('admin.sarpras.barang.export.pdf')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-pdf mr-2 text-red-600"></i>
                                        PDF (.pdf)
                                    </a>
                                    <a href="<?php echo e(route('admin.sarpras.barang.export.json')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                        <i class="fas fa-code mr-2 text-blue-600"></i>
                                        JSON (.json)
                                    </a>
                                    <a href="<?php echo e(route('admin.sarpras.barang.export.xml')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-code mr-2 text-purple-600"></i>
                                        XML (.xml)
                                    </a>
                                    <div class="border-t my-1"></div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import', App\Models\Barang::class)): ?>
                                    <a href="<?php echo e(route('admin.sarpras.barang.downloadTemplate')); ?>"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <?php echo e(__('common.download_template')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Barcode Operations -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <?php echo e(__('common.barcode')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                        <div class="py-1">
                            <a href="<?php echo e(route('admin.sarpras.barcode.scan')); ?>"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <?php echo e(__('common.scan_barcode')); ?>

                            </a>
                            <button onclick="generateAllBarcodes()"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <?php echo e(__('common.generate_all_barcodes')); ?>

                            </button>
                            <button onclick="bulkPrintBarcodes()"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <?php echo e(__('common.bulk_print_barcodes')); ?>

                            </button>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(route('admin.sarpras.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.back_to_sarpras')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_items')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->total()); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.good')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->where('kondisi', 'baik')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.minor_damage')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e($barangs->where('kondisi', 'rusak_ringan')->count()); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.major_damage')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e($barangs->where('kondisi', 'rusak_berat')->count()); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="<?php echo e(route('admin.sarpras.barang.index')); ?>" id="filterForm"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="<?php echo e(__('common.search_item_name')); ?>" class="form-input">
                </div>
                <div class="flex gap-2">
                    <select name="kategori" class="form-input"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value=""><?php echo e(__('common.all_categories')); ?></option>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>"
                                <?php echo e(request('kategori') == $k->id ? 'selected' : ''); ?>>
                                <?php echo e($k->nama_kategori); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="kondisi" class="form-input"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value=""><?php echo e(__('common.all_conditions')); ?></option>
                        <option value="baik" <?php echo e(request('kondisi') == 'baik' ? 'selected' : ''); ?>><?php echo e(__('common.good')); ?></option>
                        <option value="rusak_ringan" <?php echo e(request('kondisi') == 'rusak_ringan' ? 'selected' : ''); ?>>
                            <?php echo e(__('common.minor_damage')); ?></option>
                        <option value="rusak_berat" <?php echo e(request('kondisi') == 'rusak_berat' ? 'selected' : ''); ?>><?php echo e(__('common.major_damage')); ?></option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <?php echo e(__('common.search')); ?>

                    </button>
                    <a href="<?php echo e(route('admin.sarpras.barang.index')); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <?php echo e(__('common.reset')); ?>

                    </a>
                </div>
            </form>
        </div>

        <!-- Barang Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('common.no')); ?></th>
                            <th><?php echo e(__('common.photo')); ?></th>
                            <th><?php echo e(__('common.item_name')); ?></th>
                            <th><?php echo e(__('common.category')); ?></th>
                            <th><?php echo e(__('common.condition')); ?></th>
                            <th><?php echo e(__('common.price')); ?></th>
                            <th><?php echo e(__('common.location')); ?></th>
                            <th><?php echo e(__('common.action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($barangs->firstItem() + $index); ?></td>
                                <td>
                                    <?php if($b->photo_url): ?>
                                        <img src="<?php echo e($b->photo_url); ?>" alt="<?php echo e($b->nama_barang); ?>"
                                            class="w-12 h-12 object-cover rounded-lg">
                                    <?php else: ?>
                                        <div
                                            class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($b->nama_barang); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($b->kode_barang); ?></p>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($b->kategori->nama_kategori); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($b->kondisi_badge_color); ?>">
                                        <?php echo e($b->kondisi_display); ?>

                                    </span>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($b->formatted_harga); ?></p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($b->ruang->nama_ruang ?? '-'); ?></p>
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="<?php echo e(route('admin.sarpras.barang.show', $b)); ?>"
                                            class="text-blue-600 hover:text-blue-700" title="<?php echo e(__('common.view_detail')); ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.sarpras.barang.edit', $b)); ?>"
                                            class="text-amber-600 hover:text-amber-700" title="<?php echo e(__('common.edit')); ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.sarpras.barcode.print', $b)); ?>"
                                            class="text-green-600 hover:text-green-700" title="<?php echo e(__('common.print_barcode')); ?>"
                                            target="_blank">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.sarpras.barang.destroy', $b)); ?>"
                                            class="inline"
                                            data-confirm="<?php echo e(str_replace(':name', $b->nama_barang, __('common.delete_item_confirmation'))); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-700"
                                                title="<?php echo e(__('common.delete')); ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="text-slate-500"><?php echo e(__('common.no_items_data')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($barangs->hasPages()): ?>
                <div class="px-6 py-4 border-t border-slate-200">
                    <?php echo e($barangs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript for Barcode Operations -->
    <script>
        function generateAllBarcodes() {
            showConfirm(
                '<?php echo e(__('common.confirm')); ?>',
                '<?php echo e(__('common.generate_barcode_confirmation')); ?>',
                '<?php echo e(__('common.yes_generate')); ?>',
                '<?php echo e(__('common.cancel')); ?>'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    fetch('<?php echo e(route('admin.sarpras.barcode.generate-all')); ?>', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            closeLoading();
                            if (data.success) {
                                showSuccess(data.message);
                                // Reload after 1 second
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showError('<?php echo e(__('common.error')); ?>: ' + data.message);
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error_occurred')); ?>');
                        });
                }
            });
        }

        function bulkPrintBarcodes() {
            // Get selected items (you can implement checkbox selection)
            const selectedItems = [];
            // For now, we'll show a modal to select items
            showBulkPrintModal();
        }

        function showBulkPrintModal() {
            // Fetch all available barang via AJAX for selection
            showLoading();

            fetch('<?php echo e(route('admin.sarpras.barang.index')); ?>?per_page=all', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    closeLoading();

                    const barangList = data.data || [];

                    if (barangList.length === 0) {
                        showError('<?php echo e(__('common.no_items_available')); ?>');
                        return;
                    }

                    // Create modal
                    const modal = document.createElement('div');
                    modal.id = 'bulkPrintModal';
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';

                    let checkboxesHtml = '';
                    barangList.forEach(item => {
                        checkboxesHtml += `
                            <label class="flex items-center hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" value="${item.id}" class="mr-2 bulk-print-checkbox">
                                <span class="text-sm">${item.nama_barang} (${item.kode_barang})</span>
                            </label>
                        `;
                    });

                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.bulk_print_barcodes')); ?></h3>
                                <p class="text-sm text-gray-600 mb-4"><?php echo e(__('common.select_items_to_print')); ?></p>
                                <div class="mb-3">
                                    <label class="flex items-center text-sm font-medium text-blue-600 cursor-pointer hover:text-blue-700">
                                        <input type="checkbox" id="selectAllBarcodes" class="mr-2">
                                        <?php echo e(__('common.select_all')); ?>

                                    </label>
                                </div>
                                <div class="space-y-1 max-h-60 overflow-y-auto border rounded p-2">
                                    ${checkboxesHtml}
                                </div>
                                <div class="flex justify-end space-x-3 mt-4">
                                    <button onclick="closeBulkPrintModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                        <?php echo e(__('common.cancel')); ?>

                                    </button>
                                    <button onclick="processBulkPrint()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        <?php echo e(__('common.print_selected')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    document.body.appendChild(modal);

                    // Add select all functionality
                    document.getElementById('selectAllBarcodes').addEventListener('change', function(e) {
                        const checkboxes = document.querySelectorAll('.bulk-print-checkbox');
                        checkboxes.forEach(cb => cb.checked = e.target.checked);
                    });
                })
                .catch(error => {
                    closeLoading();
                    console.error('Error fetching barang:', error);

                    // Fallback: use current page data
                    const modal = document.createElement('div');
                    modal.id = 'bulkPrintModal';
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';

                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.bulk_print_barcodes')); ?></h3>
                                <p class="text-sm text-gray-600 mb-4"><?php echo e(__('common.select_items_to_print')); ?></p>
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center">
                                            <input type="checkbox" value="<?php echo e($item->id); ?>" class="mr-2 bulk-print-checkbox">
                                            <span class="text-sm"><?php echo e($item->nama_barang); ?> (<?php echo e($item->kode_barang); ?>)</span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="flex justify-end space-x-3 mt-4">
                                    <button onclick="closeBulkPrintModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                        <?php echo e(__('common.cancel')); ?>

                                    </button>
                                    <button onclick="processBulkPrint()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        <?php echo e(__('common.print_selected')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                });
        }

        function closeBulkPrintModal() {
            const modal = document.getElementById('bulkPrintModal');
            if (modal) {
                modal.remove();
            }
        }

        function processBulkPrint() {
            const checkboxes = document.querySelectorAll('.bulk-print-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value).filter(id => id); // Filter empty values

            if (selectedIds.length === 0) {
                showError('<?php echo e(__('common.select_at_least_one')); ?>');
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route('admin.sarpras.barcode.bulk-print')); ?>';
            form.target = '_blank';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);

            // Add selected IDs
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'barang_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            closeBulkPrintModal();
        }
    </script>

    <?php if(session('success')): ?>
        <script>
            const successKey = 'barang_alert_' + '<?php echo e(md5(session('success') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(successKey)) {
                    showSuccess('<?php echo e(session('success')); ?>');
                    sessionStorage.setItem(successKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('barang_alert_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            const errorKey = 'barang_alert_error_' + '<?php echo e(md5(session('error') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(errorKey)) {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('barang_alert_error_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            const validationKey = 'barang_alert_validation_' + '<?php echo e(md5(json_encode($errors->all()) . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(validationKey)) {
                    showError('<?php echo implode('<br>', $errors->all()); ?>');
                    sessionStorage.setItem(validationKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('barang_alert_validation_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\barang\index.blade.php ENDPATH**/ ?>