

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb Start -->
<?php if (isset($component)) { $__componentOriginaldc280fb558d35e1b3a487e6c3eae7ecd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc280fb558d35e1b3a487e6c3eae7ecd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.breadcrumb','data' => ['title' => Str::limit($berita->title, 55),'image' => 'assets/images/breadcrumbs/2.jpg','items' => [
        ['label' => 'Home',   'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
        ['label' => 'Detail', 'url' => '#'],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Str::limit($berita->title, 55)),'image' => 'assets/images/breadcrumbs/2.jpg','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Home',   'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
        ['label' => 'Detail', 'url' => '#'],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldc280fb558d35e1b3a487e6c3eae7ecd)): ?>
<?php $attributes = $__attributesOriginaldc280fb558d35e1b3a487e6c3eae7ecd; ?>
<?php unset($__attributesOriginaldc280fb558d35e1b3a487e6c3eae7ecd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldc280fb558d35e1b3a487e6c3eae7ecd)): ?>
<?php $component = $__componentOriginaldc280fb558d35e1b3a487e6c3eae7ecd; ?>
<?php unset($__componentOriginaldc280fb558d35e1b3a487e6c3eae7ecd); ?>
<?php endif; ?>
<!-- Breadcrumb End -->

<!-- Blog Single Start -->
<div class="rs-blog-single pt-100 pb-100 md-pt-70 md-pb-70">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 pr-50 md-pr-15">
                <div class="blog-details">

                    <?php if($berita->featured_image): ?>
                        <div class="bs-img mb-35">
                            <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                                style="width: 100%; border-radius: 10px; max-height: 450px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <div class="blog-full">
                        <!-- Meta -->
                        <ul class="single-post-meta mb-25" style="list-style: none; padding: 0; display: flex; gap: 20px; flex-wrap: wrap; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px;">
                            <li style="color: #666; font-size: 14px;">
                                <i class="fa fa-user-o mr-1" style="color: #1c3988;"></i>
                                <?php echo e($berita->user->name ?? 'Admin'); ?>

                            </li>
                            <li style="color: #666; font-size: 14px;">
                                <i class="fa fa-calendar mr-1" style="color: #1c3988;"></i>
                                <?php echo e($berita->published_at?->translatedFormat('d F Y')); ?>

                            </li>
                            <li style="color: #666; font-size: 14px;">
                                <i class="fa fa-tag mr-1" style="color: #1c3988;"></i>
                                Berita
                            </li>
                        </ul>

                        <h2 class="title mb-20" style="font-size: 1.8rem; font-weight: 700; line-height: 1.4; color: #1a1a2e;">
                            <?php echo e($berita->title); ?>

                        </h2>

                        <?php if($berita->excerpt): ?>
                            <p style="font-size: 1.05rem; color: #555; font-style: italic; border-left: 4px solid #1c3988; padding-left: 15px; margin-bottom: 25px; line-height: 1.7;">
                                <?php echo e($berita->excerpt); ?>

                            </p>
                        <?php endif; ?>

                        <div class="blog-content-body" style="font-size: 1rem; line-height: 1.9; color: #444;">
                            <?php echo $berita->content; ?>

                        </div>

                        <!-- Share -->
                        <div class="bs-tag-social mt-40 pt-25" style="border-top: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                            <div>
                                <span style="font-weight: 600; color: #333; margin-right: 10px;">Bagikan:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>"
                                    target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #3b5998; color: white; border-radius: 50%; margin-right: 5px; text-decoration: none;">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a href="https://wa.me/?text=<?php echo e(urlencode($berita->title . ' - ' . request()->url())); ?>"
                                    target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #25d366; color: white; border-radius: 50%; margin-right: 5px; text-decoration: none;">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($berita->title)); ?>"
                                    target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #1da1f2; color: white; border-radius: 50%; text-decoration: none;">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </div>
                            <a href="<?php echo e(route('berita.public.index')); ?>" class="readon2" style="font-size: 13px; padding: 8px 20px;">
                                ← Kembali ke Berita
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="widget-area">

                    <!-- Related Berita -->
                    <?php if($related->count() > 0): ?>
                        <div class="widget mb-40">
                            <h3 class="widget-title" style="font-size: 1.1rem; font-weight: 700; color: #1a1a2e; border-bottom: 2px solid #1c3988; padding-bottom: 10px; margin-bottom: 20px;">
                                Berita Terkait
                            </h3>
                            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="display: flex; gap: 15px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f0f0f0;">
                                    <div style="flex: 0 0 80px;">
                                        <?php if($item->featured_image): ?>
                                            <img src="<?php echo e(Storage::url($item->featured_image)); ?>" alt="<?php echo e($item->title); ?>"
                                                style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <div style="width: 80px; height: 60px; background: #e8eaf6; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-newspaper-o" style="color: #9fa8da;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div style="flex: 1;">
                                        <h5 style="font-size: 0.85rem; font-weight: 600; line-height: 1.4; margin-bottom: 5px;">
                                            <a href="<?php echo e(route('berita.public.show', $item->slug)); ?>" style="color: #1a1a2e; text-decoration: none;">
                                                <?php echo e(Str::limit($item->title, 60)); ?>

                                            </a>
                                        </h5>
                                        <span style="font-size: 12px; color: #999;">
                                            <i class="fa fa-calendar mr-1"></i>
                                            <?php echo e($item->published_at?->format('d M Y')); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Back to list -->
                    <div class="widget">
                        <a href="<?php echo e(route('berita.public.index')); ?>" class="readon2" style="display: block; text-align: center;">
                            Lihat Semua Berita
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blog Single End -->

<?php $__env->startPush('styles'); ?>
<style>
    /* Rich content styles for TinyMCE output */
    .blog-content-body h1,
    .blog-content-body h2,
    .blog-content-body h3,
    .blog-content-body h4,
    .blog-content-body h5,
    .blog-content-body h6 {
        font-weight: 700;
        margin: 1.8rem 0 0.8rem;
        color: #1a1a2e;
        line-height: 1.3;
    }
    .blog-content-body h2 { font-size: 1.5rem; }
    .blog-content-body h3 { font-size: 1.25rem; }
    .blog-content-body p  { margin-bottom: 1.2rem; }
    .blog-content-body ul,
    .blog-content-body ol {
        padding-left: 1.5rem;
        margin-bottom: 1.2rem;
    }
    .blog-content-body li { margin-bottom: 0.4rem; }
    .blog-content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .blog-content-body blockquote {
        border-left: 4px solid #1c3988;
        padding: 12px 20px;
        margin: 1.5rem 0;
        background: #f0f4ff;
        color: #555;
        font-style: italic;
        border-radius: 0 6px 6px 0;
    }
    .blog-content-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.95rem;
    }
    .blog-content-body td,
    .blog-content-body th {
        border: 1px solid #ddd;
        padding: 10px 14px;
        text-align: left;
    }
    .blog-content-body th {
        background: #f5f7ff;
        font-weight: 600;
        color: #1a1a2e;
    }
    .blog-content-body tr:nth-child(even) td { background: #fafafa; }
    .blog-content-body a {
        color: #1c3988;
        text-decoration: underline;
    }
    .blog-content-body a:hover { color: #ff5421; }
    .blog-content-body pre {
        background: #1e1e2e;
        color: #cdd6f4;
        padding: 16px;
        border-radius: 8px;
        overflow-x: auto;
        font-size: 0.9rem;
        margin: 1.5rem 0;
    }
    .blog-content-body code {
        background: #f0f4ff;
        color: #1c3988;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }
    .blog-content-body pre code {
        background: none;
        color: inherit;
        padding: 0;
    }
    .blog-content-body hr {
        border: none;
        border-top: 2px solid #eee;
        margin: 2rem 0;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.telkom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views/berita/public/show.blade.php ENDPATH**/ ?>