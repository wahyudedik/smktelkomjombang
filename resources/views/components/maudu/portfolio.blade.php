<!-- Portfolio / Kegiatan -->
<div class="portfolio-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="sub-title">Galeri</span>
                    <h2 class="title">Portfolio Kegiatan {{ theme_config('short_name', 'MAUDU') }}</h2>
                    <p class="desc">Berbagai kegiatan dan prestasi yang telah diraih</p>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $portfolios = [
                    ['title' => 'Kegiatan Belajar Mengajar', 'image' => 'activity/01.jpg'],
                    ['title' => 'Ujian Nasional', 'image' => 'activity/02.jpg'],
                    ['title' => 'Kegiatan Keagamaan', 'image' => 'activity/03.jpg'],
                    ['title' => 'Ekstrakurikuler', 'image' => 'activity/04.jpg'],
                    ['title' => 'Kemah', 'image' => 'activity/05.jpg'],
                ];
            @endphp

            @foreach ($portfolios as $index => $portfolio)
                <div class="col-md-4">
                    <div class="portfolio-item">
                        <img src="{{ asset('assets_maudu/assets/img/' . $portfolio['image']) }}"
                            alt="{{ $portfolio['title'] }}" style="width: 100%; border-radius: 50px 50px 50px 0;">
                        <div class="portfolio-content">
                            <div class="portfolio-info">
                                <div class="portfolio-title-info">
                                    <h4>{{ $portfolio['title'] }}</h4>
                                    <p>{{ theme_config('short_name', 'MAUDU') }}</p>
                                </div>
                                <a href="{{ asset('assets_maudu/assets/img/' . $portfolio['image']) }}"
                                    class="popup-image">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Portfolio End -->

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.popup-image').magnificPopup({
                type: 'image',
                mainClass: 'mfp-fade',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
@endpush
