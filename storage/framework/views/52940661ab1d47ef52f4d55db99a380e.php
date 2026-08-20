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
                <h1 class="text-2xl font-bold text-slate-900">Detail Kategori Sarpras</h1>
                <p class="text-slate-600 mt-1"><?php echo e($kategori->nama_kategori); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.sarpras.kategori.edit', $kategori)); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="<?php echo e(route('admin.sarpras.kategori.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Kategori Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Informasi Kategori</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo e($kategori->kode_kategori); ?>

                            </span>
                            <?php if($kategori->is_active): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Tidak Aktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Nama Kategori</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($kategori->nama_kategori); ?></p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Kode Kategori</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($kategori->kode_kategori); ?></p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Jumlah Barang</h4>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <?php echo e($kategori->barang_count ?? 0); ?> barang
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Dibuat</h4>
                            <p class="text-lg font-semibold text-slate-900">
                                <?php echo e($kategori->created_at->format('d M Y, H:i')); ?></p>
                        </div>
                    </div>

                    <?php if($kategori->deskripsi): ?>
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Deskripsi</h4>
                            <p class="text-slate-900"><?php echo e($kategori->deskripsi); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Barang in this category -->
                <?php if($kategori->barang && $kategori->barang->count() > 0): ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Barang dalam Kategori Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $kategori->barang->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900"><?php echo e($barang->nama_barang); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($barang->kondisi_display); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($kategori->barang->count() > 6): ?>
                            <div class="mt-4 text-center">
                                <a href="<?php echo e(route('admin.sarpras.barang.index', ['kategori' => $kategori->id])); ?>"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat semua <?php echo e($kategori->barang->count()); ?> barang
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.sarpras.kategori.edit', $kategori)); ?>"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Edit Kategori</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="<?php echo e(route('admin.sarpras.barang.create', ['kategori' => $kategori->id])); ?>"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Tambah Barang</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="<?php echo e(route('admin.sarpras.kategori.index')); ?>"
                            class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-slate-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Daftar Kategori</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Total Barang</span>
                            <span
                                class="text-sm font-semibold text-slate-900"><?php echo e($kategori->barang_count ?? 0); ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Status</span>
                            <span class="badge <?php echo e($kategori->is_active ? 'badge-success' : 'badge-warning'); ?>">
                                <?php echo e($kategori->is_active ? 'Aktif' : 'Tidak Aktif'); ?>

                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Dibuat</span>
                            <span class="text-sm text-slate-900"><?php echo e($kategori->created_at->format('d M Y')); ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Diperbarui</span>
                            <span class="text-sm text-slate-900"><?php echo e($kategori->updated_at->format('d M Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\kategori\show.blade.php ENDPATH**/ ?>