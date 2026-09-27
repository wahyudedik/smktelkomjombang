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
                            {{ $siteSettings['site_description'] ?? theme_config('tagline', 'Madrasah Hebat, Bermartabat') }}
                        </p>
                        <ul class="footer-contact">
                            <li>
                                <a href="{{ $siteSettings['whatsapp_url'] ?? theme_config('whatsapp_url', '#') }}">
                                    <i class="fab fa-whatsapp"></i>{{ $siteSettings['contact_phone'] ?? theme_config('phone') }}
                                </a>
                            </li>
                            <li>
                                <i class="far fa-map-marker-alt"></i>{{ $siteSettings['contact_address'] ?? theme_config('address') }}
                            </li>
                            <li>
                                <a href="mailto:{{ $siteSettings['contact_email'] ?? theme_config('email') }}">
                                    <i class="far fa-envelope"></i>{{ $siteSettings['contact_email'] ?? theme_config('email') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Link Terkait — dynamic dari theme_config --}}
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Link Terkait</h4>
                        <ul class="footer-list">
                            @php
                                $relatedLinks = theme_config('related_links', [
                                    ['label' => 'Tentang Yayasan', 'url' => 'route:pages.public.show,tentang-yayasan'],
                                    ['label' => 'Tentang Madrasah', 'url' => 'route:pages.public.show,tentang-madrasah'],
                                    ['label' => 'Testimonials', 'url' => 'route:testimonials.create'],
                                ]);
                            @endphp
                            @foreach($relatedLinks as $link)
                                <li><a href="{{ resolve_theme_url($link['url'] ?? '#') }}"><i class="fas fa-caret-right"></i> {{ $link['label'] ?? '' }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Madrasah Corner --}}
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Madrasah Corner</h4>
                        <ul class="footer-list">
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Raport</a></li> {{-- TODO: Add route when feature is implemented --}}
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-OSIS</a></li> {{-- TODO: Add route when feature is implemented --}}
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Sarpras</a></li> {{-- TODO: Add route when feature is implemented --}}
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Library</a></li> {{-- TODO: Add route when feature is implemented --}}
                            <li><a href="{{ resolve_theme_url('route:public.graduation.check') }}"><i class="fas fa-caret-right"></i> E-Lulus</a></li>
                            <li><a href="#"><i class="fas fa-caret-right"></i> E-Majalah</a></li> {{-- TODO: Add route when feature is implemented --}}
                        </ul>
                    </div>
                </div>

                {{-- Slogan / PPDB --}}
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Slogan Kami</h4>
                        <div class="footer-newsletter">
                            <p>{{ theme_config('slogan', 'Madrasah Hebat, Bermartabat') }}</p>
                            <div class="subscribe-form">
                                <form action="{{ theme_config('ppdb_url', '#') }}" target="_blank">
                                    <button class="theme-btn" type="submit">
                                        PPDB ONLINE <i class="far fa-pencil"></i>
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
                            {!! $siteSettings['footer_text'] ??
                                '&copy; Copyright <span id="date" class="current-year">' . date('Y') . '</span> ' . theme_config('name') . '. All Rights Reserved.' !!}
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            @if (theme_config('facebook_url'))
                                <li><a href="{{ theme_config('facebook_url') }}" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if (theme_config('instagram_url'))
                                <li><a href="{{ theme_config('instagram_url') }}" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                            @endif
                            @if (theme_config('youtube_url'))
                                <li><a href="{{ theme_config('youtube_url') }}" target="_blank" rel="noopener" title="YouTube"><i class="fab fa-youtube"></i></a></li>
                            @endif
                            @if (theme_config('whatsapp_url'))
                                <li><a href="{{ theme_config('whatsapp_url') }}" target="_blank" rel="noopener" title="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                            @endif
                            @if (theme_config('twitter_url'))
                                <li><a href="{{ theme_config('twitter_url') }}" target="_blank" rel="noopener" title="Twitter/X"><i class="fab fa-x-twitter"></i></a></li>
                            @endif
                            @if (theme_config('tiktok_url'))
                                <li><a href="{{ theme_config('tiktok_url') }}" target="_blank" rel="noopener" title="TikTok"><i class="fab fa-tiktok"></i></a></li>
                            @endif
                            @if (theme_config('pinterest_url'))
                                <li><a href="{{ theme_config('pinterest_url') }}" target="_blank" rel="noopener" title="Pinterest"><i class="fab fa-pinterest-p"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
