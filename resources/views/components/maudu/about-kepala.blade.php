<!-- Campus Life / Kepala Madrasah -->
<div class="campus-life pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="content-img wow fadeInUp" data-wow-delay=".25s">
                    @php
                        $kepala = theme_config('kepala_sekolah', []);
                    @endphp
                    @if (!empty($kepala['photo']))
                        <img src="{{ $kepala['photo'] }}" alt="{{ $kepala['name'] ?? 'Kepala Madrasah' }}"
                            class="img-fluid rounded">
                    @else
                        <img src="{{ asset('assets_maudu/assets/img/team/01.jpg') }}" alt="Kepala Madrasah"
                            class="img-fluid rounded">
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
                        {{ $kepala['description'] ?? 'Selamat datang di ' . theme_config('name') . '. Kami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda dengan menggabungkan kurikulum nasional dan kepesantrenan.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Campus Life End -->
