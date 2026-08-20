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
            <?php echo e(__('Surat Keluar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Surat Keluar</h2>
                        <a href="<?php echo e(route('admin.letters.out.create')); ?>"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Surat Keluar
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nomor Surat</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold">Kepada</th>
                                    <th class="px-4 py-3 font-semibold">Perihal</th>
                                    <th class="px-4 py-3 font-semibold">Dibuat Oleh</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800">
                                            <?php echo e($letter->letter_number); ?>

                                            <?php if(!$letter->file_path): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                    Draft
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($letter->letter_date->format('d/m/Y')); ?>

                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($letter->recipient); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(Str::limit($letter->subject, 30)); ?></td>
                                        <td class="px-4 py-3 text-slate-600 text-sm"><?php echo e($letter->creator->name ?? '-'); ?>

                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <?php if(!$letter->file_path): ?>
                                                <a href="<?php echo e(route('admin.letters.out.upload', $letter->id)); ?>"
                                                    class="text-orange-600 hover:text-orange-800" title="Upload Scan">
                                                    <i class="fas fa-upload"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('admin.letters.out.show', $letter->id)); ?>"
                                                class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada surat
                                            keluar.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <?php echo e($letters->links()); ?>

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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\out\index.blade.php ENDPATH**/ ?>