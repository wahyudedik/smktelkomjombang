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
                <h1 class="text-2xl font-bold text-slate-900">Tambah Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">Formulir pengajuan izin, sakit, cuti, atau dinas luar</p>
            </div>
            <a href="<?php echo e(route('admin.absensi.excuses.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if($errors->any()): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.absensi.excuses.store')); ?>" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 p-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengguna -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Pengguna <span class="text-red-500">*</span></label>
                    <select name="attendance_identity_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">— Pilih Pengguna —</option>
                        <?php $__currentLoopData = $identities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $identity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($identity['id']); ?>" <?php echo e(old('attendance_identity_id') == $identity['id'] ? 'selected' : ''); ?>>
                                <?php echo e($identity['label']); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Jenis -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis <span class="text-red-500">*</span></label>
                    <select name="type" class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">— Pilih Jenis —</option>
                        <option value="izin" <?php echo e(old('type') === 'izin' ? 'selected' : ''); ?>>Izin</option>
                        <option value="sakit" <?php echo e(old('type') === 'sakit' ? 'selected' : ''); ?>>Sakit</option>
                        <option value="cuti" <?php echo e(old('type') === 'cuti' ? 'selected' : ''); ?>>Cuti</option>
                        <option value="dinas" <?php echo e(old('type') === 'dinas' ? 'selected' : ''); ?>>Dinas Luar</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="<?php echo e(old('date', now()->toDateString())); ?>"
                        class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                </div>

                <!-- Alasan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Alasan <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="4" class="mt-1 block w-full rounded-md border-slate-300 text-sm"
                        placeholder="Jelaskan alasan izin/sakit..." required><?php echo e(old('reason')); ?></textarea>
                </div>

                <!-- Lampiran -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Lampiran (opsional)</label>
                    <input type="file" name="attachment"
                        class="mt-1 block w-full text-sm text-slate-700 border border-slate-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, atau PDF. Maksimal 5MB.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="<?php echo e(route('admin.absensi.excuses.index')); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\excuses\create.blade.php ENDPATH**/ ?>