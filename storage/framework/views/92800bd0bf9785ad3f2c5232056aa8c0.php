<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- title -->
    <title><?php echo e($pageTitle ?? cache('site_setting_site_name', 'Halaman Sekolah')); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- favicon -->
    <?php if(cache('site_setting_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url(cache('site_setting_favicon'))); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/logo/favicon.png')); ?>">
    <?php endif; ?> 

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="font-sans text-gray-900 antialiased">
    <?php echo e($slot); ?>

</body>

</html>
<?php /**PATH D:\PROJECT\LARAVEL\ig-to-web\resources\views/layouts/guest.blade.php ENDPATH**/ ?>