@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Halaman</h2>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('landing') }}">Home</a></li>
                <li class="active">Halaman</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- pages area -->
    <section class="pages-area pt-120 pb-120">
        <div class="container">
            <!-- Search and Filter -->
            <div class="row mb-5">
                <div class="col-xl-12">
                    <div class="pages-search-wrapper">
                        <form action="{{ route('pages.public.index') }}" method="GET" class="pages-search-form">
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari halaman..." class="form-control">
                                    </div>
                                </div>

                                @if ($categories->count() > 0)
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="category" class="form-control">
                                                <option value="">Semua Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category }}"
                                                        {{ request('category') == $category ? 'selected' : '' }}>
                                                        {{ $category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pages Grid -->
            @if ($pages->count() > 0)
                <div class="row">
                    @foreach ($pages as $page)
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div class="pages-item">
                                <div class="pages-img">
                                    @if ($page->featured_image)
                                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}">
                                    @else
                                        <div class="pages-placeholder">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    @endif

                                    @if ($page->category)
                                        <div class="pages-category">
                                            <span>{{ $page->category }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="pages-content">
                                    <h3 class="pages-title">
                                        <a href="{{ route('pages.public.show', $page->slug) }}">{{ $page->title }}</a>
                                    </h3>

                                    @if ($page->excerpt)
                                        <p class="pages-desc">{{ Str::limit($page->excerpt, 120) }}</p>
                                    @endif

                                    <div class="pages-meta">
                                        @if ($page->published_at)
                                            <span class="pages-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $page->published_at->format('d F Y') }}
                                            </span>
                                        @endif

                                        <a href="{{ route('pages.public.show', $page->slug) }}" class="pages-read-more">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="pagination-wrapper text-center mt-5" x-data="{ loading: false }">
                            <!-- Loading Overlay -->
                            <div x-show="loading" class="pagination-loading" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                            <nav aria-label="Page navigation">
                                <div @click="loading = true" class="pagination-nav">
                                    {{ $pages->links('pagination::bootstrap-5') }}
                                </div>
                            </nav>

                            <!-- Pagination Info with Stats -->
                            <div class="pagination-info-enhanced">
                                <div class="pagination-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-file-alt"></i>
                                        <strong>{{ $pages->total() }}</strong> Total
                                    </span>
                                    <span class="stat-divider">•</span>
                                    <span class="stat-item">
                                        <i class="fas fa-eye"></i>
                                        <strong>{{ $pages->firstItem() ?? 0 }}</strong> -
                                        <strong>{{ $pages->lastItem() ?? 0 }}</strong>
                                    </span>
                                    <span class="stat-divider">•</span>
                                    <span class="stat-item">
                                        <i class="fas fa-book-open"></i>
                                        Halaman <strong>{{ $pages->currentPage() }}</strong> dari
                                        <strong>{{ $pages->lastPage() }}</strong>
                                    </span>
                                </div>

                                @if ($pages->hasPages() && $pages->lastPage() > 5)
                                    <!-- Quick Jump -->
                                    <div class="quick-jump mt-3">
                                        <form method="GET" action="{{ route('pages.public.index') }}"
                                            class="d-inline-flex align-items-center gap-2">
                                            @foreach (request()->except('page') as $key => $value)
                                                <input type="hidden" name="{{ $key }}"
                                                    value="{{ $value }}">
                                            @endforeach
                                            <label class="mb-0 small text-muted">Lompat ke halaman:</label>
                                            <input type="number" name="page" min="1"
                                                max="{{ $pages->lastPage() }}" placeholder="#"
                                                class="form-control form-control-sm quick-jump-input" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary quick-jump-btn">
                                                <i class="fas fa-arrow-right"></i> Go
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <!-- Keyboard Navigation Hint (only show on desktop) -->
                            <div class="keyboard-hint d-none d-md-block">
                                <small class="text-muted">
                                    <i class="fas fa-keyboard"></i>
                                    Gunakan <kbd>←</kbd> dan <kbd>→</kbd> untuk navigasi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="pages-empty text-center">
                            <div class="pages-empty-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h3 class="pages-empty-title">Tidak Ada Halaman</h3>
                            <p class="pages-empty-desc">
                                @if (request('search') || request('category'))
                                    Tidak ada halaman yang sesuai dengan pencarian Anda.
                                @else
                                    Belum ada halaman yang dipublikasikan.
                                @endif
                            </p>

                            @if (request('search') || request('category'))
                                <a href="{{ route('pages.public.index') }}" class="btn btn-primary">
                                    Lihat Semua Halaman
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- pages area end -->

    <style>
        /* Pages Styles */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
        }

        .page-header-content {
            text-align: center;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .page-desc {
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 0;
        }

        .pages-search-wrapper {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .pages-search-form .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .pages-search-form .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .pages-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .pages-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .pages-img {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .pages-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .pages-item:hover .pages-img img {
            transform: scale(1.05);
        }

        .pages-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            opacity: 0.7;
        }

        .pages-category {
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .pages-category span {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .pages-content {
            padding: 1.5rem;
        }

        .pages-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .pages-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .pages-title a:hover {
            color: #667eea;
        }

        .pages-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .pages-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pages-date {
            color: #999;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pages-read-more {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .pages-read-more:hover {
            color: #5a6fd8;
            text-decoration: none;
        }

        .pages-read-more i {
            margin-left: 5px;
            transition: transform 0.3s ease;
        }

        .pages-read-more:hover i {
            transform: translateX(3px);
        }

        .pages-empty {
            padding: 4rem 2rem;
        }

        .pages-empty-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        .pages-empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 1rem;
        }

        .pages-empty-desc {
            color: #999;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .pagination-wrapper {
            margin-top: 3rem;
            position: relative;
        }

        /* Loading Overlay */
        .pagination-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }

        .pagination-loading .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        .pagination-nav {
            transition: opacity 0.2s ease;
        }

        .pagination-wrapper .pagination {
            justify-content: center;
            gap: 8px;
            margin-bottom: 0;
        }

        .pagination-wrapper .page-item {
            margin: 0 3px;
        }

        .pagination-wrapper .page-link {
            color: #667eea;
            border: 2px solid #e9ecef;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            min-width: 48px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .pagination-wrapper .page-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .pagination-wrapper .page-link:hover {
            border-color: #667eea;
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .pagination-wrapper .page-link:hover::before {
            left: 0;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: scale(1.15);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
            animation: activePagePulse 2s infinite;
        }

        @keyframes activePagePulse {

            0%,
            100% {
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
            }

            50% {
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.8);
            }
        }

        .pagination-wrapper .page-link:active {
            transform: translateY(-1px) scale(0.98);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #ccc;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Arrow buttons styling */
        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            padding: 12px 20px;
            font-weight: 700;
            font-size: 18px;
        }

        /* Enhanced Pagination Info */
        .pagination-info-enhanced {
            margin-top: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border-radius: 12px;
            border: 2px solid #e9ecef;
        }

        .pagination-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .stat-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
            padding: 8px 15px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .stat-item i {
            color: #667eea;
            font-size: 16px;
        }

        .stat-item strong {
            color: #667eea;
            font-weight: 700;
        }

        .stat-divider {
            color: #ddd;
            font-size: 18px;
        }

        /* Quick Jump */
        .quick-jump {
            animation: fadeInUp 0.5s ease;
        }

        .quick-jump-input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .quick-jump-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .quick-jump-btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 5px 15px;
            transition: all 0.3s ease;
        }

        .quick-jump-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateX(3px);
        }

        /* Keyboard Hint */
        .keyboard-hint {
            margin-top: 15px;
            padding: 10px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 8px;
            animation: pulse 2s infinite;
        }

        .keyboard-hint kbd {
            background: white;
            border: 2px solid #667eea;
            border-radius: 5px;
            padding: 3px 8px;
            font-weight: 600;
            color: #667eea;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 0 3px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .pages-search-wrapper {
                padding: 1.5rem;
            }

            .pages-content {
                padding: 1rem;
            }

            .pages-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            /* Responsive Pagination */
            .pagination-wrapper .page-link {
                padding: 10px 14px;
                font-size: 14px;
                min-width: 40px;
            }

            .pagination-wrapper .pagination {
                gap: 4px;
            }

            .pagination-wrapper .page-item {
                margin: 0 2px;
            }

            /* Hide some page numbers on mobile */
            .pagination-wrapper .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }

            .pagination-info-enhanced {
                padding: 15px;
            }

            .pagination-stats {
                gap: 8px;
            }

            .stat-item {
                font-size: 12px;
                padding: 6px 12px;
            }

            .stat-item i {
                font-size: 14px;
            }

            .stat-divider {
                display: none;
            }

            .quick-jump {
                margin-top: 15px;
            }

            .keyboard-hint {
                display: none !important;
            }
        }

        @media (max-width: 576px) {
            .pagination-wrapper .page-link {
                padding: 8px 12px;
                font-size: 13px;
                min-width: 36px;
            }

            .pagination-wrapper .page-item:first-child .page-link,
            .pagination-wrapper .page-item:last-child .page-link {
                font-size: 16px;
            }
        }

        /* Tooltip for buttons */
        .page-link[title] {
            position: relative;
        }

        .page-link[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            margin-bottom: 8px;
            z-index: 100;
            animation: tooltipFadeIn 0.3s ease;
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>

    <script>
        // Keyboard Navigation for Pagination
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(e) {
                // Only activate if not typing in input
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    return;
                }

                const prevLink = document.querySelector('.pagination .page-item:first-child .page-link');
                const nextLink = document.querySelector('.pagination .page-item:last-child .page-link');

                // Left arrow - Previous page
                if (e.key === 'ArrowLeft' && prevLink && !prevLink.parentElement.classList.contains(
                        'disabled')) {
                    e.preventDefault();
                    prevLink.click();
                }

                // Right arrow - Next page
                if (e.key === 'ArrowRight' && nextLink && !nextLink.parentElement.classList.contains(
                        'disabled')) {
                    e.preventDefault();
                    nextLink.click();
                }
            });

            // Smooth scroll to top on pagination click
            document.querySelectorAll('.pagination .page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endsection
