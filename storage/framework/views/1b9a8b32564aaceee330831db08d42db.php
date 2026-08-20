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
            <?php echo e(__('Surat Masuk')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Daftar Surat Masuk</h2>
                        <a href="<?php echo e(route('admin.letters.in.create')); ?>"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Catat Surat Masuk
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm uppercase">
                                    <th class="px-4 py-3 font-semibold">Nomor Surat</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal Terima</th>
                                    <th class="px-4 py-3 font-semibold">Pengirim</th>
                                    <th class="px-4 py-3 font-semibold">Perihal</th>
                                    <th class="px-4 py-3 font-semibold">File</th>
                                    <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800"><?php echo e($letter->reference_number); ?>

                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($letter->letter_date->format('d/m/Y')); ?>

                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($letter->sender); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(Str::limit($letter->subject, 30)); ?></td>
                                        <td class="px-4 py-3">
                                            <?php if($letter->file_path): ?>
                                                <a href="<?php echo e(asset('storage/' . $letter->file_path)); ?>" target="_blank"
                                                    class="text-blue-600 hover:underline text-sm">
                                                    <i class="fas fa-paperclip mr-1"></i> Lihat
                                                </a>
                                            <?php else: ?>
                                                <span class="text-slate-400 text-sm">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="<?php echo e(route('admin.letters.in.show', $letter->id)); ?>"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada surat
                                            masuk.</td>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\in\index.blade.php ENDPATH**/ ?>