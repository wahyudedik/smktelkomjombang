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
            <?php echo e(__('Buat Surat Keluar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Form Surat Keluar</h2>
                        <a href="<?php echo e(route('admin.letters.out.index')); ?>" class="text-slate-500 hover:text-slate-700">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    <?php if($errors->has('error')): ?>
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Gagal!</strong>
                            <span class="block sm:inline"><?php echo e($errors->first('error')); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.letters.out.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Format Surat</label>
                                    <select name="letter_format_id"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="">Pilih Format...</option>
                                        <?php $__currentLoopData = $formats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($format->id); ?>"><?php echo e($format->name); ?>

                                                (<?php echo e($format->format_template); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat</label>
                                    <input type="date" name="letter_date" value="<?php echo e(date('Y-m-d')); ?>"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tujuan / Kepada</label>
                                <input type="text" name="recipient"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Kepala Dinas Pendidikan" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Perihal</label>
                                <input type="text" name="subject"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Undangan Rapat Koordinasi" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Isi Ringkas / Keterangan
                                    (Opsional)</label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i> Generate Nomor & Simpan
                            </button>
                        </div>
                    </form>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\out\create.blade.php ENDPATH**/ ?>