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
                            @if (!empty($siteSettings['contact_email']))
                                <li>
                                    <i class="flaticon-email"></i>
                                    <a
                                        href="mailto:{{ $siteSettings['contact_email'] }}">{{ $siteSettings['contact_email'] }}</a>
                                </li>
                            @endif
                            @if (!empty($siteSettings['contact_phone']))
                                <li>
                                    <i class="flaticon-call"></i>
                                    <a
                                        href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone']) }}">{{ $siteSettings['contact_phone'] }}</a>
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
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="logo-cat-wrap">
                            <div class="logo-part">
                                <a href="{{ route('landing') }}" class="brand-logo-wrap">
                                    <img src="{{ theme_image('favicon', theme_info('defaults.favicon', 'assets_telkom/assets/images/fav.png')) }}"
                                        alt="{{ theme_info('name', 'Logo') }}" class="logo-badge">
                                    <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo.png')) }}"
                                        alt="{{ theme_info('name', 'SMK Telekomunikasi Darul Ulum Jombang') }}" class="logo-text">
                                </a>
                            </div>
                            <div class="categories-btn">
                                <button type="button" class="cat-btn"><i class="fa fa-th"></i>Link Terkait</button>
                                <div class="cat-menu-inner">
                                    <ul id="cat-menu">
                                        @foreach(theme_config('related_links', []) as $link)
                                            <li><a href="{{ resolve_theme_url($link['url'] ?? '#') }}">{{ $link['label'] ?? '' }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-12 text-end">
                        <div class="rs-menu-area">
                            <div class="main-menu pr-0">
                                <div class="mobile-menu">
                                    <a class="rs-menu-toggle">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                </div>
                                <nav class="rs-menu">
                                    <ul class="nav-menu">
                                        @foreach (theme_config('menu', []) as $item)
                                            @if (isset($item['children']) && count($item['children']) > 0)
                                                <li class="menu-item-has-children">
                                                    <a
                                                        href="{{ resolve_theme_url($item['url'] ?? '#') }}">{{ $item['label'] }} +</a>
                                                    <ul class="sub-menu">
                                                        @foreach ($item['children'] as $child)
                                                            <li><a
                                                                    href="{{ resolve_theme_url($child['url'] ?? '#') }}">{{ $child['label'] }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                <li class="menu-item-has">
                                                    <a href="{{ resolve_theme_url($item['url'] ?? '#') }}"
                                                        @if (($item['target'] ?? '') === '_blank') target="_blank" @endif>{{ $item['label'] }}</a>
                                                </li>
                                            @endif
                                        @endforeach
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
                    <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo-dark.png')) }}"
                        alt="{{ theme_info('name', 'Logo') }}">
                </a>
            </div>
            <ul class="canvas-social">
                @if (theme_config('facebook_url'))
                    <li><a href="{{ theme_config('facebook_url') }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a></li>
                @endif
                @if (theme_config('instagram_url'))
                    <li><a href="{{ theme_config('instagram_url') }}" target="_blank" rel="noopener"><i class="fa fa-instagram"></i></a></li>
                @endif
                @if (theme_config('youtube_url'))
                    <li><a href="{{ theme_config('youtube_url') }}" target="_blank" rel="noopener"><i class="fa fa-youtube"></i></a></li>
                @endif
            </ul>
            <div class="address-widget">
                <ul>
                    @if (!empty($siteSettings['contact_address']) || true)
                        <li>
                            <i class="flaticon-location"></i>
                            <div class="desc">{{ $siteSettings['contact_address'] ?? 'Ponpes Darul Ulum, Jl. Wahid Hasyim No.128, Kedunglosari, Kedungrejo, Kec. Bandar Kedungmulyo, Kabupaten Jombang, Jawa Timur' }}</div>
                        </li>
                    @endif
                    @if (!empty($siteSettings['contact_phone']) || true)
                        <li>
                            <i class="flaticon-call"></i>
                            <div class="desc">
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone'] ?? '085649400339') }}">{{ $siteSettings['contact_phone'] ?? '085649400339' }}</a>
                            </div>
                        </li>
                    @endif
                    @if (!empty($siteSettings['contact_email']) || true)
                        <li>
                            <i class="flaticon-email"></i>
                            <div class="desc">
                                <a href="mailto:{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}">{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- Canvas Menu end -->
    </header>
    <!--Header End-->
</div>
<!--Full width header End-->
