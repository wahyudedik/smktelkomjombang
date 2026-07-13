<!-- Video Area -->
<div class="video-area py-120">
    <div class="container">
        <div class="video-content" style="background-image: url('{{ asset('assets_maudu/assets/img/video/01.jpg') }}');">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="video-wrapper text-center">
                        <a href="{{ theme_config('video_url', 'https://www.youtube.com/watch?v=ckHzmP1evNU') }}"
                            class="popup-youtube video-play-btn" title="Play Video">
                            <i class="fas fa-play"></i>
                        </a>
                        <h3 class="video-title">Tentang Kami</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Video Area End -->

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.popup-youtube').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                preloader: false,
                iframe: {
                    markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                        '</div>',
                    patterns: {
                        youtube: {
                            index: 'youtube.com/',
                            id: 'v=',
                            src: '//www.youtube.com/embed/%id%?autoplay=1'
                        }
                    }
                }
            });
        });
    </script>
@endpush
