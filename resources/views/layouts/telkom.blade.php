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
    <script src="{{ asset('assets_telkom/assets/js/modernizr-2.8.3.min.js') }}"></script>
    <!-- jquery latest version -->
    <script src="{{ asset('assets_telkom/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap v5.0.2 js -->
    <script src="{{ asset('assets_telkom/assets/js/bootstrap.min.js') }}"></script>
    <!-- Menu js -->
    <script src="{{ asset('assets_telkom/assets/js/rsmenu-main.js') }}"></script>
    <!-- op nav js -->
    <script src="{{ asset('assets_telkom/assets/js/jquery.nav.js') }}"></script>
    <!-- owl.carousel js -->
    <script src="{{ asset('assets_telkom/assets/js/owl.carousel.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset('assets_telkom/assets/js/slick.min.js') }}"></script>
    <!-- isotope.pkgd.min js -->
    <script src="{{ asset('assets_telkom/assets/js/isotope.pkgd.min.js') }}"></script>
    <!-- imagesloaded.pkgd.min js -->
    <script src="{{ asset('assets_telkom/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- wow js -->
    <script src="{{ asset('assets_telkom/assets/js/wow.min.js') }}"></script>
    <!-- Skill bar js -->
    <script src="{{ asset('assets_telkom/assets/js/skill.bars.jquery.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.counterup.min.js') }}"></script>
    <!-- counter top js -->
    <script src="{{ asset('assets_telkom/assets/js/waypoints.min.js') }}"></script>
    <!-- video js -->
    <script src="{{ asset('assets_telkom/assets/js/jquery.mb.YTPlayer.min.js') }}"></script>
    <!-- magnific popup js -->
    <script src="{{ asset('assets_telkom/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- plugins js -->
    <script src="{{ asset('assets_telkom/assets/js/plugins.js') }}"></script>
    <!-- contact form js -->
    <script src="{{ asset('assets_telkom/assets/js/contact.form.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets_telkom/assets/js/main.js') }}"></script>

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
