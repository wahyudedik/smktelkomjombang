<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="{{ $metaDescription ?? ($siteSettings['site_description'] ?? theme_config('name', 'MA Unggulan Darul Ulum Rejoso')) }}">
    <meta name="keywords"
        content="{{ $metaKeywords ?? ($siteSettings['site_keywords'] ?? 'MA, Madrasah, Darul Ulum, Jombang') }}">

    <!-- title -->
    <title>{{ $pageTitle ?? ($siteSettings['site_name'] ?? theme_config('name')) }} - {{ config('app.name') }}</title>

    <!-- favicon -->
    @if (!empty($siteSettings['favicon']))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($siteSettings['favicon']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset(theme_config('favicon')) }}">
    @endif

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/all-fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_maudu/assets/css/style.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Additional CSS -->
    <style>
        /* Fix: prevent double scrollbar and overflow issues */
        html, body {
            overflow-x: hidden !important;
        }
        /* Hide scrollbar ganda dari owl-carousel atau elemen lain */
        .owl-carousel .owl-stage,
        .partner-wrapper,
        .testimonial-slider {
            overflow: hidden !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    {{-- Uncomment if preloader is needed --}}
    {{-- <div class="preloader">
        <div class="loader-book">
            <div class="book-page"></div>
        </div>
    </div> --}}

    <!-- MAUDU Header -->
    <x-maudu.header />

    <!-- main content -->
    <main class="main">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <!-- MAUDU Footer -->
    <x-maudu.footer />

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
    <script src="{{ asset('assets_maudu/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/counter-up.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets_maudu/assets/js/main.js') }}"></script>

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
    @stack('scripts')
</body>

</html>
