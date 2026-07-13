<!-- Testimonial Area -->
@props(['testimonials' => []])

<div class="testimonial-area ts-bg pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="sub-title">Testimoni</span>
                    <h2 class="title">Apa Kata Mereka?</h2>
                    <p class="desc">Cerita dari alumni dan siswa {{ theme_config('short_name', 'MAUDU') }}</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme">
            @if (count($testimonials) > 0)
                @foreach ($testimonials as $testimonial)
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= ($testimonial->rating ?? 5))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p>{{ $testimonial->content ?? ($testimonial->testimonial ?? '') }}</p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-info">
                                <div class="author-img">
                                    @if (!empty($testimonial->photo))
                                        <img src="{{ Storage::url($testimonial->photo) }}"
                                            alt="{{ $testimonial->name }}">
                                    @else
                                        <img src="{{ asset('assets_maudu/assets/img/testimonial/01.jpg') }}"
                                            alt="{{ $testimonial->name ?? 'Alumni' }}">
                                    @endif
                                </div>
                                <div class="author-info">
                                    <h4>{{ $testimonial->name ?? 'Alumni' }}</h4>
                                    <span>{{ $testimonial->position ?? ($testimonial->occupation ?? 'Alumni ' . theme_config('short_name')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php
                    $defaultTestimonials = [
                        [
                            'name' => 'Ahmad Fauzi',
                            'position' => 'Mahasiswa UIN',
                            'rating' => 5,
                            'content' =>
                                'Pendidikan di MAUDU sangat berkualitas. Saya mendapatkan beasiswa kuliah berkat prestasi yang diraih selama di sini.',
                        ],
                        [
                            'name' => 'Siti Nurhaliza',
                            'position' => 'Guru SD',
                            'rating' => 5,
                            'content' =>
                                'MAUDU memberikan fondasi agama yang kuat dan juga kompetensi akademik yang baik. Sangat merekomendasikan.',
                        ],
                        [
                            'name' => 'Muhammad Rizki',
                            'position' => 'Karyawan BUMN',
                            'rating' => 5,
                            'content' =>
                                'Alumni MAUDU siap bersaing di dunia kerja. Terima kasih MAUDU atas bekal ilmunya.',
                        ],
                    ];
                @endphp
                @foreach ($defaultTestimonials as $index => $item)
                    <div class="testimonial-item">
                        <div class="testimonial-rate">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p>{{ $item['content'] }}</p>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-info">
                                <div class="author-img">
                                    <img src="{{ asset('assets_maudu/assets/img/testimonial/0' . ($index + 1) . '.jpg') }}"
                                        alt="{{ $item['name'] }}">
                                </div>
                                <div class="author-info">
                                    <h4>{{ $item['name'] }}</h4>
                                    <span>{{ $item['position'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Testimonial Area End -->

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.testimonial-slider').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endpush
