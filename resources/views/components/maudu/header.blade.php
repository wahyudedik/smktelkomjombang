<!-- Header -->
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Ikuti Kami:</span>
                        <a href="{{ theme_config('facebook_url', '#') }}" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="{{ theme_config('instagram_url', '#') }}" target="_blank"><i
                                class="fab fa-instagram"></i></a>
                        <a href="{{ theme_config('youtube_url', '#') }}" target="_blank"><i
                                class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ theme_config('address') }}
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <a href="tel:{{ theme_config('phone') }}">{{ theme_config('phone') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                {{-- Logo --}}
                <a class="navbar-brand" href="{{ route('landing') }}">
                    @if (!empty($siteSettings['logo']))
                        <img src="{{ Storage::url($siteSettings['logo']) }}" alt="{{ theme_config('name') }}">
                    @else
                        <img src="{{ asset(theme_config('logo')) }}" alt="{{ theme_config('name') }}">
                    @endif
                </a>

                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <a href="#"><i class="fas fa-search"></i></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('landing') }}">Beranda</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside">
                                Profil
                            </a>
                            <ul class="dropdown-menu fade-down">
                                <li><a class="dropdown-item"
                                        href="{{ route('pages.public.index', ['category' => 'profil']) }}">Tentang
                                        Kami</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('pages.public.index', ['category' => 'visi-misi']) }}">Visi &
                                        Misi</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('pages.public.index', ['category' => 'guru']) }}">Guru &
                                        Staff</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('berita.public.index') }}">Berita</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('public.kegiatan') }}">Kegiatan</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside">
                                Layanan
                            </a>
                            <ul class="dropdown-menu fade-down">
                                <li><a class="dropdown-item" href="{{ route('public.graduation.check') }}">E-Lulus</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('pages.public.index', ['category' => 'akademik']) }}">Akademik</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('pages.public.index') }}">Lainnya</a></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="nav-right">
                        <div class="nav-right-btn mt-2 d-flex align-items-center gap-2">
                            @auth
                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            @else
                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </a>
                            @endauth
                            <a class="btn btn-primary" href="{{ theme_config('ppdb_url', '#') }}" target="_blank">
                                DAFTAR SEKARANG
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- Header End -->
