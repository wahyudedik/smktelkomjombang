<!-- counter area -->
<div class="counter-area pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="counter-box">
                    <div class="icon">
                        <img src="{{ asset('assets/img/icon/course.svg') }}" alt="">
                    </div>
                    <div>
                        <span class="counter" data-count="+" data-to="{{ cache('site_setting_counter1_number', '24') }}"
                            data-speed="3000">{{ cache('site_setting_counter1_number', '24') }}</span>
                        <h6 class="title">{{ cache('site_setting_counter1_label', 'Mata Pelajaran') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="counter-box">
                    <div class="icon">
                        <img src="{{ asset('assets/img/icon/graduation.svg') }}" alt="">
                    </div>
                    <div>
                        <span class="counter" data-count="+"
                            data-to="{{ cache('site_setting_counter2_number', '800') }}"
                            data-speed="3000">{{ cache('site_setting_counter2_number', '800') }}</span>
                        <h6 class="title">{{ cache('site_setting_counter2_label', '+ Peserta Didik') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="counter-box">
                    <div class="icon">
                        <img src="{{ asset('assets/img/icon/teacher-2.svg') }}" alt="">
                    </div>
                    <div>
                        <span class="counter" data-count="+" data-to="{{ cache('site_setting_counter3_number', '98') }}"
                            data-speed="3000">{{ cache('site_setting_counter3_number', '98') }}</span>
                        <h6 class="title">
                            {{ cache('site_setting_counter3_label', '+ Tenaga Pendidik & KEPENDIDIKAN') }}</h6>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-sm-6">
                <div class="counter-box">
                    <div class="icon">
                        <img src="{{ asset('assets/img/icon/award.svg') }}" alt="">
                    </div>
                    <div>
                        <span class="counter" data-count="+" data-to="500" data-speed="3000">500</span>
                        <h6 class="title">+ Penghargaan</h6>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- counter area end -->
