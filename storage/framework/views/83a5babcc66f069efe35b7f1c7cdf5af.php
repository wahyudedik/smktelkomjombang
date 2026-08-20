<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?php echo e($metaDescription ?? ($siteSettings['site_description'] ?? 'SMK Telekomunikasi Darul Ulum Jombang')); ?>">
    <meta name="keywords"
        content="<?php echo e($metaKeywords ?? ($siteSettings['site_keywords'] ?? 'SMK, Telekomunikasi, Jombang')); ?>">

    <!-- title -->
    <title><?php echo e($pageTitle ?? ($siteSettings['site_name'] ?? 'SMK Telekomunikasi Darul Ulum Jombang')); ?> -
        <?php echo e(config('app.name')); ?></title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon"
        href="<?php echo e(theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png'))); ?>">

    <!-- Critical CSS (render-blocking) -->
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/rsmenu-main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/rs-spacing.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/responsive.css')); ?>">

    <!-- Non-critical CSS (deferred loading) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'" crossorigin="anonymous" referrerpolicy="no-referrer">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/css/animate.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/animate.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/css/owl.carousel.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/owl.carousel.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/css/slick.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/slick.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/css/off-canvas.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/off-canvas.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/fonts/linea-fonts.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/fonts/linea-fonts.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/fonts/flaticon.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/fonts/flaticon.css')); ?>">
    </noscript>
    <link rel="preload" href="<?php echo e(asset('assets_telkom/assets/css/magnific-popup.css')); ?>" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/magnific-popup.css')); ?>">
    </noscript>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Additional CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- Inline Override for Navbar & Sub-Menu (from original template) -->
    <style>
        /* Direct Inline Override for Navbar & Sub-Menu */
        html body .full-width-header.header-style2 .rs-header .menu-area,
        html body .menu-area.menu-sticky {
            background-color: #21a7d0 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            padding: 4px 0 !important;
        }
        html body .logo-cat-wrap {
            display: flex !important;
            align-items: center !important;
            gap: 18px !important;
            flex-wrap: nowrap !important;
        }
        html body .brand-logo-wrap {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            text-decoration: none !important;
        }
        /* Logo badge & text size */
        html body .brand-logo-wrap .logo-badge {
            height: 60px !important;
            width: 60px !important;
            max-height: 60px !important;
            object-fit: contain !important;
            flex-shrink: 0 !important;
        }
        html body .brand-logo-wrap .logo-text {
            height: 44px !important;
            max-height: 44px !important;
            width: auto !important;
            object-fit: contain !important;
            flex-shrink: 0 !important;
        }

        /* TOP LEVEL NAV MENU ITEMS - CLEAN, FLEX-NOWRAP SINGLE ROW, ALWAYS SOLID WHITE (#ffffff) TEXT */
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu,
        html body .rs-menu ul.nav-menu {
            display: flex !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            align-items: center !important;
            justify-content: flex-end !important;
            margin: 0 !important;
            padding: 0 !important;
            gap: 2px !important;
        }

        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li,
        html body .rs-menu ul.nav-menu > li {
            background: transparent !important;
            background-color: transparent !important;
            display: inline-flex !important;
            align-items: center !important;
            flex-shrink: 0 !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            white-space: nowrap !important;
        }

        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a *,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li:hover > a,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li:hover > a *,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li.hover > a,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li.hover > a *,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a:hover,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a:hover *,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li.active-menu > a,
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li.current-menu-item > a {
            color: #ffffff !important;
            background: transparent !important;
            background-color: transparent !important;
            opacity: 1 !important;
            visibility: visible !important;
            text-shadow: none !important;
            box-shadow: none !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            letter-spacing: 0.3px;
            white-space: nowrap !important;
            padding: 20px 10px !important;
            border-radius: 0 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
        }

        /* HIDE ALL EXTRA JS ARROWS & CLOSE BUTTONS & PSEUDO ELEMENTS COMPLETELY */
        html body span.rs-menu-parent,
        html body .rs-menu-parent,
        html body .rs-menu-parent *,
        html body div.sub-menu-close,
        html body .sub-menu-close,
        html body .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a:after,
        html body .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.nav-menu > li > a:before,
        html body .rs-menu ul.nav-menu > li > a:after,
        html body .rs-menu ul.nav-menu > li > a:before,
        html body .rs-menu ul.sub-menu:before,
        html body .rs-menu ul.sub-menu:after {
            display: none !important;
            content: "" !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* SINGLE CLEAN SUB-MENU DROPDOWN WITH BORDER RADIUS & PERFECT CLIPPING */
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.sub-menu,
        html body .full-width-header .rs-header .menu-area .main-menu .rs-menu ul.sub-menu,
        html body .rs-menu ul.sub-menu {
            background-color: #ffffff !important;
            background: #ffffff !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.16) !important;
            border-radius: 8px !important;
            padding: 6px 0 !important;
            border: 1px solid #e2e8f0 !important;
            opacity: 1 !important;
            visibility: visible !important;
            overflow: hidden !important;
        }

        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.sub-menu li,
        html body .rs-menu ul.sub-menu li {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            background: #ffffff !important;
            background-color: #ffffff !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Normal sub-menu item state: DARK BLACK TEXT (#111111) ON WHITE BACKGROUND */
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.sub-menu li a,
        html body .full-width-header .rs-header .menu-area .main-menu .rs-menu ul.sub-menu li a,
        html body .rs-menu ul.sub-menu li a,
        html body .sub-menu li a {
            color: #111111 !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            line-height: 20px !important;
            padding: 12px 24px !important;
            background: #ffffff !important;
            background-color: #ffffff !important;
            text-shadow: none !important;
            display: block !important;
            text-align: left !important;
            opacity: 1 !important;
            visibility: visible !important;
            text-transform: capitalize !important;
            transition: all 0.2s ease !important;
        }

        /* Hover sub-menu item state: SOLID BLACK TEXT (#000000) WITH LIGHT GRAY ROW BACKGROUND (#F2F2F2) */
        html body .full-width-header.header-style2 .rs-header .menu-area .rs-menu-area .main-menu .rs-menu ul.sub-menu li a:hover,
        html body .full-width-header .rs-header .menu-area .main-menu .rs-menu ul.sub-menu li a:hover,
        html body .rs-menu ul.sub-menu li a:hover,
        html body .sub-menu li a:hover,
        html body .sub-menu li:hover > a {
            color: #000000 !important;
            background: #f2f2f2 !important;
            background-color: #f2f2f2 !important;
            padding-left: 28px !important;
            opacity: 1 !important;
            visibility: visible !important;
            font-weight: 700 !important;
            text-shadow: none !important;
        }
    </style>
</head>

<body class="home-style2">
    <!-- Telkom Header -->
    <?php if (isset($component)) { $__componentOriginal08b9220800d7090638b630079b92130b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08b9220800d7090638b630079b92130b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08b9220800d7090638b630079b92130b)): ?>
<?php $attributes = $__attributesOriginal08b9220800d7090638b630079b92130b; ?>
<?php unset($__attributesOriginal08b9220800d7090638b630079b92130b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08b9220800d7090638b630079b92130b)): ?>
<?php $component = $__componentOriginal08b9220800d7090638b630079b92130b; ?>
<?php unset($__componentOriginal08b9220800d7090638b630079b92130b); ?>
<?php endif; ?>

    <!-- main content -->
    <div class="main-content">
        <?php if(isset($slot)): ?>
            <?php echo e($slot); ?>

        <?php else: ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php endif; ?>
    </div>

    <!-- Telkom Footer -->
    <?php if (isset($component)) { $__componentOriginal3b683086c34eb0c5155234e67c56f9f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b683086c34eb0c5155234e67c56f9f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b683086c34eb0c5155234e67c56f9f6)): ?>
<?php $attributes = $__attributesOriginal3b683086c34eb0c5155234e67c56f9f6; ?>
<?php unset($__attributesOriginal3b683086c34eb0c5155234e67c56f9f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b683086c34eb0c5155234e67c56f9f6)): ?>
<?php $component = $__componentOriginal3b683086c34eb0c5155234e67c56f9f6; ?>
<?php unset($__componentOriginal3b683086c34eb0c5155234e67c56f9f6); ?>
<?php endif; ?>

    <!-- start scrollUp  -->
    <div id="scrollUp"><i class="fa fa-angle-up"></i></div>
    <!-- End scrollUp  -->

    <!-- Search Modal Start -->
    <div class="modal fade search-modal" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <button type="button" class="close" data-bs-dismiss="modal">
            <span class="flaticon-cross"></span>
        </button>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="search-block clearfix">
                    <form>
                        <div class="form-group">
                            <input class="form-control" placeholder="Search Here..." type="text">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->

    <!-- modernizr js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/modernizr-2.8.3.min.js')); ?>"></script>
    <!-- jquery latest version -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.min.js')); ?>"></script>
    <!-- Bootstrap v5.0.2 js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/bootstrap.min.js')); ?>"></script>
    <!-- Menu js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/rsmenu-main.js')); ?>"></script>
    <!-- op nav js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.nav.js')); ?>"></script>
    <!-- owl.carousel js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/owl.carousel.min.js')); ?>"></script>
    <!-- Slick js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/slick.min.js')); ?>"></script>
    <!-- isotope.pkgd.min js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/isotope.pkgd.min.js')); ?>"></script>
    <!-- imagesloaded.pkgd.min js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/imagesloaded.pkgd.min.js')); ?>"></script>
    <!-- wow js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/wow.min.js')); ?>"></script>
    <!-- Skill bar js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/skill.bars.jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.counterup.min.js')); ?>"></script>
    <!-- counter top js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/waypoints.min.js')); ?>"></script>
    <!-- video js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.mb.YTPlayer.min.js')); ?>"></script>
    <!-- magnific popup js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.magnific-popup.min.js')); ?>"></script>
    <!-- plugins js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/plugins.js')); ?>"></script>
    <!-- contact form js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/contact.form.js')); ?>"></script>
    <!-- main js -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/main.js')); ?>"></script>

    <!-- Custom Scripts -->
    <script>
        // Update copyright year
        const dateElements = document.querySelectorAll('#date, .current-year');
        dateElements.forEach(el => {
            el.innerHTML = new Date().getFullYear();
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '#!') {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    </script>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views\layouts\telkom.blade.php ENDPATH**/ ?>