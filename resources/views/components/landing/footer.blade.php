<!-- footer area -->
<footer class="footer-area">
    <div class="footer-shape">
        <img src="{{ asset('assets/img/shape/03.png') }}" alt="">
    </div>
    <div class="footer-widget">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">
                <div class="col-md-6 col-lg-4">
                    <div class="footer-widget-box about-us">
                        <a href="/" class="footer-logo">
                            @if (cache('site_setting_logo'))
                                <img src="{{ Storage::url(cache('site_setting_logo')) }}"
                                    alt="{{ cache('site_setting_site_name', 'MAUDU REJOSO') }}"
                                    style="max-height: 50px; filter: brightness(0) invert(1);">
                            @else
                                <img src="{{ asset('assets/img/logo/logo-light.png') }}" alt="">
                            @endif
                        </a>
                        <p class="mb-3">
                            {{ cache('site_setting_site_description', 'Portal Digital Pendidikan yang mengintegrasikan semua layanan sekolah dalam satu platform modern dan terpadu.') }}
                        </p>
                        <ul class="footer-contact">
                            @if (cache('site_setting_contact_phone'))
                                <li><a href="tel:{{ cache('site_setting_contact_phone') }}"><i
                                            class="fab fa-whatsapp"></i>{{ cache('site_setting_contact_phone') }}</a>
                                </li>
                            @endif
                            @if (cache('site_setting_contact_address'))
                                <li><i class="far fa-map-marker-alt"></i>{{ cache('site_setting_contact_address') }}
                                </li>
                            @endif
                            @if (cache('site_setting_contact_email'))
                                <li><a href="mailto:{{ cache('site_setting_contact_email') }}"><i
                                            class="far fa-envelope"></i>{{ cache('site_setting_contact_email') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Dynamic Footer Menus -->
                @if ($footerMenus->count() > 0)
                    @foreach ($footerMenus as $menu)
                        <div class="col-md-6 col-lg-2">
                            <div class="footer-widget-box list">
                                <h4 class="footer-widget-title">{{ $menu->menu_title }}</h4>
                                <ul class="footer-list">
                                    @if ($menu->children->count() > 0)
                                        @foreach ($menu->children as $submenu)
                                            <li>
                                                <a href="{{ $submenu->menu_url }}"
                                                    @if ($submenu->menu_target_blank) target="_blank" @endif>
                                                    <i class="fas fa-caret-right"></i>
                                                    {{ $submenu->menu_title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a href="{{ $menu->menu_url }}"
                                                @if ($menu->menu_target_blank) target="_blank" @endif>
                                                <i class="fas fa-caret-right"></i>
                                                {{ $menu->menu_title }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback Footer Menus -->
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Link Terkait</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('pages.public.index') }}"><i class="fas fa-caret-right"></i>
                                        Halaman</a></li>
                                <li><a href="{{ route('admin.guru.index') }}"><i class="fas fa-caret-right"></i> Tenaga
                                        Pendidik</a></li>
                                <li><a href="{{ route('admin.siswa.index') }}"><i class="fas fa-caret-right"></i> Data
                                        Siswa</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Layanan Digital</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('admin.lulus.check') }}"><i class="fas fa-caret-right"></i>
                                        E-Lulus</a></li>
                                <li><a href="{{ route('admin.osis.voting') }}"><i class="fas fa-caret-right"></i>
                                        E-OSIS</a></li>
                                <li><a href="{{ route('admin.sarpras.index') }}"><i class="fas fa-caret-right"></i>
                                        E-Sarpras</a></li>
                                <li><a href="{{ route('public.kegiatan') }}"><i class="fas fa-caret-right"></i>
                                        E-Galeri</a></li>
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Slogan Kami</h4>
                        <div class="footer-newsletter">
                            <p>Pendidikan Digital, Masa Depan Cerah</p>
                            <div class="subscribe-form">
                                <form action="{{ route('pages.public.index') }}">
                                    <button class="theme-btn" type="submit">
                                        LIHAT HALAMAN <i class="far fa-file-alt"></i>
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
                            &copy; Copyright {{ date('Y') }} <a href="#">Website Sekolah</a> All Rights
                            Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="{{ route('public.kegiatan') }}" target="_blank"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-youtube"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end -->

<!-- scroll-top -->
<a href="#" id="scroll-top"><i class="far fa-arrow-up-from-arc"></i></a>
<!-- scroll-top end -->
