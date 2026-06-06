<!-- CTA Section Start -->
<div class="rs-cta style2">
    <div class="partition-bg-wrap home2">
        <div class="container">
            <div class="row y-bottom">
                <div class="col-lg-6 pb-50 md-pt-100 md-pb-100">
                    <div class="video-wrap">
                        <a class="popup-videos" href="{{ $siteSettings['video_url'] ?? 'https://www.youtube.com/watch?v=F5bnwy0lRZI' }}">
                            <i class="fa fa-play"></i>
                            <h4 class="title mb-0">{{ $siteSettings['cta_video_title'] ?? 'Profil SMK Telekomunikasi DU' }}</h4>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 pl-62 pt-134 pb-150 md-pl-15 md-pt-45 md-pb-50">
                    <div class="sec-title mb-40 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="2000ms">
                        <h2 class="title mb-16">{{ $siteSettings['cta_title'] ?? 'Pendaftaran Siswa Baru ' . date('Y') }}</h2>
                        <div class="desc">{!! nl2br(e($siteSettings['cta_description'] ?? "Tempat Pendaftaran\n1. Online mandiri (24 jam) dengan alamat web psb.ponpesdarululum.id\n2. Kantor Pusat Pondok Pesantren Darul 'Ulum Jombang\n3. Buka Hari Sabtu - Kamis pukul 08:00 - 16:00 WIB\n4. Hari Jum'at & hari libur nasional pendaftaran kantor pusat libur")) !!}</div>
                    </div>
                    <div class="btn-part wow fadeInUp" data-wow-delay="400ms" data-wow-duration="2000ms">
                        <a class="readon2" href="{{ $siteSettings['cta_button_url'] ?? 'https://psb.ponpesdarululum.id/' }}" target="_blank">{{ $siteSettings['cta_button_text'] ?? 'DAFTAR' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CTA Section End -->
