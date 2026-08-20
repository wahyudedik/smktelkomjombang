<!-- Campus Life / Kepala Madrasah -->
<div class="campus-life pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="content-img wow fadeInLeft" data-wow-delay=".25s">
                    <?php
                        $kepala = theme_config('kepala_sekolah', []);
                    ?>
                    <?php if(!empty($kepala['photo'])): ?>
                        <img src="<?php echo e($kepala['photo']); ?>" alt="<?php echo e($kepala['name'] ?? 'Kepala Madrasah'); ?>">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets_maudu/assets/img/campus-life/01.jpg')); ?>" alt="">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <h4 class="site-title">
                            Kepala <?php echo e(theme_config('type', 'Madrasah')); ?>

                            <?php if(!empty($kepala['name'])): ?>
                                <span>: <?php echo e($kepala['name']); ?></span>
                            <?php endif; ?>
                        </h4>
                    </div>
                    <p class="content-text">
                        <?php echo e($kepala['description'] ?? 'Selamat datang di Website Resmi ' . theme_config('name') . '. Dengan rahmat Allah SWT, website ini menjadi media informasi, silaturahmi, dan komunikasi bagi siswa, alumni, orang tua, serta masyarakat. Kami menyajikan profil madrasah, kegiatan, prestasi, dan berbagai layanan pendidikan.'); ?>

                    </p>
                    <?php if(!empty($kepala['description_2'])): ?>
                        <p class="content-text mt-2">
                            <?php echo e($kepala['description_2']); ?>

                        </p>
                    <?php else: ?>
                        <p class="content-text mt-2">
                            Semoga kehadiran website ini memberikan manfaat, mempererat kebersamaan, serta mendukung terwujudnya pendidikan yang unggul, berkarakter, dan berorientasi pada masa depan. Kritik dan saran sangat kami harapkan demi kemajuan bersama.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Campus Life End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\about-kepala.blade.php ENDPATH**/ ?>