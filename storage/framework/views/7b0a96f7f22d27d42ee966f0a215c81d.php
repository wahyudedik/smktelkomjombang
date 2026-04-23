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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Berita</h1>
                <p class="text-slate-600 mt-1">Kelola berita & artikel yang tampil di landing page</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.create')): ?>
                <a href="<?php echo e(route('admin.berita.create')); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Berita
                </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="card mb-6">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.berita.index')); ?>" id="filterForm"
                    class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Cari judul berita..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="document.getElementById('filterForm').submit();">
                            <option value="">Semua Status</option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($status)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-secondary">Reset</a>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Penulis</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-500">
                                        <?php echo e($loop->iteration + ($beritas->currentPage() - 1) * $beritas->perPage()); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($berita->featured_image): ?>
                                            <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                                                class="w-16 h-12 object-cover rounded-lg">
                                        <?php else: ?>
                                            <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-newspaper text-slate-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 flex items-center gap-2">
                                            <?php echo e($berita->title); ?>

                                            <?php if($berita->is_featured): ?>
                                                <span class="px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">Unggulan</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($berita->excerpt): ?>
                                            <div class="text-slate-500 text-xs mt-1"><?php echo e(Str::limit($berita->excerpt, 70)); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">
                                        <?php echo e($berita->user->name ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-sm">
                                        <?php if($berita->published_at): ?>
                                            <?php echo e($berita->published_at->format('d M Y')); ?>

                                        <?php else: ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($berita->status === 'published'): ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Publish</span>
                                        <?php elseif($berita->status === 'draft'): ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.view')): ?>
                                                <a href="<?php echo e(route('admin.berita.show', $berita)); ?>"
                                                    class="p-1.5 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.edit')): ?>
                                                <a href="<?php echo e(route('admin.berita.edit', $berita)); ?>"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.delete')): ?>
                                                <form action="<?php echo e(route('admin.berita.destroy', $berita)); ?>" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <i class="fas fa-newspaper text-4xl text-slate-300 mb-3 block"></i>
                                        Belum ada berita.
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.create')): ?>
                                            <a href="<?php echo e(route('admin.berita.create')); ?>" class="text-blue-600 hover:underline">Tambah sekarang</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($beritas->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-slate-200">
                        <?php echo e($beritas->links()); ?>

                    </div>
                <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/berita/index.blade.php ENDPATH**/ ?>