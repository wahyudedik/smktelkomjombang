<?php

/**
 * Theme Registry — Pusat definisi semua tema yang tersedia.
 *
 * Menambah tema baru只需 tambah entry di 'available' array.
 * Controller TIDAK perlu diubah karena semua resolve via helper.
 *
 * @see plans/theme-system-refactoring.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | Override via environment variable: DEFAULT_THEME=telkom atau DEFAULT_THEME=maudu
    |
    */
    'default' => env('DEFAULT_THEME', 'telkom'),

    /*
    |--------------------------------------------------------------------------
    | Available Themes
    |--------------------------------------------------------------------------
    |
    | Setiap tema harus punya minimal: name, view, layout, assets_path, defaults.
    | 'defaults' berisi fallback image paths jika admin belum upload via Theme Settings.
    |
    | 🆕 Untuk menambah tema baru, cukup tambah entry di bawah ini.
    |    Lihat plans/theme-system-refactoring.md → Panduan: Menambah Tema Baru.
    |
    */
    'available' => [

        // ─── Telkom ──────────────────────────────────────────────
        'telkom' => [
            'name'        => 'SMK Telekomunikasi Darul Ulum',
            'short_name'  => 'SMK Telkom',
            'type'        => 'SMK',
            'view'        => 'telkom',              // Landing page view (telkom.blade.php)
            'layout'      => 'layouts.telkom',      // Base layout
            'assets_path' => 'assets_telkom',       // Public assets directory
            'components'  => 'components.telkom',    // Blade component namespace
            'colors'      => [
                'primary'   => '#00529C',
                'secondary' => '#003366',
            ],
            'defaults'    => [
                'favicon'    => 'assets_telkom/assets/images/fav.png',
                'logo'       => 'assets_telkom/assets/images/logo-dark.png',   // Dark logo for light header (default state)
                'logo_light' => 'assets_telkom/assets/images/logo.png',        // Light/white logo for dark header (sticky state)
            ],
        ],

        // ─── MAUDU ───────────────────────────────────────────────
        'maudu' => [
            'name'        => 'MA Unggulan Darul Ulum Rejoso',
            'short_name'  => 'MAUDU',
            'type'        => 'MA',
            'view'        => 'maudu',
            'layout'      => 'layouts.maudu',
            'assets_path' => 'assets_maudu',
            'components'  => 'components.maudu',
            'colors'      => [
                'primary'   => '#1a5632',
                'secondary' => '#0d3d21',
            ],
            'defaults'    => [
                'favicon'    => 'assets_maudu/assets/img/logo/favicon.png',
                'logo'       => 'assets_maudu/assets/img/logo/logo.png',
                'logo_light' => 'assets_maudu/assets/img/logo/logo-light.png',
            ],
        ],

        // ─── Template: Tema Baru ─────────────────────────────────
        // Copy block ini dan ganti nilai untuk menambah tema baru.
        // Controller TIDAK perlu diubah — semuanya resolve otomatis.
        //
        // 'smk_xyz' => [
        //     'name'        => 'SMK XYZ',
        //     'short_name'  => 'SMK XYZ',
        //     'type'        => 'SMK',
        //     'view'        => 'smk_xyz',
        //     'layout'      => 'layouts.smk_xyz',
        //     'assets_path' => 'assets_smk_xyz',
        //     'components'  => 'components.smk_xyz',
        //     'colors'      => [
        //         'primary'   => '#ff0000',
        //         'secondary' => '#cc0000',
        //     ],
        //     'defaults'    => [
        //         'favicon'    => 'assets_smk_xyz/images/favicon.png',
        //         'logo'       => 'assets_smk_xyz/images/logo.png',
        //         'logo_light' => 'assets_smk_xyz/images/logo-light.png',
        //     ],
        // ],
    ],
];
