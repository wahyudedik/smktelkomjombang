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
                <h1 class="text-2xl font-bold text-slate-900">Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">Kelola data izin, sakit, cuti, dan dinas luar</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.absensi.index')); ?>" class="btn btn-secondary">Kembali</a>
                <a href="<?php echo e(route('admin.absensi.excuses.create')); ?>" class="btn btn-primary">+ Tambah Izin</a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Notifikasi -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="<?php echo e(route('admin.absensi.excuses.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Pencarian</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama..."
                        class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <select name="type" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                        <option value="">Semua</option>
                        <option value="izin" <?php echo e(request('type') === 'izin' ? 'selected' : ''); ?>>Izin</option>
                        <option value="sakit" <?php echo e(request('type') === 'sakit' ? 'selected' : ''); ?>>Sakit</option>
                        <option value="cuti" <?php echo e(request('type') === 'cuti' ? 'selected' : ''); ?>>Cuti</option>
                        <option value="dinas" <?php echo e(request('type') === 'dinas' ? 'selected' : ''); ?>>Dinas Luar</option>
                        <option value="alpha" <?php echo e(request('type') === 'alpha' ? 'selected' : ''); ?>>Alpha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                        <option value="">Semua</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                        <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                    <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="mt-1 block w-full rounded-md border-slate-300 text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full text-sm">Filter</button>
                </div>
            </form>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $excuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $excuse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($loop->iteration); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($excuse->date->format('d/m/Y')); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo e($excuse->nama); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium"><?php echo e($excuse->type_label); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700 max-w-xs truncate"><?php echo e($excuse->reason); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 bg-<?php echo e($excuse->status_color); ?>-100 text-<?php echo e($excuse->status_color); ?>-800 rounded-full text-xs font-medium"><?php echo e($excuse->status_label); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($excuse->creator?->name ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="<?php echo e(route('admin.absensi.excuses.show', $excuse)); ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                                    <?php if($excuse->status === 'pending'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.absensi.excuses.approve', $excuse)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium" onclick="return confirm('Setujui izin ini?')">Setuju</button>
                                        </form>
                                        <a href="<?php echo e(route('admin.absensi.excuses.edit', $excuse)); ?>" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Edit</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data izin/sakit</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                <?php echo e($excuses->links()); ?>

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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\excuses\index.blade.php ENDPATH**/ ?>