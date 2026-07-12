<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?php echo e($metaDescription ?? ($siteSettings['site_description'] ?? theme_config('name', 'MA Unggulan Darul Ulum Rejoso'))); ?>">
    <meta name="keywords"
        content="<?php echo e($metaKeywords ?? ($siteSettings['site_keywords'] ?? 'MA, Madrasah, Darul Ulum, Jombang')); ?>">

    <!-- title -->
    <title><?php echo e($pageTitle ?? ($siteSettings['site_name'] ?? theme_config('name'))); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- favicon -->
    <?php if(!empty($siteSettings['favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url($siteSettings['favicon'])); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset(theme_config('favicon'))); ?>">
    <?php endif; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/all-fontawesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/animate.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/magnific-popup.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_maudu/assets/css/style.css')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Additional CSS -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <!-- Preloader -->
    
    

    <!-- MAUDU Header -->
    <?php if (isset($component)) { $__componentOriginal0c2c1e1511e1a98403e769fae55079bd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c2c1e1511e1a98403e769fae55079bd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c2c1e1511e1a98403e769fae55079bd)): ?>
<?php $attributes = $__attributesOriginal0c2c1e1511e1a98403e769fae55079bd; ?>
<?php unset($__attributesOriginal0c2c1e1511e1a98403e769fae55079bd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c2c1e1511e1a98403e769fae55079bd)): ?>
<?php $component = $__componentOriginal0c2c1e1511e1a98403e769fae55079bd; ?>
<?php unset($__componentOriginal0c2c1e1511e1a98403e769fae55079bd); ?>
<?php endif; ?>

    <!-- main content -->
    <main class="main">
        <?php if(isset($slot)): ?>
            <?php echo e($slot); ?>

        <?php else: ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php endif; ?>
    </main>

    <!-- MAUDU Footer -->
    <?php if (isset($component)) { $__componentOriginalc8c58644f99595d862fc260329478371 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8c58644f99595d862fc260329478371 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8c58644f99595d862fc260329478371)): ?>
<?php $attributes = $__attributesOriginalc8c58644f99595d862fc260329478371; ?>
<?php unset($__attributesOriginalc8c58644f99595d862fc260329478371); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8c58644f99595d862fc260329478371)): ?>
<?php $component = $__componentOriginalc8c58644f99595d862fc260329478371; ?>
<?php unset($__componentOriginalc8c58644f99595d862fc260329478371); ?>
<?php endif; ?>

    <!-- Search Popup -->
    <div class="search-popup">
        <button class="close-search"><i class="fas fa-times"></i></button>
        <form action="#">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Cari...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="fas fa-arrow-up"></i></a>
    <!-- scroll-top end -->

    <!-- Scripts (same order as index.html) -->
    <script src="<?php echo e(asset('assets_maudu/assets/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/modernizr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/imagesloaded.pkgd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/jquery.magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/isotope.pkgd.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/jquery.appear.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/jquery.easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/counter-up.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/wow.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_maudu/assets/js/main.js')); ?>"></script>

    <!-- Custom Scripts -->
    <script>
        // Initialize WOW.js for animations
        new WOW().init();

        // Update copyright year
        const dateElements = document.querySelectorAll('#date, .current-year');
        dateElements.forEach(el => {
            el.innerHTML = new Date().getFullYear();
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '!') {
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

        // Search popup toggle
        $(document).on('click', '.search-btn', function(e) {
            e.preventDefault();
            $('.search-popup').addClass('active');
        });
        $(document).on('click', '.close-search', function() {
            $('.search-popup').removeClass('active');
        });
    </script>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views/layouts/maudu.blade.php ENDPATH**/ ?>