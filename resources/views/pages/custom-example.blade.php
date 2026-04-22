<x-landing-layout pageTitle="Halaman Custom"
    metaDescription="Ini adalah contoh halaman custom yang menggunakan layout scalable"
    metaKeywords="custom, halaman, scalable, layout">
    @push('styles')
        <style>
            .custom-section {
                padding: 80px 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .custom-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                padding: 30px;
                margin-bottom: 30px;
            }
        </style>
    @endpush

    <!-- Custom Hero Section -->
    <x-landing.hero title="Halaman Custom" subtitle="Contoh Halaman yang Scalable"
        description="Halaman ini menggunakan komponen yang dapat digunakan kembali untuk header, footer, dan struktur utama." />

    <!-- Custom Content Section -->
    <section class="custom-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="section-title">Fitur Scalable Layout</h2>
                    <p class="section-description">Header, footer, dan struktur utama tidak berubah saat membuat halaman
                        baru</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="custom-card">
                        <h3><i class="fas fa-puzzle-piece"></i> Komponen Reusable</h3>
                        <p>Header, footer, dan hero section dapat digunakan kembali di semua halaman custom.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card">
                        <h3><i class="fas fa-cogs"></i> Mudah Dikustomisasi</h3>
                        <p>Setiap halaman dapat memiliki konten, style, dan script yang berbeda tanpa mengubah struktur
                            utama.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-card">
                        <h3><i class="fas fa-rocket"></i> Scalable</h3>
                        <p>Dapat menambah halaman baru tanpa mengubah header, footer, atau komponen utama lainnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Regular Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="text-center">
                        <h3>Konten Halaman Custom</h3>
                        <p>Ini adalah contoh konten yang dapat ditambahkan ke halaman custom. Header dan footer akan
                            tetap sama di semua halaman.</p>

                        <div class="mt-4">
                            <a href="/" class="theme-btn me-3">
                                <i class="fas fa-home"></i> Kembali ke Home
                            </a>
                            <a href="{{ route('pages.public.index') }}" class="theme-btn">
                                <i class="fas fa-file-alt"></i> Lihat Halaman Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Custom JavaScript untuk halaman ini
            console.log('Custom page loaded with scalable layout!');

            // Contoh custom functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Animasi custom cards
                const cards = document.querySelectorAll('.custom-card');
                cards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.2}s`;
                    card.classList.add('animate__animated', 'animate__fadeInUp');
                });
            });
        </script>
    @endpush
</x-landing-layout>
