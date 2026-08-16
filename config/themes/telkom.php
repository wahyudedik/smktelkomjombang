<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme: SMK Telekomunikasi Darul Ulum
    |--------------------------------------------------------------------------
    */

    'name' => 'SMK Telekomunikasi Darul Ulum',
    'short_name' => 'SMK Telkom',
    'tagline' => '',
    'type' => 'SMK',

    // Contact Info
    'address' => 'Ponpes Darul Ulum Jombang',
    'phone' => '085649400339',
    'phone_secondary' => '(0321)868188',
    'whatsapp' => '6285649400339',
    'email' => 'smktelkomdujbg@gmail.com',
    'ppdb_url' => 'https://psb.ponpesdarululum.id/',

    // Social Media
    'facebook' => 'smktelkomdarululum',
    'instagram' => 'smktelkomdarululum',
    'youtube' => 'smktelkomdarululum',
    'facebook_url' => 'https://www.facebook.com/smktelkomdarululum',
    'instagram_url' => 'https://www.instagram.com/smktelkomdarululum',
    'youtube_url' => 'https://www.youtube.com/@smktelkomdarululum',
    'tiktok' => '',
    'tiktok_url' => '',
    'twitter' => '',
    'twitter_url' => '',
    'google_plus_url' => '',
    'pinterest_url' => '',

    // Assets
    'assets_path' => 'assets_telkom',
    'favicon' => 'assets_telkom/assets/images/fav.png',
    'logo' => 'assets_telkom/assets/images/logo.png',            // Dark text logo for light header (default state)
    'logo_light' => 'assets_telkom/assets/images/logo.png',      // Same logo for footer

    // Hero Slider (paths only — resolve with asset() in views/helpers)
    'hero_images' => [
        'assets_telkom/assets/images/slider/h2-2.jpg',   // Slide 1 (matches template)
        'assets_telkom/assets/images/slider/h2-1.jpg',   // Slide 2 (matches template)
    ],

    // Hero Slides — default text sesuai template telkom.html
    'hero_slides' => [
        [
            'subtitle'    => 'Penerimaan Siswa Baru 2026',
            'title'       => 'SMK Telekomunikasi<br>Darul Ulum Jombang',
            'description' => 'Berhardware Teknologi, Bersoftware Religi',
            'button_text' => 'DAFTAR PPDB',
            'button_url'  => 'https://psb.ponpesdarululum.id/',
            'button_target' => '_blank',
        ],
        [
            'subtitle'    => 'Program Keahlian Unggulan',
            'title'       => 'Siap Kerja &<br>Berkompeten',
            'description' => 'Produksi Film | DKV | TKJ | RPL',
            'button_text' => 'JELAJAHI JURUSAN',
            'button_url'  => '#rs-services',
            'button_target' => '',
        ],
    ],

    // Jurusan
    'jurusan' => [
        [
            'name' => 'TKJ',
            'full_name' => 'Teknik Komputer dan Jaringan',
            'desc' => 'Mempelajari jaringan komputer, administrasi server, dan keamanan siber.',
            'icon' => 'fas fa-network-wired',
        ],
        [
            'name' => 'RPL',
            'full_name' => 'Rekayasa Perangkat Lunak',
            'desc' => 'Mengembangkan aplikasi web, mobile, dan perangkat lunak lainnya.',
            'icon' => 'fas fa-code',
        ],
        [
            'name' => 'DKV',
            'full_name' => 'Desain Komunikasi Visual',
            'desc' => 'Desain grafis, animasi, ilustrasi, dan produksi media digital.',
            'icon' => 'fas fa-palette',
        ],
        [
            'name' => 'PROFILM',
            'full_name' => 'Produksi Film',
            'desc' => 'Produksi film, sinematografi, editing, dan post-produksi.',
            'icon' => 'fas fa-film',
        ],
    ],

    // Kepala Sekolah — default sesuai template telkom.html
    'kepala_sekolah' => [
        'name' => 'NUR LAILA,S.Pd',
        'photo' => '',
        'description' => 'Selamat datang di website resmi <b>SMK Telekomunikasi Darul Ulum Jombang.</b> Website ini menjadi sarana informasi bagi siswa, orang tua, alumni, dan masyarakat untuk mengetahui berbagai kegiatan serta perkembangan sekolah.',
    ],

    // Video
    'video_url' => 'https://www.youtube.com/watch?v=F5bnwy0lRZI',
    'video_thumbnail' => '',

    // CTA — default sesuai template telkom.html
    'cta_title' => 'Pendaftaran Siswa Baru',
    'cta_description' => "Tempat Pendaftaran\n1. Online mandiri (24 jam) dengan alamat web psb.ponpesdarululum.id\n2. Kantor Pusat Pondok Pesantren Darul 'Ulum Jombang\n3. Buka Hari Sabtu - Kamis pukul 08:00 - 16:00 WIB\n4. Hari Jum'at & hari libur nasional pendaftaran kantor pusat libur",
    'cta_button_url' => 'https://psb.ponpesdarululum.id/',
    'cta_button_text' => 'DAFTAR',

    // Working Hours
    'working_hours' => [
        'days' => 'Sabtu - Kamis',
        'hours' => '08:00 - 16:00 WIB',
    ],

    // Menu Navigasi (matches telkom.html template exactly)
    'menu' => [
        [
            'label' => 'Profil',
            'url' => '#',
            'children' => [
                ['label' => 'PP. Darul Ulum', 'url' => 'route:pages.public.show,pp-darul-ulum'],
                ['label' => 'Visi Misi SMK', 'url' => 'route:pages.public.show,visi-misi-smk'],
                ['label' => 'Struktur SMK', 'url' => 'route:pages.public.show,struktur-smk'],
            ],
        ],
        [
            'label' => 'Akademik',
            'url' => '#',
            'children' => [
                ['label' => 'Tenaga Pendidik', 'url' => 'route:pages.public.show,tenaga-pendidik'],
                ['label' => 'Staf & Karyawan', 'url' => 'route:pages.public.show,staf-karyawan'],
                ['label' => 'Jurusan', 'url' => '#rs-services'],
            ],
        ],
        [
            'label' => 'Layanan',
            'url' => '#',
            'children' => [
                ['label' => 'Rapor Digital', 'url' => '#'],
                ['label' => 'E-Semester', 'url' => '#'],
                ['label' => 'E-LMS', 'url' => '#'],
                ['label' => 'E-Perpus', 'url' => '#'],
                ['label' => 'E-Lulus', 'url' => 'route:public.graduation.check'],
            ],
        ],
        [
            'label' => 'Kontak',
            'url' => '#rs-contact',
        ],
        [
            'label' => 'INFORMASI PPDB',
            'url' => 'https://psb.ponpesdarululum.id/',
            'target' => '_blank',
        ],
    ],
];
