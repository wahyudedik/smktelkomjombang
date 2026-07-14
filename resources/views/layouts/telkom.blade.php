<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $metaDescription ?? ($siteSettings['site_description'] ?? 'SMK Telekomunikasi Darul Ulum Jombang') }}">
    <meta name="keywords"
        content="{{ $metaKeywords ?? ($siteSettings['site_keywords'] ?? 'SMK, Telekomunikasi, Jombang') }}">

    <!-- title -->
    <title>{{ $pageTitle ?? ($siteSettings['site_name'] ?? 'SMK Telekomunikasi Darul Ulum Jombang') }} -
        {{ config('app.name') }}</title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png')) }}">

    <!-- Critical CSS (render-blocking) -->
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/rsmenu-main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/rs-spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/responsive.css') }}">

    <!-- Non-critical CSS (deferred loading) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'" crossorigin="anonymous" referrerpolicy="no-referrer">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/css/animate.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/animate.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/css/owl.carousel.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/owl.carousel.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/css/slick.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/slick.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/css/off-canvas.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/off-canvas.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/fonts/linea-fonts.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/fonts/linea-fonts.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/fonts/flaticon.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/fonts/flaticon.css') }}">
    </noscript>
    <link rel="preload" href="{{ asset('assets_telkom/assets/css/magnific-popup.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/magnific-popup.css') }}">
    </noscript>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Additional CSS -->
    @stack('styles')
</head>

<body class="home-style2">
    <!-- Telkom Header -->
    <x-telkom.header />

    <!-- main content -->
    <div class="main-content">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </div>

    <!-- Telkom Footer -->
    <x-telkom.footer />

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="fa fa-arrow-up"></i></a>
    <!-- scroll-top end -->

    <!-- Scripts (defer for non-blocking page rendering) -->
    <script src="{{ asset('assets_telkom/assets/js/modernizr-2.8.3.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/rsmenu-main.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.nav.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/owl.carousel.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/slick.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/isotope.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/wow.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/skill.bars.jquery.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.counterup.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/waypoints.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.mb.YTPlayer.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.magnific-popup.min.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/plugins.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/contact.form.js') }}" defer></script>
    <script src="{{ asset('assets_telkom/assets/js/main.js') }}" defer></script>

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
    @stack('scripts')
</body>

</html>
