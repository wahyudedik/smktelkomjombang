<!--Full width header Start-->
<div class="full-width-header header-style2">
    <!--Header Start-->
    <header id="rs-header" class="rs-header">
        <!-- Topbar Area Start -->
        <div class="topbar-area">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-md-7">
                        <ul class="topbar-contact">
                            @if(!empty($siteSettings['contact_email']))
                            <li>
                                <i class="flaticon-email"></i>
                                <a href="mailto:{{ $siteSettings['contact_email'] }}">{{ $siteSettings['contact_email'] }}</a>
                            </li>
                            @endif
                            @if(!empty($siteSettings['contact_phone']))
                            <li>
                                <i class="flaticon-call"></i>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone']) }}">{{ $siteSettings['contact_phone'] }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-5 text-end">
                        <ul class="topbar-right">
                            <li class="btn-part">
                                @auth
                                    <a class="apply-btn" href="{{ route('admin.dashboard') }}"> <i
                                            class="fa fa-tachometer-alt"> </i> Dashboard</a>
                                @else
                                    <a class="apply-btn" href="{{ route('login') }}"> <i class="fa fa-sign-in"> </i> Login
                                        System</a>
                                @endauth
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar Area End -->

        <!-- Menu Start -->
        <div class="menu-area menu-sticky">
            <div class="container">
                <div class="row y-middle">
                    <div class="col-lg-5">
                        <div class="logo-cat-wrap">
                            <div class="logo-part pr-90">
                                <a class="dark-logo" href="{{ route('landing') }}">
                                    @if(!empty($siteSettings['logo']))
                                        <img src="{{ Storage::url($siteSettings['logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}"
                                            style="max-height: 35px;">
                                    @else
                                        <img src="{{ asset('assets_telkom/assets/images/logo-dark.png') }}" alt="Logo Dark"
                                            style="max-height: 35px;">
                                    @endif
                                </a>
                                <a class="light-logo" href="{{ route('landing') }}">
                                    @if(!empty($siteSettings['logo']))
                                        <img src="{{ Storage::url($siteSettings['logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}"
                                            style="max-height: 35px;">
                                    @else
                                        <img src="{{ asset('assets_telkom/assets/images/logo.png') }}" alt="Logo Light"
                                            style="max-height: 35px;">
                                    @endif
                                </a>
                            </div>
                            <div class="categories-btn">
                                <button type="button" class="cat-btn"><i class="fa fa-th"></i>Link Terkait</button>
                                <div class="cat-menu-inner">
                                    <ul id="cat-menu">
                                        <li><a href="#">E-Rapor</a></li>
                                        <li><a href="#">E-Learning</a></li>
                                        <li><a href="#">E-Perpus</a></li>
                                        <li><a href="#">E-Administrasi</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 text-center">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-90">
                                <div class="mobile-menu">
                                    <a class="rs-menu-toggle">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </div>
                                <nav class="rs-menu">
                                    <ul class="nav-menu">
                                        <li class="menu-item-has-children">
                                            <a href="#rs-about">Profil</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('pages.public.show', 'pp-darul-ulum') }}">Tentang SMK</a></li>
                                                <li><a href="{{ route('pages.public.show', 'visi-misi-smk') }}">Visi & Misi</a></li>
                                                <li><a href="{{ route('pages.public.show', 'struktur-smk') }}">Struktur Sekolah</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-item-has-children">
                                            <a href="#rs-services">Akademik</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('pages.public.show', 'tenaga-pendidik') }}">Tenaga Pendidik</a></li>
                                                <li><a href="{{ route('pages.public.show', 'staf-karyawan') }}">Staf & Karyawan</a></li>
                                                <li><a href="#rs-services">Jurusan</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-item-has-children">
                                            <a href="#">Layanan</a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Rapor Digital</a></li>
                                                <li><a href="#">E-Semester</a></li>
                                                <li><a href="#">E-LMS</a></li>
                                                <li><a href="#">E-Perpus</a></li>
                                                <li><a href="{{ route('public.graduation.check') }}">E-Lulus</a></li>
                                            </ul>
                                        </li>

                                        <li class="menu-item-has">
                                            <a href="{{ route('berita.public.index') }}">Berita</a>
                                        </li>

                                        <li class="menu-item-has">
                                            <a href="#rs-contact">Kontak</a>
                                        </li>

                                        <li class="menu-item-has">
                                            <a href="{{ $siteSettings['cta_button_url'] ?? 'https://psb.ponpesdarululum.id/' }}" target="_blank">INFORMASI PPDB</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->

        <!-- Canvas Menu start -->
        <nav class="right_menu_togle hidden-md">
            <div class="close-btn">
                <div id="nav-close">
                    <div class="line">
                        <span class="line1"></span><span class="line2"></span>
                    </div>
                </div>
            </div>
            <div class="canvas-logo">
                <a href="{{ route('landing') }}">
                    @if(!empty($siteSettings['logo']))
                        <img src="{{ Storage::url($siteSettings['logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'Logo' }}"
                            style="max-height: 60px;">
                    @else
                        <img src="{{ asset('assets_telkom/assets/images/logo-dark.png') }}" alt="Logo"
                            style="max-height: 60px;">
                    @endif
                </a>
            </div>
        </nav>
        <!-- Canvas Menu end -->
    </header>
    <!--Header End-->
</div>
<!--Full width header End-->
