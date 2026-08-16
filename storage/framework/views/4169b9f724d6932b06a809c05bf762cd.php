<!-- Slider Section Start -->
<div class="rs-slider style1 pt-30 pb-30">
    <?php
        $heroImages = $siteSettings['hero_images'] ?? [];
        $defaultImages = [
            asset('assets_telkom/assets/images/slider/h2-2.jpg'),
            asset('assets_telkom/assets/images/slider/h2-1.jpg'),
            asset('assets_telkom/assets/images/slider/h2-2.jpg'),
        ];
    ?>
    <div class="container position-relative">
        <div class="rs-carousel owl-carousel" data-loop="true" data-items="1" data-margin="0" data-autoplay="true"
            data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800" data-dots="false"
            data-nav="true" data-nav-speed="false" data-center-mode="false" data-mobile-device="1"
            data-mobile-device-nav="true" data-mobile-device-dots="false" data-ipad-device="1"
            data-ipad-device-nav="true" data-ipad-device-dots="false" data-ipad-device2="1"
            data-ipad-device-nav2="true" data-ipad-device-dots2="false" data-md-device="1" data-md-device-nav="true"
            data-md-device-dots="false">

            
            <?php
                $slide1Img = $heroImages[0] ?? '';
                $slide1Bg = !empty($slide1Img) ? (str_starts_with($slide1Img, 'assets_telkom/') ? asset($slide1Img) : Storage::url($slide1Img)) : ($defaultImages[0] ?? '');
            ?>
            <div class="slider-item">
                <div class="slider-img">
                    <img src="<?php echo e($slide1Bg); ?>" alt="SMK Telekomunikasi Darul Ulum Jombang Banner">
                    <div class="slider-overlay"></div>
                </div>
                <div class="slider-content-overlay">
                    <div class="sl-sub-title wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide1_subtitle'] ?? $siteSettings['hero_subtitle'] ?? 'Penerimaan Siswa Baru 2026'); ?></div>
                    <h1 class="sl-title wow fadeInRight" data-wow-delay="600ms" data-wow-duration="1500ms"><?php echo $siteSettings['hero_slide1_title'] ?? $siteSettings['hero_title'] ?? 'SMK Telekomunikasi<br>Darul Ulum Jombang'; ?></h1>
                    <p class="sl-desc wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide1_description'] ?? $siteSettings['hero_subtitle'] ?? 'Berhardware Teknologi, Bersoftware Religi'); ?></p>
                    <div class="sl-btn wow fadeInUp" data-wow-delay="1000ms" data-wow-duration="1500ms">
                        <a class="readon2 banner-style" href="<?php echo e($siteSettings['hero_slide1_button_url'] ?? $siteSettings['cta_button_url'] ?? 'https://psb.ponpesdarululum.id/'); ?>" target="<?php echo e($siteSettings['hero_slide1_button_target'] ?? '_blank'); ?>"><?php echo e($siteSettings['hero_slide1_button_text'] ?? $siteSettings['cta_button_text'] ?? 'DAFTAR PPDB'); ?></a>
                    </div>
                </div>
            </div>

            
            <?php
                $slide2Img = $heroImages[1] ?? '';
                $slide2Bg = !empty($slide2Img) ? (str_starts_with($slide2Img, 'assets_telkom/') ? asset($slide2Img) : Storage::url($slide2Img)) : ($defaultImages[1] ?? '');
            ?>
            <div class="slider-item">
                <div class="slider-img">
                    <img src="<?php echo e($slide2Bg); ?>" alt="Jurusan SMK Telekomunikasi Darul Ulum Jombang">
                    <div class="slider-overlay"></div>
                </div>
                <div class="slider-content-overlay">
                    <div class="sl-sub-title wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide2_subtitle'] ?? 'Program Keahlian Unggulan'); ?></div>
                    <h1 class="sl-title wow fadeInRight" data-wow-delay="600ms" data-wow-duration="1500ms"><?php echo $siteSettings['hero_slide2_title'] ?? 'Siap Kerja &<br>Berkompeten'; ?></h1>
                    <p class="sl-desc wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide2_description'] ?? 'Produksi Film | DKV | TKJ | RPL'); ?></p>
                    <div class="sl-btn wow fadeInUp" data-wow-delay="1000ms" data-wow-duration="1500ms">
                        <a class="readon2 banner-style" href="<?php echo e($siteSettings['hero_slide2_button_url'] ?? '#rs-services'); ?>"><?php echo e($siteSettings['hero_slide2_button_text'] ?? 'JELAJAHI JURUSAN'); ?></a>
                    </div>
                </div>
            </div>

            
            <?php if(!empty($heroImages[2]) || !empty($siteSettings['hero_slide3_title'])): ?>
            <?php
                $slide3Img = $heroImages[2] ?? '';
                $slide3Bg = !empty($slide3Img) ? (str_starts_with($slide3Img, 'assets_telkom/') ? asset($slide3Img) : Storage::url($slide3Img)) : ($defaultImages[2] ?? '');
            ?>
            <div class="slider-item">
                <div class="slider-img">
                    <img src="<?php echo e($slide3Bg); ?>" alt="SMK Telekomunikasi Darul Ulum">
                    <div class="slider-overlay"></div>
                </div>
                <div class="slider-content-overlay">
                    <div class="sl-sub-title wow bounceInLeft" data-wow-delay="300ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide3_subtitle'] ?? 'Sekolah Unggulan'); ?></div>
                    <h1 class="sl-title wow fadeInRight" data-wow-delay="600ms" data-wow-duration="1500ms"><?php echo $siteSettings['hero_slide3_title'] ?? 'Bergabung Bersama Kami'; ?></h1>
                    <?php if(!empty($siteSettings['hero_slide3_description'])): ?>
                    <p class="sl-desc wow fadeInUp" data-wow-delay="800ms" data-wow-duration="1500ms"><?php echo e($siteSettings['hero_slide3_description']); ?></p>
                    <?php endif; ?>
                    <div class="sl-btn wow fadeInUp" data-wow-delay="1000ms" data-wow-duration="1500ms">
                        <a class="readon2 banner-style" href="<?php echo e($siteSettings['hero_slide3_button_url'] ?? $siteSettings['cta_button_url'] ?? 'https://psb.ponpesdarululum.id/'); ?>" <?php if(($siteSettings['hero_slide3_button_target'] ?? '') === '_blank'): ?> target="_blank" <?php endif; ?>><?php echo e($siteSettings['hero_slide3_button_text'] ?? $siteSettings['cta_button_text'] ?? 'DAFTAR SEKARANG'); ?></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Slider Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/hero-slider.blade.php ENDPATH**/ ?>