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
                <h1 class="text-2xl font-bold text-slate-900">Edit User Absensi</h1>
                <p class="text-slate-600 mt-1">PIN: <?php echo e($identity->device_pin); ?></p>
            </div>
            <a href="<?php echo e(route('admin.absensi.users.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <form method="POST" action="<?php echo e(route('admin.absensi.users.update', $identity)); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <input type="text" value="<?php echo e($identity->kind); ?>" disabled class="mt-1 block w-full rounded-md border-slate-300 bg-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <?php
                        $nama = $identity->user?->name ?? ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                    ?>
                    <input type="text" value="<?php echo e($nama); ?>" disabled class="mt-1 block w-full rounded-md border-slate-300 bg-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN Device</label>
                    <input type="text" name="device_pin" value="<?php echo e($identity->device_pin); ?>" 
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="is_active" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="1" <?php if($identity->is_active): echo 'selected'; endif; ?>>Aktif</option>
                        <option value="0" <?php if(!$identity->is_active): echo 'selected'; endif; ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?php echo e(route('admin.absensi.users.index')); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\users\edit.blade.php ENDPATH**/ ?>