<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- title -->
    <title>{{ $pageTitle ?? 'Buku Tamu' }} - {{ theme_config('name', config('app.name')) }}</title>

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon"
        href="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png')) }}">

    <!-- favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png')) }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $themeColors = theme_info('colors', ['primary' => '#00529C', 'secondary' => '#003366']);
        $primaryColor = $themeColors['primary'] ?? '#00529C';
        $secondaryColor = $themeColors['secondary'] ?? '#003366';
    @endphp

    <style>
        :root {
            --theme-primary: {{ $primaryColor }};
            --theme-secondary: {{ $secondaryColor }};
        }

        .guest-book-header {
            background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);
        }

        .guest-book-btn {
            background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);
        }

        .guest-book-btn:hover {
            background: linear-gradient(135deg, color-mix(in srgb, var(--theme-primary) 90%, black) 0%, color-mix(in srgb, var(--theme-secondary) 90%, black) 100%);
        }

        .guest-book-step {
            background-color: color-mix(in srgb, var(--theme-primary) 15%, white);
            color: var(--theme-primary);
        }

        .guest-book-focus:focus {
            --tw-ring-color: var(--theme-primary);
            border-color: var(--theme-primary);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    {{ $slot }}
</body>

</html>
