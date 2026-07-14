<!-- Footer -->
<footer class="footer-area">
    <div class="footer-shape">
        <img src="{{ asset('assets_maudu/assets/img/shape/03.png') }}" alt="shape">
    </div>

    <div class="footer-widget">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">
                {{-- About --}}
                <div class="col-md-6 col-lg-4">
                    <div class="footer-widget-box about-us">
                        <a href="{{ route('landing') }}" class="footer-logo">
                            <img src="{{ theme_image('logo_light', theme_info('defaults.logo_light', 'assets_maudu/assets/img/logo/logo-light.png')) }}"
                                alt="{{ theme_config('name') }}">
                        </a>
                        <p class="mb-3">
                            MA Unggulan Darul Ulum Rejoso salah satu madrasah dalam naungan
                            Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang
                        </p>
                        <ul class="footer-contact">
                            <li>
                                <a href="{{ theme_config('whatsapp_url', '#') }}">
                                    <i class="fab fa-whatsapp"></i>{{ theme_config('phone') }}
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>{{ theme_config('address') }}
                            </li>
                            <li>
                                <a href="mailto:{{ theme_config('email') }}">
                                    <i class="fas fa-envelope"></i>{{ theme_config('email') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Link Terkait --}}
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Link Terkait</h4>
                        <ul class="footer-list">
                            <li><a href="#"><i class="fas fa-caret-right"></i> Tentang Yayasan</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> Tentang Madrasah</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> Testimonials</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Madrasah Corner --}}
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Madrasah Corner</h4>
                        <ul class="footer-list">
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Raport</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-OSIS</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Sarpras</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Library</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Lulus</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Majalah</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Slogan / PPDB --}}
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Slogan Kami</h4>
                        <div class="footer-newsletter">
                            <p>Madrasah Hebat, Bermartabat</p>
                            <div class="subscribe-form">
                                <form action="{{ theme_config('ppdb_url', '#') }}" target="_blank">
                                    <button class="theme-btn" type="submit">
                                        PPDB ONLINE <i class="fas fa-pencil"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="copyright-wrapper">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p class="copyright-text">
                            &copy; Copyright <span id="date" class="current-year">{{ date('Y') }}</span>
                            {{ theme_config('name') }}. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            <li><a href="{{ theme_config('facebook_url', '#') }}" target="_blank"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a href="{{ theme_config('instagram_url', '#') }}" target="_blank"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li><a href="{{ theme_config('youtube_url', '#') }}" target="_blank"><i
                                        class="fab fa-youtube"></i></a></li>
                            <li><a href="{{ theme_config('whatsapp_url', '#') }}" target="_blank"><i
                                        class="fab fa-whatsapp"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
