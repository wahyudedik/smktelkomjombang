<!-- Partner Start -->
<div class="rs-partner pt-100 pb-100 md-pt-70 md-pb-70 gray-bg">
    <div class="container">
        <div class="rs-carousel owl-carousel" data-loop="true" data-items="5" data-margin="30"
            data-autoplay="true" data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800"
            data-dots="false" data-nav="false" data-nav-speed="false" data-center-mode="false"
            data-mobile-device="1" data-mobile-device-nav="false" data-mobile-device-dots="false"
            data-ipad-device="3" data-ipad-device-nav="false" data-ipad-device-dots="false"
            data-ipad-device2="2" data-ipad-device-nav2="false" data-ipad-device-dots2="false"
            data-md-device="5" data-md-device-nav="false" data-md-device-dots="false">
            <?php $__empty_1 = true; $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="partner-item">
                    <a href="<?php echo e($partner->website ?? '#'); ?>" target="_blank">
                        <img src="<?php echo e(asset('assets_telkom/assets/images/partner/' . ($loop->index + 1) . '.png')); ?>" alt="<?php echo e($partner->name); ?>">
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/1.png')); ?>" alt="Partner 1"></a>
                </div>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/2.png')); ?>" alt="Partner 2"></a>
                </div>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/3.png')); ?>" alt="Partner 3"></a>
                </div>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/4.png')); ?>" alt="Partner 4"></a>
                </div>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/5.png')); ?>" alt="Partner 5"></a>
                </div>
                <div class="partner-item">
                    <a href="#"><img src="<?php echo e(asset('assets_telkom/assets/images/partner/6.png')); ?>" alt="Partner 6"></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Partner End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/partners.blade.php ENDPATH**/ ?>