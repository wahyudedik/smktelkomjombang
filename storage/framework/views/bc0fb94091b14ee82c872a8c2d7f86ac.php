<!-- Feature Area -->
<div class="feature-area fa-negative">
    <div class="col-xl-9 ms-auto">
        <div class="feature-wrapper">
            <div class="row g-4">
                <?php
                    $features = theme_config('features', [
                        [
                            'title' => 'E-LIBRARY',
                            'desc' => 'Perpustakaan digital berisi koleksi materi dalam format elektronik.',
                            'icon' => 'fas fa-book-open',
                        ],
                        [
                            'title' => 'SERTIFIKASI KOMPETENSI',
                            'desc' => 'Uji kompetensi yang sistematis dan objektif.',
                            'icon' => 'fas fa-certificate',
                        ],
                        [
                            'title' => 'KARYA LITERASI',
                            'desc' => 'Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.',
                            'icon' => 'fas fa-pen-fancy',
                        ],
                    ]);
                ?>

                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="<?php echo e($feature['icon'] ?? 'fas fa-star'); ?>"></i>
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