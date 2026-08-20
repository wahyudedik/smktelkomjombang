<?php if (isset($component)) { $__componentOriginal61b7c119be9b054fc3033ecd71de14c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal61b7c119be9b054fc3033ecd71de14c0 = $attributes; } ?>
<?php $component = App\View\Components\LandingLayout::resolve(['pageTitle' => 'Halaman Custom','metaDescription' => 'Ini adalah contoh halaman custom yang menggunakan layout scalable','metaKeywords' => 'custom, halaman, scalable, layout'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('landing-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\LandingLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startPush('styles'); ?>
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
    <?php $__env->stopPush(); ?>

    <!-- Custom Hero Section -->
    <?php if (isset($component)) { $__componentOriginal03854d4fa393e4f16832e55c2dff7105 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03854d4fa393e4f16832e55c2dff7105 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.landing.hero','data' => ['title' => 'Halaman Custom','subtitle' => 'Contoh Halaman yang Scalable','description' => 'Halaman ini menggunakan komponen yang dapat digunakan kembali untuk header, footer, dan struktur utama.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('landing.hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Halaman Custom','subtitle' => 'Contoh Halaman yang Scalable','description' => 'Halaman ini menggunakan komponen yang dapat digunakan kembali untuk header, footer, dan struktur utama.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03854d4fa393e4f16832e55c2dff7105)): ?>
<?php $attributes = $__attributesOriginal03854d4fa393e4f16832e55c2dff7105; ?>
<?php unset($__attributesOriginal03854d4fa393e4f16832e55c2dff7105); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03854d4fa393e4f16832e55c2dff7105)): ?>
<?php $component = $__componentOriginal03854d4fa393e4f16832e55c2dff7105; ?>
<?php unset($__componentOriginal03854d4fa393e4f16832e55c2dff7105); ?>
<?php endif; ?>

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
                            <a href="<?php echo e(route('pages.public.index')); ?>" class="theme-btn">
                                <i class="fas fa-file-alt"></i> Lihat Halaman Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $__env->startPush('scripts'); ?>
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal61b7c119be9b054fc3033ecd71de14c0)): ?>
<?php $attributes = $__attributesOriginal61b7c119be9b054fc3033ecd71de14c0; ?>
<?php unset($__attributesOriginal61b7c119be9b054fc3033ecd71de14c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal61b7c119be9b054fc3033ecd71de14c0)): ?>
<?php $component = $__componentOriginal61b7c119be9b054fc3033ecd71de14c0; ?>
<?php unset($__componentOriginal61b7c119be9b054fc3033ecd71de14c0); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\pages\custom-example.blade.php ENDPATH**/ ?>