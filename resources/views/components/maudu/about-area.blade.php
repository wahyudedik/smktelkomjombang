<!-- About Area -->
<div class="about-area py-120">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                    <div class="about-img">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <img src="{{ asset('assets_maudu/assets/img/about/01.jpg') }}" alt="Gallery"
                                    class="img-fluid rounded">
                                <div class="about-experience mt-4">
                                    <span class="experience-number">15+</span>
                                    <span class="experience-text">Tahun Pengalaman</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('assets_maudu/assets/img/about/02.jpg') }}" alt="Gallery"
                                    class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <span class="sub-title">INFORMASI</span>
                        <h2 class="title">Unggulan <span>{{ theme_config('short_name', 'MAUDU') }}</span> Rejoso</h2>
                    </div>
                    <div class="about-content">
                        <div class="row">
                            @php
                                $programs = theme_config('program_unggulan', [
                                    [
                                        'title' => 'KURIKULUM MADRASAH',
                                        'desc' => 'Kolaborasi kurikulum Kepesantrenan, Kemendikbud, Kemenag.',
                                        'icon' => 'fas fa-graduation-cap',
                                    ],
                                    [
                                        'title' => 'PROGRAM STUDI KE TIMUR TENGAH',
                                        'desc' => 'Pembinaan Intensif dan Mediator Pemberangkatan.',
                                        'icon' => 'fas fa-plane-departure',
                                    ],
                                    [
                                        'title' => 'KELAS TAHFIDZ',
                                        'desc' => 'Program Tahfidz serta Pembiasaan Siswa.',
                                        'icon' => 'fas fa-book-quran',
                                    ],
                                    [
                                        'title' => 'PROGRAM KEMASYARAKATAN',
                                        'desc' => 'Kafilah Sholat Jum\'at, TPQ, Bakti Sosial.',
                                        'icon' => 'fas fa-hands-helping',
                                    ],
                                ]);
                            @endphp

                            @foreach ($programs as $index => $program)
                                @if ($index % 2 == 0)
                                    <div class="col-md-6">
                                @endif
                                <div class="about-item">
                                    <div class="about-item-icon">
                                        <i class="{{ $program['icon'] ?? 'fas fa-star' }}"></i>
                                    </div>
                                    <div class="about-item-content">
                                        <h4>{{ $program['title'] }}</h4>
                                        <p>{{ $program['desc'] }}</p>
                                    </div>
                                </div>
                                @if ($index % 2 == 1 || $index == count($programs) - 1)
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="about-bottom mt-4">
                    <a class="btn btn-primary me-3" href="{{ theme_config('ppdb_url', '#') }}" target="_blank">
                        PPDB ONLINE <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <div class="about-phone d-inline-flex align-items-center">
                        <div class="phone-icon me-3">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="number">
                            <span>Hubungi Kami</span>
                            <a href="https://wa.me/{{ theme_config('whatsapp') }}" target="_blank">
                                {{ theme_config('phone') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- About Area End -->
