<!-- Footer Start -->
<footer id="rs-footer" class="rs-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 footer-widget md-mb-50">
                    <h4 class="widget-title">Jurusan</h4>
                    <ul class="site-map">
                        @foreach(array_reverse(theme_config('jurusan', [])) as $j)
                            <li><a href="#rs-services">{{ strtoupper($j['full_name'] ?? $j['name'] ?? '') }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 footer-widget md-mb-50">
                    <h4 class="widget-title">Link Terkait</h4>
                    <ul class="site-map">
                        @foreach(theme_config('related_links', []) as $link)
                            <li><a href="{{ resolve_theme_url($link['url'] ?? '#') }}">{{ $link['label'] ?? '' }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 footer-widget">
                    <h4 class="widget-title">Address</h4>
                    <ul class="address-widget">
                        <li>
                            <i class="flaticon-location"></i>
                            <div class="desc">
                                {{ $siteSettings['contact_address'] ?? theme_config('address', 'Ponpes Darul Ulum Jombang') }}
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-call"></i>
                            <div class="desc">
                                <a href="https://wa.me/{{ theme_config('whatsapp', '6285649400339') }}">{{ theme_config('phone', '085649400339') }}</a>
                                , <a href="tel:{{ preg_replace('/[^0-9+]/', '', theme_config('phone_secondary', '(0321)868188')) }}">{{ theme_config('phone_secondary', '(0321)868188') }}</a>
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-email"></i>
                            <div class="desc">
                                <a href="mailto:{{ $siteSettings['contact_email'] ?? theme_config('email', 'smktelkomdujbg@gmail.com') }}">{{ $siteSettings['contact_email'] ?? theme_config('email', 'smktelkomdujbg@gmail.com') }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row y-middle">
                <div class="col-lg-4 md-mb-20">
                    <div class="footer-logo md-text-center">
                        <a href="{{ route('landing') }}">
                            <img src="{{ theme_image('logo_light', theme_info('defaults.logo_light', 'assets_telkom/assets/images/logo.png')) }}" alt="{{ theme_info('name', 'Logo') }}">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 md-mb-20">
                    <div class="copyright text-center md-text-start">
                        <p>{!! $siteSettings['footer_text'] ??
                            '&copy; ' . date('Y') . ' All Rights Reserved. Developed By <a href="https://www.tiktok.com/@kritis.tv" target="_blank">Kritis.TV</a>' !!}</p>
                    </div>
                </div>
                <div class="col-lg-4 text-end md-text-start">
                    <ul class="footer-social">
                        <li><a href="{{ theme_config('facebook_url') ?: '#' }}" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="{{ theme_config('twitter_url') ?: '#' }}" target="_blank" rel="noopener" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="{{ theme_config('instagram_url') ?: '#' }}" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="{{ theme_config('google_plus_url') ?: '#' }}" target="_blank" rel="noopener" title="Google+"><i class="fab fa-google-plus-g"></i></a></li>
                        <li><a href="{{ theme_config('pinterest_url') ?: '#' }}" target="_blank" rel="noopener" title="Pinterest"><i class="fab fa-pinterest-p"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
