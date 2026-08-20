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
                <h1 class="text-2xl font-bold text-slate-900">Export Rekap Absensi</h1>
                <p class="text-slate-600 mt-1">Export ke Excel atau PDF untuk berbagai format</p>
            </div>
            <a href="<?php echo e(route('admin.absensi.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export Harian -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Harian</h2>
                <form method="POST" action="<?php echo e(route('admin.absensi.export.daily')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" name="date" value="<?php echo e(now()->toDateString()); ?>"
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.absensi.export.pdf.daily')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="date" value="<?php echo e(now()->toDateString()); ?>">
                    <button type="submit" class="btn btn-secondary w-full">Download PDF</button>
                </form>
            </div>

            <!-- Export Periode -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Periode</h2>
                <form id="periodeForm" method="POST" action="<?php echo e(route('admin.absensi.export.period')); ?>">
                    <?php echo csrf_field(); ?>
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
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Grup Berdasarkan</label>
                        <select name="group_by" class="mt-1 block w-full rounded-md border-slate-300">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
                <form id="periodePdfForm" method="POST" action="<?php echo e(route('admin.absensi.export.pdf.period')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e(now()->subDays(7)->toDateString()); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e(now()->toDateString()); ?>">
                    <input type="hidden" name="group_by" value="daily">
                    <button type="submit" class="btn btn-secondary w-full">Download PDF</button>
                </form>
            </div>

            <!-- Export Summary -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Summary</h2>
                <form id="summaryForm" method="POST" action="<?php echo e(route('admin.absensi.export.summary')); ?>">
                    <?php echo csrf_field(); ?>
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
                    <p class="text-sm text-slate-600 mb-4">Ringkasan per user dengan statistik kehadiran</p>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
                <form id="summaryPdfForm" method="POST" action="<?php echo e(route('admin.absensi.export.pdf.summary')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e(now()->subDays(30)->toDateString()); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e(now()->toDateString()); ?>">
                    <button type="submit" class="btn btn-secondary w-full">Download PDF</button>
                </form>
            </div>

            <!-- Export User Detail -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export User Detail</h2>
                <p class="text-sm text-slate-600 mb-4">Pilih user di halaman User Management, kemudian klik "Export Detail"</p>
                <a href="<?php echo e(route('admin.absensi.users.index')); ?>" class="btn btn-secondary w-full">Ke User Management</a>
            </div>
        </div>
    </div>

    <script>
        // Sync date fields between Excel and PDF forms for Periode
        document.querySelectorAll('#periodeForm input[name="start_date"], #periodeForm input[name="end_date"], #periodeForm select[name="group_by"]').forEach(el => {
            el.addEventListener('change', function() {
                const pdfForm = document.getElementById('periodePdfForm');
                const target = pdfForm.querySelector('[name="' + this.name + '"]');
                if (target) target.value = this.value;
            });
        });

        // Sync date fields between Excel and PDF forms for Summary
        document.querySelectorAll('#summaryForm input[name="start_date"], #summaryForm input[name="end_date"]').forEach(el => {
            el.addEventListener('change', function() {
                const pdfForm = document.getElementById('summaryPdfForm');
                const target = pdfForm.querySelector('[name="' + this.name + '"]');
                if (target) target.value = this.value;
            });
        });
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\export\index.blade.php ENDPATH**/ ?>