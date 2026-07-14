<!-- Programs / Kerjasama Industri -->
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

<div class="program-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="site-heading">
                    <span class="sub-title">Kerjasama</span>
                    <h2 class="title">Kerjasama & Program Unggulan</h2>
                    <p class="desc">Kolaborasi dengan berbagai institusi untuk meningkatkan kualitas pendidikan</p>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
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
                    <div class="col-md-4 col-lg-3">
                        <div class="program-card text-center p-4 rounded shadow-sm h-100">
                            <?php if($logoUrl): ?>
                                <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($partner->name); ?>" class="mb-3"
                                    style="max-height: 80px;">
                            <?php else: ?>
                                <div class="program-icon mb-3">
                                    <i class="fas fa-handshake fa-3x text-primary"></i>
                                </div>
                            <?php endif; ?>
                            <h5><?php echo e($partner->name); ?></h5>
                            <?php if(!empty($partner->description)): ?>
                                <p class="text-muted small"><?php echo e(Str::limit($partner->description, 80)); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $defaultPartners = [
                        ['name' => 'Axioo', 'icon' => 'fas fa-laptop-code'],
                        ['name' => 'GAMELAB', 'icon' => 'fas fa-gamepad'],
                        ['name' => 'Lab PLTS', 'icon' => 'fas fa-solar-panel'],
                        ['name' => 'Fiber Optik', 'icon' => 'fas fa-network-wired'],
                        ['name' => 'Studio Seje', 'icon' => 'fas fa-film'],
                    ];
                ?>
                <?php $__currentLoopData = $defaultPartners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $partnerLower = strtolower($partner['name']);
                        $defaultLogoUrl = null;
                        if (isset($partnerAssetMap[$partnerLower])) {
                            $num = $partnerAssetMap[$partnerLower];
                            $assetPath = "assets_maudu/assets/img/partner/{$num}.png";
                            if (file_exists(public_path($assetPath))) {
                                $defaultLogoUrl = asset($assetPath);
                            }
                        }
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="program-card text-center p-4 rounded shadow-sm h-100">
                            <?php if($defaultLogoUrl): ?>
                                <img src="<?php echo e($defaultLogoUrl); ?>" alt="<?php echo e($partner['name']); ?>" class="mb-3"
                                    style="max-height: 80px;">
                            <?php else: ?>
                                <div class="program-icon mb-3">
                                    <i class="<?php echo e($partner['icon']); ?> fa-3x text-primary"></i>
                                </div>
                            <?php endif; ?>
                            <h5><?php echo e($partner['name']); ?></h5>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Programs End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/programs.blade.php ENDPATH**/ ?>