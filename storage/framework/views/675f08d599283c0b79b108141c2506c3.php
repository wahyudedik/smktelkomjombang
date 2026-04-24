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

<div class="rs-breadcrumbs breadcrumbs-overlay" id="breadcrumb-section"
    style="position: relative; overflow: hidden; padding: 80px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

    
    <div class="container">
        <div id="breadcrumb-text"
            class="breadcrumbs-text white-color"
            style="width: 100%; text-align: center; color: #ffffff;">
            <h1 class="page-title" style="color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.3); font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">
                <?php echo e($title); ?>

            </h1>
            <ul style="list-style: none; padding: 0; margin: 0; color: #ffffff; opacity: 0.95; font-size: 1rem;">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($i < count($items) - 1): ?>
                        <li style="display: inline-block;">
                            <a href="<?php echo e($item['url']); ?>"
                                style="color: #ffffff; padding-right: 30px; position: relative; transition: opacity 0.3s; text-decoration: none; opacity: 0.9;">
                                <?php echo e($item['label']); ?>

                                
                                <span style="position: absolute; right: 7px; top: 2px; font-size: 14px; opacity: 0.7;">/</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li style="display: inline-block; opacity: 0.95; font-weight: 500;"><?php echo e($item['label']); ?></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/breadcrumb.blade.php ENDPATH**/ ?>