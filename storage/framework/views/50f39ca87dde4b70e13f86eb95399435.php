<!-- Choose Area / Program Peminatan -->
<div class="choose-area pt-80 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
                    <div class="choose-content-info">
                        <div class="site-heading mb-0">
                            <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Program Peminatan</span>
                            <h2 class="site-title text-white mb-10">3 <span>Program </span> Peminatan</h2>
                        </div>
                        <div class="choose-content-wrap">
                            <div class="row g-4">
                                <?php
                                    $peminatan = theme_config('program_peminatan', [
                                        [
                                            'name' => 'IPA',
                                            'full_name' => 'PEMINATAN ILMU PENGETAHUAN ALAM (IPA)',
                                            'desc' => 'Menyiapkan peserta didik yang handal dalam kajian ilmiah dan alamiah dengan berlandaskan kepada ayat-ayat qauliyah dan kauniyah.',
                                            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
                                            'icon' => 'fas fa-flask',
                                        ],
                                        [
                                            'name' => 'IPS',
                                            'full_name' => 'PEMINATAN ILMU PENGETAHUAN SOSIAL (IPS)',
                                            'desc' => 'Menyiapkan peserta didik yang dapat menguasai ilmu-ilmu sosial secara terpadu antara keislaman dan pengetahuan sehingga menjadi insan yang sosialis-agamis.',
                                            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
                                            'icon' => 'fas fa-globe',
                                        ],
                                        [
                                            'name' => 'Keagamaan',
                                            'full_name' => 'PEMINATAN KEAGAMAAN',
                                            'desc' => 'Menyiapkan peserta didik yang lebih mampu menguasai ilmu-ilmu agama dengan mengkaji sumber aslinya serta mengkolaborasikan dengan perkembangan IPTEK.',
                                            'icon_path' => 'assets_maudu/assets/img/icon/course.svg',
                                            'icon' => 'fas fa-book-quran',
                                        ],
                                    ]);
                                ?>

                                <?php $__currentLoopData = $peminatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-<?php echo e($index == 2 ? '12' : '6'); ?>">
                                        <div class="choose-item">
                                            <div class="choose-item-icon">
                                                <?php if(!empty($item['icon_path'])): ?>
                                                    <img src="<?php echo e(asset($item['icon_path'])); ?>" alt="">
                                                <?php elseif(!empty($item['icon'])): ?>
                                                    <i class="<?php echo e($item['icon']); ?>"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="choose-item-info">
                                                <h4><?php echo e($item['full_name'] ?? $item['name']); ?></h4>
                                                <p><?php echo e($item['desc']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="choose-img wow fadeInRight" data-wow-delay=".25s">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/choose/01.jpg')); ?>" alt="Program Peminatan"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Choose Area End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views\components\maudu\choose-area.blade.php ENDPATH**/ ?>