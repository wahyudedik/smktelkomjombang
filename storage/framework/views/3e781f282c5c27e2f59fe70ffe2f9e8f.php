<!-- Header -->
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Follow Us: </span>
                        <a href="<?php echo e(theme_config('facebook_url', '#')); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo e(theme_config('instagram_url', '#')); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo e(theme_config('youtube_url', '#')); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="<?php echo e(theme_config('whatsapp_url', '#')); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <li>
                                <a href="<?php echo e(theme_config('google_maps_url', '#')); ?>" target="_blank">
                                    <i class="far fa-location-dot"></i> <?php echo e(theme_config('address')); ?>

                                </a>
                            </li>
                            <li>
                                <a href="mailto:<?php echo e(theme_config('email')); ?>" target="_blank">
                                    <i class="far fa-envelopes"></i> <?php echo e(theme_config('email')); ?>

                                </a>
                            </li>
                            <li>
                                <a href="tel:<?php echo e(theme_config('phone')); ?>">
                                    <i class="far fa-phone-volume"></i> <?php echo e(theme_config('phone')); ?>

                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                
                <a class="navbar-brand d-inline-flex align-items-center gap-2 me-lg-5" href="<?php echo e(route('landing')); ?>">
                    <img src="<?php echo e(asset(theme_config('logo_icon', theme_info('defaults.logo_icon', 'assets_maudu/assets/img/logo/favicon.png')))); ?>"
                        alt="logo" class="logo-icon">
                    <img src="<?php echo e(asset(theme_config('logo_text', theme_info('defaults.logo_text', 'assets_maudu/assets/img/logo/logo nama.png')))); ?>"
                        alt="logo" class="logo-text">
                </a>

                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <button type="button" class="nav-right-link search-box-outer"><i
                                class="far fa-search"></i></button>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav align-items-center mx-auto">
                        <?php $__currentLoopData = theme_config('menu', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($item['children']) && count($item['children']) > 0): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle"
                                        href="<?php echo e(resolve_theme_url($item['url'] ?? '#')); ?>" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside">
                                        <?php echo e($item['label']); ?>

                                    </a>
                                    <ul class="dropdown-menu fade-down">
                                        <?php $__currentLoopData = $item['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a class="dropdown-item"
                                                    href="<?php echo e(resolve_theme_url($child['url'] ?? '#')); ?>"><?php echo e($child['label']); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link text-nowrap" href="<?php echo e(resolve_theme_url($item['url'] ?? '#')); ?>"
                                        <?php if(($item['target'] ?? '') === '_blank'): ?> target="_blank" <?php endif; ?>><?php echo e($item['label']); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <div class="nav-right">
                        <div class="nav-right-btn mt-2 d-flex align-items-center gap-2">
                            <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>"
                                    class="theme-btn"
                                    style="background: transparent; border: 2px solid #fff; padding: 10px 20px; border-radius: 5px;">
                                    <i class="fa fa-tachometer-alt"></i> Dashboard
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>"
                                    class="theme-btn"
                                    style="background: transparent; border: 2px solid #fff; padding: 10px 20px; border-radius: 5px;">
                                    <i class="fa fa-sign-in-alt"></i> Masuk
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(theme_config('linktree_url', theme_config('ppdb_url', '#'))); ?>" target="_blank"
                                class="theme-btn">
                                <span class="fal fa-book"></span> INFORMASI PENDAFTARAN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- Header End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/header.blade.php ENDPATH**/ ?>