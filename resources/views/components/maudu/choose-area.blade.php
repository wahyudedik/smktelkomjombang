<!-- Choose Area / Program Peminatan -->
<div class="choose-area pt-80 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
                    <div class="choose-content-info">
                        <div class="site-heading mb-0">
                            <span class="sub-title">Mengapa Memilih Kami</span>
                            <h2 class="title text-white">Program Peminatan</h2>
                        </div>
                        <div class="choose-content-wrap">
                            <div class="row g-4">
                                @php
                                    $peminatan = theme_config('program_peminatan', [
                                        [
                                            'name' => 'IPA',
                                            'full_name' => 'Peminatan Ilmu Pengetahuan Alam',
                                            'desc' => 'Program peminatan sains dan teknologi.',
                                            'icon' => 'fas fa-flask',
                                        ],
                                        [
                                            'name' => 'IPS',
                                            'full_name' => 'Peminatan Ilmu Pengetahuan Sosial',
                                            'desc' => 'Program peminatan sosial dan humaniora.',
                                            'icon' => 'fas fa-globe',
                                        ],
                                        [
                                            'name' => 'Keagamaan',
                                            'full_name' => 'Peminatan Keagamaan',
                                            'desc' => 'Program peminatan keislaman dan tahfidz.',
                                            'icon' => 'fas fa-book-quran',
                                        ],
                                    ]);
                                @endphp

                                @foreach ($peminatan as $index => $item)
                                    <div class="col-md-{{ $index == 2 ? '12' : '6' }}">
                                        <div class="choose-item">
                                            <div class="choose-item-icon">
                                                <i class="{{ $item['icon'] ?? 'fas fa-star' }}"></i>
                                            </div>
                                            <div class="choose-item-info">
                                                <h4>{{ $item['full_name'] ?? $item['name'] }}</h4>
                                                <p>{{ $item['desc'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="choose-img wow fadeInRight" data-wow-delay=".25s">
                    <img src="{{ asset('assets_maudu/assets/img/choose/01.jpg') }}" alt="Program Peminatan"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Choose Area End -->
