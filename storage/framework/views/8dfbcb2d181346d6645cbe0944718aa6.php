<header class="header">
    <!-- header top -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Follow Us: </span>
                        <?php if(cache('site_setting_social_facebook')): ?>
                            <a href="<?php echo e(cache('site_setting_social_facebook')); ?>" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        <?php else: ?>
                            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>

                        <?php if(cache('site_setting_social_instagram')): ?>
                            <a href="<?php echo e(cache('site_setting_social_instagram')); ?>" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        <?php else: ?>
                            <a href="<?php echo e(route('public.kegiatan')); ?>" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        <?php endif; ?>

                        <?php if(cache('site_setting_social_youtube')): ?>
                            <a href="<?php echo e(cache('site_setting_social_youtube')); ?>" target="_blank"><i
                                    class="fab fa-youtube"></i></a>
                        <?php else: ?>
                            <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>

                        <?php if(cache('site_setting_social_whatsapp')): ?>
                            <a href="<?php echo e(cache('site_setting_social_whatsapp')); ?>" target="_blank"><i
                                    class="fab fa-whatsapp"></i></a>
                        <?php else: ?>
                            <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <?php if(cache('site_setting_contact_address')): ?>
                                <li>
                                    <a href="#" target="_blank"><i class="far fa-location-dot"></i>
                                        <?php echo e(cache('site_setting_contact_address')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(cache('site_setting_contact_email')): ?>
                                <li>
                                    <a href="mailto:<?php echo e(cache('site_setting_contact_email')); ?>" target="_blank"><i
                                            class="far fa-envelopes"></i>
                                        <?php echo e(cache('site_setting_contact_email')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(cache('site_setting_contact_phone')): ?>
                                <li>
                                    <a href="tel:<?php echo e(cache('site_setting_contact_phone')); ?>" target="_blank"><i
                                            class="far fa-phone-volume"></i>
                                        <?php echo e(cache('site_setting_contact_phone')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                <a class="navbar-brand" href="/">
                    <?php if(cache('site_setting_logo')): ?>
                        <img src="<?php echo e(Storage::url(cache('site_setting_logo'))); ?>"
                            alt="<?php echo e(cache('site_setting_site_name', 'MAUDU REJOSO')); ?>" style="max-height: 50px;">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/img/logo/logo.png')); ?>" alt="logo">
                    <?php endif; ?>
                </a>
                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <button type="button" class="nav-right-link search-box-outer"><i
                                class="far fa-search"></i></button>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        <?php $__currentLoopData = $headerMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($menu->children->count() > 0): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo e(request()->is($menu->slug) ? 'active' : ''); ?>"
                                        href="#" data-bs-toggle="dropdown">
                                        <?php if($menu->menu_icon): ?>
                                            <i class="<?php echo e($menu->menu_icon); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo e($menu->menu_title); ?>

                                    </a>
                                    <ul class="dropdown-menu fade-down">
                                        <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e($submenu->menu_url); ?>"
                                                    <?php if($submenu->menu_target_blank): ?> target="_blank" <?php endif; ?>>
                                                    <?php if($submenu->menu_icon): ?>
                                                        <i class="<?php echo e($submenu->menu_icon); ?>"></i>
                                                    <?php endif; ?>
                                                    <?php echo e($submenu->menu_title); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->is($menu->slug) ? 'active' : ''); ?>"
                                        href="<?php echo e($menu->menu_url); ?>"
                                        <?php if($menu->menu_target_blank): ?> target="_blank" <?php endif; ?>>
                                        <?php if($menu->menu_icon): ?>
                                            <i class="<?php echo e($menu->menu_icon); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo e($menu->menu_title); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Fallback menu items if no custom menus are configured -->
                        <?php if($headerMenus->count() == 0): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#"
                                    data-bs-toggle="dropdown">PROFIL</a>
                                <ul class="dropdown-menu fade-down">
                                    <li><a class="dropdown-item" href="<?php echo e(route('pages.public.index')); ?>">HALAMAN</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('public.kegiatan')); ?>">GALERI</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.siswa.index')); ?>">DATA SISWA</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                    data-bs-toggle="dropdown">AKADEMIK</a>
                                <ul class="dropdown-menu fade-down">
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.guru.index')); ?>">TENAGA
                                            PENDIDIK</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('pages.public.index')); ?>">KURIKULUM</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('public.kegiatan')); ?>">KEGIATAN</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">LAYANAN
                                    DIGITAL</a>
                                <ul class="dropdown-menu fade-down">
                                    <?php
                                        $user = Auth::user();
                                        $siswa = $user ? \App\Models\Siswa::where('user_id', $user->id)->first() : null;
                                        $isGrade12 = $siswa && str_contains($siswa->kelas, 'XII');
                                    ?>
                                    <?php if($isGrade12): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('admin.lulus.check')); ?>">🎓
                                                E-LULUS</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.osis.voting')); ?>">🗳️
                                            E-OSIS</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.sarpras.index')); ?>">🏢
                                            E-SARPRAS</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('public.kegiatan')); ?>">📸
                                            E-GALERI</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#contact">KONTAK</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="nav-right">
                        <div class="nav-right-btn mt-2">
                            <?php if(Route::has('login')): ?>
                                <?php if(auth()->guard()->check()): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="theme-btn"><span
                                            class="fal fa-user"></span> DASHBOARD</a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" class="theme-btn"><span
                                            class="fal fa-sign-in"></span> LOGIN</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- header area end -->

<!-- popup search -->
<div class="search-popup">
    <button class="close-search"><span class="far fa-times"></span></button>
    <form action="#">
        <div class="form-group">
            <input type="search" name="search-field" placeholder="Search Here..." required>
            <button type="submit"><i class="far fa-search"></i></button>
        </div>
    </form>
</div>
<!-- popup search end -->
<?php /**PATH E:\PROJEKU\ig-to-web\resources\views/components/landing/header.blade.php ENDPATH**/ ?>