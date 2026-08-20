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
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Format Surat')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Format Surat</h2>
                        <a href="<?php echo e(route('admin.letters.formats.create')); ?>"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Format Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nama Format</th>
                                    <th class="px-4 py-3 font-semibold">Template</th>
                                    <th class="px-4 py-3 font-semibold">Reset Periode</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $formats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 text-slate-800 font-medium"><?php echo e($format->name); ?></td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <span class="font-mono text-sm bg-slate-100 rounded px-2 py-1 inline-block">
                                                <?php echo e($format->format_template); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(ucfirst($format->period_mode)); ?></td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($format->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($format->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="<?php echo e(route('admin.letters.formats.edit', ['format' => $format->id])); ?>"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="<?php echo e(route('admin.letters.formats.destroy', ['format' => $format->id])); ?>"
                                                method="POST" class="inline"
                                                data-confirm="Apakah Anda yakin ingin menghapus format surat '<?php echo e($format->name); ?>'? Tindakan ini tidak dapat dibatalkan.">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada format
                                            surat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\formats\index.blade.php ENDPATH**/ ?>