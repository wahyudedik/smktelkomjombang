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
                        @php
                            $menuItems = theme_config('menu', []);
                        @endphp

                        @foreach ($menuItems as $item)
                            @if (!empty($item['children']))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="{{ $item['url'] }}"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        {{ $item['label'] }}
                                    </a>
                                    <ul class="dropdown-menu fade-down">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a class="dropdown-item" href="{{ $child['url'] }}">
                                                    {{ $child['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ $item['url'] }}">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="nav-right">
                        <div class="nav-right-btn mt-2">
                            <a class="btn btn-primary" href="{{ theme_config('ppdb_url', '#') }}" target="_blank">
                                INFORMASI PENDAFTARAN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- Header End -->
