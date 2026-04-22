<!-- choose-area -->
<div class="choose-area pt-80 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
                    <div class="choose-content-info">
                        <div class="site-heading mb-0">
                            <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Program
                                Peminatan</span>
                            <h2 class="site-title text-white mb-10">
                                {{ cache('site_setting_program_section_title', '3 Program Peminatan') }}</h2>
                        </div>
                        <div class="choose-content-wrap">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="choose-item">
                                        <div class="choose-item-icon">
                                            <img src="{{ asset('assets/img/icon/course.svg') }}" alt="">
                                        </div>
                                        <div class="choose-item-info">
                                            <h4>{{ cache('site_setting_program_ipa_title', 'PEMINATAN ILMU PENGETAHUAN ALAM (IPA)') }}
                                            </h4>
                                            <p>{{ cache('site_setting_program_ipa_description', 'Menyiapkan peserta didik yang handal dalam kajian ilmiah dan alamiah dengan berlandaskan kepada ayat-ayat qauliyah dan kauniyah.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="choose-item">
                                        <div class="choose-item-icon">
                                            <img src="{{ asset('assets/img/icon/course.svg') }}" alt="">
                                        </div>
                                        <div class="choose-item-info">
                                            <h4>{{ cache('site_setting_program_ips_title', 'PEMINATAN ILMU PENGETAHUAN SOSIAL (IPS)') }}
                                            </h4>
                                            <p>{{ cache('site_setting_program_ips_description', 'Menyiapkan peserta didik yang dapat menguasai ilmu-ilmu sosial secara terpadu antara keislaman dan pengetahuan sehingga menjadi insan yang sosialis-agamis.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="choose-item">
                                        <div class="choose-item-icon">
                                            <img src="{{ asset('assets/img/icon/course.svg') }}" alt="">
                                        </div>
                                        <div class="choose-item-info">
                                            <h4>{{ cache('site_setting_program_religion_title', 'PEMINATAN KEAGAMAAN') }}
                                            </h4>
                                            <p>{{ cache('site_setting_program_religion_description', 'Menyiapkan peserta didik yang lebih mampu menguasai ilmu-ilmu agama dengan mengkaji sumber aslinya serta mengkolaborasikan dengan perkembangan IPTEK.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="choose-img wow fadeInRight" data-wow-delay=".25s">
                    @if (cache('site_setting_program_section_image'))
                        <img src="{{ Storage::url(cache('site_setting_program_section_image')) }}"
                            alt="Program Peminatan">
                    @else
                        <img src="{{ asset('assets/img/choose/01.jpg') }}" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- choose-area end -->
