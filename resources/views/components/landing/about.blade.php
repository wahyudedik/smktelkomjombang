<!-- about area -->
<div class="about-area py-120">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                    <div class="about-img">
                        <div class="row g-4">
                            <div class="col-md-6">
                                @if (cache('site_setting_about_image_1'))
                                    <img class="img-1" src="{{ Storage::url(cache('site_setting_about_image_1')) }}"
                                        alt="About Image 1">
                                @else
                                    <img class="img-1" src="{{ asset('assets/img/about/01.jpg') }}" alt="">
                                @endif
                                <div class="about-experience mt-4">
                                    <div class="about-experience-icon">
                                        <img src="{{ asset('assets/img/icon/monitor.svg') }}" alt="">
                                    </div>
                                    <b class="text-start">Gallery Kegiatan<br> MAUDU Rejoso</b>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if (cache('site_setting_about_image_2'))
                                    <img class="img-2" src="{{ Storage::url(cache('site_setting_about_image_2')) }}"
                                        alt="About Image 2">
                                @else
                                    <img class="img-2" src="{{ asset('assets/img/about/02.jpg') }}" alt="">
                                @endif
                                @if (cache('site_setting_about_image_3'))
                                    <img class="img-3 mt-4"
                                        src="{{ Storage::url(cache('site_setting_about_image_3')) }}"
                                        alt="About Image 3">
                                @else
                                    <img class="img-3 mt-4" src="{{ asset('assets/img/about/03.jpg') }}" alt="">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> INFORMASI</span>
                        <h2 class="site-title">
                            Unggulan <span>MAUDU</span> Rejoso
                        </h2>
                    </div>
                    <div class="about-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <img src="{{ asset('assets/img/icon/information.svg') }}" alt="">
                                    </div>
                                    <div class="about-item-content">
                                        <h5>KURIKULUM MADRASAH</h5>
                                        <p>Kolaborasi antara kurikulum Kepesantrenan, Kemendikbud, Kemenag dan Kurikulum
                                            Muatan Lokal Madrasah</p>
                                    </div>
                                </div>
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <img src="{{ asset('assets/img/icon/global-education.svg') }}" alt="">
                                    </div>
                                    <div class="about-item-content">
                                        <h5>PROGRAM STUDI KE TIMUR TENGAH</h5>
                                        <p>Pembinaan Intensif dan Mediator Pemberangkatan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <img src="{{ asset('assets/img/icon/open-book.svg') }}" alt="">
                                    </div>
                                    <div class="about-item-content">
                                        <h5>KELAS TAHFIDZ, MUATAN LOKAL KITAB TURATS</h5>
                                        <p>Kelas Tahfidz, Program Tahfidz serta Program Pembiasaan Siswa</p>
                                    </div>
                                </div>
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <img src="{{ asset('assets/img/icon/location.svg') }}" alt="">
                                    </div>
                                    <div class="about-item-content">
                                        <h5>PROGRAM KEMASYARAKATAN</h5>
                                        <p>Kafilah Sholat Jum'at, Sholat Tarawih, TPQ, Bakti Sosial dan Pengabdian
                                            Masyarakat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="about-bottom">
                        <a href="https://psb.ponpesdarululum.id/" target="_blank" class="theme-btn">PPDB ONLINE<i
                                class="fas fa-arrow-right-long"></i></a>
                        <div class="about-phone">
                            <div class="icon"><i class="fal fa-headset"></i></div>
                            <div class="number">
                                <span>WA KAMI</span>
                                <h6><a href="https://wa.me/628113383722" target="_blank">081 1338 3722</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about area end -->
