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
                <h1 class="text-2xl font-bold text-slate-900">Report Absensi</h1>
                <p class="text-slate-600 mt-1">Analisis dan laporan absensi per periode</p>
            </div>
            <a href="<?php echo e(route('admin.absensi.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Report Harian -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Harian</h2>
                <form method="GET" action="<?php echo e(route('admin.absensi.report.daily')); ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" name="date" value="<?php echo e(now()->toDateString()); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Mingguan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Mingguan</h2>
                <form method="GET" action="<?php echo e(route('admin.absensi.report.weekly')); ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="<?php echo e(now()->subDays(7)->toDateString()); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="<?php echo e(now()->toDateString()); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Bulanan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Bulanan</h2>
                <form method="GET" action="<?php echo e(route('admin.absensi.report.monthly')); ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Bulan</label>
                        <input type="month" name="month" value="<?php echo e(now()->format('Y-m')); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Keterlambatan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Keterlambatan</h2>
                <form method="GET" action="<?php echo e(route('admin.absensi.report.latecomers')); ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="<?php echo e(now()->subDays(30)->toDateString()); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="<?php echo e(now()->toDateString()); ?>" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Batas Jam (Contoh: 07:30)</label>
                        <input type="time" name="threshold_time" value="07:30" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/attendance/report/index.blade.php ENDPATH**/ ?>