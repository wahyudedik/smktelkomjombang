<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?php echo e($metaDescription ?? cache('site_setting_site_description', 'SMK Telekomunikasi Darul Ulum Jombang')); ?>">
    <meta name="keywords"
        content="<?php echo e($metaKeywords ?? cache('site_setting_site_keywords', 'SMK, Telekomunikasi, Jombang')); ?>">

    <!-- title -->
    <title><?php echo e($pageTitle ?? 'SMK Telekomunikasi Darul Ulum Jombang'); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- favicon -->
    <?php if(cache('site_setting_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url(cache('site_setting_favicon'))); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets_telkom/assets/images/fav.png')); ?>">
    <?php endif; ?>

    <!-- css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/animate.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/owl.carousel.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/slick.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/off-canvas.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/fonts/linea-fonts.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/fonts/flaticon.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/rsmenu-main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/rs-spacing.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_telkom/assets/css/responsive.css')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Additional CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>
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

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="fa fa-arrow-up"></i></a>
    <!-- scroll-top end -->

    <!-- Scripts -->
    <script src="<?php echo e(asset('assets_telkom/assets/js/modernizr-2.8.3.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/rsmenu-main.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.nav.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/slick.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/isotope.pkgd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/imagesloaded.pkgd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/wow.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/skill.bars.jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.counterup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/waypoints.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.mb.YTPlayer.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/jquery.magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/plugins.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_telkom/assets/js/contact.form.js')); ?>"></script>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/layouts/telkom.blade.php ENDPATH**/ ?>