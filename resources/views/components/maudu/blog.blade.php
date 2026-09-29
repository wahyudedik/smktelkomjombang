<!-- Blog / Berita -->
@props(['blogs' => []])

<div class="portfolio-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Kegiatan {{ theme_config('short_name', 'MAUDU') }}</span>
                    <h2 class="site-title">Berita<span> Madrasah</span> Terbaru</h2>
                    <p>Oleh Redaksi AFKAR</p>
                </div>
            </div>
        </div>
        <div class="row">
            @if (count($blogs) > 0)
                @foreach ($blogs->take(6) as $index => $blog)
                    @php
                        $delays = ['0.25s', '0.50s', '0.75s', '0.25s', '0.50s', '0.75s'];
                        $delay = $delays[$index % 6];
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay="{{ $delay }}">
                            <div class="blog-date">
                                <i class="fal fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
                            </div>
                            <div class="blog-item-img">
                                @if (!empty($blog->featured_image))
                                    <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
                                @else
                                    <img src="{{ asset('assets_maudu/assets/img/blog/0' . (($index % 3) + 1) . '.jpg') }}" alt="{{ $blog->title }}">
                                @endif
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> {{ $blog->author ?? 'Admin' }}</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> {{ $blog->category ?? 'Berita' }}</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="{{ route('berita.public.show', $blog->slug) }}">{{ Str::limit($blog->title, 60) }}</a>
                                </h4>
                                <a class="theme-btn" href="{{ route('berita.public.show', $blog->slug) }}">Read More<i class="fas fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php
                    $delays = ['0.25s', '0.50s', '0.75s', '0.25s', '0.50s', '0.75s'];
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
                        [
                            'title' => 'Ujian Kenaikan Kelas Tahun Ajaran 2024/2025',
                            'category' => 'Akademik',
                            'date' => now()->subDays(21),
                        ],
                        [
                            'title' => 'Peringatan Hari Santri Nasional',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(28),
                        ],
                        [
                            'title' => 'Kunjungan Edukasi ke Universitas',
                            'category' => 'Kegiatan',
                            'date' => now()->subDays(35),
                        ],
                    ];
                @endphp
                @foreach ($defaultBlogs as $index => $blog)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay="{{ $delays[$index] }}">
                            <div class="blog-date">
                                <i class="fal fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog['date'])->format('M d, Y') }}
                            </div>
                            <div class="blog-item-img">
                                <img src="{{ asset('assets_maudu/assets/img/blog/0' . (($index % 3) + 1) . '.jpg') }}" alt="{{ $blog['title'] }}">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="#"><i class="far fa-user-circle"></i> Redaksi AFKAR</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> {{ $blog['category'] }}</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">{{ $blog['title'] }}</a>
                                </h4>
                                <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <!-- pagination -->
        <div class="pagination-area">
            <div aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- pagination end -->
    </div>
</div>
<!-- Blog End -->
