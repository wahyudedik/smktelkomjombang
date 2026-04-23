<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['siswaCount' => 0, 'kelulusanPercentage' => 0]));

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

foreach (array_filter((['siswaCount' => 0, 'kelulusanPercentage' => 0]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<!-- About Section Start -->
<div id="rs-about" class="rs-about style2 pt-94 pb-100 md-pt-64 md-pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 pr-65 md-pr-15 md-mb-50">
                <div class="about-intro">
                    <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                        <div class="sub-title primary">NUR LAILA, S.Pd</div>
                        <h6 class="title mb-21 white-color">KEPALA SEKOLAH <br>SMK TELEKOMUNIKASI DARUL ULUM JOMBANG</h6>
                        <div class="desc big white-color">Selamat datang di website resmi <b>SMK Telekomunikasi Darul Ulum Jombang.</b> Website ini menjadi sarana informasi bagi siswa, orang tua, alumni, dan masyarakat untuk mengetahui berbagai kegiatan serta perkembangan sekolah.</div>
                    </div>
                    <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                        <a class="readon2" href="#">Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 lg-pl-0 ml--25 md-ml-0">
                <div class="row rs-counter couter-area mb-40">
                    <div class="col-md-4">
                        <div class="counter-item one">
                            <!-- <h2 class="number"><?php echo e($siswaCount); ?></h2> -->
                              <h2 class="number">400+</h2>
                            <h4 class="title mb-0">Siswa</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="counter-item two">
                            <h2 class="number">4</h2>
                            <h4 class="title mb-0">Jurusan</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="counter-item three">
                            <h2 class="number"><?php echo e($kelulusanPercentage); ?>%</h2>
                            <h4 class="title mb-0">Lanjut Kuliah</h4>
                        </div>
                    </div>
                </div>
                <div class="row grid-area">
                    <div class="col-md-6 sm-mb-30">
                        <div class="image-grid">
                            <img src="<?php echo e(asset('assets_telkom/assets/images/about/style2/grid1.jpg')); ?>" alt="Grid 1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="image-grid">
                            <img src="<?php echo e(asset('assets_telkom/assets/images/about/style2/grid2.jpg')); ?>" alt="Grid 2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/about.blade.php ENDPATH**/ ?>