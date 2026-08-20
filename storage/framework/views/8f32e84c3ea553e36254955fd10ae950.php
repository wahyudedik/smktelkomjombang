<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['size' => 'md', 'type' => 'spinner']));

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

foreach (array_filter((['size' => 'md', 'type' => 'spinner']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClasses = [
        'sm' => 'h-4 w-4',
        'md' => 'h-8 w-8',
        'lg' => 'h-12 w-12',
        'xl' => 'h-16 w-16',
    ];
?>

<?php if($type === 'spinner'): ?>
    <div class="flex justify-center items-center">
        <div class="<?php echo e($sizeClasses[$size]); ?> loading-spinner"></div>
    </div>
<?php elseif($type === 'dots'): ?>
    <div class="flex justify-center items-center">
        <div class="loading-dots">
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
            <div class="loading-dot"></div>
        </div>
    </div>
<?php elseif($type === 'pulse'): ?>
    <div class="flex justify-center items-center">
        <div class="animate-pulse">
            <div class="h-4 bg-slate-200 rounded w-24"></div>
        </div>
    </div>
<?php elseif($type === 'skeleton'): ?>
    <div class="animate-pulse">
        <div class="space-y-3">
            <div class="h-4 bg-slate-200 rounded w-3/4"></div>
            <div class="h-4 bg-slate-200 rounded w-1/2"></div>
            <div class="h-4 bg-slate-200 rounded w-5/6"></div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\loading.blade.php ENDPATH**/ ?>