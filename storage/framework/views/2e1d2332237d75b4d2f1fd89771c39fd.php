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
                /**
                 * Partner logo mapping: nama partner → nomor file di assets_maudu/assets/img/partner/
                 * File tersedia: 01.png sampai 10.png
                 */
                $partnerAssetMap = [
                    'axioo' => '01',
                    'gamelab' => '02',
                    'gamelab indonesia' => '02',
                    'plts' => '03',
                    'lab plts' => '03',
                    'fiber optik' => '04',
                    'lab fiber optik' => '04',
                    'studio seje' => '05',
                    'telkom' => '06',
                    'bri' => '07',
                    'pemkab jombang' => '08',
                    'kemenag' => '09',
                    'nu' => '10',
                ];
            ?>
            <?php if(count($partners) > 0): ?>
                <?php $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $logoUrl = null;
                        $partnerLower = strtolower(trim($partner->name ?? ''));

                        // 1. Coba ambil dari storage jika file ada
                        if (!empty($partner->logo) && Storage::disk('public')->exists($partner->logo)) {
                            $logoUrl = Storage::url($partner->logo);
                        }
                        // 2. Coba mapping ke asset MAUDU berdasarkan nama partner
                        elseif (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $logoUrl = asset($assetPath);
                            }
                        }
                        // 3. Coba cari langsung berdasarkan nama file di assets_maudu
                        elseif (!empty($partner->logo)) {
                            $assetPath = "assets_maudu/assets/img/partner/{$partner->logo}";
                            if (file_exists(public_path($assetPath))) {
                                $logoUrl = asset($assetPath);
                            }
                        }
                    ?>
                    <div class="partner-item text-center">
                        <?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($partner->name); ?>" style="max-height: 60px;">
                        <?php else: ?>
                            <span class="partner-name fs-5 fw-bold text-muted"><?php echo e($partner->name); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $defaultPartners = [
                        'Axioo',
                        'GAMELAB',
                        'PLTS',
                        'Fiber Optik',
                        'Studio Seje',
                        'Telkom',
                        'BRI',
                        'Pemkab Jombang',
                        'Kemenag',
                        'NU',
                    ];
                ?>
                <?php $__currentLoopData = $defaultPartners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partnerName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $partnerLower = strtolower($partnerName);
                        $defaultLogoUrl = null;
                        if (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $defaultLogoUrl = asset($assetPath);
                            }
                        }
                    ?>
                    <div class="partner-item text-center">
                        <?php if($defaultLogoUrl): ?>
                            <img src="<?php echo e($defaultLogoUrl); ?>" alt="<?php echo e($partnerName); ?>" style="max-height: 60px;">
                        <?php else: ?>
                            <span class="partner-name fs-5 fw-bold text-muted"><?php echo e($partnerName); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Partner Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/partner.blade.php ENDPATH**/ ?>