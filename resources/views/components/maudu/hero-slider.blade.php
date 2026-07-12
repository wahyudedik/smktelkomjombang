<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-slider owl-carousel owl-theme">
        @php
            $slides = [
                [
                    'title' => 'Grand Opening MAUDU Library',
                    'subtitle' => 'Selamat Datang di',
                    'description' => 'Perpustakaan digital modern untuk mendukung pembelajaran siswa.',
                    'image' => theme_config('hero_images.0', asset('assets_maudu/assets/img/slider/slider-1.jpg')),
                ],
                [
                    'title' => 'Gedung DPRD Kabupaten Jombang',
                    'subtitle' => 'Kerjasama Strategis',
                    'description' => 'MAUDU menjalin kerjasama dengan berbagai institusi pemerintah.',
                    'image' => theme_config('hero_images.1', asset('assets_maudu/assets/img/slider/slider-2.jpg')),
                ],
                [
                    'title' => 'Kompetisi Agama, Sains, dan Seni 2024',
                    'subtitle' => 'Prestasi Gemilang',
                    'description' => 'Siswa MAUDU meraih prestasi di berbagai kompetisi tingkat nasional.',
                    'image' => theme_config('hero_images.2', asset('assets_maudu/assets/img/slider/slider-3.jpg')),
                ],
            ];
        @endphp

        @foreach ($slides as $index => $slide)
            <div class="hero-single" style="background: url('{{ $slide['image'] }}')">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-lg-7">
                            <div class="hero-content">
                                <h6 class="hero-subtitle" data-animation="fadeInUp" data-delay="0.3s">
                                    {{ $slide['subtitle'] }}
                                </h6>
                                <h1 class="hero-title" data-animation="fadeInUp" data-delay="0.5s">
                                    {{ $slide['title'] }}
                                </h1>
                                <p class="hero-description" data-animation="fadeInUp" data-delay="0.7s">
                                    {{ $slide['description'] }}
                                </p>
                                <div class="hero-btn" data-animation="fadeInUp" data-delay="0.9s">
                                    <a class="btn btn-primary" href="{{ theme_config('ppdb_url', '#') }}"
                                        target="_blank">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- Hero Section End -->
