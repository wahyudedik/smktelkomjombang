<!-- Counter Area -->
<div class="counter-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <?php
                $siswaCount = $siswaCount ?? '800';
                $counters = [
                    ['number' => '24', 'label' => 'Mata Pelajaran', 'icon_path' => 'assets_maudu/assets/img/icon/course.svg', 'icon' => 'fas fa-book'],
                    ['number' => $siswaCount, 'label' => '+ Peserta Didik', 'icon_path' => 'assets_maudu/assets/img/icon/graduation.svg', 'icon' => 'fas fa-user-graduate'],
                    ['number' => '98', 'label' => '+ Tenaga Pendidik & KEPENDIDIKAN', 'icon_path' => 'assets_maudu/assets/img/icon/teacher-2.svg', 'icon' => 'fas fa-chalkboard-teacher'],
                ];
            ?>

            <?php $__currentLoopData = $counters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-sm-6">
                    <div class="counter-box">
                        <div class="icon">
                            <?php if(!empty($counter['icon_path'])): ?>
                                <img src="<?php echo e(asset($counter['icon_path'])); ?>" alt="">
                            <?php else: ?>
                                <i class="<?php echo e($counter['icon']); ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="counter" data-count="+" data-to="<?php echo e($counter['number']); ?>" data-speed="3000">
                                <?php echo e($counter['number']); ?>

                            </span>
                            <h6 class="title"><?php echo e($counter['label']); ?></h6>
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