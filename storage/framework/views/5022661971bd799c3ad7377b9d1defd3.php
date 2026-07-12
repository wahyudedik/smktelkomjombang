<!-- Counter Area -->
<div class="counter-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <?php
                $counters = [
                    ['number' => '24', 'label' => 'Mata Pelajaran', 'icon' => 'fas fa-book'],
                    ['number' => $siswaCount ?? '800', 'label' => 'Peserta Didik', 'icon' => 'fas fa-user-graduate'],
                    ['number' => '98', 'label' => 'Tenaga Pendidik', 'icon' => 'fas fa-chalkboard-teacher'],
                ];
            ?>

            <?php $__currentLoopData = $counters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-sm-6">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="<?php echo e($counter['icon']); ?>"></i>
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="<?php echo e($counter['number']); ?>">
                                    <?php echo e($counter['number']); ?>

                                </span>
                                <?php if(strpos($counter['number'], '+') === false && $counter['number'] !== ($siswaCount ?? '800')): ?>
                                    <span class="suffix">+</span>
                                <?php endif; ?>
                            </div>
                            <h4 class="counter-title"><?php echo e($counter['label']); ?></h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- Counter Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        // Counter animation will be handled by counter-up.js
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/counter.blade.php ENDPATH**/ ?>