<!-- Blog / Berita -->
@props(['blogs' => []])

<div class="blog-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="site-heading">
                    <span class="sub-title">Berita</span>
                    <h2 class="title">Berita & Artikel Terbaru</h2>
                    <p class="desc">Informasi terkini seputar kegiatan dan prestasi
                        {{ theme_config('short_name', 'MAUDU') }}</p>
                </div>
            </div>
        </div>
        <div class="blog-slider owl-carousel owl-theme">
            @if (count($blogs) > 0)
                @foreach ($blogs->take(6) as $blog)
                    <div class="blog-item">
                        <div class="blog-img">
                            @if (!empty($blog->image))
                                <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">
                            @else
                                <img src="{{ asset('assets_maudu/assets/img/blog/01.jpg') }}" alt="{{ $blog->title }}"
                                    class="img-fluid">
                            @endif
                            <div class="blog-date">
                                <span class="date">{{ \Carbon\Carbon::parse($blog->created_at)->format('d') }}</span>
                                <span
                                    class="month">{{ \Carbon\Carbon::parse($blog->created_at)->format('M Y') }}</span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="fas fa-user"></i> {{ $blog->author ?? 'Admin' }}</span>
                                <span><i class="fas fa-folder"></i> {{ $blog->category ?? 'Berita' }}</span>
                            </div>
                            <h4 class="blog-title">
                                <a
                                    href="{{ route('berita.public.show', $blog->slug) }}">{{ Str::limit($blog->title, 60) }}</a>
                            </h4>
                            <p class="blog-desc">{{ Str::limit($blog->excerpt ?? ($blog->content ?? ''), 120) }}</p>
                            <div class="blog-bottom">
                                <a href="{{ route('berita.public.show', $blog->slug) }}" class="read-more">Baca
                                    Selengkapnya <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php
                    $defaultBlogs = [
                        [
                            'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2025/2026',
                            'category' => 'PPDB',
                            'date' => now()->subDays(3),
                        ],
                        [
                            'title' => 'Prestasi Siswa di Kompetisi Agama Tingkat Nasional',
                            'category' => 'Prestasi',
                            'date' => now()->subDays(7),
                        ],
                        [
                            'title' => 'Kegiatan Bakti Sosial ke Panti Asuhan',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(14),
                        ],
                    ];
                @endphp
                @foreach ($defaultBlogs as $index => $blog)
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="{{ asset('assets_maudu/assets/img/blog/0' . ($index + 1) . '.jpg') }}"
                                alt="{{ $blog['title'] }}" class="img-fluid">
                            <div class="blog-date">
                                <span class="date">{{ \Carbon\Carbon::parse($blog['date'])->format('d') }}</span>
                                <span class="month">{{ \Carbon\Carbon::parse($blog['date'])->format('M Y') }}</span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span><i class="fas fa-user"></i> Admin</span>
                                <span><i class="fas fa-folder"></i> {{ $blog['category'] }}</span>
                            </div>
                            <h4 class="blog-title">
                                <a href="#">{{ $blog['title'] }}</a>
                            </h4>
                            <p class="blog-desc">Informasi terkini seputar kegiatan dan prestasi di
                                {{ theme_config('short_name') }}.</p>
                            <div class="blog-bottom">
                                <a href="#" class="read-more">Baca Selengkapnya <i
                                        class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<!-- Blog End -->

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined') return;
            $('.blog-slider').owlCarousel({
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
