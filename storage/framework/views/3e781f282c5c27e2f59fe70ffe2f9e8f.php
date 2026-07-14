<!-- Header -->
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Ikuti Kami:</span>
                        <a href="<?php echo e(theme_config('facebook_url', '#')); ?>" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo e(theme_config('instagram_url', '#')); ?>" target="_blank"><i
                                class="fab fa-instagram"></i></a>
                        <a href="<?php echo e(theme_config('youtube_url', '#')); ?>" target="_blank"><i
                                class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo e(theme_config('address')); ?>

                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <a href="tel:<?php echo e(theme_config('phone')); ?>"><?php echo e(theme_config('phone')); ?></a>
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
                
                <a class="navbar-brand" href="<?php echo e(route('landing')); ?>">
                    <img src="<?php echo e(theme_image('logo', theme_info('defaults.logo', 'assets_maudu/assets/img/logo/logo.png'))); ?>"
                        alt="<?php echo e(theme_config('name')); ?>">
                </a>

                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <a href="#"><i class="fas fa-search"></i></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('landing')); ?>">Beranda</a>
                        </li>
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
                                    <a class="nav-link" href="<?php echo e(resolve_theme_url($item['url'] ?? '#')); ?>"
                                        <?php if(($item['target'] ?? '') === '_blank'): ?> target="_blank" <?php endif; ?>><?php echo e($item['label']); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <div class="nav-right">
                        <div class="nav-right-btn mt-2 d-flex align-items-center gap-2">
                            <?php if(auth()->guard()->check()): ?>
                                <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('dashboard')); ?>">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            <?php else: ?>
                                <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('login')); ?>">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </a>
                            <?php endif; ?>
                            <a class="btn btn-primary" href="<?php echo e(theme_config('ppdb_url', '#')); ?>" target="_blank">
                                DAFTAR SEKARANG
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