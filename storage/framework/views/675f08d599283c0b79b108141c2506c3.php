<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'  => '',
    'image'  => null,   // path relatif dari assets_telkom, e.g. 'assets/images/breadcrumbs/1.jpg'
    'items'  => [],     // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title'  => '',
    'image'  => null,   // path relatif dari assets_telkom, e.g. 'assets/images/breadcrumbs/1.jpg'
    'items'  => [],     // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $imgSrc = $image
        ? asset('assets_telkom/' . $image)
        : asset('assets_telkom/assets/images/breadcrumbs/1.jpg');
?>

<div class="rs-breadcrumbs breadcrumbs-overlay" id="breadcrumb-section"
    style="position: relative; overflow: hidden; min-height: 200px;">

    
    <div class="breadcrumbs-img" style="position: relative;">
        <img id="breadcrumb-bg-img"
            src="<?php echo e($imgSrc); ?>"
            alt="Breadcrumb"
            crossorigin="anonymous"
            style="width: 100%; max-height: 280px; object-fit: cover; display: block;">
    </div>

    
    <div id="breadcrumb-overlay"
        style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); z-index: 1; transition: background 0.3s;">
    </div>

    
    <div id="breadcrumb-text"
        class="breadcrumbs-text white-color"
        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
               width: 100%; text-align: center; z-index: 2; padding: 0 20px;">
        <h1 class="page-title" style="color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.4);">
            <?php echo e($title); ?>

        </h1>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($i < count($items) - 1): ?>
                    <li style="display: inline-block;">
                        <a href="<?php echo e($item['url']); ?>"
                            style="color: inherit; padding-right: 30px; position: relative; transition: color 0.3s;">
                            <?php echo e($item['label']); ?>

                            
                            <span style="position: absolute; right: 7px; top: 2px; font-size: 12px; opacity: 0.7;">/</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li style="display: inline-block; opacity: 0.85;"><?php echo e($item['label']); ?></li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('ddd84fae-bee9-464e-9e70-06a662adf668')): $__env->markAsRenderedOnce('ddd84fae-bee9-464e-9e70-06a662adf668'); ?>
<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/breadcrumb.blade.php ENDPATH**/ ?>