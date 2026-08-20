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
                <h1 class="text-2xl font-bold text-slate-900">Daftar Barang Sarpras</h1>
                <p class="text-slate-600 mt-1">Kelola data barang sarana dan prasarana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.sarpras.barang.create')); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Barang</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->total()); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Kondisi Baik</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->where('kondisi', 'baik')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Kondisi Rusak</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->where('kondisi', 'rusak')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Kondisi Hilang</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($barangs->where('kondisi', 'hilang')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-2">Pencarian</label>
                    <input type="search" id="search" name="search" value="<?php echo e(request('search')); ?>"
                        class="form-input" placeholder="Cari barang...">
                </div>
                <div>
                    <label for="kondisi" class="block text-sm font-medium text-slate-700 mb-2">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" <?php echo e(request('kondisi') == 'baik' ? 'selected' : ''); ?>>Baik</option>
                        <option value="rusak" <?php echo e(request('kondisi') == 'rusak' ? 'selected' : ''); ?>>Rusak</option>
                        <option value="hilang" <?php echo e(request('kondisi') == 'hilang' ? 'selected' : ''); ?>>Hilang</option>
                    </select>
                </div>
                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-slate-700 mb-2">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kategori->id); ?>"
                                <?php echo e(request('kategori_id') == $kategori->id ? 'selected' : ''); ?>>
                                <?php echo e($kategori->nama_kategori); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Kode</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nama Barang</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Kondisi</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Harga</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Ruang</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    <?php echo e($barangs->firstItem() + $index); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        <?php echo e($b->kode_barang); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if($b->foto): ?>
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover"
                                                    src="<?php echo e($b->photo_url); ?>" alt="<?php echo e($b->nama_barang); ?>">
                                            </div>
                                        <?php else: ?>
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-slate-200 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900"><?php echo e($b->nama_barang); ?>

                                            </div>
                                            <div class="text-sm text-slate-500"><?php echo e($b->merk); ?>

                                                <?php echo e($b->model); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($b->kategori->nama_kategori); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo $b->condition_badge; ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-slate-900"><?php echo e($b->formatted_price); ?></p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-slate-900"><?php echo e($b->ruang->nama_ruang ?? '-'); ?></p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="<?php echo e(route('admin.sarpras.barang.show', $b)); ?>"
                                            class="text-blue-600 hover:text-blue-700" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.sarpras.barang.edit', $b)); ?>"
                                            class="text-amber-600 hover:text-amber-700" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button" class="text-red-600 hover:text-red-700"
                                            data-delete-url="<?php echo e(route('admin.sarpras.barang.destroy', $b)); ?>"
                                            data-item-name="barang <?php echo e($b->nama_barang); ?>" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H9a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-400 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-slate-900 mb-2">Tidak ada data barang</h3>
                                        <p class="text-slate-500 mb-4">Belum ada barang yang terdaftar dalam sistem.
                                        </p>
                                        <a href="<?php echo e(route('admin.sarpras.barang.create')); ?>" class="btn btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tambah Barang Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($barangs->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-slate-200 sm:px-6">
                    <?php echo e($barangs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include Components -->
    <?php if (isset($component)) { $__componentOriginal9502acf056f720678e71cee27afcafd8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9502acf056f720678e71cee27afcafd8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9502acf056f720678e71cee27afcafd8)): ?>
<?php $attributes = $__attributesOriginal9502acf056f720678e71cee27afcafd8; ?>
<?php unset($__attributesOriginal9502acf056f720678e71cee27afcafd8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9502acf056f720678e71cee27afcafd8)): ?>
<?php $component = $__componentOriginal9502acf056f720678e71cee27afcafd8; ?>
<?php unset($__componentOriginal9502acf056f720678e71cee27afcafd8); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal12edf0912f556d11ae8cc8574a1823ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal12edf0912f556d11ae8cc8574a1823ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loading-states','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loading-states'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal12edf0912f556d11ae8cc8574a1823ea)): ?>
<?php $attributes = $__attributesOriginal12edf0912f556d11ae8cc8574a1823ea; ?>
<?php unset($__attributesOriginal12edf0912f556d11ae8cc8574a1823ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal12edf0912f556d11ae8cc8574a1823ea)): ?>
<?php $component = $__componentOriginal12edf0912f556d11ae8cc8574a1823ea; ?>
<?php unset($__componentOriginal12edf0912f556d11ae8cc8574a1823ea); ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\barang\index-improved.blade.php ENDPATH**/ ?>