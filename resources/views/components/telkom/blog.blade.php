<!-- Section Gray bg Wrap start -->
<div class="gray-bg">
<!-- Blog Section Start -->
<div id="rs-blog" class="rs-blog style2 pt-94 pb-100 md-pt-64 md-pb-70">
    <div class="container">
        <div class="sec-title mb-60 text-center">
            <div class="sub-title primary">News Update</div>
            <h2 class="title mb-0">Latest News & Events</h2>
        </div>
        <div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="30"
            data-autoplay="true" data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800"
            data-dots="false" data-nav="false" data-nav-speed="false" data-center-mode="false"
            data-mobile-device="1" data-mobile-device-nav="false" data-mobile-device-dots="false"
            data-ipad-device="2" data-ipad-device-nav="false" data-ipad-device-dots="false"
            data-ipad-device2="1" data-ipad-device-nav2="false" data-ipad-device-dots2="false"
            data-md-device="3" data-md-device-nav="false" data-md-device-dots="false">
            @forelse($blogs as $blog)
                <div class="blog-item">
                    <div class="image-part">
                        @if ($blog->featured_image)
                            <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title ?? 'Blog' }}">
                        @else
                            <img src="{{ asset('assets_telkom/assets/images/blog/style2/' . (($loop->index % 3) + 1) . '.jpg') }}" alt="{{ $blog->title ?? 'Blog' }}">
                        @endif
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> {{ $blog->user->name ?? 'Admin' }}</li>
                            <li><i class="fa fa-calendar"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'N/A' }}</li>
                        </ul>
                        <h3 class="title"><a href="{{ route('berita.public.show', $blog->slug) }}">{{ $blog->title ?? 'Blog Title' }}</a></h3>
                        <div class="desc">{{ Str::limit($blog->excerpt ?: strip_tags($blog->content ?? ''), 100) }}</div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.show', $blog->slug) }}">Read More</a></li>
                        </ul>
                    </div>
                </div>
            @empty
                <div class="blog-item">
                    <div class="image-part">
                        <img src="{{ asset('assets_telkom/assets/images/blog/style2/1.jpg') }}" alt="Blog Default">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> {{ $siteSettings['site_name'] ?? 'Admin' }}</li>
                            <li><i class="fa fa-calendar"></i> {{ now()->format('M d, Y') }}</li>
                        </ul>
                        <h3 class="title"><a href="{{ route('berita.public.index') }}">Belum ada berita terbaru</a></h3>
                        <div class="desc">Berita dan artikel terbaru akan segera hadir. Silakan kunjungi kembali nanti untuk mendapatkan informasi terkini.</div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.index') }}">Lihat Berita</a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="{{ asset('assets_telkom/assets/images/blog/style2/2.jpg') }}" alt="Blog Default">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> {{ $siteSettings['site_name'] ?? 'Admin' }}</li>
                            <li><i class="fa fa-calendar"></i> {{ now()->format('M d, Y') }}</li>
                        </ul>
                        <h3 class="title"><a href="{{ route('berita.public.index') }}">Informasi Kegiatan Sekolah</a></h3>
                        <div class="desc">Pantau terus kegiatan dan acara sekolah yang berlangsung di SMK Telekomunikasi Darul Ulum.</div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.index') }}">Lihat Berita</a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="{{ asset('assets_telkom/assets/images/blog/style2/3.jpg') }}" alt="Blog Default">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> {{ $siteSettings['site_name'] ?? 'Admin' }}</li>
                            <li><i class="fa fa-calendar"></i> {{ now()->format('M d, Y') }}</li>
                        </ul>
                        <h3 class="title"><a href="{{ route('berita.public.index') }}">Prestasi & Pengumuman</a></h3>
                        <div class="desc">Ikuti perkembangan terbaru mengenai prestasi siswa dan pengumuman penting dari sekolah.</div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.index') }}">Lihat Berita</a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="{{ asset('assets_telkom/assets/images/blog/style2/2.jpg') }}" alt="Blog Default">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> {{ $siteSettings['site_name'] ?? 'Admin' }}</li>
                            <li><i class="fa fa-calendar"></i> {{ now()->format('M d, Y') }}</li>
                        </ul>
                        <h3 class="title"><a href="{{ route('berita.public.index') }}">Pengumuman & Informasi Sekolah</a></h3>
                        <div class="desc">Pantau terus pengumuman dan informasi penting dari SMK Telekomunikasi Darul Ulum Jombang.</div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.index') }}">Read More</a></li>
                        </ul>
                    </div>
                </div>
            @endforelse
        </div>
        <!-- View All Button — configurable via backend -->
        @if(!empty($siteSettings['show_view_all_news']))
        <div class="text-center mt-50">
            <a class="readon2" href="{{ route('berita.public.index') }}">
                {{ $siteSettings['view_all_news_text'] ?? 'Lihat Semua Berita' }} &nbsp;<i class="fa fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</div>
<!-- Blog Section End -->

</div>
<!-- Section bg Wrap 2 End -->
