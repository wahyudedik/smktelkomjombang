<!-- Portfolio / Kegiatan -->
<div class="portfolio-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="sub-title">Galeri</span>
                    <h2 class="title">Portfolio Kegiatan <?php echo e(theme_config('short_name', 'MAUDU')); ?></h2>
                    <p class="desc">Berbagai kegiatan dan prestasi yang telah diraih</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
                $portfolios = [
                    ['title' => 'Kegiatan Belajar Mengajar', 'image' => 'activity/01.jpg'],
                    ['title' => 'Ujian Nasional', 'image' => 'activity/02.jpg'],
                    ['title' => 'Kegiatan Keagamaan', 'image' => 'activity/03.jpg'],
                    ['title' => 'Ekstrakurikuler', 'image' => 'activity/04.jpg'],
                    ['title' => 'Kemah', 'image' => 'activity/05.jpg'],
                ];
            ?>

            <?php $__currentLoopData = $portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $portfolio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="portfolio-item">
                        <img src="<?php echo e(asset('assets_maudu/assets/img/' . $portfolio['image'])); ?>"
                            alt="<?php echo e($portfolio['title']); ?>" style="width: 100%; border-radius: 50px 50px 50px 0;">
                        <div class="portfolio-content">
                            <div class="portfolio-info">
                                <div class="portfolio-title-info">
                                    <h4><?php echo e($portfolio['title']); ?></h4>
                                    <p><?php echo e(theme_config('short_name', 'MAUDU')); ?></p>
                                </div>
                                <a href="<?php echo e(asset('assets_maudu/assets/img/' . $portfolio['image'])); ?>"
                                    class="popup-image">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- Portfolio End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.popup-image').magnificPopup({
                type: 'image',
                mainClass: 'mfp-fade',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\portfolio.blade.php ENDPATH**/ ?>