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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <!-- Back to Top Button (Alpine.js powered) -->
    <div x-data="backToTop()" x-init="init()" class="fixed bottom-6 right-6 z-50">
        <button
            x-show="visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75"
            @click="scrollToTop()"
            class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-dark-900"
            title="Kembali ke atas"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>
    </div>

    <script>
        // Back to Top Alpine.js component
        function backToTop() {
            return {
                visible: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.visible = window.scrollY > 300;
                    }, { passive: true });
                },
                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            };
        }
    </script>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views/layouts/app.blade.php ENDPATH**/ ?>