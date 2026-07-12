<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme: MA Unggulan Darul Ulum Rejoso
    |--------------------------------------------------------------------------
    */

    'name' => 'MA Unggulan Darul Ulum Rejoso',
    'short_name' => 'MAUDU',
    'tagline' => 'Madrasah Hebat, Bermartabat',
    'type' => 'MA',

    // Contact Info
    'address' => 'Jl. Wonokerto Selatan, Peterongan, Jombang',
    'phone' => '(0321) 868911',
    'phone_secondary' => '',
    'whatsapp' => '628113383722',
    'email' => 'adminmaudu@gmail.com',
    'ppdb_url' => 'https://psb.ponpesdarululum.id/',

    // Social Media
    'facebook' => 'OfficialMaudu',
    'instagram' => 'official_maudu',
    'youtube' => 'OfficialMaudu-f6b',
    'facebook_url' => 'https://www.facebook.com/OfficialMaudu',
    'instagram_url' => 'https://www.instagram.com/official_maudu',
    'youtube_url' => 'https://www.youtube.com/@OfficialMaudu-f6b',
    'tiktok' => '',
    'tiktok_url' => '',

    // Assets
    'assets_path' => 'assets_maudu',
    'favicon' => 'assets_maudu/assets/img/logo/favicon.png',
    'logo' => 'assets_maudu/assets/img/logo/logo.png',
    'logo_light' => 'assets_maudu/assets/img/logo/logo-light.png',

    // Hero Slider (paths only — resolve with asset() in views/helpers)
    'hero_images' => [
        'assets_maudu/assets/img/slider/slider-1.jpg',
        'assets_maudu/assets/img/slider/slider-2.jpg',
        'assets_maudu/assets/img/slider/slider-3.jpg',
    ],

    // Program Peminatan (khusus MA)
    'program_peminatan' => [
        [
            'name' => 'IPA',
            'full_name' => 'Peminatan Ilmu Pengetahuan Alam',
            'desc' => 'Program peminatan sains dan teknologi.',
            'icon' => 'fas fa-flask',
        ],
        [
            'name' => 'IPS',
            'full_name' => 'Peminatan Ilmu Pengetahuan Sosial',
            'desc' => 'Program peminatan sosial dan humaniora.',
            'icon' => 'fas fa-globe',
        ],
        [
            'name' => 'Keagamaan',
            'full_name' => 'Peminatan Keagamaan',
            'desc' => 'Program peminatan keislaman dan tahfidz.',
            'icon' => 'fas fa-book-quran',
        ],
    ],

    // Jurusan — alias to program_peminatan for cross-theme component compatibility.
    // Resolved automatically by ThemeHelper; do not duplicate data here.

    // Fitur Unggulan MAUDU
    'features' => [
        [
            'title' => 'E-LIBRARY',
            'desc' => 'Perpustakaan digital berisi koleksi materi dalam format elektronik.',
            'icon' => 'fas fa-book-open',
        ],
        [
            'title' => 'SERTIFIKASI KOMPETENSI',
            'desc' => 'Uji kompetensi yang sistematis dan objektif.',
            'icon' => 'fas fa-certificate',
        ],
        [
            'title' => 'KARYA LITERASI',
            'desc' => 'Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.',
            'icon' => 'fas fa-pen-fancy',
        ],
    ],

    // Program Unggulan
    'program_unggulan' => [
        [
            'title' => 'KURIKULUM MADRASAH',
            'desc' => 'Kolaborasi kurikulum Kepesantrenan, Kemendikbud, Kemenag.',
            'icon' => 'fas fa-graduation-cap',
        ],
        [
            'title' => 'PROGRAM STUDI KE TIMUR TENGAH',
            'desc' => 'Pembinaan Intensif dan Mediator Pemberangkatan.',
            'icon' => 'fas fa-plane-departure',
        ],
        [
            'title' => 'KELAS TAHFIDZ, MUATAN LOKAL KITAB TURATS',
            'desc' => 'Program Tahfidz serta Pembiasaan Siswa.',
            'icon' => 'fas fa-book-quran',
        ],
        [
            'title' => 'PROGRAM KEMASYARAKATAN',
            'desc' => 'Kafilah Sholat Jum\'at, TPQ, Bakti Sosial.',
            'icon' => 'fas fa-hands-helping',
        ],
    ],

    // Kepala Madrasah
    'kepala_sekolah' => [
        'name' => '',
        'photo' => '',
        'description' => '',
    ],

    // Video
    'video_url' => 'https://www.youtube.com/watch?v=ckHzmP1evNU',
    'video_thumbnail' => '',

    // CTA
    'cta_title' => 'Pendaftaran Peserta Didik Baru',
    'cta_description' => 'Buka Hari Senin - Sabtu pukul 08:00 - 16:00 WIB',
    'cta_button_url' => 'https://psb.ponpesdarululum.id/',
    'cta_button_text' => 'Daftar Sekarang',

    // Working Hours
    'working_hours' => [
        'days' => 'Sabtu - Kamis',
        'hours' => '08:00 - 16:00 WIB',
    ],

    // Menu Navigasi
    'menu' => [
        [
            'label' => 'PROFIL',
            'url' => '#',
            'children' => [
                ['label' => 'Yayasan', 'url' => '#'],
                ['label' => 'MAUDU', 'url' => '#'],
                ['label' => 'Prestasi Siswa', 'url' => '#'],
                ['label' => 'Gallery', 'url' => '#'],
            ],
        ],
        [
            'label' => 'AKADEMIK',
            'url' => '#',
            'children' => [
                ['label' => 'Tenaga Pendidik', 'url' => '#'],
                ['label' => 'Jurusan', 'url' => '#'],
                ['label' => 'Kalender Akademik', 'url' => '#'],
                ['label' => 'Studi Ekskursi', 'url' => '#'],
                ['label' => 'Studi Kampus', 'url' => '#'],
                ['label' => 'Ekstrakurikuler', 'url' => '#'],
            ],
        ],
        [
            'label' => 'LAYANAN PESERTA DIDIK',
            'url' => '#',
            'children' => [
                ['label' => 'E-Siswa & Alumni', 'url' => '#'],
                ['label' => 'E-Raport', 'url' => '#'],
                ['label' => 'E-OSIS', 'url' => '#'],
                ['label' => 'E-Lulus', 'url' => '#'],
                ['label' => 'E-Majalah', 'url' => '#'],
            ],
        ],
        [
            'label' => 'EVENT MAUDU',
            'url' => '#event-maudu',
        ],
    ],
];
