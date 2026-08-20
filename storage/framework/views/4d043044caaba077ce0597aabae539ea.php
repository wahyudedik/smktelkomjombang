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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('common.e_graduation_data')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <a href="<?php echo e(route('admin.lulus.create')); ?>"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <?php echo e(__('common.add_graduation_data')); ?>

                    </a>
                    <a href="<?php echo e(route('admin.lulus.import')); ?>"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                        <?php echo e(__('common.import_data')); ?>

                    </a>
                    <a href="<?php echo e(route('admin.lulus.export', request()->query())); ?>"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded ml-2">
                        <?php echo e(__('common.export_data')); ?>

                    </a>
                </div>
                <div class="flex-1">
                    <a href="<?php echo e(route('admin.lulus.check')); ?>"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <?php echo e(__('common.check_graduation_status')); ?>

                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="<?php echo e(route('admin.lulus.index')); ?>" id="filterForm"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700"><?php echo e(__('common.search_label')); ?></label>
                            <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                                placeholder="<?php echo e(__('common.search_name_nisn_nis')); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700"><?php echo e(__('common.status')); ?></label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700"><?php echo e(__('common.academic_year')); ?></label>
                            <select name="tahun_ajaran" id="tahun_ajaran"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value=""><?php echo e(__('common.all_academic_years')); ?></option>
                                <?php $__currentLoopData = $tahunAjaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tahun); ?>"
                                        <?php echo e(request('tahun_ajaran') == $tahun ? 'selected' : ''); ?>>
                                        <?php echo e($tahun); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-gray-700"><?php echo e(__('common.major')); ?></label>
                            <select name="jurusan" id="jurusan"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value=""><?php echo e(__('common.all_majors')); ?></option>
                                <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($j); ?>"
                                        <?php echo e(request('jurusan') == $j ? 'selected' : ''); ?>>
                                        <?php echo e($j); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <button type="submit"
                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.filter')); ?>

                            </button>
                            <a href="<?php echo e(route('admin.lulus.index')); ?>"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                <?php echo e(__('common.reset')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <?php if($kelulusans->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.photo')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.name')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            NISN</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.major')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.academic_year')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.status')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.activity')); ?></th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('common.action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $kelulusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelulusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if($kelulusan->foto): ?>
                                                    <img src="<?php echo e($kelulusan->photo_url); ?>"
                                                        alt="<?php echo e($kelulusan->nama); ?>"
                                                        class="h-10 w-10 rounded-full object-cover">
                                                <?php else: ?>
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span
                                                            class="text-gray-600 text-sm font-medium"><?php echo e(substr($kelulusan->nama, 0, 1)); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo e($kelulusan->nama); ?>

                                                </div>
                                                <?php if($kelulusan->nis): ?>
                                                    <div class="text-sm text-gray-500">NIS: <?php echo e($kelulusan->nis); ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($kelulusan->nisn); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($kelulusan->major_display); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($kelulusan->graduation_year_display); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    <?php if($kelulusan->status_badge_color == 'green'): ?> bg-green-100 text-green-800
                                                    <?php elseif($kelulusan->status_badge_color == 'red'): ?> bg-red-100 text-red-800
                                                    <?php elseif($kelulusan->status_badge_color == 'yellow'): ?> bg-yellow-100 text-yellow-800
                                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                    <?php echo e($kelulusan->status_display); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($kelulusan->current_activity); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="<?php echo e(route('admin.lulus.show', $kelulusan)); ?>"
                                                        class="text-indigo-600 hover:text-indigo-900"><?php echo e(__('common.view')); ?></a>
                                                    <a href="<?php echo e(route('admin.lulus.edit', $kelulusan)); ?>"
                                                        class="text-yellow-600 hover:text-yellow-900"><?php echo e(__('common.edit')); ?></a>
                                                    <form method="POST"
                                                        action="<?php echo e(route('admin.lulus.destroy', $kelulusan)); ?>"
                                                        class="inline"
                                                        data-confirm="<?php echo e(__('common.delete_data_confirmation')); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900"><?php echo e(__('common.delete')); ?></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <?php echo e($kelulusans->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg"><?php echo e(__('common.no_graduation_data_found')); ?></div>
                            <a href="<?php echo e(route('admin.lulus.create')); ?>"
                                class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.add_first_data')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\lulus\index.blade.php ENDPATH**/ ?>