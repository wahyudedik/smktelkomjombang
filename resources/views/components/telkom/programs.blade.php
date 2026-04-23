<!-- Degree Section Start -->
<div class="rs-degree style1 modify gray-bg pt-100 pb-70 md-pt-70 md-pb-40">
    <div class="container">
        <div class="row y-middle">
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="sec-title wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                    <div class="sub-title primary">Kerjasama Industri</div>
                    <h2 class="title mb-0">Kurikulum dan Pengajar</h2>
                </div>
            </div>
            @forelse($partners as $partner)
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/' . ($loop->index + 1) . '.jpg') }}" alt="{{ $partner->name }}">
                        <div class="title-part">
                            <h4 class="title">{{ $partner->name }}</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">{{ $partner->name }}</a></h4>
                            <p class="desc">{{ $partner->description }}</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/1.jpg') }}" alt="Axioo">
                        <div class="title-part">
                            <h4 class="title">Axioo Class Program</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">Axioo Class Program</a></h4>
                            <p class="desc">Kerjasama Kurikulum SMK dengan Industri Axioo</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/2.jpg') }}" alt="GAMELAB">
                        <div class="title-part">
                            <h4 class="title">GAMELAB Indonesia</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">GAMELAB Indonesia</a></h4>
                            <p class="desc">Kerjasama Kurikulum SMK dengan Industri GAMELAB</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/3.jpg') }}" alt="Lab PLTS">
                        <div class="title-part">
                            <h4 class="title">Lab PLTS</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">Lab PLTS</a></h4>
                            <p class="desc">Praktik Pembangkit Listrik Tenaga Surya</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/4.jpg') }}" alt="Lab Fiber Optik">
                        <div class="title-part">
                            <h4 class="title">Lab Fiber Optik</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">Lab Fiber Optik</a></h4>
                            <p class="desc">Praktik Pengkabel Fiber Optik</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="degree-wrap">
                        <img src="{{ asset('assets_telkom/assets/images/degrees/5.jpg') }}" alt="Studio Seje">
                        <div class="title-part">
                            <h4 class="title">Studio Seje</h4>
                        </div>
                        <div class="content-part">
                            <h4 class="title"><a href="#">Studio Seje</a></h4>
                            <p class="desc">Praktik Fotografi dan Videografi</p>
                            <div class="btn-part">
                                <a href="#">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Degree Section End -->
