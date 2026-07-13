<!-- Blog / Berita -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['blogs' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['blogs' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="blog-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="site-heading">
                    <span class="sub-title">Berita</span>
                    <h2 class="title">Berita & Artikel Terbaru</h2>
                    <p class="desc">Informasi terkini seputar kegiatan dan prestasi
                        <?php echo e(theme_config('short_name', 'MAUDU')); ?></p>
                </div>
            </div>
        </div>
        <div class="blog-slider owl-carousel owl-theme">
            <?php if(count($blogs) > 0): ?>
                <?php $__currentLoopData = $blogs->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="blog-item">
                        <div class="blog-img">
                            <?php if(!empty($blog->image)): ?>
                                <img src="<?php echo e(Storage::url($blog->image)); ?>" alt="<?php echo e($blog->title); ?>" class="img-fluid">
                            <?php else: ?>
                                <img src="<?php echo e(asset('assets_maudu/assets/img/blog/01.jpg')); ?>" alt="<?php echo e($blog->title); ?>"
                                    class="img-fluid">
                            <?php endif; ?>
                            <div class="blog-date">
                                <span class="date"><?php echo e(\Carbon\Carbon::parse($blog->created_at)->format('d')); ?></span>
                                <span
                                    class="month"><?php echo e(\Carbon\Carbon::parse($blog->created_at)->format('M Y')); ?></span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="fas fa-user"></i> <?php echo e($blog->author ?? 'Admin'); ?></span>
                                <span><i class="fas fa-folder"></i> <?php echo e($blog->category ?? 'Berita'); ?></span>
                            </div>
                            <h4 class="blog-title">
                                <a
                                    href="<?php echo e(route('berita.public.show', $blog->slug)); ?>"><?php echo e(Str::limit($blog->title, 60)); ?></a>
                            </h4>
                            <p class="blog-desc"><?php echo e(Str::limit($blog->excerpt ?? ($blog->content ?? ''), 120)); ?></p>
                            <div class="blog-bottom">
                                <a href="<?php echo e(route('berita.public.show', $blog->slug)); ?>" class="read-more">Baca
                                    Selengkapnya <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $defaultBlogs = [
                        [
                            'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2025/2026',
                            'category' => 'PPDB',
                            'date' => now()->subDays(3),
                        ],
                        [
                            'title' => 'Prestasi Siswa di Kompetisi Agama Tingkat Nasional',
                            'category' => 'Prestasi',
                            'date' => now()->subDays(7),
                        ],
                        [
                            'title' => 'Kegiatan Bakti Sosial ke Panti Asuhan',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(14),
                        ],
                    ];
                ?>
                <?php $__currentLoopData = $defaultBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="<?php echo e(asset('assets_maudu/assets/img/blog/0' . ($index + 1) . '.jpg')); ?>"
                                alt="<?php echo e($blog['title']); ?>" class="img-fluid">
                            <div class="blog-date">
                                <span class="date"><?php echo e(\Carbon\Carbon::parse($blog['date'])->format('d')); ?></span>
                                <span class="month"><?php echo e(\Carbon\Carbon::parse($blog['date'])->format('M Y')); ?></span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="fas fa-user"></i> Admin</span>
                                <span><i class="fas fa-folder"></i> <?php echo e($blog['category']); ?></span>
                            </div>
                            <h4 class="blog-title">
                                <a href="#"><?php echo e($blog['title']); ?></a>
                            </h4>
                            <p class="blog-desc">Informasi terkini seputar kegiatan dan prestasi di
                                <?php echo e(theme_config('short_name')); ?>.</p>
                            <div class="blog-bottom">
                                <a href="#" class="read-more">Baca Selengkapnya <i
                                        class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Blog End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.blog-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/blog.blade.php ENDPATH**/ ?>