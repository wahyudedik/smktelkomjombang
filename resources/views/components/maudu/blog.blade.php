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
            @php
                // Paginator (LengthAwarePaginator) exposes total(); fall back to count() for plain collections.
                $hasBlogs = method_exists($blogs, 'total') ? $blogs->total() > 0 : count($blogs) > 0;
            @endphp
            @if ($hasBlogs)
                @foreach ($blogs as $index => $blog)
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
                                        <li><a href="#"><i class="far fa-user-circle"></i> {{ $blog->user->name ?? 'Admin' }}</a></li>
                                        <li><a href="#"><i class="far fa-comments"></i> {{ $blog->category ?? 'Berita' }}</a></li>
                                        @php
                                            $views = number_format($blog->views_count ?? 0, 0, ',', '.');
                                        @endphp
                                        <li title="{{ $views }} kali dilihat" aria-label="{{ $views }} kali dilihat">
                                            <i class="fas fa-eye"></i> {{ $views }}
                                        </li>
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
        @if (method_exists($blogs, 'hasPages') && $blogs->hasPages())
            @php
                $currentPage = $blogs->currentPage();
                $lastPage = $blogs->lastPage();
                // Window of up to 5 page numbers around the current page.
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $start + 4);
                $start = max(1, $end - 4);
            @endphp
            <div class="pagination-area">
                <div aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($blogs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Previous">
                                    <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $blogs->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                </a>
                            </li>
                        @endif

                        @for ($num = $start; $num <= $end; $num++)
                            <li class="page-item {{ $num === $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $blogs->url($num) }}">{{ $num }}</a>
                            </li>
                        @endfor

                        @if ($blogs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $blogs->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Next">
                                    <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
        <!-- pagination end -->
    </div>
</div>
<!-- Blog End -->
