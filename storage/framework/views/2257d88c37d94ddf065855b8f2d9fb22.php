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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mapping PIN</h1>
                <p class="text-slate-600 mt-1">Pemetaan PIN perangkat ke data user/guru/siswa</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('admin.absensi.index')); ?>" class="btn btn-secondary">Rekap</a>
                <a href="<?php echo e(route('admin.absensi.logs')); ?>" class="btn btn-secondary">Logs</a>
                <a href="<?php echo e(route('admin.absensi.devices.index')); ?>" class="btn btn-secondary">Devices</a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="POST" action="<?php echo e(route('admin.absensi.mapping.store')); ?>"
                class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kind</label>
                    <select name="kind" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="user" <?php if(old('kind') === 'user'): echo 'selected'; endif; ?>>user</option>
                        <option value="guru" <?php if(old('kind') === 'guru'): echo 'selected'; endif; ?>>guru</option>
                        <option value="siswa" <?php if(old('kind') === 'siswa'): echo 'selected'; endif; ?>>siswa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">user_id</label>
                    <input type="number" name="user_id" value="<?php echo e(old('user_id')); ?>"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">guru_id</label>
                    <input type="number" name="guru_id" value="<?php echo e(old('guru_id')); ?>"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">siswa_id</label>
                    <input type="number" name="siswa_id" value="<?php echo e(old('siswa_id')); ?>"
                        class="mt-1 block w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN</label>
                    <input type="text" name="device_pin" value="<?php echo e(old('device_pin')); ?>"
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>
                <div class="md:col-span-5 flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Kind</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $mappings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $nama =
                                    $m->guru?->nama_lengkap ?? ($m->siswa?->nama_lengkap ?? ($m->user?->name ?? '-'));
                                $ref = $m->user_id
                                    ? "user:{$m->user_id}"
                                    : ($m->guru_id
                                        ? "guru:{$m->guru_id}"
                                        : ($m->siswa_id
                                            ? "siswa:{$m->siswa_id}"
                                            : '-'));
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($m->kind); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">
                                    <?php echo e($nama); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($ref); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($m->device_pin); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    <?php echo e($m->is_active ? 'Ya' : 'Tidak'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada
                                    mapping</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                <?php echo e($mappings->links()); ?>

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
<?php /**PATH E:\PROJEKU\telkom\resources\views/attendance/mapping.blade.php ENDPATH**/ ?>