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
    'whatsapp_url' => 'https://wa.me/628113383722',
    'google_maps_url' => 'https://share.google/XB6eTt65kqQF9xYVR',
    'email' => 'adminmaudu@gmail.com',
    'ppdb_url' => 'https://psb.ponpesdarululum.id/',
    'linktree_url' => 'https://linktr.ee/maudu',

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
    'logo_icon' => 'assets_maudu/assets/img/logo/favicon.png',
    'logo_text' => 'assets_maudu/assets/img/logo/logo nama.png',
    'logo_light' => 'assets_maudu/assets/img/logo/logo-light.png',

    // Hero Slider (paths only — resolve with asset() in views/helpers)
    'hero_images' => [
        'assets_maudu/assets/img/slider/slider-1.jpg',
        'assets_maudu/assets/img/slider/slider-2.jpg',
        'assets_maudu/assets/img/slider/slider-3.jpg',
    ],

    // Hero Slide Text (content per slide — from template default)
    'hero_slides' => [
        [
            'subtitle' => 'Welcome To MAUDU Library',
            'title' => 'Grand Opening <span>MAUDU</span> Library',
            'description' => 'Acara Grandopening Dihadiri oleh Majelis Pimpinan Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang',
        ],
        [
            'subtitle' => 'Studi Edukasi Sosial',
            'title' => 'Gedung <span>DPRD</span> Kabupaten Jombang',
            'description' => '',
        ],
        [
            'subtitle' => 'Event KOMPASS',
            'title' => 'Kompetisi Agama, <span>Sains,</span> dan Seni 2024',
            'description' => '',
        ],
    ],

    // Program Peminatan (khusus MA) — full descriptions from template
    'program_peminatan' => [
        [
            'name' => 'IPA',
            'full_name' => 'PEMINATAN ILMU PENGETAHUAN ALAM (IPA)',
            'desc' => 'Menyiapkan peserta didik yang handal dalam kajian ilmiah dan alamiah dengan berlandaskan kepada ayat-ayat qauliyah dan kauniyah.',
            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
            'icon' => 'fas fa-flask',
        ],
        [
            'name' => 'IPS',
            'full_name' => 'PEMINATAN ILMU PENGETAHUAN SOSIAL (IPS)',
            'desc' => 'Menyiapkan peserta didik yang dapat menguasai ilmu-ilmu sosial secara terpadu antara keislaman dan pengetahuan sehingga menjadi insan yang sosialis-agamis.',
            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
            'icon' => 'fas fa-globe',
        ],
        [
            'name' => 'Keagamaan',
            'full_name' => 'PEMINATAN KEAGAMAAN',
            'desc' => 'Menyiapkan peserta didik yang lebih mampu menguasai ilmu-ilmu agama dengan mengkaji sumber aslinya serta mengkolaborasikan dengan perkembangan IPTEK.',
            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
            'icon' => 'fas fa-book-quran',
        ],
    ],

    // Jurusan — alias to program_peminatan for cross-theme component compatibility.
    // Resolved automatically by ThemeHelper; do not duplicate data here.

    // Fitur Unggulan MAUDU — descriptions from template
    'features' => [
        [
            'title' => 'E-LIBRARY',
            'desc' => 'Perpustakaan digital berisi Koleksi materi dalam format elektronik',
            'icon_path' => 'assets_maudu/assets/img/icon/library.svg',
            'icon' => 'fas fa-book-open',
        ],
        [
            'title' => 'SERTIFIKASI KOMPETENSI',
            'desc' => 'Uji kompetensi yang sistematis dan objektif',
            'icon_path' => 'assets_maudu/assets/img/icon/teacher-2.svg',
            'icon' => 'fas fa-certificate',
        ],
        [
            'title' => 'KARYA LITERASI',
            'desc' => 'Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.',
            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
            'icon' => 'fas fa-pen-fancy',
        ],
    ],

    // Program Unggulan — full descriptions from template
    'program_unggulan' => [
        [
            'title' => 'KURIKULUM MADRASAH',
            'desc' => 'Kolaborasi antara kurikulum Kepesantrenan, Kemendikbud, Kemenag dan Kurikulum Muatan Lokal Madrasah',
            'icon_path' => 'assets_maudu/assets/img/icon/information.svg',
            'icon' => 'fas fa-graduation-cap',
        ],
        [
            'title' => 'PROGRAM STUDI KE TIMUR TENGAH',
            'desc' => 'Pembinaan Intensif dan Mediator Pemberangkatan',
            'icon_path' => 'assets_maudu/assets/img/icon/global-education.svg',
            'icon' => 'fas fa-plane-departure',
        ],
        [
            'title' => 'KELAS TAHFIDZ, MUATAN LOKAL KITAB TURATS',
            'desc' => 'Kelas Tahfidz, Program Tahfidz serta Program Pembiasaan Siswa',
            'icon_path' => 'assets_maudu/assets/img/icon/open-book.svg',
            'icon' => 'fas fa-book-quran',
        ],
        [
            'title' => 'PROGRAM KEMASYARAKATAN',
            'desc' => 'Kafilah Sholat Jum\'at, Sholat Tarawih, TPQ, Bakti Sosial dan Pengabdian Masyarakat',
            'icon_path' => 'assets_maudu/assets/img/icon/location.svg',
            'icon' => 'fas fa-hands-helping',
        ],
    ],

    // Kepala Madrasah
    'kepala_sekolah' => [
        'name' => 'Khoiruddinul Qoyyum,S.S.,M.Pd',
        'photo' => '',
        'description' => 'Selamat datang di Website Resmi Madrasah Aliyah Unggulan Darul \'Ulum Rejoso. Dengan rahmat Allah SWT, website ini menjadi media informasi, silaturahmi, dan komunikasi bagi siswa, alumni, orang tua, serta masyarakat. Kami menyajikan profil madrasah, kegiatan, prestasi, dan berbagai layanan pendidikan.',
        'description_2' => 'Semoga kehadiran website ini memberikan manfaat, mempererat kebersamaan, serta mendukung terwujudnya pendidikan yang unggul, berkarakter, dan berorientasi pada masa depan. Kritik dan saran sangat kami harapkan demi kemajuan bersama.',
    ],

    // Video
    'video_url' => 'https://www.youtube.com/watch?v=ckHzmP1evNU',
    'video_thumbnail' => 'assets_maudu/assets/img/video/01.jpg',

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
                ['label' => 'Yayasan', 'url' => 'route:pages.public.show,tentang-yayasan'],
                ['label' => 'MAUDU', 'url' => 'route:pages.public.show,tentang-madrasah'],
                ['label' => 'Prestasi Siswa', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'Gallery', 'url' => '#'], // TODO: Add route when feature is implemented
            ],
        ],
        [
            'label' => 'AKADEMIK',
            'url' => '#',
            'children' => [
                ['label' => 'Tenaga Pendidik', 'url' => 'route:pages.public.index'],
                ['label' => 'Jurusan', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'Kalender Akademik', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'Studi Ekskursi', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'Studi Kampus', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'Ekstrakurikuler', 'url' => '#'], // TODO: Add route when feature is implemented
            ],
        ],
        [
            'label' => 'LAYANAN PESERTA DIDIK',
            'url' => '#',
            'children' => [
                ['label' => 'E-Siswa & Alumni', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'E-Raport', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'E-OSIS', 'url' => '#'], // TODO: Add route when feature is implemented
                ['label' => 'E-Lulus', 'url' => 'route:public.graduation.check'],
                ['label' => 'E-Majalah', 'url' => '#'], // TODO: Add route when feature is implemented
            ],
        ],
        [
            'label' => 'EVENT MAUDU',
            'url' => 'route:public.kegiatan',
        ],
    ],
];
