<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $metaDescription ?? cache('site_setting_site_description', 'SMK Telekomunikasi Darul Ulum Jombang') }}">
    <meta name="keywords"
        content="{{ $metaKeywords ?? cache('site_setting_site_keywords', 'SMK, Telekomunikasi, Jombang') }}">

    <!-- title -->
    <title>{{ $pageTitle ?? 'SMK Telekomunikasi Darul Ulum Jombang' }} - {{ config('app.name') }}</title>

    <!-- favicon -->
    @if (cache('site_setting_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(cache('site_setting_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets_telkom/assets/images/fav.png') }}">
    @endif

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/off-canvas.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/fonts/linea-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/fonts/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/rsmenu-main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/rs-spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_telkom/assets/css/responsive.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
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

    <!-- Scripts -->
    <script src="{{ asset('assets_telkom/assets/js/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/rsmenu-main.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.nav.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/skill.bars.jquery.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.mb.YTPlayer.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets_telkom/assets/js/contact.form.js') }}"></script>
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
