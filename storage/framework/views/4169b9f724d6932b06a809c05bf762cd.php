<!-- Slider Section Start -->
<div class="rs-slider style1">
    <?php
        $heroImages = $siteSettings['hero_images'] ?? [];
        $defaultImages = [
            asset('assets_telkom/assets/images/slider/h2-1.jpg'),
            asset('assets_telkom/assets/images/slider/h2-2.jpg'),
            asset('assets_telkom/assets/images/slider/h2-3.jpg'),
        ];
    ?>
    <div class="rs-carousel owl-carousel" data-loop="true" data-items="1" data-margin="0" data-autoplay="true"
        data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800" data-dots="false"
        data-nav="false" data-nav-speed="false" data-center-mode="false" data-mobile-device="1"
        data-mobile-device-nav="false" data-mobile-device-dots="false" data-ipad-device="1"
        data-ipad-device-nav="false" data-ipad-device-dots="false" data-ipad-device2="1"
        data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="1" data-md-device-nav="true"
        data-md-device-dots="false">

        
        <?php
            $slide1Bg = !empty($heroImages[0]) ? Storage::url($heroImages[0]) : ($defaultImages[0] ?? '');
        ?>
        <div class="slider-content slide1" style="background: url('<?php echo e($slide1Bg); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="container">
                <div class="sl-sub-title white-color wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide1_subtitle'] ?? $siteSettings['hero_subtitle'] ?? 'Selamat Datang Di Website'); ?></div>
                <h1 class="sl-title white-color wow fadeInRight" data-wow-delay="600ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide1_title'] ?? $siteSettings['hero_title'] ?? 'SMK Telekomunikasi Darul Ulum'); ?></h1>
                <?php if(!empty($siteSettings['hero_slide1_description'])): ?>
                <p class="sl-desc white-color wow fadeInUp" data-wow-delay="750ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide1_description']); ?></p>
                <?php endif; ?>
                <div class="sl-btn wow fadeInUp" data-wow-delay="900ms" data-wow-duration="2000ms" style="opacity: 0;">
                    <a class="readon2 banner-style" href="#rs-about"><?php echo e($siteSettings['hero_subtitle'] ?? 'Discover More'); ?></a>
                </div>
            </div>
        </div>

        
        <?php
            $slide2Bg = !empty($heroImages[1]) ? Storage::url($heroImages[1]) : ($defaultImages[1] ?? '');
        ?>
        <div class="slider-content slide2" style="background: url('<?php echo e($slide2Bg); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="container">
                <div class="sl-sub-title white-color wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide2_subtitle'] ?? $siteSettings['hero_subtitle'] ?? 'Selamat Datang Di Website'); ?></div>
                <h1 class="sl-title white-color wow fadeInRight" data-wow-delay="600ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide2_title'] ?? $siteSettings['hero_title'] ?? 'SMK Telekomunikasi Darul Ulum'); ?></h1>
                <?php if(!empty($siteSettings['hero_slide2_description'])): ?>
                <p class="sl-desc white-color wow fadeInUp" data-wow-delay="750ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide2_description']); ?></p>
                <?php endif; ?>
                <div class="sl-btn wow fadeInUp" data-wow-delay="900ms" data-wow-duration="2000ms" style="opacity: 0;">
                    <a class="readon2 banner-style" href="#rs-about"><?php echo e($siteSettings['hero_subtitle'] ?? 'Discover More'); ?></a>
                </div>
            </div>
        </div>

        
        <?php if(!empty($heroImages[2]) || !empty($siteSettings['hero_slide3_title'])): ?>
        <?php
            $slide3Bg = !empty($heroImages[2]) ? Storage::url($heroImages[2]) : ($defaultImages[2] ?? '');
        ?>
        <div class="slider-content slide3" style="background: url('<?php echo e($slide3Bg); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="container">
                <div class="sl-sub-title white-color wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide3_subtitle'] ?? $siteSettings['hero_subtitle'] ?? 'Selamat Datang Di Website'); ?></div>
                <h1 class="sl-title white-color wow fadeInRight" data-wow-delay="600ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide3_title'] ?? $siteSettings['hero_title'] ?? 'SMK Telekomunikasi Darul Ulum'); ?></h1>
                <?php if(!empty($siteSettings['hero_slide3_description'])): ?>
                <p class="sl-desc white-color wow fadeInUp" data-wow-delay="750ms" data-wow-duration="2000ms" style="opacity: 0;"><?php echo e($siteSettings['hero_slide3_description']); ?></p>
                <?php endif; ?>
                <div class="sl-btn wow fadeInUp" data-wow-delay="900ms" data-wow-duration="2000ms" style="opacity: 0;">
                    <a class="readon2 banner-style" href="#rs-about"><?php echo e($siteSettings['hero_subtitle'] ?? 'Discover More'); ?></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- Slider Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/hero-slider.blade.php ENDPATH**/ ?>