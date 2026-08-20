<?php $__env->startSection('content'); ?>

    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginal9993066b1b38333cdd5fd75ea58dea13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.breadcrumb','data' => ['title' => Str::limit($berita->title, 55),'items' => [
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
        ['label' => 'Detail', 'url' => '#'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Str::limit($berita->title, 55)),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
        ['label' => 'Detail', 'url' => '#'],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $attributes = $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $component = $__componentOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>

    <!-- Blog Single -->
    <section class="blog-area py-120">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <article class="blog-single">

                        <?php if($berita->featured_image): ?>
                            <div class="blog-featured-img mb-4">
                                <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                                    style="width: 100%; border-radius: 12px; max-height: 450px; object-fit: cover;">
                            </div>
                        <?php endif; ?>

                        <!-- Meta -->
                        <div class="blog-meta mb-4" style="padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                            <div class="d-flex flex-wrap gap-3">
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-user me-1" style="color: #1a5632;"></i>
                                    <?php echo e($berita->user->name ?? 'Admin'); ?>

                                </span>
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-calendar me-1" style="color: #1a5632;"></i>
                                    <?php echo e($berita->published_at?->translatedFormat('d F Y')); ?>

                                </span>
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-tag me-1" style="color: #1a5632;"></i>
                                    Berita
                                </span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2
                            style="font-size: 1.8rem; font-weight: 700; line-height: 1.4; color: #1a1a2e; margin-bottom: 20px;">
                            <?php echo e($berita->title); ?>

                        </h2>

                        <!-- Content -->
                        <div style="color: #333; font-size: 1rem; line-height: 1.8;" class="blog-content-body">
                            <?php echo $berita->content; ?>

                        </div>

                        <!-- Share -->
                        <div class="mt-5 pt-4" style="border-top: 2px solid #f0f0f0;">
                            <h5 style="font-weight: 700; color: #1a5632; margin-bottom: 12px;">Bagikan:</h5>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>"
                                    target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="fab fa-facebook-f me-1"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($berita->title)); ?>"
                                    target="_blank" class="btn btn-outline-info btn-sm rounded-pill">
                                    <i class="fab fa-twitter me-1"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text=<?php echo e(urlencode($berita->title . ' - ' . request()->url())); ?>"
                                    target="_blank" class="btn btn-outline-success btn-sm rounded-pill">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="<?php echo e(route('berita.public.index')); ?>" class="btn btn-outline-success w-100 rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Berita
                        </a>
                    </div>

                    <!-- Related Berita -->
                    <?php if(isset($related) && $related->count() > 0): ?>
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h5 style="font-weight: 700; color: #1a5632; margin-bottom: 20px;">Berita Terkait</h5>
                                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex mb-3 pb-3 <?php echo e($loop->last ? 'border-0 mb-0 pb-0' : ''); ?>"
                                        style="border-bottom: 1px solid #f0f0f0;">
                                        <?php if($rel->featured_image): ?>
                                            <div
                                                style="width: 80px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0; margin-right: 12px;">
                                                <img src="<?php echo e(Storage::url($rel->featured_image)); ?>"
                                                    alt="<?php echo e($rel->title); ?>"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?php echo e(route('berita.public.show', $rel->slug)); ?>"
                                                class="text-decoration-none"
                                                style="color: #1a1a2e; font-weight: 600; font-size: 0.9rem;">
                                                <?php echo e(Str::limit($rel->title, 50)); ?>

                                            </a>
                                            <br>
                                            <small style="color: #999;">
                                                <i
                                                    class="fas fa-calendar me-1"></i><?php echo e($rel->published_at?->format('d M Y')); ?>

                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.maudu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\berita\public\show-maudu.blade.php ENDPATH**/ ?>