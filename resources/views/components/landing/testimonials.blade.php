<!-- testimonial area -->
<div class="testimonial-area ts-bg pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i>
                        Testimonials</span>
                    <h2 class="site-title text-white">Apa Kata<span> Alumni ?</span></h2>
                    <p class="text-white">Alumni kuliah di dalam Negeri dan di luar Negeri</p>
                </div>
            </div>
        </div>
        <div class="testimonial-slider owl-carousel owl-theme">
            @php
                // Ambil testimonial dari database atau gunakan dummy data
                $testimonials = \App\Models\Testimonial::approved()->featured()->latest()->limit(6)->get();

                // Jika tidak ada testimonial di database, gunakan dummy data
                if ($testimonials->isEmpty()) {
                    $testimonials = collect(\App\Models\Testimonial::getDummyTestimonials());
                }
            @endphp

            @foreach ($testimonials as $testimonial)
                <div class="testimonial-item">
                    <div class="testimonial-rate">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $testimonial['rating'] ? '' : '-o' }}"></i>
                        @endfor
                    </div>
                    <div class="testimonial-quote">
                        <p>{{ $testimonial['testimonial'] }}</p>
                    </div>
                    <div class="testimonial-content">
                        <div class="testimonial-author-img">
                            <img src="{{ $testimonial['photo'] }}" alt="{{ $testimonial['name'] }}">
                        </div>
                        <div class="testimonial-author-info">
                            <h4>{{ $testimonial['name'] }}</h4>
                            <p>
                                @if ($testimonial['position'] === 'Alumni')
                                    Alumni {{ $testimonial['graduation_year'] }}
                                @elseif ($testimonial['position'] === 'Siswa')
                                    {{ $testimonial['class'] }}
                                @else
                                    {{ $testimonial['position'] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="testimonial-quote-icon"><i class="far fa-quote-right"></i></span>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- testimonial area end -->
