<!-- video-area -->
<div class="video-area py-120">
    <div class="container">
        <div class="video-content"
            style="background-image: url(<?php echo e(cache('site_setting_video_thumbnail') ? Storage::url(cache('site_setting_video_thumbnail')) : asset('assets/img/video/01.jpg')); ?>);">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="video-wrapper">
                        <a class="play-btn popup-youtube"
                            href="<?php echo e(cache('site_setting_video_url', 'https://www.youtube.com/watch?v=ckHzmP1evNU')); ?>">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- video-area end -->
<?php /**PATH E:\PROJEKU\ig-to-web\resources\views/components/landing/video.blade.php ENDPATH**/ ?>