@props([
    'title' => 'Portal Digital Pendidikan',
    'subtitle' => 'Selamat Datang Di Portal Digital Pendidikan',
    'description' =>
        'Website sekolah yang mengintegrasikan semua layanan pendidikan dalam satu platform digital yang modern dan efisien.',
    'backgroundImage' => null,
    'showCarousel' => false,
    'carouselItems' => [],
])

<!-- hero area -->
<section class="hero-area"
    @if ($backgroundImage) style="background-image: url('{{ $backgroundImage }}');" @endif>
    <div class="hero-shape">
        <img src="{{ asset('assets/img/shape/01.png') }}" alt="">
    </div>
    <div class="hero-shape-2">
        <img src="{{ asset('assets/img/shape/02.png') }}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="hero-content-inner">
                        <h1 class="hero-title">
                            <i class="fas fa-book-open"></i>
                            {{ $title }}
                        </h1>
                        <h2 class="hero-subtitle">{{ $subtitle }}</h2>
                        <p class="hero-description">{{ $description }}</p>

                        @if ($showCarousel && count($carouselItems) > 0)
                            <div class="hero-carousel">
                                <div class="owl-carousel hero-slider">
                                    @foreach ($carouselItems as $item)
                                        <div class="hero-slide">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                                            <div class="hero-slide-content">
                                                <h3>{{ $item['title'] }}</h3>
                                                <p>{{ $item['description'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero area end -->
