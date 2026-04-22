@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Testimonial Link Expired</h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">Home</a></li>
                <li class="active">Expired</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-warning">
                        <div class="card-body text-center p-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fas fa-clock fa-4x text-warning"></i>
                            </div>

                            <!-- Title -->
                            <h2 class="h4 mb-3 text-dark">Testimonial Link Expired</h2>

                            <!-- Message -->
                            <p class="text-muted mb-4">
                                Maaf, link testimonial ini sudah tidak aktif.
                            </p>

                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle mr-2"></i>
                                Link ini sudah melewati batas waktu aktif dan tidak dapat digunakan lagi.
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
                                Butuh bantuan? Hubungi administrator sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
