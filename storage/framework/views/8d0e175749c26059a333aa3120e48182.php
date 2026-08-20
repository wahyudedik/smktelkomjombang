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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.sarpras_rooms_list')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.manage_sarpras_rooms')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.sarpras.ruang.create')); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <?php echo e(__('common.add_room')); ?>

                </a>
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
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_rooms')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($ruangs->total()); ?></p>
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
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.active_rooms')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($ruangs->where('is_active', true)->count()); ?>

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
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_items')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($ruangs->sum('barang_count')); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_area')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($ruangs->sum('luas_ruang')); ?> m²</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="<?php echo e(route('admin.sarpras.ruang.index')); ?>" id="filterForm"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="<?php echo e(__('common.search_room_name')); ?>" class="form-input">
                </div>
                <div class="flex gap-2">
                    <select name="status" class="form-input"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value=""><?php echo e(__('common.all_status')); ?></option>
                        <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>><?php echo e(__('common.active')); ?></option>
                        <option value="tidak_aktif" <?php echo e(request('status') == 'tidak_aktif' ? 'selected' : ''); ?>><?php echo e(__('common.inactive')); ?>

                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <?php echo e(__('common.search')); ?>

                    </button>
                    <a href="<?php echo e(route('admin.sarpras.ruang.index')); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <?php echo e(__('common.reset')); ?>

                    </a>
                </div>
            </form>
        </div>

        <!-- Ruang Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('common.no')); ?></th>
                            <th><?php echo e(__('common.photo')); ?></th>
                            <th><?php echo e(__('common.room_name')); ?></th>
                            <th><?php echo e(__('common.location')); ?></th>
                            <th><?php echo e(__('common.area')); ?></th>
                            <th><?php echo e(__('common.capacity')); ?></th>
                            <th><?php echo e(__('common.item_count')); ?></th>
                            <th><?php echo e(__('common.status')); ?></th>
                            <th><?php echo e(__('common.action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ruangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($ruangs->firstItem() + $index); ?></td>
                                <td>
                                    <?php if($r->photo_url): ?>
                                        <img src="<?php echo e($r->photo_url); ?>" alt="<?php echo e($r->nama_ruang); ?>"
                                            class="w-12 h-12 object-cover rounded-lg">
                                    <?php else: ?>
                                        <div
                                            class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($r->nama_ruang); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($r->kode_ruang); ?></p>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($r->lokasi ?? '-'); ?></p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($r->formatted_luas); ?></p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($r->kapasitas ?? '-'); ?></p>
                                </td>
                                <td>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        <?php echo e($r->barang_count ?? 0); ?> <?php echo e(__('common.items')); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($r->is_active): ?>
                                        <span class="badge badge-success"><?php echo e(__('common.active')); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><?php echo e(__('common.inactive')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="<?php echo e(route('admin.sarpras.ruang.show', $r)); ?>"
                                            class="text-blue-600 hover:text-blue-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.sarpras.ruang.edit', $r)); ?>"
                                            class="text-amber-600 hover:text-amber-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.sarpras.ruang.destroy', $r)); ?>"
                                            class="inline"
                                            data-confirm="<?php echo e(str_replace(':name', $r->nama_ruang, __('common.delete_room_confirmation'))); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-700">
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
                                <td colspan="9" class="text-center py-8">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-slate-500"><?php echo e(__('common.no_rooms_data')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($ruangs->hasPages()): ?>
                <div class="px-6 py-4 border-t border-slate-200">
                    <?php echo e($ruangs->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <script>
            const successKey = 'ruang_alert_' + '<?php echo e(md5(session('success') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(successKey)) {
                    showSuccess('<?php echo e(session('success')); ?>');
                    sessionStorage.setItem(successKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('ruang_alert_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            const errorKey = 'ruang_alert_error_' + '<?php echo e(md5(session('error') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(errorKey)) {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('ruang_alert_error_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            const validationKey = 'ruang_alert_validation_' + '<?php echo e(md5(json_encode($errors->all()) . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(validationKey)) {
                    showError('<?php echo implode('<br>', $errors->all()); ?>');
                    sessionStorage.setItem(validationKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('ruang_alert_validation_'));
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\ruang\index.blade.php ENDPATH**/ ?>