@props(['title' => null])

{{--
    Layout standalone untuk halaman publik E-OSIS (tanpa login).
    Tailwind + Vite (app.css/app.js) — sama dengan stack admin, tapi tanpa
    sidebar navigasi. Logo/favicon theme-aware via theme_image() sehingga
    berfungsi untuk semua tema (telkom, maudu, dst).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Pemilihan OSIS' }} - {{ theme_config('name', config('app.name')) }}</title>

    <!-- favicon (theme-aware) -->
    <link rel="icon" type="image/x-icon"
        href="{{ theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png')) }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 min-w-0">
                <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png')) }}"
                    alt="{{ theme_config('name', config('app.name')) }}" class="h-10 w-auto flex-shrink-0">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-900 truncate">
                        {{ theme_config('name', config('app.name')) }}</p>
                    <p class="text-xs text-slate-500">E-OSIS — Pemilihan OSIS Online</p>
                </div>
            </a>
            <nav class="flex items-center space-x-4 flex-shrink-0">
                <a href="{{ route('osis.public.landing') }}"
                    class="text-sm text-slate-600 hover:text-slate-900 font-medium hidden sm:inline">Vote</a>
                <a href="{{ route('osis.public.results') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium">Hasil Pemilihan</a>
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ theme_config('name', config('app.name')) }} — Sistem Pemilihan OSIS Online
        </div>
    </footer>
</body>

</html>
