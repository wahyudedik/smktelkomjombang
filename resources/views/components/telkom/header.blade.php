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
                    <div class="col-lg-5">
                        <div class="logo-cat-wrap">
                            <div class="logo-part pr-90">
                                <a class="dark-logo" href="{{ route('landing') }}">
                                    <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo/logo.png')) }}"
                                        alt="{{ theme_info('name', 'Logo') }}" style="max-height: 35px;">
                                </a>
                                <a class="light-logo" href="{{ route('landing') }}">
                                    <img src="{{ theme_image('logo_light', theme_info('defaults.logo_light', 'assets_telkom/assets/images/logo/logo-light.png')) }}"
                                        alt="{{ theme_info('name', 'Logo') }}" style="max-height: 35px;">
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
                                        @foreach (theme_config('menu', []) as $item)
                                            @if (isset($item['children']) && count($item['children']) > 0)
                                                <li class="menu-item-has-children">
                                                    <a
                                                        href="{{ resolve_theme_url($item['url'] ?? '#') }}">{{ $item['label'] }}</a>
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
                    <img src="{{ theme_image('logo', theme_info('defaults.logo', 'assets_telkom/assets/images/logo/logo.png')) }}"
                        alt="{{ theme_info('name', 'Logo') }}" style="max-height: 60px;">
                </a>
            </div>
        </nav>
        <!-- Canvas Menu end -->
    </header>
    <!--Header End-->
</div>
<!--Full width header End-->
