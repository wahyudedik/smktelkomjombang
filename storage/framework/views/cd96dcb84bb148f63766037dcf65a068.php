<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Portal Digital Pendidikan',
    'subtitle' => 'Selamat Datang Di Portal Digital Pendidikan',
    'description' =>
        'Website sekolah yang mengintegrasikan semua layanan pendidikan dalam satu platform digital yang modern dan efisien.',
    'backgroundImage' => null,
    'showCarousel' => false,
    'carouselItems' => [],
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
    'title' => 'Portal Digital Pendidikan',
    'subtitle' => 'Selamat Datang Di Portal Digital Pendidikan',
    'description' =>
        'Website sekolah yang mengintegrasikan semua layanan pendidikan dalam satu platform digital yang modern dan efisien.',
    'backgroundImage' => null,
    'showCarousel' => false,
    'carouselItems' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- hero area -->
<section class="hero-area"
    <?php if($backgroundImage): ?> style="background-image: url('<?php echo e($backgroundImage); ?>');" <?php endif; ?>>
    <div class="hero-shape">
        <img src="<?php echo e(asset('assets/img/shape/01.png')); ?>" alt="">
    </div>
    <div class="hero-shape-2">
        <img src="<?php echo e(asset('assets/img/shape/02.png')); ?>" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hero-content">
                    <div class="hero-content-inner">
                        <h1 class="hero-title">
                            <i class="fas fa-book-open"></i>
                            <?php echo e($title); ?>

                        </h1>
                        <h2 class="hero-subtitle"><?php echo e($subtitle); ?></h2>
                        <p class="hero-description"><?php echo e($description); ?></p>

                        <?php if($showCarousel && count($carouselItems) > 0): ?>
                            <div class="hero-carousel">
                                <div class="owl-carousel hero-slider">
                                    <?php $__currentLoopData = $carouselItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="hero-slide">
                                            <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>">
                                            <div class="hero-slide-content">
                                                <h3><?php echo e($item['title']); ?></h3>
                                                <p><?php echo e($item['description']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero area end -->
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\landing\hero.blade.php ENDPATH**/ ?>