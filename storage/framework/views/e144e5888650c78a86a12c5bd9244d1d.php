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
                <h1 class="text-2xl font-bold text-slate-900">Detail Berita</h1>
                <p class="text-slate-600 mt-1"><?php echo e(Str::limit($berita->title, 60)); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.edit')): ?>
                    <a href="<?php echo e(route('admin.berita.edit', $berita)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-body">

                <?php if($berita->featured_image): ?>
                    <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                        class="w-full h-64 object-cover rounded-xl mb-6 border border-slate-200">
                <?php endif; ?>

                <!-- Meta -->
                <div class="flex flex-wrap gap-3 mb-4">
                    <?php if($berita->status === 'published'): ?>
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Publish</span>
                    <?php elseif($berita->status === 'draft'): ?>
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                    <?php endif; ?>
                    <?php if($berita->is_featured): ?>
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                            <i class="fas fa-star mr-1"></i>Unggulan
                        </span>
                    <?php endif; ?>
                    <?php if($berita->published_at): ?>
                        <span class="text-xs text-slate-500 flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <?php echo e($berita->published_at->format('d M Y H:i')); ?>

                        </span>
                    <?php endif; ?>
                    <span class="text-xs text-slate-500 flex items-center">
                        <i class="fas fa-user mr-1"></i><?php echo e($berita->user->name ?? '-'); ?>

                    </span>
                </div>

                <h2 class="text-2xl font-bold text-slate-900 mb-4"><?php echo e($berita->title); ?></h2>

                <?php if($berita->excerpt): ?>
                    <p class="text-slate-500 italic border-l-4 border-blue-400 pl-4 mb-6"><?php echo e($berita->excerpt); ?></p>
                <?php endif; ?>

                <div class="prose max-w-none text-slate-700 leading-relaxed">
                    <?php echo $berita->content; ?>

                </div>

                <div class="border-t border-slate-200 pt-4 mt-6 flex items-center justify-between text-xs text-slate-400">
                    <span>Dibuat: <?php echo e($berita->created_at->format('d M Y H:i')); ?></span>
                    <span>Diperbarui: <?php echo e($berita->updated_at->format('d M Y H:i')); ?></span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-secondary">← Kembali ke Daftar</a>
            <div class="flex space-x-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.edit')): ?>
                    <a href="<?php echo e(route('admin.berita.edit', $berita)); ?>" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit Berita
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('berita.delete')): ?>
                    <form action="<?php echo e(route('admin.berita.destroy', $berita)); ?>" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/berita/show.blade.php ENDPATH**/ ?>