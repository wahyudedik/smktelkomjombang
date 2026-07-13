@extends('layouts.maudu')

@section('content')

    <!-- Breadcrumb -->
    <x-maudu.breadcrumb title="Berita & Artikel" :items="[
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Berita', 'url' => route('berita.public.index')],
    ]" />

    <!-- Berita Area -->
    <section class="blog-area py-120">
        <div class="container">

            <!-- Search Bar -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 col-md-8">
                    <form action="{{ route('berita.public.index') }}" method="GET">
                        <div
                            style="display: flex; border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #fff;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..."
                                style="flex: 1; padding: 12px 20px; border: none; outline: none; font-size: 15px;">
                            <button type="submit" class="btn btn-primary"
                                style="border-radius: 0; padding: 12px 25px; margin: 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (request('search'))
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <p style="color: #666; font-size: 15px;">
                            Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                            &nbsp;—&nbsp;
                            <a href="{{ route('berita.public.index') }}" style="color: #1a5632;">Lihat semua berita</a>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Featured Berita -->
            @if (!request('search') && $featured)
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    @if ($featured->featured_image)
                                        <img src="{{ Storage::url($featured->featured_image) }}"
                                            alt="{{ $featured->title }}"
                                            style="width: 100%; height: 100%; object-fit: cover; min-height: 250px;">
                                    @else
                                        <div
                                            style="width: 100%; height: 100%; min-height: 250px; background: linear-gradient(135deg, #1a5632, #2d8a5e); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper"
                                                style="font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body p-4 d-flex flex-column justify-content-center"
                                        style="height: 100%;">
                                        <span class="badge bg-success mb-2" style="width: fit-content;">Unggulan</span>
                                        <h3 class="card-title" style="font-weight: 700;">
                                            <a href="{{ route('berita.public.show', $featured->slug) }}"
                                                class="text-decoration-none" style="color: #1a1a2e;">
                                                {{ $featured->title }}
                                            </a>
                                        </h3>
                                        <p class="card-text" style="color: #666;">
                                            {{ Str::limit($featured->excerpt ?? strip_tags($featured->content ?? ''), 200) }}
                                        </p>
                                        <div class="mt-auto">
                                            <small style="color: #999;">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $featured->published_at?->format('d F Y') }}
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-user me-1"></i>
                                                {{ $featured->user->name ?? 'Admin' }}
                                            </small>
                                        </div>
                                    </div>
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
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="blog-item"
                                style="background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%; display: flex; flex-direction: column;"
                                onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.06)'">
                                <div class="blog-img" style="position: relative; height: 200px; overflow: hidden;">
                                    @if ($berita->featured_image)
                                        <img src="{{ Storage::url($berita->featured_image) }}" alt="{{ $berita->title }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div
                                            style="width: 100%; height: 100%; background: linear-gradient(135deg, #1a5632, #2d8a5e); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-newspaper"
                                                style="font-size: 2rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif
                                    <div class="blog-date" style="position: absolute; top: 15px; left: 15px;">
                                        <span
                                            style="display: block; background: #1a5632; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                            {{ $berita->published_at?->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="blog-content p-4 d-flex flex-column" style="flex: 1;">
                                    <div class="blog-meta mb-2">
                                        <span style="color: #999; font-size: 0.85rem;">
                                            <i class="fas fa-user me-1"></i> {{ $berita->user->name ?? 'Admin' }}
                                        </span>
                                    </div>
                                    <h5 class="blog-title" style="font-weight: 700;">
                                        <a href="{{ route('berita.public.show', $berita->slug) }}"
                                            class="text-decoration-none" style="color: #1a1a2e;">
                                            {{ Str::limit($berita->title, 60) }}
                                        </a>
                                    </h5>
                                    <p class="blog-desc" style="color: #666; font-size: 0.95rem;">
                                        {{ Str::limit($berita->excerpt ?? strip_tags($berita->content ?? ''), 120) }}
                                    </p>
                                    <div class="mt-auto pt-3">
                                        <a href="{{ route('berita.public.show', $berita->slug) }}" class="read-more"
                                            style="color: #1a5632; font-weight: 600; text-decoration: none;">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $beritas->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h4 style="color: #666;">Belum ada berita</h4>
                        <p style="color: #999;">Berita dan artikel terbaru akan segera hadir.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
