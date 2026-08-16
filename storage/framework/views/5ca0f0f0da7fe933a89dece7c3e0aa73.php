<!-- Footer Start -->
<footer id="rs-footer" class="rs-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 footer-widget mb-4">
                    <h4 class="widget-title">Jurusan</h4>
                    <ul class="site-map">
                        <li><a href="#rs-services">PRODUKSI FILM</a></li>
                        <li><a href="#rs-services">DESAIN KOMUNIKASI VISUAL</a></li>
                        <li><a href="#rs-services">TEKNIK KOMPUTER DAN JARINGAN</a></li>
                        <li><a href="#rs-services">REKAYASA PERANGKAT LUNAK</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 footer-widget mb-4">
                    <h4 class="widget-title">Link Terkait</h4>
                    <ul class="site-map">
                        <li><a href="#">E-Rapor</a></li>
                        <li><a href="#">E-Osis</a></li>
                        <li><a href="#">E-Learning</a></li>
                        <li><a href="#">E-Perpus</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12 footer-widget mb-4">
                    <h4 class="widget-title">Address</h4>
                    <ul class="address-widget">
                        <li>
                            <i class="flaticon-location"></i>
                            <div class="desc">
                                <?php echo e($siteSettings['contact_address'] ?? 'Ponpes Darul Ulum, Jl. Wahid Hasyim No.128, Kedunglosari, Kedungrejo, Kec. Bandar Kedungmulyo, Kabupaten Jombang, Jawa Timur'); ?>

                            </div>
                        </li>
                        <li>
                            <i class="flaticon-call"></i>
                            <div class="desc">
                                <?php if(!empty($siteSettings['contact_phone'])): ?>
                                    <a
                                        href="tel:<?php echo e($siteSettings['contact_phone']); ?>"><?php echo e($siteSettings['contact_phone']); ?></a>
                                <?php else: ?>
                                    <a href="tel:085649400339">0856-4940-0339</a> ,
                                    <a href="tel:03218681888">(0321) 8681-888</a>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-email"></i>
                            <div class="desc">
                                <a
                                    href="mailto:<?php echo e($siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com'); ?>"><?php echo e($siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com'); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row y-middle">
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3 mb-md-0">
                    <div class="footer-logo text-center text-md-start">
                        <a href="<?php echo e(route('landing')); ?>">
                            <img src="<?php echo e(theme_image('logo_light', theme_info('defaults.logo_light', 'assets_telkom/assets/images/logo.png'))); ?>"
                                alt="<?php echo e(theme_info('name', 'Logo')); ?>" style="max-height: 50px;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3 mb-md-0">
                    <div class="copyright text-center">
                        <p><?php echo $siteSettings['footer_text'] ??
                            '&copy; ' .
                                date('Y') .
                                ' All Rights Reserved. Developed By <a href="https://www.tiktok.com/@kritis.tv" target="_blank">Kritis.TV</a>'; ?></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 text-center text-md-end">
                    <ul class="footer-social">
                        <?php if(theme_config('facebook_url')): ?>
                            <li><a href="<?php echo e(theme_config('facebook_url')); ?>" target="_blank" rel="noopener"
                                    title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        <?php endif; ?>
                        <?php if(theme_config('instagram_url')): ?>
                            <li><a href="<?php echo e(theme_config('instagram_url')); ?>" target="_blank" rel="noopener"
                                    title="Instagram"><i class="fa fa-instagram"></i></a></li>
                        <?php endif; ?>
                        <?php if(theme_config('youtube_url')): ?>
                            <li><a href="<?php echo e(theme_config('youtube_url')); ?>" target="_blank" rel="noopener"
                                    title="YouTube"><i class="fa fa-youtube"></i></a></li>
                        <?php endif; ?>
                        <?php if(theme_config('whatsapp')): ?>
                            <li><a href="https://wa.me/<?php echo e(theme_config('whatsapp')); ?>" target="_blank" rel="noopener"
                                    title="WhatsApp"><i class="fa fa-whatsapp"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/footer.blade.php ENDPATH**/ ?>