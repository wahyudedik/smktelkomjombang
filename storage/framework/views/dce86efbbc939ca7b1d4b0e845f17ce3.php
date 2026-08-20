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
                <h1 class="text-2xl font-bold text-slate-900">Detail Izin / Sakit</h1>
                <p class="text-slate-600 mt-1"><?php echo e($excuse->nama); ?> — <?php echo e($excuse->date->format('d F Y')); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.absensi.excuses.index')); ?>" class="btn btn-secondary">Kembali</a>
                <?php if($excuse->status === 'pending'): ?>
                    <a href="<?php echo e(route('admin.absensi.excuses.edit', $excuse)); ?>" class="btn btn-primary">Edit</a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Notifikasi -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Detail Card -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi Izin</h2>
                    <span class="px-3 py-1 bg-<?php echo e($excuse->status_color); ?>-100 text-<?php echo e($excuse->status_color); ?>-800 rounded-full text-sm font-medium">
                        <?php echo e($excuse->status_label); ?>

                    </span>
                </div>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Nama</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium"><?php echo e($excuse->nama); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Jenis</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium"><?php echo e($excuse->type_label); ?></span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Tanggal</dt>
                        <dd class="mt-1 text-sm text-slate-900"><?php echo e($excuse->date->format('d F Y')); ?> (<?php echo e($excuse->date->translatedFormat('l')); ?>)</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Dibuat Oleh</dt>
                        <dd class="mt-1 text-sm text-slate-900"><?php echo e($excuse->creator?->name ?? '-'); ?></dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-slate-500">Alasan</dt>
                        <dd class="mt-1 text-sm text-slate-900"><?php echo e($excuse->reason); ?></dd>
                    </div>
                    <?php if($excuse->attachment_path): ?>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Lampiran</dt>
                            <dd class="mt-1">
                                <a href="<?php echo e(Storage::disk('public')->url($excuse->attachment_path)); ?>" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    📎 Lihat Lampiran
                                </a>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Info Persetujuan -->
        <?php if($excuse->status !== 'pending'): ?>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi Persetujuan</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Disetujui Oleh</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?php echo e($excuse->approvedBy?->name ?? '-'); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Waktu</dt>
                            <dd class="mt-1 text-sm text-slate-900"><?php echo e($excuse->approved_at?->format('d F Y, H:i') ?? '-'); ?></dd>
                        </div>
                        <?php if($excuse->rejection_reason): ?>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Alasan Penolakan</dt>
                                <dd class="mt-1 text-sm text-red-600"><?php echo e($excuse->rejection_reason); ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <?php if($excuse->status === 'pending'): ?>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Aksi</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                        <!-- Approve -->
                        <form method="POST" action="<?php echo e(route('admin.absensi.excuses.approve', $excuse)); ?>" class="flex-shrink-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white"
                                onclick="return confirm('Setujui izin ini?')">
                                ✅ Setujui
                            </button>
                        </form>

                        <!-- Reject -->
                        <div x-data="{ showReject: false }" class="flex-1">
                            <button @click="showReject = !showReject" class="btn bg-red-600 hover:bg-red-700 text-white">
                                ❌ Tolak
                            </button>
                            <div x-show="showReject" x-cloak class="mt-4">
                                <form method="POST" action="<?php echo e(route('admin.absensi.excuses.reject', $excuse)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-slate-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-slate-300 text-sm"
                                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white text-sm"
                                            onclick="return confirm('Tolak izin ini?')">Konfirmasi Tolak</button>
                                        <button type="button" @click="showReject = false" class="btn btn-secondary text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\excuses\show.blade.php ENDPATH**/ ?>