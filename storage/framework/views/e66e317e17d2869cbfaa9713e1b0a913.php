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
                <h1 class="text-2xl font-bold text-slate-900">Report Mingguan</h1>
                <p class="text-slate-600 mt-1"><?php echo e($start->format('d F Y')); ?> — <?php echo e($end->format('d F Y')); ?></p>
            </div>
            <a href="<?php echo e(route('admin.absensi.report.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($stats['total_days']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Record</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e($stats['total_records']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Rata-rata/Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($stats['avg_per_day']); ?></p>
            </div>
        </div>

        <!-- Data per Hari -->
        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateKey => $dailyRecords): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-4">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-900">
                        <?php echo e(\Carbon\Carbon::parse($dateKey)->translatedFormat('l, d F Y')); ?>

                        <span class="ml-2 text-xs font-normal text-slate-500">(<?php echo e($dailyRecords->count()); ?> record)</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">PIN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__currentLoopData = $dailyRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $identity = $attendance->identity;
                                    $nama = $identity->user?->name ?? ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                                    $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                                    $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                                    $durasi = '-';
                                    if ($attendance->first_in_at && $attendance->last_out_at) {
                                        $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                                        $hours = intdiv($diff, 60);
                                        $minutes = $diff % 60;
                                        $durasi = "{$hours}j {$minutes}m";
                                    }
                                ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($identity->kind); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo e($nama); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($identity->device_pin); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($firstIn); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($lastOut); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($durasi); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php if($attendance->status === 'present'): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Hadir</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Tidak Hadir</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                <p class="text-sm text-slate-600">Tidak ada data absensi untuk periode ini</p>
            </div>
        <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\report\weekly.blade.php ENDPATH**/ ?>