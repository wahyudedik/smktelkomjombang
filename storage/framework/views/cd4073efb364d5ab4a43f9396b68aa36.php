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

<div class="portfolio-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Kegiatan <?php echo e(theme_config('short_name', 'MAUDU')); ?></span>
                    <h2 class="site-title">Berita<span> Madrasah</span> Terbaru</h2>
                    <p>Oleh Redaksi AFKAR</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if(count($blogs) > 0): ?>
                <?php $__currentLoopData = $blogs->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $delays = ['0.25s', '0.50s', '0.75s', '0.25s', '0.50s', '0.75s'];
                        $delay = $delays[$index % 6];
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay="<?php echo e($delay); ?>">
                            <div class="blog-date">
                                <i class="fal fa-calendar-alt"></i> <?php echo e(\Carbon\Carbon::parse($blog->created_at)->format('M d, Y')); ?>

                            </div>
                            <div class="blog-item-img">
                                <?php if(!empty($blog->image)): ?>
                                    <img src="<?php echo e(Storage::url($blog->image)); ?>" alt="<?php echo e($blog->title); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/0' . (($index % 3) + 1) . '.jpg')); ?>" alt="<?php echo e($blog->title); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> <?php echo e($blog->author ?? 'Admin'); ?></a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> <?php echo e($blog->category ?? 'Berita'); ?></a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="<?php echo e(route('berita.public.show', $blog->slug)); ?>"><?php echo e(Str::limit($blog->title, 60)); ?></a>
                                </h4>
                                <a class="theme-btn" href="<?php echo e(route('berita.public.show', $blog->slug)); ?>">Read More<i class="fas fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $delays = ['0.25s', '0.50s', '0.75s', '0.25s', '0.50s', '0.75s'];
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
                        [
                            'title' => 'Ujian Kenaikan Kelas Tahun Ajaran 2024/2025',
                            'category' => 'Akademik',
                            'date' => now()->subDays(21),
                        ],
                        [
                            'title' => 'Peringatan Hari Santri Nasional',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(28),
                        ],
                        [
                            'title' => 'Kunjungan Edukasi ke Universitas',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(35),
                        ],
                    ];
                ?>
                <?php $__currentLoopData = $defaultBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay="<?php echo e($delays[$index]); ?>">
                            <div class="blog-date">
                                <i class="fal fa-calendar-alt"></i> <?php echo e(\Carbon\Carbon::parse($blog['date'])->format('M d, Y')); ?>

                            </div>
                            <div class="blog-item-img">
                                <img src="<?php echo e(asset('assets_maudu/assets/img/blog/0' . (($index % 3) + 1) . '.jpg')); ?>" alt="<?php echo e($blog['title']); ?>">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> Redaksi AFKAR</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> <?php echo e($blog['category']); ?></a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#"><?php echo e($blog['title']); ?></a>
                                </h4>
                                <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        <!-- pagination -->
        <div class="pagination-area">
            <div aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- pagination end -->
    </div>
</div>
<!-- Blog End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/blog.blade.php ENDPATH**/ ?>