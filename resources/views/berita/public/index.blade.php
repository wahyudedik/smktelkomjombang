@extends('layouts.telkom')

@section('content')

<!-- Breadcrumb Start -->
<x-telkom.breadcrumb
    title="Berita & Artikel"
    image="assets/images/breadcrumbs/1.jpg"
    :items="[
        ['label' => 'Home',   'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
    ]"
/>
<!-- Breadcrumb End -->

<!-- Blog Section Start -->
<div class="rs-blog style1 pt-100 pb-100 md-pt-70 md-pb-70">
    <div class="container">

        <!-- Search Bar -->
        <div class="row justify-content-center mb-50">
            <div class="col-lg-6 col-md-8">
                <form action="{{ route('berita.public.index') }}" method="GET">
                    <div style="display: flex; border: 2px solid #e0e0e0; border-radius: 5px; overflow: hidden;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berita..."
                            style="flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 15px;">
                        <button type="submit" class="readon2"
                            style="border-radius: 0; padding: 12px 25px; margin: 0;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (request('search'))
            <div class="row mb-30">
                <div class="col-12">
                    <p style="color: #666; font-size: 15px;">
                        Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                        &nbsp;—&nbsp;
                        <a href="{{ route('berita.public.index') }}" style="color: #1c3988;">Lihat semua berita</a>
                    </p>
                </div>
            </div>
        @endif

        @if (!request('search') && $featured)
            <!-- Featured Berita -->
            <div class="row mb-60">
                <div class="col-12">
                    <div class="blog-item" style="display: flex; flex-wrap: wrap; background: #f8f9fa; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
                        <div style="flex: 0 0 45%; max-width: 45%;">
                            @if ($featured->featured_image)
                                <img src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}"
                                    style="width: 100%; height: 320px; object-fit: cover; display: block;">
                            @else
                                <div style="width: 100%; height: 320px; background: linear-gradient(135deg, #1c3988 0%, #3a5fc8 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-newspaper-o" style="font-size: 70px; color: rgba(255,255,255,0.3);"></i>
                                </div>
                            @endif
                        </div>
                        <div style="flex: 1; padding: 40px; display: flex; flex-direction: column; justify-content: center; min-width: 280px;">
                            <span class="sub-title primary" style="font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 15px; display: block;">
                                ★ BERITA UNGGULAN
                            </span>
                            <h2 style="font-size: 1.6rem; font-weight: 700; line-height: 1.4; margin-bottom: 15px; color: #1a1a2e;">
                                <a href="{{ route('berita.public.show', $featured->slug) }}"
                                    style="color: inherit; text-decoration: none;">
                                    {{ $featured->title }}
                                </a>
                            </h2>
                            @if ($featured->excerpt)
                                <p style="color: #666; line-height: 1.7; margin-bottom: 20px; font-size: 15px;">
                                    {{ $featured->excerpt }}
                                </p>
                            @endif
                            <ul class="blog-meta" style="margin-bottom: 25px;">
                                <li><i class="fa fa-user-o"></i> {{ $featured->user->name ?? 'Admin' }}</li>
                                <li><i class="fa fa-calendar"></i> {{ $featured->published_at?->format('d M Y') }}</li>
                            </ul>
                            <div>
                                <a href="{{ route('berita.public.show', $featured->slug) }}" class="readon2">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Berita Grid -->
        @if ($beritas->count() > 0)
            <div class="row">
                @foreach ($beritas as $berita)
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="blog-item">
                            <div class="image-part">
                                @if ($berita->featured_image)
                                    <img src="{{ Storage::url($berita->featured_image) }}" alt="{{ $berita->title }}">
                                @else
                                    <img src="{{ asset('assets_telkom/assets/images/blog/style2/' . (($loop->index % 3) + 1) . '.jpg') }}"
                                        alt="{{ $berita->title }}">
                                @endif
                            </div>
                            <div class="blog-content new-style">
                                <ul class="blog-meta">
                                    <li><i class="fa fa-user-o"></i> {{ $berita->user->name ?? 'Admin' }}</li>
                                    <li><i class="fa fa-calendar"></i> {{ $berita->published_at?->format('d M Y') }}</li>
                                </ul>
                                <h3 class="title">
                                    <a href="{{ route('berita.public.show', $berita->slug) }}">
                                        {{ $berita->title }}
                                    </a>
                                </h3>
                                <div class="desc">
                                    {{ Str::limit($berita->excerpt ?: strip_tags($berita->content), 100) }}
                                </div>
                                <ul class="blog-bottom">
                                    <li class="btn-part">
                                        <a class="readon-arrow" href="{{ route('berita.public.show', $berita->slug) }}">
                                            Baca Selengkapnya
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($beritas->hasPages())
                <div class="row mt-40">
                    <div class="col-12">
                        <div class="pagination-area text-center">
                            <nav>
                                <ul class="pagination-part" style="list-style: none; padding: 0; display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                                    {{-- Previous --}}
                                    @if ($beritas->onFirstPage())
                                        <li style="opacity: 0.4; cursor: not-allowed;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #999;">
                                                <i class="fa fa-angle-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $beritas->previousPageUrl() }}"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; transition: all 0.3s;">
                                                <i class="fa fa-angle-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pages --}}
                                    @foreach ($beritas->getUrlRange(1, $beritas->lastPage()) as $page => $url)
                                        @if ($page == $beritas->currentPage())
                                            <li>
                                                <span class="readon2" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; padding: 0; font-size: 14px;">
                                                    {{ $page }}
                                                </span>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ $url }}"
                                                    style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; font-size: 14px; transition: all 0.3s;">
                                                    {{ $page }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($beritas->hasMorePages())
                                        <li>
                                            <a href="{{ $beritas->nextPageUrl() }}"
                                                style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #333; text-decoration: none; transition: all 0.3s;">
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li style="opacity: 0.4; cursor: not-allowed;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 2px solid #e0e0e0; border-radius: 5px; color: #999;">
                                                <i class="fa fa-angle-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <p style="color: #999; font-size: 13px; margin-top: 15px;">
                                Menampilkan {{ $beritas->firstItem() }}–{{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center" style="padding: 60px 20px;">
                <i class="fa fa-newspaper-o" style="font-size: 70px; color: #ddd; display: block; margin-bottom: 20px;"></i>
                <h4 style="color: #999; margin-bottom: 15px;">
                    @if (request('search'))
                        Tidak ada berita untuk "{{ request('search') }}"
                    @else
                        Belum ada berita yang dipublikasikan.
                    @endif
                </h4>
                @if (request('search'))
                    <a href="{{ route('berita.public.index') }}" class="readon2" style="display: inline-block; margin-top: 10px;">
                        Lihat Semua Berita
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
<!-- Blog Section End -->

@endsection
