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
                <h1 class="text-2xl font-bold text-slate-900">Report Bulanan</h1>
                <p class="text-slate-600 mt-1"><?php echo e($month->translatedFormat('F Y')); ?></p>
            </div>
            <a href="<?php echo e(route('admin.absensi.report.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Pengguna</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($stats['total_users']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Rata-rata Kehadiran</p>
                <p class="text-2xl font-bold mt-1 <?php echo e($stats['avg_attendance'] >= 80 ? 'text-green-600' : ($stats['avg_attendance'] >= 60 ? 'text-yellow-600' : 'text-red-600')); ?>">
                    <?php echo e($stats['avg_attendance']); ?>%
                </p>
            </div>
        </div>

        <!-- Tabel Rekap -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Total Hari</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Tidak Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $persentase = $item['persentase'];
                                $badgeColor = $persentase >= 80 ? 'green' : ($persentase >= 60 ? 'yellow' : 'red');
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($item['kind']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo e($item['nama']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($item['pin']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 text-center"><?php echo e($item['total_days']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-center font-medium"><?php echo e($item['hadir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-center font-medium"><?php echo e($item['tidak_hadir']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 bg-<?php echo e($badgeColor); ?>-100 text-<?php echo e($badgeColor); ?>-800 rounded-full text-xs font-medium">
                                        <?php echo e($persentase); ?>%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?php echo e(route('admin.absensi.report.user-detail', $item['identity'])); ?>?start_date=<?php echo e($month->startOfMonth()->toDateString()); ?>&end_date=<?php echo e($month->endOfMonth()->toDateString()); ?>"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\report\monthly.blade.php ENDPATH**/ ?>