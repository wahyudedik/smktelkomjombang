@extends('layouts.landing')

@section('content')
    <!-- page header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="page-header-content text-center">
                        <h1 class="page-title">{{ $page->title }}</h1>
                        <p class="page-desc">{{ $page->excerpt ?: 'Informasi lengkap dari halaman ini' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page header end -->

    <!-- page content -->
    <section class="page-content pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Back Button -->
                    <div class="page-back mb-4">
                        <a href="{{ route('pages.public.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Halaman
                        </a>
                    </div>

                    <!-- Page Article -->
                    <article class="page-article">
                        @if ($page->featured_image)
                            <div class="page-featured-img mb-4">
                                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                                    class="img-fluid rounded">
                            </div>
                        @endif

                        <!-- Page Meta -->
                        <div class="page-meta mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="page-meta-info">
                                        @if ($page->category)
                                            <span class="page-category">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $page->category }}
                                            </span>
                                        @endif

                                        @if ($page->published_at)
                                            <span class="page-date">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                {{ $page->published_at->format('d F Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="page-share text-md-end">
                                        <span class="share-label">Bagikan:</span>
                                        <div class="share-buttons">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                                target="_blank" class="share-btn facebook">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($page->title) }}"
                                                target="_blank" class="share-btn twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                            <a href="https://wa.me/?text={{ urlencode($page->title . ' - ' . request()->url()) }}"
                                                target="_blank" class="share-btn whatsapp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            <button onclick="copyToClipboard('{{ request()->url() }}')"
                                                class="share-btn copy">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Page Content -->
                        <div class="page-content-body">
                            {!! $page->content !!}
                        </div>

                        <!-- Custom Fields -->
                        @if ($page->custom_fields && is_array(json_decode($page->custom_fields, true)))
                            @php
                                $customFields = json_decode($page->custom_fields, true);
                            @endphp

                            @if (count($customFields) > 0)
                                <div class="page-custom-fields mt-5">
                                    <h3 class="custom-fields-title">Informasi Tambahan</h3>
                                    <div class="row">
                                        @foreach ($customFields as $key => $value)
                                            <div class="col-md-6 mb-3">
                                                <div class="custom-field-item">
                                                    <h4 class="custom-field-label">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </h4>
                                                    <p class="custom-field-value">{{ $value }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
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
                        <div class="related-pages mt-5">
                            <h3 class="related-pages-title">Halaman Terkait</h3>
                            <div class="row">
                                @foreach ($relatedPages as $relatedPage)
                                    <div class="col-md-4 mb-4">
                                        <div class="related-page-item">
                                            <div class="related-page-img">
                                                @if ($relatedPage->featured_image)
                                                    <img src="{{ Storage::url($relatedPage->featured_image) }}"
                                                        alt="{{ $relatedPage->title }}" class="img-fluid">
                                                @else
                                                    <div class="related-page-placeholder">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="related-page-content">
                                                <h4 class="related-page-title">
                                                    <a href="{{ route('pages.public.show', $relatedPage->slug) }}">
                                                        {{ $relatedPage->title }}
                                                    </a>
                                                </h4>

                                                <a href="{{ route('pages.public.show', $relatedPage->slug) }}"
                                                    class="related-page-link">
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
    <!-- page content end -->

    <style>
        /* Page Content Styles */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
        }

        .page-header-content {
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .page-desc {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .page-back {
            margin-bottom: 2rem;
        }

        .page-article {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .page-featured-img img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .page-meta {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 2rem;
        }

        .page-meta-info {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-category {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .page-date {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .share-label {
            font-weight: 500;
            color: #666;
            margin-right: 10px;
        }

        .share-buttons {
            display: inline-flex;
            gap: 8px;
        }

        .share-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .share-btn.facebook {
            background: #3b5998;
            color: white;
        }

        .share-btn.twitter {
            background: #1da1f2;
            color: white;
        }

        .share-btn.whatsapp {
            background: #25d366;
            color: white;
        }

        .share-btn.copy {
            background: #6c757d;
            color: white;
        }

        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .page-content-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }

        .page-content-body h1,
        .page-content-body h2,
        .page-content-body h3,
        .page-content-body h4,
        .page-content-body h5,
        .page-content-body h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .page-content-body p {
            margin-bottom: 1.5rem;
        }

        .page-content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .page-custom-fields {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .custom-fields-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .custom-field-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .custom-field-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .custom-field-value {
            font-size: 1rem;
            color: #333;
            margin: 0;
        }

        .related-pages {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .related-pages-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .related-page-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .related-page-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .related-page-img {
            height: 150px;
            overflow: hidden;
        }

        .related-page-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-page-item:hover .related-page-img img {
            transform: scale(1.05);
        }

        .related-page-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            opacity: 0.7;
        }

        .related-page-content {
            padding: 1.5rem;
        }

        .related-page-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .related-page-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .related-page-title a:hover {
            color: #667eea;
        }

        .related-page-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .related-page-link:hover {
            color: #5a6fd8;
            text-decoration: none;
        }

        .related-page-link i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .related-page-link:hover i {
            transform: translateX(3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-article {
                padding: 1.5rem;
            }

            .page-meta-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .page-share {
                text-align: left !important;
                margin-top: 1rem;
            }

            .share-buttons {
                justify-content: flex-start;
            }
        }
    </style>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const btn = event.target.closest('.share-btn');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = '#28a745';

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.background = '#6c757d';
                }, 2000);
            }, function(err) {
                console.error('Failed to copy: ', err);
                showError('Gagal menyalin link');
            });
        }
    </script>
@endsection
