<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ function_exists('is_rtl') && is_rtl() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#116E63">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="IG to Web">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Apple Touch Icons -->
    @if (cache('site_setting_favicon'))
        <link rel="apple-touch-icon" href="{{ Storage::url(cache('site_setting_favicon')) }}">
    @else
        <link rel="apple-touch-icon" href="{{ asset('assets/img/logo/favicon.png') }}">
    @endif

    <!-- title -->
    <title>{{ $pageTitle ?? cache('site_setting_site_name', 'Halaman Sekolah') }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/all-fontawesome.min.css') }}">

    <!-- favicon -->
    @if (cache('site_setting_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(cache('site_setting_favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="pb-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                window.showSuccess('Berhasil', '{{ session('success') }}');
            @endif

            @if (session('error'))
                window.showError('Gagal', '{{ session('error') }}');
            @endif
        });
    </script>
</body>

</html>
