@extends('layouts.maudu')

@section('content')

    <!-- Breadcrumb -->
    <x-maudu.breadcrumb title="Semua Halaman" :items="[
        ['label' => 'Home', 'url' => route('landing')],
        ['label' => 'Halaman', 'url' => route('pages.public.index')],
    ]" />

    <!-- Pages Area -->
    <section class="py-120" style="background: #f8f9fa;">
        <div class="container">
            <!-- Search & Filter -->
            <div style="margin-bottom: 40px;">
                <form action="{{ route('pages.public.index') }}" method="GET">
                    <div
                        style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8 col-md-7">
                                <label
                                    style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                    <i class="fas fa-search" style="color: #1a5632; margin-right: 4px;"></i>
                                    Cari Halaman
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Ketik judul halaman yang dicari..."
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; outline: none;"
                                    onfocus="this.style.borderColor='#1a5632'" onblur="this.style.borderColor='#e9ecef'">
                            </div>

                            @if ($categories->count() > 0)
                                <div class="col-lg-3 col-md-3">
                                    <label
                                        style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                        <i class="fas fa-filter" style="color: #1a5632; margin-right: 4px;"></i>
                                        Kategori
                                    </label>
                                    <select name="category"
                                        style="width: 100%; padding: 12px 16px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; background: #fff; transition: border-color 0.3s; outline: none; cursor: pointer;"
                                        onfocus="this.style.borderColor='#1a5632'"
                                        onblur="this.style.borderColor='#e9ecef'">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ request('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-lg-1 col-md-2">
                                <button type="submit"
                                    style="width: 100%; padding: 12px; background: linear-gradient(135deg, #1a5632, #0d3d21); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; transition: transform 0.2s, box-shadow 0.2s;"
                                    onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 12px rgba(26,86,50,0.3)'"
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Pages Grid -->
            @if ($pages->count() > 0)
                <div class="row">
                    @foreach ($pages as $page)
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%; display: flex; flex-direction: column;"
                                onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.06)'">
                                <!-- Image -->
                                <div style="position: relative; height: 200px; overflow: hidden;">
                                    @if ($page->featured_image)
                                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                            onmouseover="this.style.transform='scale(1.08)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    @else
                                        <div
                                            style="width: 100%; height: 100%; background: linear-gradient(135deg, #1a5632, #2d8a5e); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-file-alt"
                                                style="font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    @endif

                                    @if ($page->category)
                                        <span
                                            style="position: absolute; top: 12px; left: 12px; padding: 5px 14px; background: linear-gradient(135deg, #1a5632, #0d3d21); color: #fff; border-radius: 20px; font-size: 0.78rem; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            {{ $page->category }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                                    <h3
                                        style="font-size: 1.15rem; font-weight: 700; margin-bottom: 10px; line-height: 1.4;">
                                        <a href="{{ route('pages.public.show', $page->slug) }}"
                                            style="color: #333; text-decoration: none; transition: color 0.3s;"
                                            onmouseover="this.style.color='#1a5632'" onmouseout="this.style.color='#333'">
                                            {{ $page->title }}
                                        </a>
                                    </h3>

                                    @if ($page->excerpt)
                                        <p
                                            style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin-bottom: 16px; flex: 1;">
                                            {{ Str::limit($page->excerpt, 120) }}
                                        </p>
                                    @else
                                        <div style="flex: 1;"></div>
                                    @endif

                                    <!-- Meta -->
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                                        @if ($page->published_at)
                                            <span
                                                style="display: inline-flex; align-items: center; gap: 6px; color: #6c757d; font-size: 0.85rem;">
                                                <i class="far fa-calendar-alt" style="color: #1a5632;"></i>
                                                {{ $page->published_at->format('d M Y') }}
                                            </span>
                                        @endif

                                        <a href="{{ route('pages.public.show', $page->slug) }}"
                                            style="display: inline-flex; align-items: center; gap: 6px; color: #1a5632; font-weight: 600; font-size: 0.88rem; text-decoration: none; transition: gap 0.3s;"
                                            onmouseover="this.style.gap='10px'" onmouseout="this.style.gap='6px'">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 40px;">
                    <div style="display: flex; justify-content: center;">
                        {{ $pages->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div style="text-align: center; padding: 60px 20px;">
                    <div
                        style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #e8f5e9, #f1f8e9); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #1a5632; opacity: 0.6;"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #333; margin-bottom: 12px;">Tidak Ada Halaman
                    </h3>
                    <p
                        style="color: #6c757d; font-size: 1rem; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                        @if (request('search') || request('category'))
                            Tidak ada halaman yang sesuai dengan pencarian Anda.
                        @else
                            Belum ada halaman yang dipublikasikan.
                        @endif
                    </p>

                    @if (request('search') || request('category'))
                        <a href="{{ route('pages.public.index') }}"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: linear-gradient(135deg, #1a5632, #0d3d21); color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; transition: transform 0.2s, box-shadow 0.2s;"
                            onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 16px rgba(26,86,50,0.3)'"
                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                            <i class="fas fa-list"></i>
                            Lihat Semua Halaman
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

@endsection
