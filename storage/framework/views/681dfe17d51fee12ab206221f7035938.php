<!-- Testimonial Area -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['testimonials' => []]));

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

foreach (array_filter((['testimonials' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="testimonial-area ts-bg pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Testimonials</span>
                    <h2 class="site-title text-white">Apa Kata<span> Alumni ?</span></h2>
                    <p class="text-white">Alumni kuliah di dalam Negeri dan di luar Negeri</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme">
            <?php
                // Check if DB testimonials have meaningful content (non-empty testimonial text)
                $hasValidTestimonials = $testimonials->isNotEmpty() && $testimonials->contains(function ($t) {
                    return !empty($t->testimonial) || !empty($t->content);
                });
            ?>
            <?php if($hasValidTestimonials): ?>
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $testimonialText = $testimonial->testimonial ?? $testimonial->content ?? '';
                        // Skip testimonials with empty content
                        if (empty(trim($testimonialText))) continue;
                    ?>
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= ($testimonial->rating ?? 5)): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial-quote">
                            <p><?php echo e($testimonialText); ?></p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <?php if(!empty($testimonial->photo)): ?>
                                    <img src="<?php echo e(Storage::url($testimonial->photo)); ?>" alt="<?php echo e($testimonial->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets_maudu/assets/img/testimonial/01.jpg')); ?>" alt="<?php echo e($testimonial->name ?? 'Alumni'); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="testimonial-author-info">
                                <h4><?php echo e($testimonial->name ?? 'Alumni'); ?></h4>
                                <p><?php echo e($testimonial->position ?? ($testimonial->occupation ?? 'Alumni ' . theme_config('short_name'))); ?></p>
                            </div>
                        </div>
                        <span class="testimonial-quote-icon"><i class="far fa-quote-right"></i></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php
                    $defaultTestimonials = [
                        [
                            'name' => 'Riza Azkia (2012)',
                            'position' => 'Al-Azhar Kairo - Staff KBRI di Baghdad, Iraq',
                            'content' => 'Di Madrasah ini, kita tidak hanya diajarkan ilmu umum, dan agama, tapi juga ditempa dengan pengamalan akhlak yang sangat luar biasa. Belajar di Madrasah Aliyah Unggulan Darul Ulum adalah pengalaman yang sangat berharga untuk saya. Terimakasih kepada segenap Bapak Ibu guru, berkat ajaran doa beliau, saya sampai pada titik ini.',
                            'photo' => 'assets_maudu/assets/img/testimonial/01.jpg',
                        ],
                        [
                            'name' => 'NAILA KHAIRUN NAJWA (2024)',
                            'position' => 'Universitas Az-Zaitunah Tunisia',
                            'content' => 'Setelah sampai di Tunisia, saya semakin menyadari bahwa pembelajaran di MAU tidak hanya berorientasi pada akademik semata. MAU juga membentuk kepribadian kami agar siap menghadapi berbagai situasi. Kami dilatih untuk berpikir kritis, menyampaikan pendapat dengan percaya diri, dan menjaga adab dalam setiap interaksi.',
                            'photo' => 'assets_maudu/assets/img/testimonial/02.jpg',
                        ],
                        [
                            'name' => 'Naura Bya Sakan Naja (2024)',
                            'position' => 'Ushuluddin - Yarmouk University, Yordania',
                            'content' => 'Ilmu adalah cahaya yang membimbing kita menuju jalan kesuksesan. Sukses yang hendak digapai bukan semata duniawiyah tetapi berkelanjutan Ukhrawiyah. Bak asa dalam pelangitan doa yg dikenal sebagai doa sapujagat: Khasanah Fiddunya Khasanah Fil akhirah. Semoga Allah limpahkan Rahmat dan berkahNya bagi para guru.',
                            'photo' => 'assets_maudu/assets/img/testimonial/03.jpg',
                        ],
                        [
                            'name' => 'UMIT ISLAMY DAVALA (2024)',
                            'position' => 'Teknik Sipil - Institut Teknologi Sepuluh Nopember',
                            'content' => 'Di MAU Darul Ulum, saya tidak hanya belajar ilmu agama, tetapi juga dilatih untuk disiplin, fokus, dan memiliki etos kerja yang tinggi. Nilai-nilai ini sangat membantu saya selama proses persiapan SNBT. Saya percaya, setiap siswa MAU Darul Ulum memiliki potensi besar untuk bersaing dan meraih prestasi di tingkat nasional.',
                            'photo' => 'assets_maudu/assets/img/testimonial/04.jpg',
                        ],
                        [
                            'name' => 'DR. KH. Zainul Arifin, M.A, M.Ed.',
                            'position' => 'Pengasuh Ponpes Darul Arifin, Jambi',
                            'content' => 'Di Darul Ulum khususnya MAU, saya memperolah banyak sekali pengalaman yang berkesan. Bimbingan para masyayikh dan guru yang sabar dan ikhlas, baik akademik maupun non akademik, membentuk diri saya seperti yang saat ini. Sekolah sambil nyantri di Darul ulum mengajarkan Kuat Dzikir dan Pikir sehingga membentuk pribadi yang mantap secara intelektual dan matang secara spiritual.',
                            'photo' => 'assets_maudu/assets/img/testimonial/05.jpg',
                        ],
                    ];
                ?>
                <?php $__currentLoopData = $defaultTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial-quote">
                            <p><?php echo e($item['content']); ?></p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="<?php echo e(asset($item['photo'])); ?>" alt="<?php echo e($item['name']); ?>">
                            </div>
                            <div class="testimonial-author-info">
                                <h4><?php echo e($item['name']); ?></h4>
                                <p><?php echo e($item['position']); ?></p>
                            </div>
                        </div>
                        <span class="testimonial-quote-icon"><i class="far fa-quote-right"></i></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Testimonial Area End -->

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.testimonial-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/testimonial.blade.php ENDPATH**/ ?>