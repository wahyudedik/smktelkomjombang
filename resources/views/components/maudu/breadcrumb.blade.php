@props([
    'title' => '',
    'items' => [], // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
])

<!-- Breadcrumb Area -->
<section class="breadcrumb-area"
    style="background: linear-gradient(135deg, #1a5632 0%, #0d3d21 100%); padding: 80px 0; position: relative; overflow: hidden;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <h2
                        style="color: #fff; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        {{ $title }}
                    </h2>
                    <nav style="color: #ffffff; opacity: 0.95; font-size: 1rem;">
                        <a href="{{ route('landing') }}"
                            style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">Home</a>
                        @foreach ($items as $i => $item)
                            <span style="margin: 0 10px; opacity: 0.7;">/</span>
                            @if ($i < count($items) - 1)
                                <a href="{{ $item['url'] }}"
                                    style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">
                                    {{ $item['label'] }}
                                </a>
                            @else
                                <span style="opacity: 0.95; font-weight: 500;">{{ $item['label'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Decorative shapes -->
    <div
        style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;">
    </div>
    <div
        style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;">
    </div>
</section>
