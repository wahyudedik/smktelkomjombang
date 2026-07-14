@extends('layouts.telkom')

@php
    $pageTitle = $page->title;
@endphp

@section('content')
    <!-- Breadcrumb -->
    <section class="page-title-area" style="background: linear-gradient(135deg, #00529C 0%, #003d73 100%); padding: 80px 0; position: relative; overflow: hidden;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-content text-center">
                        <h2 style="color: #fff; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            {{ $page->title }}
                        </h2>
                        <div class="breadcrumb-links" style="color: #ffffff; opacity: 0.95; font-size: 1rem;">
                            <a href="/" style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">Home</a>
                            <span style="margin: 0 10px; opacity: 0.7;">/</span>
                            <a href="{{ route('pages.public.index') }}" style="color: #ffffff; text-decoration: none; transition: opacity 0.3s;">Halaman</a>
                            <span style="margin: 0 10px; opacity: 0.7;">/</span>
                            <span style="opacity: 0.95; font-weight: 500;">{{ $page->title }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative shapes -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    </section>

    <!-- Page Content -->
    <section style="padding: 80px 0; background: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <!-- Back Button -->
                    <div style="margin-bottom: 2rem;">
                        <a href="{{ route('pages.public.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: #ffffff; color: #00529C; border: 2px solid #00529C; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08);"
                           onmouseover="this.style.background='#00529C'; this.style.color='#fff';"
                           onmouseout="this.style.background='#ffffff'; this.style.color='#00529C';">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Daftar Halaman
                        </a>
                    </div>

                    <!-- Article Card -->
                    <article style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                        <!-- Featured Image -->
                        @if ($page->featured_image)
                            <div style="width: 100%; max-height: 450px; overflow: hidden;">
                                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                                     style="width: 100%; height: auto; object-fit: cover; display: block;">
                            </div>
                        @endif

                        <div style="padding: 40px;">
                            <!-- Meta Info -->
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                                @if ($page->category)
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; background: linear-gradient(135deg, #00529C, #003d73); color: #fff; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        <i class="fas fa-tag"></i>
                                        {{ $page->category }}
                                    </span>
                                @endif

                                @if ($page->published_at)
                                    <span style="display: inline-flex; align-items: center; gap: 6px; color: #6c757d; font-size: 0.9rem;">
                                        <i class="far fa-calendar-alt" style="color: #00529C;"></i>
                                        {{ $page->published_at->format('d F Y') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Excerpt -->
                            @if ($page->excerpt)
                                <div style="padding: 16px 20px; background: linear-gradient(135deg, #e8f4fd 0%, #f0f7ff 100%); border-left: 4px solid #00529C; border-radius: 0 8px 8px 0; margin-bottom: 30px;">
                                    <p style="margin: 0; color: #495057; font-size: 1.05rem; font-style: italic; line-height: 1.7;">
                                        {{ $page->excerpt }}
                                    </p>
                                </div>
                            @endif

                            <!-- Page Content -->
                            <div style="color: #333; font-size: 1rem; line-height: 1.8;" class="page-content-body">
                                {!! $page->content !!}
                            </div>

                            <!-- Custom Fields -->
                            @if ($page->custom_fields && is_array(json_decode($page->custom_fields, true)))
                                @php
                                    $customFields = json_decode($page->custom_fields, true);
                                @endphp

                                @if (count($customFields) > 0)
                                    <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #f0f0f0;">
                                        <h3 style="font-size: 1.3rem; font-weight: 700; color: #00529C; margin-bottom: 20px;">
                                            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                                            Informasi Tambahan
                                        </h3>
                                        <div class="row">
                                            @foreach ($customFields as $key => $value)
                                                <div class="col-md-6 mb-3">
                                                    <div style="padding: 16px; background: #f8f9fa; border-radius: 10px; border: 1px solid #e9ecef;">
                                                        <h4 style="font-size: 0.85rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                                        </h4>
                                                        <p style="margin: 0; font-size: 1rem; color: #333; font-weight: 500;">{{ $value }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Share Buttons -->
                            <div style="margin-top: 40px; padding-top: 24px; border-top: 2px solid #f0f0f0;">
                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                                    <span style="font-weight: 600; color: #333; font-size: 0.95rem;">
                                        <i class="fas fa-share-alt" style="color: #00529C; margin-right: 4px;"></i>
                                        Bagikan:
                                    </span>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                           target="_blank"
                                           style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: #1877f2; color: #fff; border-radius: 10px; text-decoration: none; font-size: 1rem; transition: transform 0.2s;"
                                           onmouseover="this.style.transform='scale(1.1)'"
                                           onmouseout="this.style.transform='scale(1)'"
                                           title="Share on Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($page->title) }}"
                                           target="_blank"
                                           style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: #1da1f2; color: #fff; border-radius: 10px; text-decoration: none; font-size: 1rem; transition: transform 0.2s;"
                                           onmouseover="this.style.transform='scale(1.1)'"
                                           onmouseout="this.style.transform='scale(1)'"
                                           title="Share on Twitter/X">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="https://wa.me/?text={{ urlencode($page->title . ' - ' . request()->url()) }}"
                                           target="_blank"
                                           style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: #25d366; color: #fff; border-radius: 10px; text-decoration: none; font-size: 1rem; transition: transform 0.2s;"
                                           onmouseover="this.style.transform='scale(1.1)'"
                                           onmouseout="this.style.transform='scale(1)'"
                                           title="Share on WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <button onclick="copyToClipboard('{{ request()->url() }}')"
                                                style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: #6c757d; color: #fff; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem; transition: transform 0.2s;"
                                                onmouseover="this.style.transform='scale(1.1)'"
                                                onmouseout="this.style.transform='scale(1)'"
                                                title="Copy Link">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Related Pages -->
                    @php
                        $relatedPages = \App\Models\Page::where('status', 'published')
                            ->where('id', '!=', $page->id)
                            ->where('category', $page->category)
                            ->orderBy('published_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp

                    @if ($relatedPages->count() > 0)
                        <div style="margin-top: 50px;">
                            <h3 style="font-size: 1.4rem; font-weight: 700; color: #00529C; margin-bottom: 24px;">
                                <i class="fas fa-book-open" style="margin-right: 8px;"></i>
                                Halaman Terkait
                            </h3>
                            <div class="row">
                                @foreach ($relatedPages as $relatedPage)
                                    <div class="col-md-4 mb-4">
                                        <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%;"
                                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'"
                                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.06)'">
                                            @if ($relatedPage->featured_image)
                                                <div style="height: 160px; overflow: hidden;">
                                                    <img src="{{ Storage::url($relatedPage->featured_image) }}"
                                                         alt="{{ $relatedPage->title }}"
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            @else
                                                <div style="height: 160px; background: linear-gradient(135deg, #00529C 0%, #003d73 100%); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-file-alt" style="font-size: 2.5rem; color: rgba(255,255,255,0.6);"></i>
                                                </div>
                                            @endif
                                            <div style="padding: 20px;">
                                                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px;">
                                                    <a href="{{ route('pages.public.show', $relatedPage->slug) }}"
                                                       style="color: #333; text-decoration: none; transition: color 0.3s;"
                                                       onmouseover="this.style.color='#00529C'"
                                                       onmouseout="this.style.color='#333'">
                                                        {{ $relatedPage->title }}
                                                    </a>
                                                </h4>
                                                <a href="{{ route('pages.public.show', $relatedPage->slug) }}"
                                                   style="display: inline-flex; align-items: center; gap: 6px; color: #00529C; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: gap 0.3s;"
                                                   onmouseover="this.style.gap='10px'"
                                                   onmouseout="this.style.gap='6px'">
                                                    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
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

    <style>
        /* Page Content Body Styles */
        .page-content-body h1,
        .page-content-body h2,
        .page-content-body h3,
        .page-content-body h4,
        .page-content-body h5,
        .page-content-body h6 {
            color: #00529C;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .page-content-body h1 { font-size: 2rem; }
        .page-content-body h2 { font-size: 1.6rem; }
        .page-content-body h3 { font-size: 1.3rem; }

        .page-content-body p {
            margin-bottom: 1rem;
            line-height: 1.8;
            color: #444;
        }

        .page-content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1rem 0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .page-content-body ul,
        .page-content-body ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .page-content-body li {
            margin-bottom: 0.5rem;
            line-height: 1.7;
        }

        .page-content-body a {
            color: #00529C;
            text-decoration: underline;
            transition: color 0.3s;
        }

        .page-content-body a:hover {
            color: #003d73;
        }

        .page-content-body blockquote {
            border-left: 4px solid #00529C;
            padding: 16px 20px;
            margin: 1.5rem 0;
            background: #f8f9fa;
            border-radius: 0 8px 8px 0;
            font-style: italic;
            color: #555;
        }

        .page-content-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .page-content-body table th,
        .page-content-body table td {
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .page-content-body table th {
            background: #00529C;
            color: #fff;
            font-weight: 600;
        }

        .page-content-body table tr:nth-child(even) {
            background: #f8f9fa;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show toast notification
                var toast = document.createElement('div');
                toast.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>Link berhasil disalin!';
                toast.style.cssText = 'position: fixed; bottom: 30px; right: 30px; background: #00529C; color: #fff; padding: 14px 24px; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); z-index: 9999; font-size: 0.95rem; animation: slideInRight 0.3s ease;';
                document.body.appendChild(toast);
                setTimeout(function() {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s ease';
                    setTimeout(function() { toast.remove(); }, 300);
                }, 2500);
            }).catch(function() {
                // Fallback for older browsers
                var textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                var toast = document.createElement('div');
                toast.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>Link berhasil disalin!';
                toast.style.cssText = 'position: fixed; bottom: 30px; right: 30px; background: #00529C; color: #fff; padding: 14px 24px; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); z-index: 9999; font-size: 0.95rem; animation: slideInRight 0.3s ease;';
                document.body.appendChild(toast);
                setTimeout(function() {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.3s ease';
                    setTimeout(function() { toast.remove(); }, 300);
                }, 2500);
            });
        }
    </script>
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
@endpush
