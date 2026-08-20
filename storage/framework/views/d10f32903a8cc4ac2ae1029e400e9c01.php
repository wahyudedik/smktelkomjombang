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
                <h1 class="text-2xl font-bold text-slate-900">Detail Kegiatan</h1>
                <p class="text-slate-600 mt-1"><?php echo e($event->title); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.edit')): ?>
                    <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.events.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-body">

                <!-- Gambar -->
                <?php if($event->image): ?>
                    <div class="mb-6">
                        <img src="<?php echo e(Storage::url($event->image)); ?>" alt="<?php echo e($event->title); ?>"
                            class="w-full max-h-80 object-cover rounded-xl border border-slate-200">
                    </div>
                <?php endif; ?>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="font-semibold text-slate-800">
                            <?php echo e(\Carbon\Carbon::parse($event->date)->translatedFormat('d F Y')); ?>

                        </p>
                        <p class="text-sm text-slate-500"><?php echo e(\Carbon\Carbon::parse($event->date)->format('H:i')); ?> WIB</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Kategori</p>
                        <p class="font-semibold text-slate-800"><?php echo e($event->category ?? '-'); ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Status</p>
                        <?php if($event->status === 'active'): ?>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                        <?php elseif($event->status === 'inactive'): ?>
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Nonaktif</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Judul -->
                <h2 class="text-2xl font-bold text-slate-900 mb-4"><?php echo e($event->title); ?></h2>

                <!-- Deskripsi -->
                <?php if($event->description): ?>
                    <div class="prose max-w-none text-slate-700 mb-6">
                        <?php echo nl2br(e($event->description)); ?>

                    </div>
                <?php else: ?>
                    <p class="text-slate-400 italic mb-6">Tidak ada deskripsi.</p>
                <?php endif; ?>

                <!-- Meta -->
                <div class="border-t border-slate-200 pt-4 flex items-center justify-between text-xs text-slate-400">
                    <span>Dibuat: <?php echo e($event->created_at->format('d M Y H:i')); ?></span>
                    <span>Diperbarui: <?php echo e($event->updated_at->format('d M Y H:i')); ?></span>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between mt-4">
            <a href="<?php echo e(route('admin.events.index')); ?>" class="btn btn-secondary">
                ← Kembali ke Daftar
            </a>
            <div class="flex space-x-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.edit')): ?>
                    <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit Kegiatan
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.delete')): ?>
                    <form action="<?php echo e(route('admin.events.destroy', $event)); ?>" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\events\show.blade.php ENDPATH**/ ?>