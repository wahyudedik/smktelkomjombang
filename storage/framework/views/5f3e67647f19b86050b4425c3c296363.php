<!-- About Area -->
<div class="about-area py-120">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                    <div class="about-img">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <img class="img-1" src="<?php echo e(asset('assets_maudu/assets/img/about/01.jpg')); ?>" alt="Gallery">
                                <div class="about-experience mt-4">
                                    <div class="about-experience-icon">
                                        <img src="<?php echo e(asset('assets_maudu/assets/img/icon/monitor.svg')); ?>" alt="">
                                    </div>
                                    <b class="text-start">Gallery Kegiatan<br> <?php echo e(theme_config('short_name', 'MAUDU')); ?> Rejoso</b>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img class="img-2" src="<?php echo e(asset('assets_maudu/assets/img/about/02.jpg')); ?>" alt="Gallery">
                                <img class="img-3 mt-4" src="<?php echo e(asset('assets_maudu/assets/img/about/03.jpg')); ?>" alt="Gallery">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> INFORMASI</span>
                        <h2 class="site-title">
                            Unggulan <span><?php echo e(theme_config('short_name', 'MAUDU')); ?></span> Rejoso
                        </h2>
                    </div>
                    <div class="about-content">
                        <div class="row">
                            <?php
                                $programs = theme_config('program_unggulan', [
                                    [
                                        'title' => 'KURIKULUM MADRASAH',
                                        'desc' => 'Kolaborasi antara kurikulum Kepesantrenan, Kemendikbud, Kemenag dan Kurikulum Muatan Lokal Madrasah',
                                        'icon_path' => 'assets_maudu/assets/img/icon/information.svg',
                                        'icon' => 'fas fa-graduation-cap',
                                    ],
                                    [
                                        'title' => 'PROGRAM STUDI KE TIMUR TENGAH',
                                        'desc' => 'Pembinaan Intensif dan Mediator Pemberangkatan',
                                        'icon_path' => 'assets_maudu/assets/img/icon/global-education.svg',
                                        'icon' => 'fas fa-plane-departure',
                                    ],
                                    [
                                        'title' => 'KELAS TAHFIDZ, MUATAN LOKAL KITAB TURATS',
                                        'desc' => 'Kelas Tahfidz, Program Tahfidz serta Program Pembiasaan Siswa',
                                        'icon_path' => 'assets_maudu/assets/img/icon/open-book.svg',
                                        'icon' => 'fas fa-book-quran',
                                    ],
                                    [
                                        'title' => 'PROGRAM KEMASYARAKATAN',
                                        'desc' => 'Kafilah Sholat Jum\'at, Sholat Tarawih, TPQ, Bakti Sosial dan Pengabdian Masyarakat',
                                        'icon_path' => 'assets_maudu/assets/img/icon/location.svg',
                                        'icon' => 'fas fa-hands-helping',
                                    ],
                                ]);
                            ?>

                            <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($index % 2 == 0): ?>
                                    <div class="col-md-6">
                                <?php endif; ?>
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <?php if(!empty($program['icon_path'])): ?>
                                            <img src="<?php echo e(asset($program['icon_path'])); ?>" alt="">
                                        <?php elseif(!empty($program['icon'])): ?>
                                            <i class="<?php echo e($program['icon']); ?>"></i>
                                        <?php else: ?>
                                            <i class="fas fa-star"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="about-item-content">
                                        <h5><?php echo e($program['title']); ?></h5>
                                        <p><?php echo e($program['desc']); ?></p>
                                    </div>
                                </div>
                                <?php if($index % 2 == 1 || $index == count($programs) - 1): ?>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="about-bottom">
                        <a href="<?php echo e(theme_config('ppdb_url', '#')); ?>" target="_blank" class="theme-btn">
                            PPDB ONLINE<i class="fas fa-arrow-right-long"></i>
                        </a>
                        <div class="about-phone">
                            <div class="icon"><i class="fal fa-headset"></i></div>
                            <div class="number">
                                <span>WA KAMI</span>
                                <h6>
                                    <a href="https://wa.me/<?php echo e(theme_config('whatsapp', '628113383722')); ?>" target="_blank">
                                        <?php echo e(theme_config('phone')); ?>

                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Area End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/about-area.blade.php ENDPATH**/ ?>