@extends('layouts.maudu')

@section('content')

    <!-- Breadcrumb -->
    <x-maudu.breadcrumb :title="Str::limit($berita->title, 55)" :items="[
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
        ['label' => 'Detail', 'url' => '#'],
    ]" />

    <!-- Blog Single -->
    <section class="blog-area py-120">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <article class="blog-single">

                        @if ($berita->featured_image)
                            <div class="blog-featured-img mb-4">
                                <img src="{{ Storage::url($berita->featured_image) }}" alt="{{ $berita->title }}"
                                    style="width: 100%; border-radius: 12px; max-height: 450px; object-fit: cover;">
                            </div>
                        @endif

                        <!-- Meta -->
                        <div class="blog-meta mb-4" style="padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;">
                            <div class="d-flex flex-wrap gap-3">
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-user me-1" style="color: #1a5632;"></i>
                                    {{ $berita->user->name ?? 'Admin' }}
                                </span>
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-calendar me-1" style="color: #1a5632;"></i>
                                    {{ $berita->published_at?->translatedFormat('d F Y') }}
                                </span>
                                <span style="color: #666; font-size: 14px;">
                                    <i class="fas fa-tag me-1" style="color: #1a5632;"></i>
                                    Berita
                                </span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2
                            style="font-size: 1.8rem; font-weight: 700; line-height: 1.4; color: #1a1a2e; margin-bottom: 20px;">
                            {{ $berita->title }}
                        </h2>

                        <!-- Content -->
                        <div style="color: #333; font-size: 1rem; line-height: 1.8;" class="blog-content-body">
                            {!! $berita->content !!}
                        </div>

                        <!-- Share -->
                        <div class="mt-5 pt-4" style="border-top: 2px solid #f0f0f0;">
                            <h5 style="font-weight: 700; color: #1a5632; margin-bottom: 12px;">Bagikan:</h5>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="fab fa-facebook-f me-1"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($berita->title) }}"
                                    target="_blank" class="btn btn-outline-info btn-sm rounded-pill">
                                    <i class="fab fa-twitter me-1"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($berita->title . ' - ' . request()->url()) }}"
                                    target="_blank" class="btn btn-outline-success btn-sm rounded-pill">
                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="{{ route('berita.public.index') }}" class="btn btn-outline-success w-100 rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Berita
                        </a>
                    </div>

                    <!-- Related Berita -->
                    @if (isset($related) && $related->count() > 0)
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h5 style="font-weight: 700; color: #1a5632; margin-bottom: 20px;">Berita Terkait</h5>
                                @foreach ($related as $rel)
                                    <div class="d-flex mb-3 pb-3 {{ $loop->last ? 'border-0 mb-0 pb-0' : '' }}"
                                        style="border-bottom: 1px solid #f0f0f0;">
                                        @if ($rel->featured_image)
                                            <div
                                                style="width: 80px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0; margin-right: 12px;">
                                                <img src="{{ Storage::url($rel->featured_image) }}"
                                                    alt="{{ $rel->title }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('berita.public.show', $rel->slug) }}"
                                                class="text-decoration-none"
                                                style="color: #1a1a2e; font-weight: 600; font-size: 0.9rem;">
                                                {{ Str::limit($rel->title, 50) }}
                                            </a>
                                            <br>
                                            <small style="color: #999;">
                                                <i
                                                    class="fas fa-calendar me-1"></i>{{ $rel->published_at?->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
