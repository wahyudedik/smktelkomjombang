<!-- Latest Events Section Start -->
<div class="rs-latest-events style1 bg-wrap pt-100 md-pt-70 md-pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 pr-65 pt-24 md-pt-0 md-pr-15 md-mb-30">
                <div class="sec-title mb-42">
                    <div class="sub-title primary">Kegiatan Sekolah</div>
                    <h2 class="title mb-0">SKATELDU</h2>
                </div>
                <div class="single-img wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                    <img src="<?php echo e(asset('assets_telkom/assets/images/event/single.jpg')); ?>" alt="Event Image">
                </div>
            </div>
            <div class="col-lg-6 lg-pl-0">
                <div class="event-wrap">
                    <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="events-short mb-30 wow fadeInUp" data-wow-delay="<?php echo e(300 + ($loop->index * 100)); ?>ms" data-wow-duration="2000ms">
                            <div class="date-part bgc<?php echo e(($loop->index % 3) + 1); ?>">
                                <span class="month"><?php echo e($event->date ? \Carbon\Carbon::parse($event->date)->format('M') : 'N/A'); ?></span>
                                <div class="date"><?php echo e($event->date ? \Carbon\Carbon::parse($event->date)->format('d') : '0'); ?></div>
                            </div>
                            <div class="content-part">
                                <h4 class="title mb-0"><?php echo e($event->title ?? 'Event'); ?></h4>
                                <div class="categorie">
                                    <a href="#"><?php echo e($event->category ?? 'Kegiatan'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="events-short mb-30 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                            <div class="date-part bgc1">
                                <span class="month">Mei</span>
                                <div class="date">6</div>
                            </div>
                            <div class="content-part">
                                <h4 class="title mb-0">Studi Kunjungan Ke Perusahaan dan Kampus</h4>
                                <div class="categorie">
                                    <a href="#">Kelas 2 Semua Jurusan</a>
                                </div>
                            </div>
                        </div>
                        <div class="events-short mb-30 wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                            <div class="date-part bgc2">
                                <span class="month">Juni</span>
                                <div class="date">1</div>
                            </div>
                            <div class="content-part">
                                <h4 class="title mb-0">Praktek Kerja Industri</h4>
                                <div class="categorie">
                                    <a href="#">Kelas 2</a>
                                </div>
                            </div>
                        </div>
                        <div class="events-short wow fadeInUp" data-wow-delay="500ms" data-wow-duration="2000ms">
                            <div class="date-part bgc3">
                                <span class="month">Juli</span>
                                <div class="date">15</div>
                            </div>
                            <div class="content-part">
                                <h4 class="title mb-0">Ujian Akhir Sekolah</h4>
                                <div class="categorie">
                                    <a href="#">Kelas 3</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="btn-part wow fadeInUp" data-wow-delay="600ms" data-wow-duration="2000ms">
                    <a class="readon2" href="#">Detail Kegiatan</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Latest Events Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/events.blade.php ENDPATH**/ ?>