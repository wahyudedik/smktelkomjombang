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
                <h1 class="text-2xl font-bold text-slate-900">Status Sync User</h1>
                <p class="text-slate-600 mt-1">PIN: <?php echo e($identity->device_pin); ?></p>
            </div>
            <a href="<?php echo e(route('admin.absensi.users.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Total Command</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($status['total_commands']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1"><?php echo e($status['pending']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Sent</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($status['sent']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Done</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?php echo e($status['done']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm font-medium text-slate-600">Failed</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e($status['failed']); ?></p>
            </div>
        </div>

        <!-- Daftar Command -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Riwayat Command</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Command</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Sent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Executed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Result</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $status['commands']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($cmd['id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($cmd['kind']); ?></td>
                                <td class="px-6 py-4 text-sm text-slate-700 font-mono"><?php echo e($cmd['command']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if($cmd['status'] === 'pending'): ?>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                                    <?php elseif($cmd['status'] === 'sent'): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Sent</span>
                                    <?php elseif($cmd['status'] === 'done'): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Done</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($cmd['sent_at'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($cmd['executed_at'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($cmd['result_code'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-600">Belum ada command</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <a href="<?php echo e(route('admin.absensi.users.index')); ?>" class="btn btn-secondary">Kembali</a>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\users\sync-status.blade.php ENDPATH**/ ?>