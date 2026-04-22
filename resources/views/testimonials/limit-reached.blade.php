@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Testimonial Limit Reached</h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">Home</a></li>
                <li class="active">Limit Reached</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-info">
                        <div class="card-body text-center p-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-4x text-info"></i>
                            </div>

                            <!-- Title -->
                            <h2 class="h4 mb-3 text-dark">Submission Limit Reached</h2>

                            <!-- Message -->
                            <p class="text-muted mb-4">
                                Link testimonial ini sudah mencapai batas maksimal pengisian.
                            </p>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Terima kasih atas antusiasme Anda! Batas maksimal testimonial untuk link ini sudah tercapai.
                            </div>

                            <!-- Action -->
                            <div class="mt-4">
                                <a href="/" class="btn btn-primary">
                                    <i class="fas fa-home mr-2"></i>
                                    Kembali ke Beranda
                                </a>
                            </div>

                            <p class="text-muted small mt-4 mb-0">
                                <i class="fas fa-question-circle mr-1"></i>
                                Untuk informasi lebih lanjut, hubungi administrator sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
