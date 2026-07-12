<!-- CTA / Pendaftaran -->
<div class="cta-area py-120" style="background: linear-gradient(135deg, #1a365d 0%, #2d5a87 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="video-wrap position-relative">
                    <a href="<?php echo e(theme_config('video_url', 'https://www.youtube.com/watch?v=F5bnwy0lRZI')); ?>"
                        class="popup-youtube video-play-btn-lg" title="Play Video">
                        <div class="video-thumbnail rounded overflow-hidden">
                            <?php if(!empty($siteSettings['video_thumbnail'])): ?>
                                <img src="<?php echo e(Storage::url($siteSettings['video_thumbnail'])); ?>" alt="Video"
                                    class="img-fluid">
                            <?php else: ?>
                                <img src="<?php echo e(asset('assets_maudu/assets/img/video/01.jpg')); ?>" alt="Video"
                                    class="img-fluid">
                            <?php endif; ?>
                            <div class="play-btn-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="cta-content text-white">
                    <div class="site-heading mb-4">
                        <span
                            class="sub-title text-white-50"><?php echo e(theme_config('cta_title', 'Pendaftaran Siswa Baru')); ?></span>
                        <h2 class="title text-white"><?php echo e(theme_config('name')); ?></h2>
                    </div>
                    <div class="cta-description mb-4">
                        <p class="text-white-50">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo e(theme_config('address')); ?>

                        </p>
                        <p class="text-white-50">
                            <i class="fas fa-clock me-2"></i>
                            <?php echo e(theme_config('working_hours.days', 'Sabtu - Kamis')); ?>,
                            <?php echo e(theme_config('working_hours.hours', '08:00 - 16:00 WIB')); ?>

                        </p>
                    </div>
                    <div class="cta-steps mb-4">
                        <h5 class="text-white mb-3">Cara Mendaftar:</h5>
                        <ol class="text-white-50 ps-3">
                            <li>Kunjungi website PPDB atau datang langsung ke <?php echo e(theme_config('short_name')); ?></li>
                            <li>Isi formulir pendaftaran secara online/offline</li>
                            <li>Lengkapi berkas yang diperlukan</li>
                            <li>Ikuti tes masuk</li>
                        </ol>
                    </div>
                    <div class="cta-buttons">
                        <a class="btn btn-light btn-lg me-3" href="<?php echo e(theme_config('cta_button_url', '#')); ?>"
                            target="_blank">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?php echo e(theme_config('cta_button_text', 'Daftar Sekarang')); ?>

                        </a>
                        <a class="btn btn-outline-light btn-lg" href="https://wa.me/<?php echo e(theme_config('whatsapp')); ?>"
                            target="_blank">
                            <i class="fab fa-whatsapp me-2"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CTA End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('.video-play-btn-lg').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                preloader: false
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/cta.blade.php ENDPATH**/ ?>