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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.permission_management')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.manage_permissions_description')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.permissions.bulk-create')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <?php echo e(__('common.bulk_create')); ?>

                </a>
                <a href="<?php echo e(route('admin.permissions.create')); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <?php echo e(__('common.add_permission')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('admin.permissions.index')); ?>" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-slate-700 mb-2"><?php echo e(__('common.search')); ?></label>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="<?php echo e(__('common.search_permission')); ?>"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-slate-700 mb-2"><?php echo e(__('common.guard')); ?></label>
                            <select name="guard"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value=""><?php echo e(__('common.all_guards')); ?></option>
                                <option value="web" <?php echo e(request('guard') == 'web' ? 'selected' : ''); ?>>Web</option>
                                <option value="api" <?php echo e(request('guard') == 'api' ? 'selected' : ''); ?>>API</option>
                            </select>
                        </div>
                        <div class="min-w-48">
                            <label class="block text-sm font-medium text-slate-700 mb-2"><?php echo e(__('common.sort_by')); ?></label>
                            <select name="sort_by"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="name" <?php echo e(request('sort_by') == 'name' ? 'selected' : ''); ?>><?php echo e(__('common.sort_by_name')); ?>

                                </option>
                                <option value="module" <?php echo e(request('sort_by') == 'module' ? 'selected' : ''); ?>><?php echo e(__('common.sort_by_module')); ?>

                                </option>
                                <option value="action" <?php echo e(request('sort_by') == 'action' ? 'selected' : ''); ?>><?php echo e(__('common.sort_by_action')); ?>

                                </option>
                                <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>
                                    <?php echo e(__('common.sort_by_created_at')); ?></option>
                            </select>
                        </div>
                        <div class="min-w-32">
                            <label class="block text-sm font-medium text-slate-700 mb-2"><?php echo e(__('common.direction')); ?></label>
                            <select name="sort_direction"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="asc" <?php echo e(request('sort_direction') == 'asc' ? 'selected' : ''); ?>><?php echo e(__('common.asc')); ?>

                                </option>
                                <option value="desc" <?php echo e(request('sort_direction') == 'desc' ? 'selected' : ''); ?>><?php echo e(__('common.desc')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="btn btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <?php echo e(__('common.filter')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Permissions Table -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.permission')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.module')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.action')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.guard')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.roles')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.created')); ?>

                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    <?php echo e(__('common.actions')); ?>

                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">
                                                <?php echo e($permission->display_name ?? $permission->name); ?>

                                            </div>
                                            <div class="text-sm text-slate-500">
                                                <?php echo e($permission->name); ?>

                                            </div>
                                            <?php if($permission->description): ?>
                                                <div class="text-xs text-slate-400 mt-1">
                                                    <?php echo e($permission->description); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?php echo e($permission->module ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo e($permission->action ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php echo e($permission->guard_name); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            <?php $__empty_2 = true; $__currentLoopData = $permission->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <?php echo e($role->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="text-xs text-slate-400"><?php echo e(__('common.no_roles')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <?php echo e($permission->created_at->format('d M Y')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="<?php echo e(route('admin.permissions.show', $permission)); ?>"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="<?php echo e(route('admin.permissions.edit', $permission)); ?>"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="<?php echo e(route('admin.permissions.destroy', $permission)); ?>"
                                                method="POST" class="inline"
                                                data-confirm="<?php echo e(__('common.delete_permission_confirmation')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900">
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
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-slate-500">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-slate-900"><?php echo e(__('common.no_permissions_found')); ?>

                                            </h3>
                                            <p class="mt-1 text-sm text-slate-500"><?php echo e(__('common.get_started_permission')); ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($permissions->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-slate-200">
                        <?php echo e($permissions->appends(request()->query())->links()); ?>

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
<?php /**PATH E:\PROJEKU\telkom\resources\views\permissions\index.blade.php ENDPATH**/ ?>