<!-- Partner Area -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['partners' => []]));

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

foreach (array_filter((['partners' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="partner-area bg pt-50 pb-50">
    <div class="container">
        <div class="partner-wrapper partner-slider owl-carousel owl-theme">
            <?php
                // Filter DB partners — only show those with valid uploaded logos
                $validPartners = $partners->filter(fn($p) => !empty($p->logo) && Storage::disk('public')->exists($p->logo));
            ?>
            <?php if($validPartners->count() > 0): ?>
                <?php $__currentLoopData = $validPartners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(Storage::url($partner->logo)); ?>" alt="<?php echo e($partner->name); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                
                <?php for($i = 1; $i <= 10; $i++): ?>
                    <?php
                        $num = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                    ?>
                    <?php if(file_exists(public_path($assetPath))): ?>
                        <img src="<?php echo e(asset($assetPath)); ?>" alt="Partner <?php echo e($num); ?>">
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Partner Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.partner-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2
                    },
                    576: {
                        items: 3
                    },
                    768: {
                        items: 4
                    },
                    1024: {
                        items: 5
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\partner.blade.php ENDPATH**/ ?>