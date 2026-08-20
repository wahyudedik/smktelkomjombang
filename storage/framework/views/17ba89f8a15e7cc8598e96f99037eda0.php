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
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('common.teacher_data')); ?>

            </h2>
            <div class="flex flex-wrap items-center gap-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import', App\Models\Guru::class)): ?>
                    <a href="<?php echo e(route('admin.guru.import')); ?>"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <?php echo e(__('common.import')); ?>

                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export', App\Models\Guru::class)): ?>
                    <div class="relative inline-block" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <?php echo e(__('common.export')); ?>

                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" style="display: none;">
                            <div class="py-1">
                                <a href="<?php echo e(route('admin.guru.export')); ?>"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-excel mr-2 text-green-600"></i>Excel (.xlsx)
                                </a>
                                <a href="<?php echo e(route('admin.guru.export.pdf')); ?>"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-pdf mr-2 text-red-600"></i>PDF (.pdf)
                                </a>
                                <a href="<?php echo e(route('admin.guru.export.json')); ?>"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                    <i class="fas fa-code mr-2 text-blue-600"></i>JSON (.json)
                                </a>
                                <a href="<?php echo e(route('admin.guru.export.xml')); ?>"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-file-code mr-2 text-purple-600"></i>XML (.xml)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Guru::class)): ?>
                    <a href="<?php echo e(route('admin.guru.create')); ?>"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <?php echo e(__('common.add_teacher')); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="<?php echo e(route('admin.guru.index')); ?>" id="filterForm"
                            class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.search')); ?></label>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="<?php echo e(__('common.search_name_or_nip')); ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.status')); ?></label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value=""><?php echo e(__('common.all_status')); ?></option>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>"
                                            <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.employment_status')); ?></label>
                                <select name="employment_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value=""><?php echo e(__('common.all_status')); ?></option>
                                    <?php $__currentLoopData = $employmentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>"
                                            <?php echo e(request('employment_status') == $status ? 'selected' : ''); ?>>
                                            <?php echo e($status); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.subject')); ?></label>
                                <select name="subject"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value=""><?php echo e(__('common.all_subjects')); ?></option>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($subject); ?>"
                                            <?php echo e(request('subject') == $subject ? 'selected' : ''); ?>>
                                            <?php echo e($subject); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    <?php echo e(__('common.filter')); ?>

                                </button>
                                <a href="<?php echo e(route('admin.guru.index')); ?>"
                                    class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center">
                                    <?php echo e(__('common.reset')); ?>

                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Gurus Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort_by' => 'nama_lengkap', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])); ?>"
                                            class="hover:text-gray-700">
                                            <?php echo e(__('common.name')); ?>

                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('common.nip')); ?></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('common.status')); ?></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('common.employment_status')); ?></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('common.subject')); ?></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php echo e(__('common.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $gurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if($guru->foto): ?>
                                                    <img class="h-10 w-10 rounded-full object-cover mr-3"
                                                        src="<?php echo e($guru->photo_url); ?>"
                                                        alt="<?php echo e($guru->nama_lengkap); ?>">
                                                <?php else: ?>
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                        <span
                                                            class="text-gray-600 font-medium"><?php echo e(substr($guru->nama_lengkap, 0, 1)); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo e($guru->full_name); ?>

                                                    </div>
                                                    <div class="text-sm text-gray-500"><?php echo e($guru->jabatan ?? 'Guru'); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($guru->nip); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($guru->status_badge_color === 'green'): ?> bg-green-100 text-green-800
                                                <?php elseif($guru->status_badge_color === 'red'): ?> bg-red-100 text-red-800
                                                <?php elseif($guru->status_badge_color === 'blue'): ?> bg-blue-100 text-blue-800
                                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $guru->status_aktif))); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php if($guru->employment_badge_color === 'green'): ?> bg-green-100 text-green-800
                                                <?php elseif($guru->employment_badge_color === 'blue'): ?> bg-blue-100 text-blue-800
                                                <?php elseif($guru->employment_badge_color === 'yellow'): ?> bg-yellow-100 text-yellow-800
                                                <?php elseif($guru->employment_badge_color === 'orange'): ?> bg-orange-100 text-orange-800
                                                <?php elseif($guru->employment_badge_color === 'red'): ?> bg-red-100 text-red-800
                                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                <?php echo e($guru->status_kepegawaian); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($guru->subjects_string); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $guru)): ?>
                                                    <a href="<?php echo e(route('admin.guru.show', $guru)); ?>"
                                                        class="text-blue-600 hover:text-blue-900"><?php echo e(__('common.view')); ?></a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $guru)): ?>
                                                    <a href="<?php echo e(route('admin.guru.edit', $guru)); ?>"
                                                        class="text-indigo-600 hover:text-indigo-900"><?php echo e(__('common.edit')); ?></a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $guru)): ?>
                                                    <form method="POST"
                                                        action="<?php echo e(route('admin.guru.destroy', $guru)); ?>" class="inline"
                                                        data-confirm="<?php echo e(str_replace(':name', $guru->full_name, __('common.delete_teacher_confirmation'))); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900"><?php echo e(__('common.delete')); ?></button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            <?php echo e(__('common.no_teacher_data_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <?php echo e($gurus->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <script>
            // Generate unique key for this success message
            const successKey = 'guru_alert_' + '<?php echo e(md5(session('success') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                // Check if this alert has already been shown
                if (!sessionStorage.getItem(successKey)) {
                    showSuccess('<?php echo e(session('success')); ?>');
                    // Mark this alert as shown
                    sessionStorage.setItem(successKey, 'shown');

                    // Clean up old alerts (keep only last 5)
                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            const errorKey = 'guru_alert_error_' + '<?php echo e(md5(session('error') . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(errorKey)) {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_error_'));
                    if (keys.length > 5) {
                        keys.slice(0, keys.length - 5).forEach(k => sessionStorage.removeItem(k));
                    }
                }
            });
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            const validationKey = 'guru_alert_validation_' + '<?php echo e(md5(json_encode($errors->all()) . time())); ?>';

            document.addEventListener('DOMContentLoaded', function() {
                if (!sessionStorage.getItem(validationKey)) {
                    showError('<?php echo implode('<br>', $errors->all()); ?>');
                    sessionStorage.setItem(validationKey, 'shown');

                    const keys = Object.keys(sessionStorage).filter(k => k.startsWith('guru_alert_validation_'));
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\guru\index.blade.php ENDPATH**/ ?>