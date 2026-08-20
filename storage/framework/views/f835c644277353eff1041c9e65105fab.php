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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.sarpras_maintenance_list')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.manage_sarpras_maintenance')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.sarpras.maintenance.create')); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <?php echo e(__('common.add_maintenance')); ?>

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
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_maintenance')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($maintenances->count()); ?></p>
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
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.completed')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e($maintenances->where('status', 'selesai')->count()); ?></p>
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.in_process')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e($maintenances->where('status', 'dalam_proses')->count()); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_cost')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e('Rp ' . number_format($maintenances->sum('biaya'), 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="<?php echo e(route('admin.sarpras.maintenance.index')); ?>"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="<?php echo e(__('common.search_maintenance_type')); ?>" class="form-input">
                </div>
                <div class="flex gap-2">
                    <select name="status" class="form-input">
                        <option value=""><?php echo e(__('common.all_status')); ?></option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>><?php echo e(__('common.pending')); ?></option>
                        <option value="dalam_proses" <?php echo e(request('status') == 'dalam_proses' ? 'selected' : ''); ?>><?php echo e(__('common.in_process')); ?></option>
                        <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>><?php echo e(__('common.completed')); ?>

                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <?php echo e(__('common.search')); ?>

                    </button>
                    <a href="<?php echo e(route('admin.sarpras.maintenance.index')); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <?php echo e(__('common.reset')); ?>

                    </a>
                </div>
            </form>
        </div>

        <!-- Maintenance Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('common.no')); ?></th>
                            <th><?php echo e(__('common.maintenance_type')); ?></th>
                            <th><?php echo e(__('common.item_name')); ?></th>
                            <th><?php echo e(__('common.status')); ?></th>
                            <th><?php echo e(__('common.cost')); ?></th>
                            <th><?php echo e(__('common.maintenance_date')); ?></th>
                            <th><?php echo e(__('common.technician')); ?></th>
                            <th><?php echo e(__('common.action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $maintenances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($maintenances->firstItem() + $index); ?></td>
                                <td>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($m->jenis_maintenance); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e(Str::limit($m->deskripsi_masalah, 50)); ?>

                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <p class="font-medium text-slate-900"><?php echo e($m->item_name); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($m->item_type_display); ?></p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo e($m->status_badge_color); ?>">
                                        <?php echo e($m->status_display); ?>

                                    </span>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($m->formatted_biaya); ?></p>
                                </td>
                                <td>
                                    <div>
                                        <p class="text-sm text-slate-900">
                                            <?php echo e($m->tanggal_maintenance->format('d M Y')); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo e($m->created_at->diffForHumans()); ?></p>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-900"><?php echo e($m->user->name ?? '-'); ?></p>
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="<?php echo e(route('admin.sarpras.maintenance.show', $m)); ?>"
                                            class="text-blue-600 hover:text-blue-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.sarpras.maintenance.edit', $m)); ?>"
                                            class="text-amber-600 hover:text-amber-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST"
                                            action="<?php echo e(route('admin.sarpras.maintenance.destroy', $m)); ?>"
                                            class="inline"
                                            data-confirm="<?php echo e(str_replace(':name', $m->jenis_maintenance, __('common.delete_maintenance_confirmation'))); ?>">
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
                                <td colspan="8" class="text-center py-8">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-slate-500"><?php echo e(__('common.no_maintenance_data')); ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($maintenances->hasPages()): ?>
                <div class="px-6 py-4 border-t border-slate-200">
                    <?php echo e($maintenances->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <script>
            const successKey = 'maintenance_alert_' + '<?php echo e(md5(session('success') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(successKey)) {
                    showSuccess('<?php echo e(session('success')); ?>');
                    sessionStorage.setItem(successKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('maintenance_alert_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            const errorKey = 'maintenance_alert_error_' + '<?php echo e(md5(session('error') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(errorKey)) {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('maintenance_alert_error_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            const validationKey = 'maintenance_alert_validation_' + '<?php echo e(md5(json_encode($errors->all()) . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(validationKey)) {
                    showError('<?php echo implode('<br>', $errors->all()); ?>');
                    sessionStorage.setItem(validationKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('maintenance_alert_validation_'));
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\maintenance\index.blade.php ENDPATH**/ ?>