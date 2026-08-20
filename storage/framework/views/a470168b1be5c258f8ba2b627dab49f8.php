<!-- Video Area -->
<div class="video-area py-120">
    <div class="container">
        <div class="video-content" style="background-image: url('<?php echo e(asset('assets_maudu/assets/img/video/01.jpg')); ?>');">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="video-wrapper">
                        <a href="<?php echo e(theme_config('video_url', 'https://www.youtube.com/watch?v=ckHzmP1evNU')); ?>"
                            class="popup-youtube play-btn" title="Play Video">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Video Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.popup-youtube').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                preloader: false,
                iframe: {
                    markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                        '</div>',
                    patterns: {
                        youtube: {
                            index: 'youtube.com/',
                            id: 'v=',
                            src: '//www.youtube.com/embed/%id%?autoplay=1'
                        }
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\video.blade.php ENDPATH**/ ?>