@extends('layouts.landing')

@section('content')
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
        <div class="container">
            <h2 class="breadcrumb-title">Panduan Setup Instagram API</h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">Beranda</a></li>
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="active">Setup Instagram</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- Documentation Content -->
    <div class="py-5 bg-light">
        <div class="container">
            <!-- Header -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="mb-4">
                        <i class="fab fa-instagram fa-4x"
                            style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <p class="lead text-muted">Panduan langkah demi langkah untuk mengintegrasikan Instagram dengan website
                        sekolah Anda</p>
                </div>
            </div>

            <!-- Overview -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Gambaran Umum
                    </h2>
                    <p class="card-text">
                        Panduan ini akan membantu Anda mengatur <strong>Instagram Platform API with Instagram Login</strong>
                        untuk website sekolah Anda.
                        Integrasi ini akan secara otomatis mengambil dan menampilkan postingan Instagram sekolah Anda di
                        website.
                    </p>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Yang akan Anda dapatkan:</strong> Tampilan feed Instagram otomatis, insights & analytics,
                        dan manajemen komentar.
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>API Version:</strong> Menggunakan Instagram Platform API (bukan Instagram Basic Display API
                        yang sudah deprecated).
                        <a href="https://developers.facebook.com/docs/instagram-platform/" target="_blank"
                            class="text-primary">
                            Dokumentasi Resmi <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Prerequisites -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">
                        <i class="fas fa-list-check text-success mr-2"></i>
                        Persyaratan
                    </h2>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> <strong>Instagram Professional
                                Account</strong>
                            (Business atau Creator)</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Akses ke <strong>Meta for
                                Developers</strong>
                            (developers.facebook.com)</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Akses <strong>Superadmin</strong>
                            ke website sekolah Anda</li>
                    </ul>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Keuntungan Instagram Login:</strong> Tidak perlu Facebook Page! Setup lebih sederhana dengan
                        login langsung menggunakan akun Instagram sekolah.
                    </div>
                </div>
            </div>

            <!-- Setup Steps -->
            @for ($i = 1; $i <= 7; $i++)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">
                            <span class="badge bg-primary rounded-circle"
                                style="width: 2rem; height: 2rem; display: inline-flex; align-items: center; justify-content: center;">{{ $i }}</span>
                            @if ($i == 1)
                                Buat Meta Business App
                            @elseif($i == 2)
                                Tambahkan Instagram Product
                            @elseif($i == 3)
                                Konfigurasi Business Login for Instagram
                            @elseif($i == 4)
                                Generate Access Token
                            @elseif($i == 5)
                                Dapatkan Instagram Professional Account ID
                            @elseif($i == 6)
                                Konfigurasi Pengaturan Website
                            @elseif($i == 7)
                                Test Koneksi & Lihat Feed
                            @endif
                        </h2>

                        @if ($i == 1)
                            <ol class="ps-3">
                                <li>Buka aplikasi <strong>Instagram</strong> di smartphone Anda</li>
                                <li>Tap <strong>Profile</strong> → <strong>☰ Menu</strong> → <strong>Settings</strong></li>
                                <li>Pilih <strong>Account</strong> → <strong>Linked accounts</strong></li>
                                <li>Tap <strong>Facebook</strong></li>
                                <li>Login dengan Facebook account yang memiliki Page sekolah</li>
                                <li>Pilih <strong>Facebook Page sekolah</strong> untuk di-link</li>
                                <li>Klik <strong>Done</strong> atau <strong>Allow</strong></li>
                            </ol>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Berhasil!</strong> Instagram sekarang sudah linked ke Facebook Page.
                                Anda bisa verifikasi dengan membuka Facebook Page → Settings → Instagram.
                            </div>
                        @elseif($i == 2)
                            <ol class="ps-3">
                                <li>Buka Facebook Developers: <a href="https://developers.facebook.com" target="_blank"
                                        class="text-primary">https://developers.facebook.com <i
                                            class="fas fa-external-link-alt text-xs"></i></a></li>
                                <li>Klik <strong>"My Apps"</strong> → <strong>"Create App"</strong></li>
                                <li>Pilih <strong>"Business"</strong> sebagai tipe aplikasi (bukan Consumer)</li>
                                <li>Isi <strong>App Name</strong>: "Website [Nama Sekolah]"</li>
                                <li>Pilih <strong>App Purpose</strong>: "Yourself or your own business"</li>
                                <li>Pilih <strong>Business Portfolio</strong> (atau buat baru jika belum ada)</li>
                                <li>Klik <strong>"Create App"</strong></li>
                            </ol>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Catatan:</strong> Gunakan tipe "Business" bukan "Consumer" untuk Instagram Platform
                                API.
                            </div>
                        @elseif($i == 3)
                            <ol class="ps-3">
                                <li>Di dashboard aplikasi Anda, cari menu <strong>"Add products"</strong> di sidebar kiri
                                </li>
                                <li>Cari <strong>"Instagram"</strong> (dengan icon Instagram)</li>
                                <li>Klik tombol <strong>"Set Up"</strong> pada Instagram product</li>
                                <li>Anda akan melihat Instagram ditambahkan ke menu sidebar</li>
                            </ol>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Berhasil!</strong> Instagram Platform API sekarang aktif untuk aplikasi Anda.
                            </div>
                        @elseif($i == 4)
                            <ol class="ps-3">
                                <li>Di dashboard aplikasi, klik <strong>"Instagram"</strong> di sidebar</li>
                                <li>Klik <strong>"API Setup"</strong></li>
                                <li>Pilih <strong>Facebook Page</strong> yang sudah linked ke Instagram</li>
                                <li>Klik <strong>"Connect Instagram Account"</strong></li>
                                <li>Login dengan Instagram account sekolah jika diminta</li>
                                <li>Izinkan akses yang diminta</li>
                                <li>Instagram account sekarang connected ke app</li>
                            </ol>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Tips:</strong> Pastikan Anda login sebagai admin dari Facebook Page yang sudah
                                linked ke Instagram.
                            </div>
                        @elseif($i == 5)
                            <ol class="ps-3">
                                <li>Di dashboard aplikasi, buka <strong>"Tools"</strong> → <strong>"Graph API
                                        Explorer"</strong></li>
                                <li>Pilih aplikasi Anda di dropdown <strong>"Meta App"</strong></li>
                                <li>Pilih Facebook Page sekolah di <strong>"User or Page"</strong></li>
                                <li>Klik <strong>"Generate Access Token"</strong></li>
                                <li>Pilih permissions: <code>instagram_basic</code>, <code>instagram_content_publish</code>,
                                    <code>pages_read_engagement</code>
                                </li>
                                <li>Klik <strong>"Generate Token"</strong></li>
                                <li><strong>Salin Access Token</strong> yang dihasilkan (simpan di tempat aman!)</li>
                                <li>Untuk mendapatkan <strong>Instagram Business Account ID</strong>, gunakan query:
                                    <code class="bg-dark text-light px-2 py-1 rounded d-block mt-2">GET
                                        /{page-id}?fields=instagram_business_account</code>
                                </li>
                                <li>Salin <strong>Instagram Business Account ID</strong> dari response</li>
                            </ol>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Penting:</strong> Access Token dari Graph API Explorer adalah short-lived token (1
                                jam).
                                Untuk production, Anda perlu menggunakan <strong>long-lived Page Access Token</strong> (60
                                hari, dapat di-refresh).
                            </div>
                        @elseif($i == 6)
                            <ol class="ps-3">
                                <li>Buka dashboard superadmin website sekolah Anda</li>
                                <li>Navigasi ke "Instagram Settings"</li>
                                <li>Masukkan Access Token dan User ID</li>
                                <li>Klik "Test Connection" untuk memverifikasi pengaturan Anda</li>
                                <li>Klik "Save Settings" untuk mengaktifkan integrasi</li>
                            </ol>
                        @elseif($i == 7)
                            <ol class="ps-3">
                                <li>Setelah menyimpan pengaturan, buka halaman feed Instagram website Anda</li>
                                <li>Kunjungi: <code class="bg-dark text-light px-2 py-1 rounded">/kegiatan</code></li>
                                <li>Anda akan melihat postingan Instagram ditampilkan secara otomatis</li>
                                <li>Feed akan diperbarui secara otomatis berdasarkan pengaturan sync Anda</li>
                            </ol>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Berhasil!</strong> Integrasi Instagram Anda sekarang aktif dan menampilkan postingan
                                di website Anda.
                            </div>
                        @endif
                    </div>
                </div>
            @endfor

            <!-- Troubleshooting -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-3">
                        <i class="fas fa-tools text-warning mr-2"></i>
                        Pemecahan Masalah
                    </h2>
                    <div class="accordion" id="troubleshootingAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1">
                                    Koneksi Gagal
                                </button>
                            </h3>
                            <div id="collapse1" class="accordion-collapse collapse show"
                                data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li>Verifikasi Access Token Anda benar dan belum kadaluarsa</li>
                                        <li>Periksa bahwa User ID Anda benar</li>
                                        <li>Pastikan akun Instagram Anda adalah akun Business</li>
                                        <li>Pastikan aplikasi Anda disetujui untuk Instagram Basic Display</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2">
                                    Tidak Ada Postingan yang Muncul
                                </button>
                            </h3>
                            <div id="collapse2" class="accordion-collapse collapse"
                                data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li>Periksa apakah akun Instagram Anda memiliki postingan</li>
                                        <li>Verifikasi bahwa postingan bersifat publik</li>
                                        <li>Coba sinkronisasi data secara manual</li>
                                        <li>Periksa pengaturan frekuensi sinkronisasi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3">
                                    Token Kadaluarsa
                                </button>
                            </h3>
                            <div id="collapse3" class="accordion-collapse collapse"
                                data-bs-parent="#troubleshootingAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li>Token Instagram kadaluarsa setelah 60 hari</li>
                                        <li>Generate token baru dari Facebook Developer Console</li>
                                        <li>Perbarui token di pengaturan website Anda</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Links -->
            <div class="card shadow-sm mb-4 bg-primary text-white">
                <div class="card-body">
                    <h2 class="h4 mb-3">
                        <i class="fas fa-life-ring mr-2"></i>
                        Butuh Bantuan?
                    </h2>
                    <p class="mb-4">
                        Jika Anda masih mengalami kesulitan dalam setup integrasi Instagram, berikut beberapa sumber daya:
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="https://developers.facebook.com/docs/instagram-basic-display-api" target="_blank"
                            class="btn btn-light">
                            <i class="fas fa-book mr-2"></i>
                            Dokumentasi Instagram API
                        </a>
                        <a href="{{ route('admin.superadmin.instagram-settings') }}" class="btn btn-success">
                            <i class="fas fa-cog mr-2"></i>
                            Halaman Pengaturan
                        </a>
                        <a href="{{ route('public.kegiatan') }}" class="btn btn-warning">
                            <i class="fas fa-images mr-2"></i>
                            Lihat Feed
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
