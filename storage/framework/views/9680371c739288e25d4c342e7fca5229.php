<!-- campus life -->
<?php
    // Use campus_life specific data if available, otherwise fallback to general headmaster data
    $headmasterPhoto = cache('site_setting_campus_life_headmaster_photo') ?: cache('site_setting_headmaster_photo');
    $headmasterName = cache('site_setting_campus_life_headmaster_name') ?: cache('site_setting_headmaster_name', 'Khoiruddinul Qoyyum,S.S.,M.Pd');
    $headmasterDescription = cache('site_setting_campus_life_headmaster_description') ?: cache('site_setting_headmaster_description', 'Sebagai kepala madrasah yang berpengalaman, kami berkomitmen untuk memberikan pendidikan terbaik bagi para siswa dengan mengintegrasikan nilai-nilai keislaman dan pengetahuan modern.');
    $headmasterVision = cache('site_setting_campus_life_headmaster_vision') ?: cache('site_setting_headmaster_vision', 'Visi kami adalah menciptakan generasi yang unggul dalam akademik, berakhlak mulia, dan siap menghadapi tantangan masa depan dengan bekal ilmu pengetahuan yang komprehensif.');
?>
<div class="campus-life pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="content-img wow fadeInLeft" data-wow-delay=".25s">
                    <?php if($headmasterPhoto): ?>
                        <img src="<?php echo e(Storage::url($headmasterPhoto)); ?>" alt="Foto Kepala Sekolah">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/img/campus-life/01.jpg')); ?>" alt="">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <h4 class="site-title">
                            Kepala Madrasah <span>: <?php echo e($headmasterName); ?></span>
                        </h4>
                    </div>
                    <p class="content-text">
                        <?php echo e($headmasterDescription); ?>

                    </p>
                    <p class="content-text mt-2">
                        <?php echo e($headmasterVision); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- campus life end-->
<?php /**PATH E:\PROJEKU\ig-to-web\resources\views/components/landing/campus-life.blade.php ENDPATH**/ ?>