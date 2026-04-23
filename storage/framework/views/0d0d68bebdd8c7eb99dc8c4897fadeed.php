

<?php $__env->startSection('content'); ?>

<!-- Breadcrumb Start -->
<?php if (isset($component)) { $__componentOriginaldc280fb558d35e1b3a487e6c3eae7ecd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc280fb558d35e1b3a487e6c3eae7ecd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.breadcrumb','data' => ['title' => 'Berita & Artikel','image' => 'assets/images/breadcrumbs/1.jpg','items' => [
        ['label' => 'Home',   'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Berita & Artikel','image' => 'assets/images/breadcrumbs/1.jpg','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Home',   'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
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

<!-- Blog Section Start -->
<div class="rs-blog style1 pt-100 pb-100 md-pt-70 md-pb-70">
    <div class="container">

        <!-- Search Bar -->
        <div class="row justify-content-center mb-50">
            <div class="col-lg-6 col-md-8">
                <form action="<?php echo e(route('berita.public.index')); ?>" method="GET">
                    <div style="display: flex; border: 2px solid #e0e0e0; border-radius: 5px; overflow: hidden;">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Cari berita..."
                            style="flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 15px;">
                        <button type="submit" class="readon2"
                            style="border-radius: 0; padding: 12px 25px; margin: 0;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if(request('search')): ?>
            <div class="row mb-30">
                <div class="col-12">
                    <p style="color: #666; font-size: 15px;">
                        Hasil pencarian untuk: <strong>"<?php echo e(request('search')); ?>"</strong>
                        &nbsp;—&nbsp;
                        <a href="<?php echo e(route('berita.public.index')); ?>" style="color: #1c3988;">Lihat semua berita</a>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!request('search') && $featured): ?>
            <!-- Featured Berita -->
            <div class="row mb-60">
                <div class="col-12">
                    <div class="blog-item" style="display: flex; flex-wrap: wrap; background: #f8f9fa; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                        <div style="flex: 0 0 45%; max-width: 45%;">
                            <?php if($featured->featured_image): ?>
                                <img src="<?php echo e(Storage::url($featured->featured_image)); ?>" alt="<?php echo e($featured->title); ?>"
                                    style="width: 100%; height: 320px; object-fit: cover; display: block;">
                            <?php else: ?>
                                <div style="width: 100%; height: 320px; background: linear-gradient(135deg, #1c3988 0%, #3a5fc8 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-newspaper-o" style="font-size: 70px; color: rgba(255,255,255,0.3);"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="flex: 1; padding: 40px; display: flex; flex-direction: column; justify-content: center; min-width: 280px;">
                            <span class="sub-title primary" style="font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 15px; display: block;">
                                ★ BERITA UNGGULAN
                            </span>
                            <h2 style="font-size: 1.6rem; font-weight: 700; line-height: 1.4; margin-bottom: 15px; color: #1a1a2e;">
                                <a href="<?php echo e(route('berita.public.show', $featured->slug)); ?>"
                                    style="color: inherit; text-decoration: none;">
                                    <?php echo e($featured->title); ?>

                                </a>
                            </h2>
                            <?php if($featured->excerpt): ?>
                                <p style="color: #666; line-height: 1.7; margin-bottom: 20px; font-size: 15px;">
                                    <?php echo e($featured->excerpt); ?>

                                </p>
                            <?php endif; ?>
                            <ul class="blog-meta" style="margin-bottom: 25px;">
                                <li><i class="fa fa-user-o"></i> <?php echo e($featured->user->name ?? 'Admin'); ?></li>
                                <li><i class="fa fa-calendar"></i> <?php echo e($featured->published_at?->format('d M Y')); ?></li>
                            </ul>
                            <div>
                                <a href="<?php echo e(route('berita.public.show', $featured->slug)); ?>" class="readon2">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Berita Grid -->
        <?php if($beritas->count() > 0): ?>
            <div class="row">
                <?php $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="blog-item">
                            <div class="image-part">
                                <?php if($berita->featured_image): ?>
                                    <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets_telkom/assets/images/blog/style2/' . (($loop->index % 3) + 1) . '.jpg')); ?>"
                                        alt="<?php echo e($berita->title); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="blog-content new-style">
                                <ul class="blog-meta">
                                    <li><i class="fa fa-user-o"></i> <?php echo e($berita->user->name ?? 'Admin'); ?></li>
                                    <li><i class="fa fa-calendar"></i> <?php echo e($berita->published_at?->format('d M Y')); ?></li>
                                </ul>
                                <h3 class="title">
                                    <a href="<?php echo e(route('berita.public.show', $berita->slug)); ?>">
                                        <?php echo e($berita->title); ?>

                                    </a>
                                </h3>
                                <div class="desc">
                                    <?php echo e(Str::limit($berita->excerpt ?: strip_tags($berita->content), 100)); ?>

                                </div>
                                <ul class="blog-bottom">
                                    <li class="btn-part">
                                        <a class="readon-arrow" href="<?php echo e(route('berita.public.show', $berita->slug)); ?>">
                                            Baca Selengkapnya
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($beritas->hasPages()): ?>
                <div class="row mt-40">
                    <div class="col-12">
                        <div class="pagination-area text-center">
                            <nav>
                                <ul class="pagination-part" style="list-style: none; padding: 0; display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                                    
                                    <?php if($beritas->onFirstPage()): ?>
                                        <li style="opacity: 0.4; cursor: not-allowed;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #999;">
                                                <i class="fa fa-angle-left"></i>
                                            </span>
                                        </li>
                                    <?php else: ?>
                                        <li>
                                            <a href="<?php echo e($beritas->previousPageUrl()); ?>"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; transition: all 0.3s;">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php $__currentLoopData = $beritas->getUrlRange(1, $beritas->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($page == $beritas->currentPage()): ?>
                                            <li>
                                                <span class="readon2" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; padding: 0; font-size: 14px;">
                                                    <?php echo e($page); ?>

                                                </span>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a href="<?php echo e($url); ?>"
                                                    style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; font-size: 14px; transition: all 0.3s;">
                                                    <?php echo e($page); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    
                                    <?php if($beritas->hasMorePages()): ?>
                                        <li>
                                            <a href="<?php echo e($beritas->nextPageUrl()); ?>"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; transition: all 0.3s;">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li style="opacity: 0.4; cursor: not-allowed;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #999;">
                                                <i class="fa fa-angle-right"></i>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <p style="color: #999; font-size: 13px; margin-top: 15px;">
                                Menampilkan <?php echo e($beritas->firstItem()); ?>–<?php echo e($beritas->lastItem()); ?> dari <?php echo e($beritas->total()); ?> berita
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center" style="padding: 60px 20px;">
                <i class="fa fa-newspaper-o" style="font-size: 70px; color: #ddd; display: block; margin-bottom: 20px;"></i>
                <h4 style="color: #999; margin-bottom: 15px;">
                    <?php if(request('search')): ?>
                        Tidak ada berita untuk "<?php echo e(request('search')); ?>"
                    <?php else: ?>
                        Belum ada berita yang dipublikasikan.
                    <?php endif; ?>
                </h4>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('berita.public.index')); ?>" class="readon2" style="display: inline-block; margin-top: 10px;">
                        Lihat Semua Berita
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<!-- Blog Section End -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.telkom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views/berita/public/index.blade.php ENDPATH**/ ?>