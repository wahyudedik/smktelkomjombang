<!-- testimonial area -->
<div class="testimonial-area ts-bg pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i>
                        Testimonials</span>
                    <h2 class="site-title text-white">Apa Kata<span> Alumni ?</span></h2>
                    <p class="text-white">Alumni kuliah di dalam Negeri dan di luar Negeri</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme">
            <?php
                // Ambil testimonial dari database atau gunakan dummy data
                $testimonials = \App\Models\Testimonial::approved()->featured()->latest()->limit(6)->get();

                // Jika tidak ada testimonial di database, gunakan dummy data
                if ($testimonials->isEmpty()) {
                    $testimonials = collect(\App\Models\Testimonial::getDummyTestimonials());
                }
            ?>

            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="testimonial-item">
                    <div class="testimonial-rate">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star<?php echo e($i <= $testimonial['rating'] ? '' : '-o'); ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="testimonial-quote">
                        <p><?php echo e($testimonial['testimonial']); ?></p>
                    </div>
                    <div class="testimonial-content">
                        <div class="testimonial-author-img">
                            <img src="<?php echo e($testimonial['photo']); ?>" alt="<?php echo e($testimonial['name']); ?>">
                        </div>
                        <div class="testimonial-author-info">
                            <h4><?php echo e($testimonial['name']); ?></h4>
                            <p>
                                <?php if($testimonial['position'] === 'Alumni'): ?>
                                    Alumni <?php echo e($testimonial['graduation_year']); ?>

                                <?php elseif($testimonial['position'] === 'Siswa'): ?>
                                    <?php echo e($testimonial['class']); ?>

                                <?php else: ?>
                                    <?php echo e($testimonial['position']); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <span class="testimonial-quote-icon"><i class="far fa-quote-right"></i></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- testimonial area end -->
<?php /**PATH E:\PROJEKU\ig-to-web\resources\views/components/landing/testimonials.blade.php ENDPATH**/ ?>