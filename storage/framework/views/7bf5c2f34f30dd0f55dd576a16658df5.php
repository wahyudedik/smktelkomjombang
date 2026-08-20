<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-slider owl-carousel owl-theme">
        <?php
            $heroSlides = theme_config('hero_slides', []);
            $heroImages = theme_config('hero_images', []);
            $defaultImages = [
                asset('assets_maudu/assets/img/slider/slider-1.jpg'),
                asset('assets_maudu/assets/img/slider/slider-2.jpg'),
                asset('assets_maudu/assets/img/slider/slider-3.jpg'),
            ];

            // Fallback to config slides or hardcoded defaults
            $slides = count($heroSlides) > 0 ? $heroSlides : [
                ['subtitle' => 'Welcome To MAUDU Library', 'title' => 'Grand Opening <span>MAUDU</span> Library', 'description' => 'Acara Grandopening Dihadiri oleh Majelis Pimpinan Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang'],
                ['subtitle' => 'Studi Edukasi Sosial', 'title' => 'Gedung <span>DPRD</span> Kabupaten Jombang', 'description' => ''],
                ['subtitle' => 'Event KOMPASS', 'title' => 'Kompetisi Agama, <span>Sains,</span> dan Seni 2024', 'description' => ''],
            ];

            $animations = [
                ['subtitle' => 'fadeInDown', 'title' => 'fadeInRight', 'desc' => 'fadeInLeft'],
                ['subtitle' => 'fadeInDown', 'title' => 'fadeInRight', 'desc' => 'fadeInLeft'],
                ['subtitle' => 'fadeInDown', 'title' => 'fadeInRight', 'desc' => 'fadeInLeft'],
            ];
        ?>

        <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $image = $heroImages[$index] ?? ($defaultImages[$index] ?? $defaultImages[0]);
                $anim = $animations[$index] ?? $animations[0];
                $delay = 0.25;
            ?>
            <div class="hero-single" style="background: url('<?php echo e($image); ?>')">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-lg-7">
                            <div class="hero-content">
                                <h6 class="hero-sub-title" data-animation="<?php echo e($anim['subtitle']); ?>" data-delay="<?php echo e($delay); ?>s">
                                    <i class="far fa-book-open-reader"></i><?php echo e($slide['subtitle'] ?? ''); ?>

                                </h6>
                                <h1 class="hero-title" data-animation="<?php echo e($anim['title']); ?>" data-delay="<?php echo e($delay + 0.25); ?>s">
                                    <?php echo $slide['title'] ?? ''; ?>

                                </h1>
                                <?php if(!empty($slide['description'])): ?>
                                    <p data-animation="<?php echo e($anim['desc']); ?>" data-delay="<?php echo e($delay + 0.50); ?>s">
                                        <?php echo e($slide['description']); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<!-- Hero Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\hero-slider.blade.php ENDPATH**/ ?>