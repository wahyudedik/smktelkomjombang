<!-- Instagram Feed Section Start -->
<div class="rs-instagram style2 pt-94 pb-100 md-pt-64 md-pb-70">
    <div class="container">
        <div class="sec-title mb-60 text-center">
            <div class="sub-title primary"><i class="fab fa-instagram"></i> Galeri Instagram</div>
            <h2 class="title mb-0">Kegiatan <span>Terbaru</span></h2>
            <p>Update kegiatan sekolah dari media sosial kami</p>
        </div>

        <div class="row">
            @forelse($instagramPosts as $post)
                <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-30">
                    <div class="blog-item">
                        <div class="image-part position-relative">
                            <a href="{{ $post['permalink'] ?? '#' }}" target="_blank" rel="noopener">
                                <img src="{{ $post['media_url'] ?? asset('assets_telkom/assets/images/blog/style2/1.jpg') }}"
                                    alt="{{ Str::limit($post['caption'] ?? 'Kegiatan Sekolah', 50) }}"
                                    style="height: 250px; object-fit: cover; width: 100%;">
                            </a>
                            <div class="position-absolute top-0 end-0 m-2">
                                <a href="{{ $post['permalink'] ?? '#' }}" target="_blank" rel="noopener"
                                    class="btn btn-sm btn-dark" style="border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                            @if (($post['media_type'] ?? '') === 'VIDEO')
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="btn btn-sm btn-light" style="border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; opacity: 0.85;">
                                        <i class="fas fa-play"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="blog-content new-style">
                            <ul class="blog-meta">
                                <li>
                                    <span class="badge bg-danger me-1" style="font-size: 11px;">
                                        <i class="fas fa-heart"></i> {{ number_format($post['like_count'] ?? 0) }}
                                    </span>
                                    <span class="badge bg-primary" style="font-size: 11px;">
                                        <i class="fas fa-comment"></i> {{ number_format($post['comment_count'] ?? 0) }}
                                    </span>
                                </li>
                                <li>
                                    <i class="fa fa-calendar"></i>
                                    {{ isset($post['timestamp']) && $post['timestamp'] instanceof \Carbon\Carbon ? $post['timestamp']->diffForHumans() : 'Baru saja' }}
                                </li>
                            </ul>
                            <div class="desc">{{ Str::limit($post['caption'] ?? 'Kegiatan Sekolah', 120) }}</div>
                            <ul class="blog-bottom">
                                <li class="btn-part">
                                    <a class="readon-arrow" href="{{ $post['permalink'] ?? '#' }}" target="_blank" rel="noopener">
                                        Lihat di Instagram <i class="fab fa-instagram ms-1"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fab fa-instagram fa-4x" style="color: #E1306C; opacity: 0.3;"></i>
                        <h4 class="mt-3" style="color: #999;">Belum ada kegiatan</h4>
                        <p style="color: #aaa;">Kegiatan sekolah akan muncul di sini setelah terhubung dengan Instagram</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- View More Button -->
        @if (count($instagramPosts) > 0)
            <div class="text-center mt-50">
                <a class="readon2" href="{{ route('public.kegiatan') }}">
                    Lihat Semua Kegiatan <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</div>
<!-- Instagram Feed Section End -->
