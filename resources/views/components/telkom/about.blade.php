@props(['siswaCount' => 0, 'kelulusanPercentage' => 0])
<!-- About Section Start -->
<div id="rs-about" class="rs-about style2 pt-94 pb-100 md-pt-64 md-pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 pr-65 md-pr-15 md-mb-50">
                <div class="about-intro">
                    {{-- Headmaster Photo --}}
                    @if(!empty($siteSettings['headmaster_photo']))
                    <div class="headmaster-photo mb-30">
                        <img src="{{ Storage::url($siteSettings['headmaster_photo']) }}" alt="{{ $siteSettings['headmaster_name'] ?? 'Kepala Sekolah' }}"
                            style="max-width: 120px; border-radius: 50%; object-fit: cover;">
                    </div>
                    @endif

                    <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                        <div class="sub-title primary">{{ $siteSettings['headmaster_name'] ?? 'NUR LAILA, S.Pd' }}</div>
                        <h6 class="title mb-21 white-color">KEPALA SEKOLAH <br>{{ strtoupper($siteSettings['site_name'] ?? 'SMK TELEKOMUNIKASI DARUL ULUM JOMBANG') }}</h6>
                        <div class="desc big white-color">{!! $siteSettings['headmaster_description'] ?? 'Selamat datang di website resmi <b>SMK Telekomunikasi Darul Ulum Jombang.</b> Website ini menjadi sarana informasi bagi siswa, orang tua, alumni, dan masyarakat untuk mengetahui berbagai kegiatan serta perkembangan sekolah.' !!}</div>
                    </div>
                    <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                        <a class="readon2" href="#rs-about">{{ $siteSettings['about_button_text'] ?? 'Selengkapnya' }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 lg-pl-0 ml--25 md-ml-0">
                <div class="row rs-counter couter-area mb-40">
                    <div class="col-md-4">
                        <div class="counter-item one">
                            <h2 class="number rs-count kplus">{{ $siteSettings['counter1_number'] ?? ($siswaCount > 0 ? $siswaCount : '400') }}</h2>
                            <h4 class="title mb-0">{{ $siteSettings['counter1_label'] ?? 'Siswa' }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="counter-item two">
                            <h2 class="number rs-count">{{ $siteSettings['counter2_number'] ?? '4' }}</h2>
                            <h4 class="title mb-0">{{ $siteSettings['counter2_label'] ?? 'Jurusan' }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="counter-item three">
                            <h2 class="number rs-count percent">{{ $siteSettings['counter3_number'] ?? ($kelulusanPercentage > 0 ? $kelulusanPercentage : '75') }}</h2>
                            <h4 class="title mb-0">{{ $siteSettings['counter3_label'] ?? 'Lanjut Kuliah' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="row grid-area">
                    <div class="col-md-6 sm-mb-30">
                        <div class="image-grid">
                            @if(!empty($siteSettings['about_image_1']))
                                <img src="{{ Storage::url($siteSettings['about_image_1']) }}" alt="Tentang Kami 1">
                            @else
                                <img src="{{ asset('assets_telkom/assets/images/about/style2/grid1.jpg') }}" alt="Grid 1">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="image-grid">
                            @if(!empty($siteSettings['about_image_2']))
                                <img src="{{ Storage::url($siteSettings['about_image_2']) }}" alt="Tentang Kami 2">
                            @else
                                <img src="{{ asset('assets_telkom/assets/images/about/style2/grid2.jpg') }}" alt="Grid 2">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Section End -->
