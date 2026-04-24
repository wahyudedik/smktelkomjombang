

<?php $__env->startSection('content'); ?>
    <!-- Hero Slider Section -->
    <?php if (isset($component)) { $__componentOriginalb1b61971b4ce7a5b940664b287217d6b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb1b61971b4ce7a5b940664b287217d6b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.hero-slider','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.hero-slider'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb1b61971b4ce7a5b940664b287217d6b)): ?>
<?php $attributes = $__attributesOriginalb1b61971b4ce7a5b940664b287217d6b; ?>
<?php unset($__attributesOriginalb1b61971b4ce7a5b940664b287217d6b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb1b61971b4ce7a5b940664b287217d6b)): ?>
<?php $component = $__componentOriginalb1b61971b4ce7a5b940664b287217d6b; ?>
<?php unset($__componentOriginalb1b61971b4ce7a5b940664b287217d6b); ?>
<?php endif; ?>

    <!-- Services/Jurusan Section -->
    <?php if (isset($component)) { $__componentOriginal5bc1166ebac27f32fbdf44a4656993e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bc1166ebac27f32fbdf44a4656993e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.services','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.services'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bc1166ebac27f32fbdf44a4656993e4)): ?>
<?php $attributes = $__attributesOriginal5bc1166ebac27f32fbdf44a4656993e4; ?>
<?php unset($__attributesOriginal5bc1166ebac27f32fbdf44a4656993e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bc1166ebac27f32fbdf44a4656993e4)): ?>
<?php $component = $__componentOriginal5bc1166ebac27f32fbdf44a4656993e4; ?>
<?php unset($__componentOriginal5bc1166ebac27f32fbdf44a4656993e4); ?>
<?php endif; ?>

    <!-- About Section -->
    <?php if (isset($component)) { $__componentOriginal64e9a38a788ef592b230a1d4047f1526 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal64e9a38a788ef592b230a1d4047f1526 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.about','data' => ['siswaCount' => $siswaCount,'kelulusanPercentage' => $kelulusanPercentage]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.about'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['siswaCount' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siswaCount),'kelulusanPercentage' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($kelulusanPercentage)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal64e9a38a788ef592b230a1d4047f1526)): ?>
<?php $attributes = $__attributesOriginal64e9a38a788ef592b230a1d4047f1526; ?>
<?php unset($__attributesOriginal64e9a38a788ef592b230a1d4047f1526); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal64e9a38a788ef592b230a1d4047f1526)): ?>
<?php $component = $__componentOriginal64e9a38a788ef592b230a1d4047f1526; ?>
<?php unset($__componentOriginal64e9a38a788ef592b230a1d4047f1526); ?>
<?php endif; ?>

    <!-- Programs/Kerjasama Section -->
    <?php if (isset($component)) { $__componentOriginalfb68eb2b9b9da23503a1d3c82d74968f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb68eb2b9b9da23503a1d3c82d74968f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.programs','data' => ['partners' => $partners]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.programs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['partners' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($partners)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb68eb2b9b9da23503a1d3c82d74968f)): ?>
<?php $attributes = $__attributesOriginalfb68eb2b9b9da23503a1d3c82d74968f; ?>
<?php unset($__attributesOriginalfb68eb2b9b9da23503a1d3c82d74968f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb68eb2b9b9da23503a1d3c82d74968f)): ?>
<?php $component = $__componentOriginalfb68eb2b9b9da23503a1d3c82d74968f; ?>
<?php unset($__componentOriginalfb68eb2b9b9da23503a1d3c82d74968f); ?>
<?php endif; ?>

    <!-- CTA Section -->
    <?php if (isset($component)) { $__componentOriginal206275271a09e865abc2bd91e93d4b14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal206275271a09e865abc2bd91e93d4b14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.cta','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal206275271a09e865abc2bd91e93d4b14)): ?>
<?php $attributes = $__attributesOriginal206275271a09e865abc2bd91e93d4b14; ?>
<?php unset($__attributesOriginal206275271a09e865abc2bd91e93d4b14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal206275271a09e865abc2bd91e93d4b14)): ?>
<?php $component = $__componentOriginal206275271a09e865abc2bd91e93d4b14; ?>
<?php unset($__componentOriginal206275271a09e865abc2bd91e93d4b14); ?>
<?php endif; ?>

    <!-- Events Section -->
    <?php if (isset($component)) { $__componentOriginal08fa4f2188051b244aa551b82c94bee4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal08fa4f2188051b244aa551b82c94bee4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.events','data' => ['events' => $events]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.events'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['events' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($events)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal08fa4f2188051b244aa551b82c94bee4)): ?>
<?php $attributes = $__attributesOriginal08fa4f2188051b244aa551b82c94bee4; ?>
<?php unset($__attributesOriginal08fa4f2188051b244aa551b82c94bee4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal08fa4f2188051b244aa551b82c94bee4)): ?>
<?php $component = $__componentOriginal08fa4f2188051b244aa551b82c94bee4; ?>
<?php unset($__componentOriginal08fa4f2188051b244aa551b82c94bee4); ?>
<?php endif; ?>

    <!-- Partners Section -->
    <?php if (isset($component)) { $__componentOriginal72e5c5486ce16f4f1b63753086832988 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72e5c5486ce16f4f1b63753086832988 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.partners','data' => ['partners' => $partners]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.partners'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['partners' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($partners)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72e5c5486ce16f4f1b63753086832988)): ?>
<?php $attributes = $__attributesOriginal72e5c5486ce16f4f1b63753086832988; ?>
<?php unset($__attributesOriginal72e5c5486ce16f4f1b63753086832988); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72e5c5486ce16f4f1b63753086832988)): ?>
<?php $component = $__componentOriginal72e5c5486ce16f4f1b63753086832988; ?>
<?php unset($__componentOriginal72e5c5486ce16f4f1b63753086832988); ?>
<?php endif; ?>

    <!-- Testimonials Section -->
    <?php if (isset($component)) { $__componentOriginal5a2de31ac60438e90474c083b84ec48e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a2de31ac60438e90474c083b84ec48e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.testimonials','data' => ['testimonials' => $testimonials]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testimonials)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a2de31ac60438e90474c083b84ec48e)): ?>
<?php $attributes = $__attributesOriginal5a2de31ac60438e90474c083b84ec48e; ?>
<?php unset($__attributesOriginal5a2de31ac60438e90474c083b84ec48e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a2de31ac60438e90474c083b84ec48e)): ?>
<?php $component = $__componentOriginal5a2de31ac60438e90474c083b84ec48e; ?>
<?php unset($__componentOriginal5a2de31ac60438e90474c083b84ec48e); ?>
<?php endif; ?>

    <!-- Blog Section -->
    <?php if (isset($component)) { $__componentOriginalf3450caae6f24b5675c2cd35530a4cdd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf3450caae6f24b5675c2cd35530a4cdd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.telkom.blog','data' => ['blogs' => $blogs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('telkom.blog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blogs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blogs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf3450caae6f24b5675c2cd35530a4cdd)): ?>
<?php $attributes = $__attributesOriginalf3450caae6f24b5675c2cd35530a4cdd; ?>
<?php unset($__attributesOriginalf3450caae6f24b5675c2cd35530a4cdd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf3450caae6f24b5675c2cd35530a4cdd)): ?>
<?php $component = $__componentOriginalf3450caae6f24b5675c2cd35530a4cdd; ?>
<?php unset($__componentOriginalf3450caae6f24b5675c2cd35530a4cdd); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.telkom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views/telkom.blade.php ENDPATH**/ ?>