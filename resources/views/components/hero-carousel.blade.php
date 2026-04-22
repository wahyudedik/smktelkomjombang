@php
    $heroImages = cache('site_setting_hero_images');
    if ($heroImages) {
        $heroImages = json_decode($heroImages, true);
    }

    // Default hero slides sesuai dengan index.html
    $defaultSlides = [
        [
            'image' => asset('assets/img/slider/slider-1.jpg'),
            'subtitle' =>
                '<i class="far fa-book-open-reader"></i>' .
                (cache('site_setting_hero_slide1_subtitle') ?: 'Welcome To MAUDU Library'),
            'title' => cache('site_setting_hero_slide1_title') ?: 'Grand Opening <span>MAUDU</span> Library',
            'description' =>
                cache('site_setting_hero_slide1_description') ?:
                'Acara Grandopening Dihadiri oleh Majelis Pimpinan Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang',
        ],
        [
            'image' => asset('assets/img/slider/slider-2.jpg'),
            'subtitle' =>
                '<i class="far fa-book-open-reader"></i>' .
                (cache('site_setting_hero_slide2_subtitle') ?: 'Studi Edukasi Sosial'),
            'title' => cache('site_setting_hero_slide2_title') ?: 'Gedung <span>DPRD</span> Kabupaten Jombang',
            'description' => cache('site_setting_hero_slide2_description') ?: '',
        ],
        [
            'image' => asset('assets/img/slider/slider-3.jpg'),
            'subtitle' =>
                '<i class="far fa-book-open-reader"></i>' .
                (cache('site_setting_hero_slide3_subtitle') ?: 'Event KOMPASS'),
            'title' => cache('site_setting_hero_slide3_title') ?: 'Kompetisi Agama, <span>Sains,</span> dan Seni 2024',
            'description' => cache('site_setting_hero_slide3_description') ?: '',
        ],
    ];
@endphp

<!-- hero slider -->
<div class="hero-section">
    <div class="hero-slider owl-carousel owl-theme">
        @if ($heroImages && count($heroImages) > 0)
            {{-- Gunakan gambar dari admin panel --}}
            @foreach ($heroImages as $index => $image)
                @php
                    $slide = $defaultSlides[$index] ?? $defaultSlides[0];
                @endphp
                <div class="hero-single" style="background: url({{ Storage::url($image) }})">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-lg-7">
                                <div class="hero-content">
                                    <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                                        {!! $slide['subtitle'] !!}
                                    </h6>
                                    <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                        {!! $slide['title'] !!}
                                    </h1>
                                    @if ($slide['description'])
                                        <p data-animation="fadeInLeft" data-delay=".75s">
                                            {{ $slide['description'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Gunakan slides default sesuai index.html --}}
            @foreach ($defaultSlides as $index => $slide)
                <div class="hero-single" style="background: url({{ $slide['image'] }})">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-lg-7">
                                <div class="hero-content">
                                    <h6 class="hero-sub-title" data-animation="fadeInDown" data-delay=".25s">
                                        {!! $slide['subtitle'] !!}
                                    </h6>
                                    <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                        {!! $slide['title'] !!}
                                    </h1>
                                    @if ($slide['description'])
                                        <p data-animation="fadeInLeft" data-delay=".75s">
                                            {{ $slide['description'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<!-- hero slider end -->
