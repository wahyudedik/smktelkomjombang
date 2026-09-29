<!-- Header -->
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-wrap">
                <div class="header-top-left">
                    <div class="header-top-social">
                        <span>Follow Us: </span>
                        @if (theme_config('facebook_url'))
                            <a href="{{ theme_config('facebook_url') }}" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if (theme_config('instagram_url'))
                            <a href="{{ theme_config('instagram_url') }}" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (theme_config('youtube_url'))
                            <a href="{{ theme_config('youtube_url') }}" target="_blank" rel="noopener" title="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if (theme_config('whatsapp_url'))
                            <a href="{{ theme_config('whatsapp_url') }}" target="_blank" rel="noopener" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        @endif
                        @if (theme_config('twitter_url'))
                            <a href="{{ theme_config('twitter_url') }}" target="_blank" rel="noopener" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                        @endif
                    </div>
                </div>
                <div class="header-top-right">
                    <div class="header-top-contact">
                        <ul>
                            <li>
                                <a href="{{ $siteSettings['google_maps_url'] ?? theme_config('google_maps_url', '#') }}" target="_blank">
                                    <i class="far fa-location-dot"></i> {{ $siteSettings['contact_address'] ?? theme_config('address') }}
                                </a>
                            </li>
                            <li>
                                <a href="mailto:{{ $siteSettings['contact_email'] ?? theme_config('email') }}" target="_blank">
                                    <i class="far fa-envelope"></i> {{ $siteSettings['contact_email'] ?? theme_config('email') }}
                                </a>
                            </li>
                            <li>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone'] ?? theme_config('phone')) }}">
                                    <i class="far fa-phone-volume"></i> {{ $siteSettings['contact_phone'] ?? theme_config('phone') }}
                                </a>
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
                {{-- Logo: 2 images (icon + text) matching template --}}
                <a class="navbar-brand d-inline-flex align-items-center gap-2 me-lg-5" href="{{ route('landing') }}">
                    <img src="{{ asset(theme_config('logo_icon', theme_info('defaults.logo_icon', 'assets_maudu/assets/img/logo/favicon.png'))) }}"
                        alt="logo" class="logo-icon">
                    <img src="{{ asset(theme_config('logo_text', theme_info('defaults.logo_text', 'assets_maudu/assets/img/logo/logo nama.png'))) }}"
                        alt="logo" class="logo-text">
                </a>

                <div class="mobile-menu-right">
                    <div class="search-btn">
                        <button type="button" class="nav-right-link search-box-outer"><i
                                class="far fa-search"></i></button>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav align-items-center mx-auto">
                        {{-- DB menus first, config fallback --}}
                        @php $useDbMenus = $headerMenus->count() > 0; @endphp
                        @if ($useDbMenus)
                            @foreach ($headerMenus as $menu)
                                @if ($menu->children->count() > 0)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle"
                                            href="{{ $menu->menu_url }}" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside"
                                            @if ($menu->menu_target_blank) target="_blank" @endif>
                                            {{ $menu->menu_title }}
                                        </a>
                                        <ul class="dropdown-menu fade-down">
                                            @foreach ($menu->children as $submenu)
                                                <li><a class="dropdown-item"
                                                        href="{{ $submenu->menu_url }}"
                                                        @if ($submenu->menu_target_blank) target="_blank" @endif>{{ $submenu->menu_title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link text-nowrap" href="{{ $menu->menu_url }}"
                                            @if ($menu->menu_target_blank) target="_blank" @endif>{{ $menu->menu_title }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach (theme_config('menu', []) as $item)
                                @if (isset($item['children']) && count($item['children']) > 0)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle"
                                            href="{{ resolve_theme_url($item['url'] ?? '#') }}" data-bs-toggle="dropdown"
                                            data-bs-auto-close="outside">
                                            {{ $item['label'] }}
                                        </a>
                                        <ul class="dropdown-menu fade-down">
                                            @foreach ($item['children'] as $child)
                                                <li><a class="dropdown-item"
                                                        href="{{ resolve_theme_url($child['url'] ?? '#') }}">{{ $child['label'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link text-nowrap" href="{{ resolve_theme_url($item['url'] ?? '#') }}"
                                            @if (($item['target'] ?? '') === '_blank') target="_blank" @endif>{{ $item['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>

                    <div class="nav-right">
                        <div class="nav-right-btn mt-2 d-flex align-items-center gap-2">
                            <a href="{{ theme_config('linktree_url', theme_config('ppdb_url', '#')) }}" target="_blank"
                                class="nav-cta-btn">
                                <span class="fal fa-book"></span> INFORMASI PENDAFTARAN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <style>
        .nav-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--color-white);
            background: var(--theme-color2);
            border-radius: 50px;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            line-height: 1.4;
        }
        .nav-cta-btn:hover {
            color: var(--color-white);
            background: var(--theme-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }
        .nav-cta-btn i,
        .nav-cta-btn .fal {
            font-size: 13px;
        }
        @media (max-width: 1199px) {
            .nav-cta-btn {
                padding: 10px 18px;
                font-size: 11px;
            }
        }
    </style>
</header>
<!-- Header End -->
