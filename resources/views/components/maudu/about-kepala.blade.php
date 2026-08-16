<!-- Campus Life / Kepala Madrasah -->
<div class="campus-life pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="content-img wow fadeInLeft" data-wow-delay=".25s">
                    @php
                        $kepala = theme_config('kepala_sekolah', []);
                    @endphp
                    @if (!empty($kepala['photo']))
                        <img src="{{ $kepala['photo'] }}" alt="{{ $kepala['name'] ?? 'Kepala Madrasah' }}">
                    @else
                        <img src="{{ asset('assets_maudu/assets/img/campus-life/01.jpg') }}" alt="">
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <h4 class="site-title">
                            Kepala {{ theme_config('type', 'Madrasah') }}
                            @if (!empty($kepala['name']))
                                <span>: {{ $kepala['name'] }}</span>
                            @endif
                        </h4>
                    </div>
                    <p class="content-text">
                        {{ $kepala['description'] ?? 'Selamat datang di Website Resmi ' . theme_config('name') . '. Dengan rahmat Allah SWT, website ini menjadi media informasi, silaturahmi, dan komunikasi bagi siswa, alumni, orang tua, serta masyarakat. Kami menyajikan profil madrasah, kegiatan, prestasi, dan berbagai layanan pendidikan.' }}
                    </p>
                    @if (!empty($kepala['description_2']))
                        <p class="content-text mt-2">
                            {{ $kepala['description_2'] }}
                        </p>
                    @else
                        <p class="content-text mt-2">
                            Semoga kehadiran website ini memberikan manfaat, mempererat kebersamaan, serta mendukung terwujudnya pendidikan yang unggul, berkarakter, dan berorientasi pada masa depan. Kritik dan saran sangat kami harapkan demi kemajuan bersama.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Campus Life End -->
