<!-- Footer Start -->
<footer id="rs-footer" class="rs-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 footer-widget mb-4">
                    <h4 class="widget-title">Jurusan</h4>
                    <ul class="site-map">
                        <li><a href="#rs-services">PRODUKSI FILM</a></li>
                        <li><a href="#rs-services">DESAIN KOMUNIKASI VISUAL</a></li>
                        <li><a href="#rs-services">TEKNIK KOMPUTER DAN JARINGAN</a></li>
                        <li><a href="#rs-services">REKAYASA PERANGKAT LUNAK</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 footer-widget mb-4">
                    <h4 class="widget-title">Link Terkait</h4>
                    <ul class="site-map">
                        <li><a href="{{ route('berita.public.index') }}">Berita Terbaru</a></li>
                        <li><a href="{{ route('public.kegiatan') }}">Kegiatan Sekolah</a></li>
                        <li><a href="{{ route('public.graduation.check') }}">Cek Kelulusan</a></li>
                        <li><a href="{{ route('pages.public.index') }}">Semua Halaman</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12 footer-widget mb-4">
                    <h4 class="widget-title">Kontak</h4>
                    <ul class="address-widget">
                        <li>
                            <i class="flaticon-location"></i>
                            <div class="desc">
                                {{ $siteSettings['contact_address'] ?? 'Ponpes Darul Ulum, Jl. Wahid Hasyim No.128, Kedunglosari, Kedungrejo, Kec. Bandar Kedungmulyo, Kabupaten Jombang, Jawa Timur' }}
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-call"></i>
                            <div class="desc">
                                @if (!empty($siteSettings['contact_phone']))
                                    <a
                                        href="tel:{{ $siteSettings['contact_phone'] }}">{{ $siteSettings['contact_phone'] }}</a>
                                @else
                                    <a href="tel:085649400339">0856-4940-0339</a> ,
                                    <a href="tel:03218681888">(0321) 8681-888</a>
                                @endif
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-email"></i>
                            <div class="desc">
                                <a
                                    href="mailto:{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}">{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}</a>
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
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3 mb-md-0">
                    <div class="footer-logo text-center text-md-start">
                        <a href="{{ route('landing') }}">
                            <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo/logo.png')) }}"
                                alt="{{ theme_info('name', 'Logo') }}" style="max-height: 50px;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3 mb-md-0">
                    <div class="copyright text-center">
                        <p>{!! $siteSettings['footer_text'] ??
                            '&copy; ' .
                                date('Y') .
                                ' All Rights Reserved. Developed By <a href="https://www.tiktok.com/@kritis.tv" target="_blank">Kritis.TV</a>' !!}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 text-center text-md-end">
                    <ul class="footer-social">
                        @if (theme_config('facebook_url'))
                            <li><a href="{{ theme_config('facebook_url') }}" target="_blank" rel="noopener"
                                    title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        @endif
                        @if (theme_config('instagram_url'))
                            <li><a href="{{ theme_config('instagram_url') }}" target="_blank" rel="noopener"
                                    title="Instagram"><i class="fa fa-instagram"></i></a></li>
                        @endif
                        @if (theme_config('youtube_url'))
                            <li><a href="{{ theme_config('youtube_url') }}" target="_blank" rel="noopener"
                                    title="YouTube"><i class="fa fa-youtube"></i></a></li>
                        @endif
                        @if (theme_config('whatsapp'))
                            <li><a href="https://wa.me/{{ theme_config('whatsapp') }}" target="_blank" rel="noopener"
                                    title="WhatsApp"><i class="fa fa-whatsapp"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
