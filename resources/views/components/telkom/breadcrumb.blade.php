@props([
    'title'  => '',
    'image'  => null,   // path relatif dari assets_telkom, e.g. 'assets/images/breadcrumbs/1.jpg'
    'items'  => [],     // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
])

@php
    $imgSrc = $image
        ? asset('assets_telkom/' . $image)
        : asset('assets_telkom/assets/images/breadcrumbs/1.jpg');
@endphp

<div class="rs-breadcrumbs breadcrumbs-overlay" id="breadcrumb-section"
    style="position: relative; overflow: hidden; min-height: 200px;">

    {{-- Background image --}}
    <div class="breadcrumbs-img" style="position: relative;">
        <img id="breadcrumb-bg-img"
            src="{{ $imgSrc }}"
            alt="Breadcrumb"
            crossorigin="anonymous"
            style="width: 100%; max-height: 280px; object-fit: cover; display: block;">
    </div>

    {{-- Overlay (opacity controlled by JS) --}}
    <div id="breadcrumb-overlay"
        style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); z-index: 1; transition: background 0.3s;">
    </div>

    {{-- Text --}}
    <div id="breadcrumb-text"
        class="breadcrumbs-text white-color"
        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
               width: 100%; text-align: center; z-index: 2; padding: 0 20px;">
        <h1 class="page-title" style="color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.4);">
            {{ $title }}
        </h1>
        <ul style="list-style: none; padding: 0; margin: 0;">
            @foreach ($items as $i => $item)
                @if ($i < count($items) - 1)
                    <li style="display: inline-block;">
                        <a href="{{ $item['url'] }}"
                            style="color: inherit; padding-right: 30px; position: relative; transition: color 0.3s;">
                            {{ $item['label'] }}
                            {{-- separator arrows --}}
                            <span style="position: absolute; right: 7px; top: 2px; font-size: 12px; opacity: 0.7;">/</span>
                        </a>
                    </li>
                @else
                    <li style="display: inline-block; opacity: 0.85;">{{ $item['label'] }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    /**
     * Sample the average brightness of the center region of an image
     * using an off-screen canvas.
     * Returns a value 0 (black) – 255 (white).
     */
    function getImageBrightness(imgEl, callback) {
        var canvas = document.createElement('canvas');
        var ctx    = canvas.getContext('2d');

        // Sample a 100×60 region from the center of the image
        var sampleW = 100, sampleH = 60;
        canvas.width  = sampleW;
        canvas.height = sampleH;

        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function () {
            var sx = Math.max(0, (img.naturalWidth  / 2) - sampleW / 2);
            var sy = Math.max(0, (img.naturalHeight / 2) - sampleH / 2);
            ctx.drawImage(img, sx, sy, sampleW, sampleH, 0, 0, sampleW, sampleH);

            try {
                var data = ctx.getImageData(0, 0, sampleW, sampleH).data;
                var sum  = 0, count = 0;
                for (var i = 0; i < data.length; i += 4) {
                    // Perceived luminance (ITU-R BT.709)
                    sum += 0.2126 * data[i] + 0.7152 * data[i+1] + 0.0722 * data[i+2];
                    count++;
                }
                callback(sum / count);
            } catch (e) {
                // CORS blocked — default to dark overlay / white text
                callback(0);
            }
        };
        img.onerror = function () { callback(0); };
        img.src = imgEl.src;
    }

    function applyBreadcrumbTheme() {
        var section  = document.getElementById('breadcrumb-section');
        var bgImg    = document.getElementById('breadcrumb-bg-img');
        var overlay  = document.getElementById('breadcrumb-overlay');
        var textEl   = document.getElementById('breadcrumb-text');
        var titleEl  = textEl ? textEl.querySelector('.page-title') : null;

        if (!bgImg || !overlay || !textEl) return;

        getImageBrightness(bgImg, function (brightness) {
            var isDark = brightness < 128; // image is dark → use white text

            if (isDark) {
                // Dark image: semi-dark overlay + white text
                overlay.style.background = 'rgba(0, 0, 0, 0.45)';
                textEl.style.color       = '#ffffff';
                if (titleEl) {
                    titleEl.style.color      = '#ffffff';
                    titleEl.style.textShadow = '0 2px 10px rgba(0,0,0,0.5)';
                }
                // Make all links white
                textEl.querySelectorAll('a, li').forEach(function (el) {
                    el.style.color = '#ffffff';
                });
            } else {
                // Light image: light overlay + dark text
                overlay.style.background = 'rgba(255, 255, 255, 0.55)';
                textEl.style.color       = '#1a1a2e';
                if (titleEl) {
                    titleEl.style.color      = '#1a1a2e';
                    titleEl.style.textShadow = '0 2px 8px rgba(255,255,255,0.8)';
                }
                textEl.querySelectorAll('a, li').forEach(function (el) {
                    el.style.color = '#1a1a2e';
                });
            }
        });
    }

    // Run after DOM + images are ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyBreadcrumbTheme);
    } else {
        applyBreadcrumbTheme();
    }
})();
</script>
@endpush
@endonce
