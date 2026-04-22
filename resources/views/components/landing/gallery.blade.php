<!-- Instagram Gallery -->
<div class="py-120" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-book-open-reader"></i> Kegiatan MAUDU</span>
                    <h2 class="site-title">Galeri Kegiatan <span>Terbaru</span></h2>
                    <p>Update kegiatan sekolah dari Instagram</p>
                </div>
            </div>
        </div>

        <!-- Posts Grid -->
        <div class="row">
            @forelse ($posts ?? [] as $post)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $post['media_url'] ?? asset('assets/img/portfolio/01.jpg') }}"
                                class="card-img-top" alt="Kegiatan Sekolah" style="height: 250px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <a href="{{ $post['permalink'] ?? '#' }}" target="_blank" class="btn btn-sm btn-dark">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text flex-grow-1">
                                {{ Str::limit($post['caption'] ?? 'Kegiatan Sekolah', 150) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-heart"></i> {{ number_format($post['like_count'] ?? 0) }}
                                    </span>
                                    <span class="badge bg-primary ms-1">
                                        <i class="fas fa-comment"></i>
                                        {{ number_format($post['comment_count'] ?? 0) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ isset($post['timestamp']) && $post['timestamp'] instanceof \Carbon\Carbon ? $post['timestamp']->diffForHumans() : 'Recently' }}
                                </small>
                            </div>
                            <div class="mt-2">
                                <a href="{{ $post['permalink'] ?? '#' }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fab fa-instagram me-1"></i> Lihat di Instagram
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fab fa-instagram fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada kegiatan</h4>
                        <p class="text-muted">Kegiatan sekolah akan muncul di sini setelah terhubung dengan Instagram
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- View More Button -->
        @if (!empty($posts) && count($posts) > 0)
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('public.kegiatan') }}" class="theme-btn">
                        Lihat Semua Kegiatan <i class="far fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Instagram Gallery end -->
