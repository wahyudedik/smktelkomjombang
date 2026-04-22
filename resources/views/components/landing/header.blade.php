<header class="header">
    <!-- header top -->
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Follow Us: </span>
                        @if (cache('site_setting_social_facebook'))
                            <a href="{{ cache('site_setting_social_facebook') }}" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        @else
                            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif

                        @if (cache('site_setting_social_instagram'))
                            <a href="{{ cache('site_setting_social_instagram') }}" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        @else
                            <a href="{{ route('public.kegiatan') }}" target="_blank"><i
                                    class="fab fa-instagram"></i></a>
                        @endif

                        @if (cache('site_setting_social_youtube'))
                            <a href="{{ cache('site_setting_social_youtube') }}" target="_blank"><i
                                    class="fab fa-youtube"></i></a>
                        @else
                            <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                        @endif

                        @if (cache('site_setting_social_whatsapp'))
                            <a href="{{ cache('site_setting_social_whatsapp') }}" target="_blank"><i
                                    class="fab fa-whatsapp"></i></a>
                        @else
                            <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            @if (cache('site_setting_contact_address'))
                                <li>
                                    <a href="#" target="_blank"><i class="far fa-location-dot"></i>
                                        {{ cache('site_setting_contact_address') }}</a>
                                </li>
                            @endif
                            @if (cache('site_setting_contact_email'))
                                <li>
                                    <a href="mailto:{{ cache('site_setting_contact_email') }}" target="_blank"><i
                                            class="far fa-envelopes"></i>
                                        {{ cache('site_setting_contact_email') }}</a>
                                </li>
                            @endif
                            @if (cache('site_setting_contact_phone'))
                                <li>
                                    <a href="tel:{{ cache('site_setting_contact_phone') }}" target="_blank"><i
                                            class="far fa-phone-volume"></i>
                                        {{ cache('site_setting_contact_phone') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                <a class="navbar-brand" href="/">
                    @if (cache('site_setting_logo'))
                        <img src="{{ Storage::url(cache('site_setting_logo')) }}"
                            alt="{{ cache('site_setting_site_name', 'MAUDU REJOSO') }}" style="max-height: 50px;">
                    @else
                        <img src="{{ asset('assets/img/logo/logo.png') }}" alt="logo">
                    @endif
                </a>
                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <button type="button" class="nav-right-link search-box-outer"><i
                                class="far fa-search"></i></button>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        @foreach ($headerMenus as $menu)
                            @if ($menu->children->count() > 0)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ request()->is($menu->slug) ? 'active' : '' }}"
                                        href="#" data-bs-toggle="dropdown">
                                        @if ($menu->menu_icon)
                                            <i class="{{ $menu->menu_icon }}"></i>
                                        @endif
                                        {{ $menu->menu_title }}
                                    </a>
                                    <ul class="dropdown-menu fade-down">
                                        @foreach ($menu->children as $submenu)
                                            <li>
                                                <a class="dropdown-item" href="{{ $submenu->menu_url }}"
                                                    @if ($submenu->menu_target_blank) target="_blank" @endif>
                                                    @if ($submenu->menu_icon)
                                                        <i class="{{ $submenu->menu_icon }}"></i>
                                                    @endif
                                                    {{ $submenu->menu_title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is($menu->slug) ? 'active' : '' }}"
                                        href="{{ $menu->menu_url }}"
                                        @if ($menu->menu_target_blank) target="_blank" @endif>
                                        @if ($menu->menu_icon)
                                            <i class="{{ $menu->menu_icon }}"></i>
                                        @endif
                                        {{ $menu->menu_title }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        <!-- Fallback menu items if no custom menus are configured -->
                        @if ($headerMenus->count() == 0)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#"
                                    data-bs-toggle="dropdown">PROFIL</a>
                                <ul class="dropdown-menu fade-down">
                                    <li><a class="dropdown-item" href="{{ route('pages.public.index') }}">HALAMAN</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('public.kegiatan') }}">GALERI</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.siswa.index') }}">DATA SISWA</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"
                                    data-bs-toggle="dropdown">AKADEMIK</a>
                                <ul class="dropdown-menu fade-down">
                                    <li><a class="dropdown-item" href="{{ route('admin.guru.index') }}">TENAGA
                                            PENDIDIK</a></li>
                                    <li><a class="dropdown-item" href="{{ route('pages.public.index') }}">KURIKULUM</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('public.kegiatan') }}">KEGIATAN</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">LAYANAN
                                    DIGITAL</a>
                                <ul class="dropdown-menu fade-down">
                                    @php
                                        $user = Auth::user();
                                        $siswa = $user ? \App\Models\Siswa::where('user_id', $user->id)->first() : null;
                                        $isGrade12 = $siswa && str_contains($siswa->kelas, 'XII');
                                    @endphp
                                    @if ($isGrade12)
                                        <li><a class="dropdown-item" href="{{ route('admin.lulus.check') }}">🎓
                                                E-LULUS</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('admin.osis.voting') }}">🗳️
                                            E-OSIS</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.sarpras.index') }}">🏢
                                            E-SARPRAS</a></li>
                                    <li><a class="dropdown-item" href="{{ route('public.kegiatan') }}">📸
                                            E-GALERI</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#contact">KONTAK</a></li>
                        @endif
                    </ul>
                    <div class="nav-right">
                        <div class="nav-right-btn mt-2">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('admin.dashboard') }}" class="theme-btn"><span
                                            class="fal fa-user"></span> DASHBOARD</a>
                                @else
                                    <a href="{{ route('login') }}" class="theme-btn"><span
                                            class="fal fa-sign-in"></span> LOGIN</a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- header area end -->

<!-- popup search -->
<div class="search-popup">
    <button class="close-search"><span class="far fa-times"></span></button>
    <form action="#">
        <div class="form-group">
            <input type="search" name="search-field" placeholder="Search Here..." required>
            <button type="submit"><i class="far fa-search"></i></button>
        </div>
    </form>
</div>
<!-- popup search end -->
