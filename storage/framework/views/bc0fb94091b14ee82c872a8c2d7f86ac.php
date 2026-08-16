<!-- Feature Area -->
<div class="feature-area fa-negative">
    <div class="col-xl-9 ms-auto">
        <div class="feature-wrapper">
            <div class="row g-4">
                <?php
                    $features = theme_config('features', [
                        [
                            'title' => 'E-LIBRARY',
                            'desc' => 'Perpustakaan digital berisi Koleksi materi dalam format elektronik',
                            'icon_path' => 'assets_maudu/assets/img/icon/library.svg',
                            'icon' => 'fas fa-book-open',
                        ],
                        [
                            'title' => 'SERTIFIKASI KOMPETENSI',
                            'desc' => 'Uji kompetensi yang sistematis dan objektif',
                            'icon_path' => 'assets_maudu/assets/img/icon/teacher-2.svg',
                            'icon' => 'fas fa-certificate',
                        ],
                        [
                            'title' => 'KARYA LITERASI',
                            'desc' => 'Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.',
                            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
                            'icon' => 'fas fa-pen-fancy',
                        ],
                    ]);
                ?>

                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-item">
                            <span class="count"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                            <div class="feature-icon">
                                <?php if(!empty($feature['icon_path'])): ?>
                                    <img src="<?php echo e(asset($feature['icon_path'])); ?>" alt="">
                                <?php elseif(!empty($feature['icon'])): ?>
                                    <i class="<?php echo e($feature['icon']); ?>"></i>
                                <?php else: ?>
                                    <i class="fas fa-star"></i>
                                <?php endif; ?>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title"><?php echo e($feature['title']); ?></h4>
                                <p class="feature-text"><?php echo e($feature['desc']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<!-- Feature Area End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/feature-area.blade.php ENDPATH**/ ?>