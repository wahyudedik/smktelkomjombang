<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
    dir="<?php echo e(function_exists('is_rtl') && is_rtl() ? 'rtl' : 'ltr'); ?>" x-data="darkMode()"
    :class="{ 'dark': $store.darkMode?.active }">

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
    <link rel="apple-touch-icon"
        href="<?php echo e(theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png'))); ?>">

    <!-- title -->
    <title><?php echo e($pageTitle ?? cache('site_setting_site_name', 'Halaman Sekolah')); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/all-fontawesome.min.css')); ?>">

    <!-- favicon -->
    <link rel="icon" type="image/x-icon"
        href="<?php echo e(theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png'))); ?>">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Additional Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-dark-900 transition-colors duration-300" x-data>

    <!-- Dark Mode Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                active: localStorage.getItem('darkMode') === 'true',
                toggle() {
                    this.active = !this.active;
                    localStorage.setItem('darkMode', this.active);
                }
            });
        });

        function darkMode() {
            return {
                init() {
                    if (localStorage.getItem('darkMode') === 'true') {
                        document.documentElement.classList.add('dark');
                    }
                }
            }
        }

        // Dark mode toggle component (shared across all admin pages)
        function darkModeToggle() {
            return {
                active: localStorage.getItem('darkMode') === 'true',
                toggle() {
                    this.active = !this.active;
                    localStorage.setItem('darkMode', this.active);
                    if (this.active) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            };
        }
    </script>
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
        <main class="pb-8 dark:text-dark-100">
            <?php echo e($slot); ?>

        </main>
    </div>

    <!-- Additional Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Initialize dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                window.showSuccess && window.showSuccess('Berhasil', '<?php echo e(session('success')); ?>');
            <?php endif; ?>

            <?php if(session('error')): ?>
                window.showError && window.showError('Gagal', '<?php echo e(session('error')); ?>');
            <?php endif; ?>
        });
    </script>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views\layouts\app.blade.php ENDPATH**/ ?>