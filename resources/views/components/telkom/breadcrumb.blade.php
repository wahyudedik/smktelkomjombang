@props([
    'title'  => '',
    'image'  => null,   // path relatif dari assets_telkom, e.g. 'assets/images/breadcrumbs/1.jpg'
    'items'  => [],     // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
])

<div class="rs-breadcrumbs breadcrumbs-overlay" id="breadcrumb-section"
    style="position: relative; overflow: hidden; padding: 80px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

    {{-- Text --}}
    <div class="container">
        <div id="breadcrumb-text"
            class="breadcrumbs-text white-color"
            style="width: 100%; text-align: center; color: #ffffff;">
            <h1 class="page-title" style="color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.3); font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
                {{ $title }}
            </h1>
            <ul style="list-style: none; padding: 0; margin: 0; color: #ffffff; opacity: 0.95; font-size: 1rem;">
                @foreach ($items as $i => $item)
                    @if ($i < count($items) - 1)
                        <li style="display: inline-block;">
                            <a href="{{ $item['url'] }}"
                                style="color: #ffffff; padding-right: 30px; position: relative; transition: opacity 0.3s; text-decoration: none; opacity: 0.9;">
                                {{ $item['label'] }}
                                {{-- separator arrows --}}
                                <span style="position: absolute; right: 7px; top: 2px; font-size: 14px; opacity: 0.7;">/</span>
                            </a>
                        </li>
                    @else
                        <li style="display: inline-block; opacity: 0.95; font-weight: 500;">{{ $item['label'] }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
