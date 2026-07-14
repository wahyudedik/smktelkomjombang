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
                <h1 class="text-2xl font-bold text-slate-900">Manage User Absensi</h1>
                <p class="text-slate-600 mt-1">Tambah, edit, hapus user dan sinkronisasi ke device</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('admin.absensi.index')); ?>" class="btn btn-secondary">Rekap</a>
                <a href="<?php echo e(route('admin.absensi.logs')); ?>" class="btn btn-secondary">Logs</a>
                <a href="<?php echo e(route('admin.absensi.devices.index')); ?>" class="btn btn-secondary">Devices</a>
                <a href="<?php echo e(route('admin.absensi.mapping.index')); ?>" class="btn btn-secondary">Mapping</a>
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

        <!-- Form Tambah User -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Tambah User Baru</h2>
            <form method="POST" action="<?php echo e(route('admin.absensi.users.store')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <select name="kind" id="kind" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="user">User</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih User --</option>
                        <?php $__currentLoopData = $availableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="display: none;">
                    <label class="block text-sm font-medium text-slate-700">Guru</label>
                    <select name="guru_id" id="guru_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih Guru --</option>
                        <?php $__currentLoopData = $availableGurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($g->id); ?>"><?php echo e($g->nama_lengkap); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="display: none;">
                    <label class="block text-sm font-medium text-slate-700">Siswa</label>
                    <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="">-- Pilih Siswa --</option>
                        <?php $__currentLoopData = $availableSiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>"><?php echo e($s->nama_lengkap); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN Device</label>
                    <input type="text" name="device_pin" placeholder="Contoh: 1001" 
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>

        <!-- Tombol Sync All -->
        <div class="mb-6">
            <form method="POST" action="<?php echo e(route('admin.absensi.users.sync-all')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-secondary">Sync Semua User ke Device</button>
            </form>
        </div>

        <!-- Daftar User -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $nama = $u->user?->name ?? ($u->guru?->nama_lengkap ?? ($u->siswa?->nama_lengkap ?? '-'));
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($u->kind); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo e($nama); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($u->device_pin); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if($u->is_active): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aktif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="<?php echo e(route('admin.absensi.users.edit', $u)); ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <a href="<?php echo e(route('admin.absensi.users.sync-status', $u)); ?>" class="btn btn-sm btn-secondary">Status</a>
                                    <form method="POST" action="<?php echo e(route('admin.absensi.users.destroy', $u)); ?>" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada user</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('kind').addEventListener('change', function() {
            const kind = this.value;
            document.getElementById('user_id').parentElement.style.display = kind === 'user' ? 'block' : 'none';
            document.getElementById('guru_id').parentElement.style.display = kind === 'guru' ? 'block' : 'none';
            document.getElementById('siswa_id').parentElement.style.display = kind === 'siswa' ? 'block' : 'none';
        });
        // Trigger on load
        document.getElementById('kind').dispatchEvent(new Event('change'));
    </script>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/attendance/users/index.blade.php ENDPATH**/ ?>