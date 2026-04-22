<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    dir="<?php echo e(function_exists('is_rtl') && is_rtl() ? 'rtl' : 'ltr'); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#116E63">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="IG to Web">
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">

    <!-- Apple Touch Icons -->
    <?php if(cache('site_setting_favicon')): ?>
        <link rel="apple-touch-icon" href="<?php echo e(Storage::url(cache('site_setting_favicon'))); ?>">
    <?php else: ?>
        <link rel="apple-touch-icon" href="<?php echo e(asset('assets/img/logo/favicon.png')); ?>">
    <?php endif; ?>

    <!-- title -->
    <title><?php echo e($pageTitle ?? cache('site_setting_site_name', 'Halaman Sekolah')); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/all-fontawesome.min.css')); ?>">

    <!-- favicon -->
    <?php if(cache('site_setting_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url(cache('site_setting_favicon'))); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/logo/favicon.png')); ?>">
    <?php endif; ?>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="pb-8">
            <?php echo e($slot); ?>

        </main>
    </div>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                window.showSuccess('Berhasil', '<?php echo e(session('success')); ?>');
            <?php endif; ?>

            <?php if(session('error')): ?>
                window.showError('Gagal', '<?php echo e(session('error')); ?>');
            <?php endif; ?>
        });
    </script>
</body>

</html>
<?php /**PATH D:\PROJECT\LARAVEL\ig-to-web\resources\views/layouts/app.blade.php ENDPATH**/ ?>