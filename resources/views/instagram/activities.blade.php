@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">{{ __('common.event_maudu') }}</h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">{{ __('common.home') }}</a></li>
                <li class="active">{{ __('common.kegiatan') }}</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- Success Message -->
    @if (session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Event Sections -->
    <!-- KOMPASS Event -->
    <div class="campus-tour pt-120 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">
                                KOMPASS
                            </h2>
                        </div>
                        <p class="content-text">
                            {{ cache('event_kompass_description', 'Kompetisi Agama, Sains, dan Seni yang menjadi ajang unjuk kemampuan siswa dalam berbagai bidang. Event ini menampilkan kreativitas dan prestasi siswa dalam mengintegrasikan ilmu agama, sains, dan seni.') }}
                        </p>
                        <p class="content-text mt-2">
                            {{ cache('event_kompass_detail', 'KOMPASS merupakan program unggulan yang mengasah kemampuan siswa dalam berbagai kompetensi, mulai dari keagamaan, sains, hingga seni budaya.') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-img wow fadeInRight" data-wow-delay=".25s">
                        <img src="{{ asset('assets/img/campus-tour/01.jpg') }}" alt="KOMPASS">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KOMPASS end -->

    <!-- MHW Event -->
    <div class="campus-life pt-120 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="content-img wow fadeInLeft" data-wow-delay=".25s">
                        <img src="{{ asset('assets/img/campus-life/01.jpg') }}" alt="MHW">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">
                                MHW <span>: MAUDU</span> Healthy Work
                            </h2>
                        </div>
                        <p class="content-text">
                            {{ cache('event_mhw_description', 'Program kesehatan dan kebugaran yang mengintegrasikan nilai-nilai keislaman dengan gaya hidup sehat. MHW membentuk karakter siswa yang sehat jasmani dan rohani.') }}
                        </p>
                        <p class="content-text mt-2">
                            {{ cache('event_mhw_detail', 'MAUDU Healthy Work mengajarkan pentingnya menjaga kesehatan sebagai bagian dari ibadah dan tanggung jawab sebagai muslim yang baik.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MHW end -->

    <!-- MAUDUFEST Event -->
    <div class="campus-tour pt-120 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">
                                MAUDUFEST
                            </h2>
                        </div>
                        <p class="content-text">
                            {{ cache('event_maudufest_description', 'Festival tahunan yang menampilkan berbagai prestasi dan kreativitas siswa MAUDU. Event ini menjadi puncak dari semua kegiatan pembelajaran sepanjang tahun.') }}
                        </p>
                        <p class="content-text mt-2">
                            {{ cache('event_maudufest_detail', 'MAUDUFEST adalah ajang apresiasi bagi semua pencapaian siswa dalam bidang akademik, seni, olahraga, dan keagamaan.') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-img wow fadeInRight" data-wow-delay=".25s">
                        <img src="{{ asset('assets/img/campus-tour/01.jpg') }}" alt="MAUDUFEST">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAUDUFEST end -->

    <!-- MANASIK HAJI Event -->
    <div class="campus-life pt-120 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="content-img wow fadeInLeft" data-wow-delay=".25s">
                        <img src="{{ asset('assets/img/campus-life/01.jpg') }}" alt="Manasik Haji">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">
                                MANASIK<span> HAJI</span>
                            </h2>
                        </div>
                        <p class="content-text">
                            {{ cache('event_manasik_description', 'Praktik ibadah haji yang dilakukan di lingkungan sekolah untuk memberikan pengalaman langsung kepada siswa tentang tata cara pelaksanaan haji yang benar.') }}
                        </p>
                        <p class="content-text mt-2">
                            {{ cache('event_manasik_detail', 'Manasik Haji mengajarkan siswa tentang rukun dan sunnah haji, serta nilai-nilai spiritual yang terkandung dalam ibadah haji.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MANASIK HAJI end -->

    <!-- RUKYATUL HILAL Event -->
    <div class="campus-tour pt-120 pb-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="content-info wow fadeInUp" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                            <h2 class="site-title">
                                RUKYATUL HILAL
                            </h2>
                        </div>
                        <p class="content-text">
                            {{ cache('event_rukyatul_description', 'Kegiatan pengamatan hilal (bulan sabit) untuk menentukan awal bulan hijriyah. Siswa diajak untuk memahami aspek astronomi dalam penentuan kalender Islam.') }}
                        </p>
                        <p class="content-text mt-2">
                            {{ cache('event_rukyatul_detail', 'Rukyatul Hilal mengintegrasikan ilmu falak dengan pembelajaran agama, memberikan pemahaman yang mendalam tentang sistem kalender Islam.') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content-img wow fadeInRight" data-wow-delay=".25s">
                        <img src="{{ asset('assets/img/campus-tour/01.jpg') }}" alt="Rukyatul Hilal">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- RUKYATUL HILAL end -->

    <!-- Instagram Feed Gallery -->
    <div class="campus-life pt-120 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="site-heading text-center mb-5">
                        <h2 class="site-title">{{ __('common.gallery_latest_activities') }}</h2>
                        <p class="site-subtitle">{{ __('common.update_kegiatan_instagram') }}</p>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">{{ __('common.loading_instagram_data') }}</p>
            </div>

            <!-- Posts Grid -->
            <div id="postsContainer" class="row">
                @forelse ($posts as $index => $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ $post['media_url'] }}" class="card-img-top" alt="Kegiatan Sekolah"
                                    style="height: 250px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <a href="{{ $post['permalink'] }}" target="_blank" class="btn btn-sm btn-dark">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="card-text flex-grow-1">
                                    {{ Str::limit($post['caption'], 150) }}
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
                                    <a href="{{ $post['permalink'] }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fab fa-instagram me-1"></i> {{ __('common.lihat_di_instagram') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fab fa-instagram fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('common.belum_ada_kegiatan') }}</h4>
                            <p class="text-muted">{{ __('common.kegiatan_akan_muncul') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Instagram Feed Gallery end -->

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const refreshBtn = document.getElementById('refreshBtn');
                const refreshText = document.getElementById('refreshText');
                const loadingState = document.getElementById('loadingState');
                const postsContainer = document.getElementById('postsContainer');
                const lastUpdated = document.getElementById('lastUpdated');

                // Refresh button functionality
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        // Show loading state
                        refreshBtn.disabled = true;
                        refreshText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('common.refreshing') }}';

                        if (loadingState) {
                            loadingState.style.display = 'block';
                        }

                        // Fetch new data
                        fetch('/kegiatan/posts', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(async response => {
                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    throw new Error(
                                        `Unexpected response format. Status: ${response.status}`);
                                }
                                const data = await response.json();
                                return {
                                    ok: response.ok,
                                    status: response.status,
                                    data
                                };
                            })
                            .then(result => {
                                if (!result.ok) {
                                    showError('{{ __('common.error') }}', '{{ __('common.data_update_failed') }}: Status ' + result.status);
                                    return;
                                }

                                if (result.data.success) {
                                    // Update last updated time
                                    if (lastUpdated) {
                                        lastUpdated.textContent = new Date().toLocaleString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });
                                    }

                                    // Show success message
                                    showSuccess('{{ __('common.success') }}', '{{ __('common.data_update_success') }}');

                                    // Reload page to show new data
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    showError('{{ __('common.error') }}', '{{ __('common.data_update_failed') }}: ' + (result.data.message ||
                                        'Unknown error'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showError('{{ __('common.error') }}', '{{ __('common.data_update_failed') }}: ' + error.message);
                            })
                            .finally(() => {
                                // Reset button state
                                refreshBtn.disabled = false;
                                refreshText.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>{{ __('common.perbarui_data') }}';

                                if (loadingState) {
                                    loadingState.style.display = 'none';
                                }
                            });
                    });
                }

                // showNotification sekarang menggunakan SweetAlert2 helper functions yang sudah didefinisikan di app.js
                // Tidak perlu didefinisikan lagi di sini

                // Auto refresh every 30 minutes
                setInterval(() => {
                    fetch('/kegiatan/posts', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                return null; // Silent fail for auto refresh
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            if (result && result.ok && result.data.success && lastUpdated) {
                                lastUpdated.textContent = new Date().toLocaleString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Auto refresh error:', error);
                            // Silent fail for auto refresh to avoid annoying users
                        });
                }, 30 * 60 * 1000); // 30 minutes
            });
        </script>
    @endpush
@endsection
