<!--Full width header Start-->
<div class="full-width-header header-style2">
    <!--Header Start-->
    <header id="rs-header" class="rs-header">
        <!-- Topbar Area Start -->
        <div class="topbar-area">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-md-7">
                        <ul class="topbar-contact">
                            <?php if(!empty($siteSettings['contact_email'])): ?>
                                <li>
                                    <i class="flaticon-email"></i>
                                    <a
                                        href="mailto:<?php echo e($siteSettings['contact_email']); ?>"><?php echo e($siteSettings['contact_email']); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(!empty($siteSettings['contact_phone'])): ?>
                                <li>
                                    <i class="flaticon-call"></i>
                                    <a
                                        href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone'])); ?>"><?php echo e($siteSettings['contact_phone']); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-md-5 text-end">
                        <ul class="topbar-right">
                            <li class="btn-part">
                                <?php if(auth()->guard()->check()): ?>
                                    <a class="apply-btn" href="<?php echo e(route('admin.dashboard')); ?>"> <i
                                            class="fa fa-tachometer-alt"> </i> Dashboard</a>
                                <?php else: ?>
                                    <a class="apply-btn" href="<?php echo e(route('login')); ?>"> <i class="fa fa-sign-in"> </i> Login
                                        System</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar Area End -->

        <!-- Menu Start -->
        <div class="menu-area menu-sticky">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-5">
                        <div class="logo-cat-wrap">
                            <div class="logo-part pr-90">
                                <a class="dark-logo" href="<?php echo e(route('landing')); ?>">
                                    <img src="<?php echo e(theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png'))); ?>"
                                        alt="<?php echo e(theme_info('name', 'Logo')); ?>" style="max-height: 35px;">
                                </a>
                                <a class="light-logo" href="<?php echo e(route('landing')); ?>">
                                    <img src="<?php echo e(theme_image('logo_light', theme_info('defaults.logo_light', 'assets_telkom/assets/images/logo.png'))); ?>"
                                        alt="<?php echo e(theme_info('name', 'Logo')); ?>" style="max-height: 35px;">
                                </a>
                            </div>
                            <div class="categories-btn">
                                <button type="button" class="cat-btn"><i class="fa fa-th"></i>Link Terkait</button>
                                <div class="cat-menu-inner">
                                    <ul id="cat-menu">
                                        <li><a href="#">E-Rapor</a></li>
                                        <li><a href="#">E-Learning</a></li>
                                        <li><a href="#">E-Perpus</a></li>
                                        <li><a href="#">E-Administrasi</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 text-center">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-90">
                                <div class="mobile-menu">
                                    <a class="rs-menu-toggle">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </div>
                                <nav class="rs-menu">
                                    <ul class="nav-menu">
                                        <?php $__currentLoopData = theme_config('menu', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($item['children']) && count($item['children']) > 0): ?>
                                                <li class="menu-item-has-children">
                                                    <a
                                                        href="<?php echo e(resolve_theme_url($item['url'] ?? '#')); ?>"><?php echo e($item['label']); ?></a>
                                                    <ul class="sub-menu">
                                                        <?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li><a
                                                                    href="<?php echo e(resolve_theme_url($child['url'] ?? '#')); ?>"><?php echo e($child['label']); ?></a>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li class="menu-item-has">
                                                    <a href="<?php echo e(resolve_theme_url($item['url'] ?? '#')); ?>"
                                                        <?php if(($item['target'] ?? '') === '_blank'): ?> target="_blank" <?php endif; ?>><?php echo e($item['label']); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->

        <!-- Canvas Menu start -->
        <nav class="right_menu_togle hidden-md">
            <div class="close-btn">
                <div id="nav-close">
                    <div class="line">
                        <span class="line1"></span><span class="line2"></span>
                    </div>
                </div>
            </div>
            <div class="canvas-logo">
                <a href="<?php echo e(route('landing')); ?>">
                    <img src="<?php echo e(theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png'))); ?>"
                        alt="<?php echo e(theme_info('name', 'Logo')); ?>" style="max-height: 60px;">
                </a>
            </div>
        </nav>
        <!-- Canvas Menu end -->
    </header>
    <!--Header End-->
</div>
<!--Full width header End-->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/header.blade.php ENDPATH**/ ?>