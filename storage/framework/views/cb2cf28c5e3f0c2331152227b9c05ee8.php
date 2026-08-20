<?php $__env->startSection('content'); ?>

    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginal9993066b1b38333cdd5fd75ea58dea13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.breadcrumb','data' => ['title' => 'Berita & Artikel','items' => [
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Berita & Artikel','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
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

    <!-- Berita Area -->
    <section class="blog-area py-120">
        <div class="container">

            <!-- Search Bar -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 col-md-8">
                    <form action="<?php echo e(route('berita.public.index')); ?>" method="GET">
                        <div
                            style="display: flex; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #fff;">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari berita..."
                                style="flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 15px;">
                            <button type="submit" class="btn btn-primary"
                                style="border-radius: 0; padding: 12px 25px; margin: 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(request('search')): ?>
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <p style="color: #666; font-size: 15px;">
                            Hasil pencarian untuk: <strong>"<?php echo e(request('search')); ?>"</strong>
                            &nbsp;—&nbsp;
                            <a href="<?php echo e(route('berita.public.index')); ?>" style="color: #1a5632;">Lihat semua berita</a>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Featured Berita -->
            <?php if(!request('search') && $featured): ?>
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <?php if($featured->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($featured->featured_image)); ?>"
                                            alt="<?php echo e($featured->title); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover; min-height: 250px;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; min-height: 250px; background: linear-gradient(135deg, #1a5632, #2d8a5e); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper"
                                                style="font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body p-4 d-flex flex-column justify-content-center"
                                        style="height: 100%;">
                                        <span class="badge bg-success mb-2" style="width: fit-content;">Unggulan</span>
                                        <h3 class="card-title" style="font-weight: 700;">
                                            <a href="<?php echo e(route('berita.public.show', $featured->slug)); ?>"
                                                class="text-decoration-none" style="color: #1a1a2e;">
                                                <?php echo e($featured->title); ?>

                                            </a>
                                        </h3>
                                        <p class="card-text" style="color: #666;">
                                            <?php echo e(Str::limit($featured->excerpt ?? strip_tags($featured->content ?? ''), 200)); ?>

                                        </p>
                                        <div class="mt-auto">
                                            <small style="color: #999;">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo e($featured->published_at?->format('d F Y')); ?>

                                                <span class="mx-2">|</span>
                                                <i class="fas fa-user me-1"></i>
                                                <?php echo e($featured->user->name ?? 'Admin'); ?>

                                            </small>
                                        </div>
                                    </div>
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
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="blog-item"
                                style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%; display: flex; flex-direction: column;"
                                onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.06)'">
                                <div class="blog-img" style="position: relative; height: 200px; overflow: hidden;">
                                    <?php if($berita->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($berita->featured_image)); ?>" alt="<?php echo e($berita->title); ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div
                                            style="width: 100%; height: 100%; background: linear-gradient(135deg, #1a5632, #2d8a5e); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper"
                                                style="font-size: 2rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="blog-date" style="position: absolute; top: 15px; left: 15px;">
                                        <span
                                            style="display: block; background: #1a5632; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                            <?php echo e($berita->published_at?->format('d M Y')); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="blog-content p-4 d-flex flex-column" style="flex: 1;">
                                    <div class="blog-meta mb-2">
                                        <span style="color: #999; font-size: 0.85rem;">
                                            <i class="fas fa-user me-1"></i> <?php echo e($berita->user->name ?? 'Admin'); ?>

                                        </span>
                                    </div>
                                    <h5 class="blog-title" style="font-weight: 700;">
                                        <a href="<?php echo e(route('berita.public.show', $berita->slug)); ?>"
                                            class="text-decoration-none" style="color: #1a1a2e;">
                                            <?php echo e(Str::limit($berita->title, 60)); ?>

                                        </a>
                                    </h5>
                                    <p class="blog-desc" style="color: #666; font-size: 0.95rem;">
                                        <?php echo e(Str::limit($berita->excerpt ?? strip_tags($berita->content ?? ''), 120)); ?>

                                    </p>
                                    <div class="mt-auto pt-3">
                                        <a href="<?php echo e(route('berita.public.show', $berita->slug)); ?>" class="read-more"
                                            style="color: #1a5632; font-weight: 600; text-decoration: none;">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        <?php echo e($beritas->withQueryString()->links()); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h4 style="color: #666;">Belum ada berita</h4>
                        <p style="color: #999;">Berita dan artikel terbaru akan segera hadir.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.maudu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\berita\public\index-maudu.blade.php ENDPATH**/ ?>