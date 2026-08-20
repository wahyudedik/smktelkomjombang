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
                <h1 class="text-2xl font-bold text-slate-900">Detail Absensi — <?php echo e($nama); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e($start->format('d F Y')); ?> — <?php echo e($end->format('d F Y')); ?> | PIN: <?php echo e($identity->device_pin); ?></p>
            </div>
            <a href="<?php echo e(route('admin.absensi.report.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Info Pengguna -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center">
                        <span class="text-2xl font-bold text-slate-500"><?php echo e(substr($nama, 0, 1)); ?></span>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900"><?php echo e($nama); ?></h2>
                    <p class="text-sm text-slate-600">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2"><?php echo e($identity->kind); ?></span>
                        PIN: <?php echo e($identity->device_pin); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Total Hari</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($stats['total_days']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Hadir</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?php echo e($stats['hadir']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Tidak Hadir</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e($stats['tidak_hadir']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <p class="text-sm font-medium text-slate-600">Persentase</p>
                <p class="text-2xl font-bold mt-1 <?php echo e($stats['persentase'] >= 80 ? 'text-green-600' : ($stats['persentase'] >= 60 ? 'text-yellow-600' : 'text-red-600')); ?>">
                    <?php echo e($stats['persentase']); ?>%
                </p>
            </div>
        </div>

        <!-- Tabel Detail Absensi -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-sm font-semibold text-slate-900">Riwayat Absensi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                                $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                                $durasi = '-';
                                if ($attendance->first_in_at && $attendance->last_out_at) {
                                    $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                                    $hours = intdiv($diff, 60);
                                    $minutes = $diff % 60;
                                    $durasi = "{$hours}j {$minutes}m";
                                }

                                // Cek keterlambatan
                                $lateThreshold = \Carbon\Carbon::parse(config('attendance.late_threshold', '07:30'));
                                $isLate = false;
                                if ($attendance->first_in_at) {
                                    $checkTime = $attendance->first_in_at->copy()->setDate(2000, 1, 1);
                                    $isLate = $checkTime->gt($lateThreshold);
                                }
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($attendance->date->format('d/m/Y')); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700"><?php echo e($attendance->date->translatedFormat('l')); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm <?php echo e($isLate ? 'text-red-600 font-medium' : 'text-slate-700'); ?>">
                                    <?php echo e($firstIn); ?>

                                    <?php if($isLate): ?>
                                        <span class="text-xs text-red-500 ml-1">(terlambat)</span>
                                    <?php endif; ?>
                                </td>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-600">Tidak ada data absensi untuk periode ini</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">
                <?php echo e($attendances->withQueryString()->links()); ?>

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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\report\user-detail.blade.php ENDPATH**/ ?>