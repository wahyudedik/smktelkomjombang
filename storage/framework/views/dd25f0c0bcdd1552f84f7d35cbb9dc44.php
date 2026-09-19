

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => '', 'items' => []]));

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

foreach (array_filter((['title' => '', 'items' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<nav class="mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center flex-wrap text-sm text-slate-500 dark:text-dark-400 space-x-1">
        
        <li class="flex items-center">
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
               title="Dashboard">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
        </li>

        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex items-center">
                
                <svg class="w-4 h-4 mx-1 text-slate-300 dark:text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                <?php if($loop->last): ?>
                    
                    <a href="<?php echo e($item['url'] ?? '#'); ?>"
                       class="font-medium text-slate-700 dark:text-dark-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <?php echo e($item['label'] ?? ''); ?>

                    </a>
                <?php else: ?>
                    <span class="text-slate-400 dark:text-dark-500">
                        <?php echo e($item['label'] ?? ''); ?>

                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>

    
    <?php if($title): ?>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-1"><?php echo e($title); ?></h1>
    <?php endif; ?>
</nav>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/admin/breadcrumb.blade.php ENDPATH**/ ?>