<!-- Events / Kegiatan -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['events' => []]));

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

foreach (array_filter((['events' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="events-area py-120">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="site-heading">
                    <span class="sub-title">Kegiatan</span>
                    <h2 class="title">Kegiatan Terkini</h2>
                    <p class="desc">Berbagai kegiatan dan acara menarik di <?php echo e(theme_config('short_name', 'MAUDU')); ?>

                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="events-list">
                    <?php if(count($events) > 0): ?>
                        <?php $__currentLoopData = $events->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="event-item mb-4 wow fadeInUp"
                                data-wow-delay="<?php echo e($loop->index * 0.15 . 's'); ?>">
                                <div class="d-flex align-items-center">
                                    <div class="event-date me-4 text-center">
                                        <span class="date-month d-block bg-primary text-white rounded px-2 py-1 small">
                                            <?php echo e(\Carbon\Carbon::parse($event->date ?? $event->created_at)->format('M')); ?>

                                        </span>
                                        <span class="date-day d-block h4 mb-0 mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($event->date ?? $event->created_at)->format('d')); ?>

                                        </span>
                                    </div>
                                    <div class="event-content">
                                        <h5 class="event-title mb-1">
                                            <a href="#"><?php echo e($event->title); ?></a>
                                        </h5>
                                        <span class="event-category text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            <?php echo e($event->category ?? 'Umum'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <?php
                            $defaultEvents = [
                                [
                                    'title' => 'KOMPASS - Kompetisi Agama, Sains, dan Seni',
                                    'category' => 'Kompetisi',
                                    'date' => now()->subDays(5),
                                ],
                                [
                                    'title' => 'MHW - Madrasah Humanitarian Week',
                                    'category' => 'Kegiatan Sosial',
                                    'date' => now()->subDays(10),
                                ],
                                [
                                    'title' => 'MAUDUFEST - Festival Budaya dan Seni',
                                    'category' => 'Festival',
                                    'date' => now()->subDays(15),
                                ],
                            ];
                        ?>
                        <?php $__currentLoopData = $defaultEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="event-item mb-4 wow fadeInUp" data-wow-delay="<?php echo e($index * 0.15 . 's'); ?>">
                                <div class="d-flex align-items-center">
                                    <div class="event-date me-4 text-center">
                                        <span class="date-month d-block bg-primary text-white rounded px-2 py-1 small">
                                            <?php echo e(\Carbon\Carbon::parse($event['date'])->format('M')); ?>

                                        </span>
                                        <span class="date-day d-block h4 mb-0 mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($event['date'])->format('d')); ?>

                                        </span>
                                    </div>
                                    <div class="event-content">
                                        <h5 class="event-title mb-1">
                                            <a href="#"><?php echo e($event['title']); ?></a>
                                        </h5>
                                        <span class="event-category text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            <?php echo e($event['category']); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Events End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/events.blade.php ENDPATH**/ ?>