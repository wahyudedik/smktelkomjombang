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
                <h1 class="text-2xl font-bold text-slate-900">Enroll Fingerprint</h1>
                <p class="text-slate-600 mt-1"><?php echo e($name); ?> (PIN: <?php echo e($identity->device_pin); ?>)</p>
            </div>
            <a href="<?php echo e(route('admin.absensi.biometric.index')); ?>" class="btn btn-secondary">Kembali</a>
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
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Instruksi:</strong> Pilih device dan jari yang akan di-enroll, kemudian klik "Mulai Enrollment". 
                    Setelah itu, silakan scan jari di device sampai enrollment selesai.
                </p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.absensi.biometric.fingerprint.store', $identity)); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Device</label>
                    <select name="device_id" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="">-- Pilih Device --</option>
                        <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?> (<?php echo e($d->serial_number); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Jari yang Akan Di-Enroll</label>
                    <select name="finger_index" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="">-- Pilih Jari --</option>
                        <option value="0">Jari 1 (Ibu jari kiri)</option>
                        <option value="1">Jari 2 (Telunjuk kiri)</option>
                        <option value="2">Jari 3 (Jari tengah kiri)</option>
                        <option value="3">Jari 4 (Jari manis kiri)</option>
                        <option value="4">Jari 5 (Kelingking kiri)</option>
                        <option value="5">Jari 6 (Ibu jari kanan)</option>
                        <option value="6">Jari 7 (Telunjuk kanan)</option>
                        <option value="7">Jari 8 (Jari tengah kanan)</option>
                        <option value="8">Jari 9 (Jari manis kanan)</option>
                        <option value="9">Jari 10 (Kelingking kanan)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Mulai Enrollment</button>
                    <a href="<?php echo e(route('admin.absensi.biometric.index')); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-slate-50 rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-3">Tips Enrollment Fingerprint</h3>
            <ul class="list-disc pl-5 space-y-2 text-sm text-slate-700">
                <li>Pastikan jari bersih dan kering</li>
                <li>Letakkan jari di sensor dengan tekanan yang konsisten</li>
                <li>Scan jari 3-4 kali untuk hasil yang lebih baik</li>
                <li>Disarankan enroll 2-4 jari berbeda untuk backup</li>
                <li>Pastikan pencahayaan cukup di area sensor</li>
            </ul>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\biometric\enroll-fingerprint.blade.php ENDPATH**/ ?>