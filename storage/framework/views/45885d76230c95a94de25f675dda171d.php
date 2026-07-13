<!-- Footer -->
<footer class="footer-area">
    <div class="footer-shape">
        <img src="<?php echo e(asset('assets_maudu/assets/img/shape/01.png')); ?>" alt="shape">
    </div>

    <div class="footer-widget">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">
                
                <div class="col-md-6 col-lg-4">
                    <div class="footer-widget-box about-us">
                        <a href="<?php echo e(route('landing')); ?>" class="footer-logo">
                            <?php if(!empty($siteSettings['logo'])): ?>
                                <img src="<?php echo e(Storage::url($siteSettings['logo'])); ?>" alt="<?php echo e(theme_config('name')); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset(theme_config('logo_light'))); ?>" alt="<?php echo e(theme_config('name')); ?>">
                            <?php endif; ?>
                        </a>
                        <p class="mb-3"><?php echo e(theme_config('tagline', theme_config('name'))); ?></p>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo e(theme_config('address')); ?>

                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <a href="tel:<?php echo e(theme_config('phone')); ?>"><?php echo e(theme_config('phone')); ?></a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?php echo e(theme_config('email')); ?>"><?php echo e(theme_config('email')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h3 class="footer-title">Tautan Cepat</h3>
                        <ul class="footer-list">
                            <li><a href="<?php echo e(route('landing')); ?>">Beranda</a></li>
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'profil'])); ?>">Profil</a></li>
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'akademik'])); ?>">Akademik</a>
                            </li>
                            <li><a href="<?php echo e(route('public.kegiatan')); ?>">Kegiatan</a></li>
                            <li><a href="<?php echo e(route('berita.public.index')); ?>">Berita</a></li>
                        </ul>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h3 class="footer-title">Layanan</h3>
                        <ul class="footer-list">
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'siswa-alumni'])); ?>">E-Siswa &
                                    Alumni</a></li>
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'raport'])); ?>">E-Raport</a></li>
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'osis'])); ?>">E-OSIS</a></li>
                            <li><a href="<?php echo e(route('public.graduation.check')); ?>">E-Lulus</a></li>
                            <li><a href="<?php echo e(route('pages.public.index', ['category' => 'majalah'])); ?>">E-Majalah</a>
                            </li>
                        </ul>
                    </div>
                </div>

                
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h3 class="footer-title">Informasi Pendaftaran</h3>
                        <p class="footer-newsletter-text">Daftarkan putra-putri Anda di MA Unggulan Darul Ulum Rejoso.
                        </p>
                        <div class="footer-newsletter">
                            <div class="subscribe-form">
                                <form action="<?php echo e(theme_config('ppdb_url', '#')); ?>" target="_blank">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-graduation-cap me-2"></i> PSB Online
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="footer-social mt-3">
                            <a href="<?php echo e(theme_config('facebook_url', '#')); ?>" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo e(theme_config('instagram_url', '#')); ?>" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="<?php echo e(theme_config('youtube_url', '#')); ?>" target="_blank"><i
                                    class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="copyright-wrapper">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p class="copyright-text">
                            &copy; <span id="date" class="current-year"><?php echo e(date('Y')); ?></span>
                            <?php echo e(theme_config('name')); ?>. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            <li><a href="<?php echo e(theme_config('facebook_url', '#')); ?>" target="_blank"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a href="<?php echo e(theme_config('instagram_url', '#')); ?>" target="_blank"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li><a href="<?php echo e(theme_config('youtube_url', '#')); ?>" target="_blank"><i
                                        class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/footer.blade.php ENDPATH**/ ?>