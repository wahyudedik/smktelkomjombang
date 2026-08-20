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
            <?php echo e(__('Detail Surat Masuk')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Detail Surat Masuk</h2>
                        <a href="<?php echo e(route('admin.letters.in.index')); ?>" class="text-slate-500 hover:text-slate-700">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-4 rounded-lg">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Informasi Surat</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="block text-xs text-slate-400">Nomor Surat (Pengirim)</span>
                                    <span
                                        class="block text-slate-800 font-medium"><?php echo e($letter->reference_number); ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Tanggal Surat</span>
                                    <span
                                        class="block text-slate-800 font-medium"><?php echo e($letter->letter_date->translatedFormat('d F Y')); ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Dicatat Tanggal</span>
                                    <span
                                        class="block text-slate-800"><?php echo e($letter->created_at->translatedFormat('d F Y H:i')); ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Dicatat Oleh</span>
                                    <span class="block text-slate-800"><?php echo e($letter->creator->name ?? '-'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-lg">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Pengirim & Isi</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="block text-xs text-slate-400">Pengirim</span>
                                    <span class="block text-slate-800 font-medium"><?php echo e($letter->sender); ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Perihal</span>
                                    <span class="block text-slate-800 font-medium"><?php echo e($letter->subject); ?></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Ringkasan/Isi</span>
                                    <p class="text-slate-800 text-sm whitespace-pre-line">
                                        <?php echo e($letter->description ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($letter->file_path): ?>
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">File Lampiran</h3>
                            <div
                                class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Scan Surat Masuk</p>
                                        <p class="text-xs text-blue-700">Klik tombol di kanan untuk melihat file</p>
                                    </div>
                                </div>
                                <a href="<?php echo e(asset('storage/' . $letter->file_path)); ?>" target="_blank"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> Lihat File
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase mb-4">Riwayat Aktivitas</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $letter->activityLogs()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-500 mr-3"></div>
                                    <div>
                                        <p class="text-sm text-slate-800"><?php echo e($log->details); ?></p>
                                        <p class="text-xs text-slate-400">
                                            Oleh <?php echo e($log->user->name ?? 'System'); ?> &bull;
                                            <?php echo e($log->created_at->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\in\show.blade.php ENDPATH**/ ?>