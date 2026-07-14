<!-- Testimonial Area -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['testimonials' => []]));

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

foreach (array_filter((['testimonials' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="testimonial-area ts-bg pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="sub-title">Testimoni</span>
                    <h2 class="title">Apa Kata Mereka?</h2>
                    <p class="desc">Cerita dari alumni dan siswa <?php echo e(theme_config('short_name', 'MAUDU')); ?></p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme">
            <?php if(count($testimonials) > 0): ?>
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= ($testimonial->rating ?? 5)): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p><?php echo e($testimonial->content ?? ($testimonial->testimonial ?? '')); ?></p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-info">
                                <div class="author-img">
                                    <?php if(!empty($testimonial->photo)): ?>
                                        <img src="<?php echo e(Storage::url($testimonial->photo)); ?>"
                                            alt="<?php echo e($testimonial->name); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets_maudu/assets/img/testimonial/01.jpg')); ?>"
                                            alt="<?php echo e($testimonial->name ?? 'Alumni'); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="author-info">
                                    <h4><?php echo e($testimonial->name ?? 'Alumni'); ?></h4>
                                    <span><?php echo e($testimonial->position ?? ($testimonial->occupation ?? 'Alumni ' . theme_config('short_name'))); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $defaultTestimonials = [
                        [
                            'name' => 'Ahmad Fauzi',
                            'position' => 'Mahasiswa UIN',
                            'rating' => 5,
                            'content' =>
                                'Pendidikan di MAUDU sangat berkualitas. Saya mendapatkan beasiswa kuliah berkat prestasi yang diraih selama di sini.',
                        ],
                        [
                            'name' => 'Siti Nurhaliza',
                            'position' => 'Guru SD',
                            'rating' => 5,
                            'content' =>
                                'MAUDU memberikan fondasi agama yang kuat dan juga kompetensi akademik yang baik. Sangat merekomendasikan.',
                        ],
                        [
                            'name' => 'Muhammad Rizki',
                            'position' => 'Karyawan BUMN',
                            'rating' => 5,
                            'content' =>
                                'Alumni MAUDU siap bersaing di dunia kerja. Terima kasih MAUDU atas bekal ilmunya.',
                        ],
                    ];
                ?>
                <?php $__currentLoopData = $defaultTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p><?php echo e($item['content']); ?></p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-info">
                                <div class="author-img">
                                    <img src="<?php echo e(asset('assets_maudu/assets/img/testimonial/0' . ($index + 1) . '.jpg')); ?>"
                                        alt="<?php echo e($item['name']); ?>">
                                </div>
                                <div class="author-info">
                                    <h4><?php echo e($item['name']); ?></h4>
                                    <span><?php echo e($item['position']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Testimonial Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.testimonial-slider').owlCarousel({
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/testimonial.blade.php ENDPATH**/ ?>