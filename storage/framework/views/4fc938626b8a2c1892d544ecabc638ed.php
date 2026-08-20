<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => '',
    'items' => [], // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
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
    'title' => '',
    'items' => [], // array of ['label' => '...', 'url' => '...'] — item terakhir otomatis active
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area"
    style="background: linear-gradient(135deg, #1a5632 0%, #0d3d21 100%); padding: 80px 0; position: relative; overflow: hidden;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <h2
                        style="color: #fff; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        <?php echo e($title); ?>

                    </h2>
                    <nav style="color: #ffffff; opacity: 0.95; font-size: 1rem;">
                        <a href="<?php echo e(route('landing')); ?>"
                            style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">Home</a>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span style="margin: 0 10px; opacity: 0.7;">/</span>
                            <?php if($i < count($items) - 1): ?>
                                <a href="<?php echo e($item['url']); ?>"
                                    style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">
                                    <?php echo e($item['label']); ?>

                                </a>
                            <?php else: ?>
                                <span style="opacity: 0.95; font-weight: 500;"><?php echo e($item['label']); ?></span>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\breadcrumb.blade.php ENDPATH**/ ?>