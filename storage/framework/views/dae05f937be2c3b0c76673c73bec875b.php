<?php $__env->startSection('content'); ?>
    
    <?php if (isset($component)) { $__componentOriginal9993066b1b38333cdd5fd75ea58dea13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.breadcrumb','data' => ['title' => 'Kegiatan','items' => [
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Kegiatan', 'url' => route('public.kegiatan')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Kegiatan','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Kegiatan', 'url' => route('public.kegiatan')],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $attributes = $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $component = $__componentOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- KOMPASS Event -->
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div style="padding-right: 40px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Kegiatan
                            Unggulan</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 20px;">KOMPASS</h2>
                        <p style="color: #555; font-size: 15px; line-height: 1.8;">
                            <?php echo e(cache('event_kompass_description', 'Kompetisi Agama, Sains, dan Seni yang menjadi ajang unjuk kemampuan siswa dalam berbagai bidang. Event ini menampilkan kreativitas dan prestasi siswa dalam mengintegrasikan ilmu agama, sains, dan seni.')); ?>

                        </p>
                        <p style="color: #555; font-size: 15px; line-height: 1.8; margin-top: 12px;">
                            <?php echo e(cache('event_kompass_detail', 'KOMPASS merupakan program unggulan yang mengasah kemampuan siswa dalam berbagai kompetensi, mulai dari keagamaan, sains, hingga seni budaya.')); ?>

                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/01.jpg')); ?>" alt="KOMPASS"
                        style="width: 100%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- MHW Event -->
    <section style="padding: 80px 0; background: #f8faf8;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/02.jpg')); ?>" alt="MHW"
                        style="width: 100%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                </div>
                <div class="col-lg-6">
                    <div style="padding-left: 40px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Program
                            Kesehatan</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 20px;">MHW <span
                                style="color: #1a5632;">: MAUDU</span> Healthy Work</h2>
                        <p style="color: #555; font-size: 15px; line-height: 1.8;">
                            <?php echo e(cache('event_mhw_description', 'Program kesehatan dan kebugaran yang mengintegrasikan nilai-nilai keislaman dengan gaya hidup sehat. MHW membentuk karakter siswa yang sehat jasmani dan rohani.')); ?>

                        </p>
                        <p style="color: #555; font-size: 15px; line-height: 1.8; margin-top: 12px;">
                            <?php echo e(cache('event_mhw_detail', 'MAUDU Healthy Work mengajarkan pentingnya menjaga kesehatan sebagai bagian dari ibadah dan tanggung jawab sebagai muslim yang baik.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAUDUFEST Event -->
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div style="padding-right: 40px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Festival
                            Tahunan</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 20px;">MAUDUFEST</h2>
                        <p style="color: #555; font-size: 15px; line-height: 1.8;">
                            <?php echo e(cache('event_maudufest_description', 'Festival tahunan yang menampilkan berbagai prestasi dan kreativitas siswa MAUDU. Event ini menjadi puncak dari semua kegiatan pembelajaran sepanjang tahun.')); ?>

                        </p>
                        <p style="color: #555; font-size: 15px; line-height: 1.8; margin-top: 12px;">
                            <?php echo e(cache('event_maudufest_detail', 'MAUDUFEST adalah ajang apresiasi bagi semua pencapaian siswa dalam bidang akademik, seni, olahraga, dan keagamaan.')); ?>

                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/03.jpg')); ?>" alt="MAUDUFEST"
                        style="width: 100%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- MANASIK HAJI Event -->
    <section style="padding: 80px 0; background: #f8faf8;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/01.jpg')); ?>" alt="Manasik Haji"
                        style="width: 100%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                </div>
                <div class="col-lg-6">
                    <div style="padding-left: 40px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Ibadah
                            Praktik</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 20px;">MANASIK<span
                                style="color: #1a5632;"> HAJI</span></h2>
                        <p style="color: #555; font-size: 15px; line-height: 1.8;">
                            <?php echo e(cache('event_manasik_description', 'Praktik ibadah haji yang dilakukan di lingkungan sekolah untuk memberikan pengalaman langsung kepada siswa tentang tata cara pelaksanaan haji yang benar.')); ?>

                        </p>
                        <p style="color: #555; font-size: 15px; line-height: 1.8; margin-top: 12px;">
                            <?php echo e(cache('event_manasik_detail', 'Manasik Haji mengajarkan siswa tentang rukun dan sunnah haji, serta nilai-nilai spiritual yang terkandung dalam ibadah haji.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RUKYATUL HILAL Event -->
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div style="padding-right: 40px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Astronomi
                            Islam</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 20px;">RUKYATUL HILAL
                        </h2>
                        <p style="color: #555; font-size: 15px; line-height: 1.8;">
                            <?php echo e(cache('event_rukyatul_description', 'Kegiatan pengamatan hilal (bulan sabit) untuk menentukan awal bulan hijriyah. Siswa diajak untuk memahami aspek astronomi dalam penentuan kalender Islam.')); ?>

                        </p>
                        <p style="color: #555; font-size: 15px; line-height: 1.8; margin-top: 12px;">
                            <?php echo e(cache('event_rukyatul_detail', 'Rukyatul Hilal mengintegrasikan ilmu falak dengan pembelajaran agama, memberikan pemahaman yang mendalam tentang sistem kalender Islam.')); ?>

                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="<?php echo e(asset('assets_maudu/assets/img/blog/02.jpg')); ?>" alt="Rukyatul Hilal"
                        style="width: 100%; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- Instagram Feed Gallery -->
    <section style="padding: 80px 0; background: #f8faf8;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div style="text-align: center; margin-bottom: 50px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Gallery</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0;">
                            <?php echo e(__('common.gallery_latest_activities')); ?></h2>
                        <p style="color: #666; font-size: 15px;"><?php echo e(__('common.update_kegiatan_instagram')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
                            <div style="position: relative;">
                                <img src="<?php echo e($post['media_url']); ?>" class="card-img-top" alt="Kegiatan Sekolah"
                                    style="height: 250px; object-fit: cover;">
                                <div style="position: absolute; top: 12px; right: 12px;">
                                    <a href="<?php echo e($post['permalink']); ?>" target="_blank" class="btn btn-sm btn-dark"
                                        style="border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p class="card-text flex-grow-1" style="color: #555; font-size: 14px; line-height: 1.6;">
                                    <?php echo e(Str::limit($post['caption'], 150)); ?>

                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <span class="badge" style="background: #e74c3c; color: #fff;">
                                            <i class="fas fa-heart"></i> <?php echo e(number_format($post['like_count'] ?? 0)); ?>

                                        </span>
                                        <span class="badge ms-1" style="background: #1a5632; color: #fff;">
                                            <i class="fas fa-comment"></i>
                                            <?php echo e(number_format($post['comment_count'] ?? 0)); ?>

                                        </span>
                                    </div>
                                    <small style="color: #999;">
                                        <?php echo e(isset($post['timestamp']) && $post['timestamp'] instanceof \Carbon\Carbon ? $post['timestamp']->diffForHumans() : 'Recently'); ?>

                                    </small>
                                </div>
                                <div class="mt-2">
                                    <a href="<?php echo e($post['permalink']); ?>" target="_blank" class="btn btn-sm w-100"
                                        style="border: 1.5px solid #1a5632; color: #1a5632; border-radius: 8px;">
                                        <i class="fab fa-instagram me-1"></i> <?php echo e(__('common.lihat_di_instagram')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div style="text-align: center; padding: 60px 0;">
                            <i class="fab fa-instagram" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                            <h4 style="color: #999;"><?php echo e(__('common.belum_ada_kegiatan')); ?></h4>
                            <p style="color: #999;"><?php echo e(__('common.kegiatan_akan_muncul')); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                                return null;
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                data
                            };
                        })
                        .then(result => {
                            if (result && result.ok && result.data.success) {
                                // Silently update - user can refresh manually
                            }
                        })
                        .catch(error => {
                            console.error('Auto refresh error:', error);
                        });
                }, 30 * 60 * 1000);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.maudu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\instagram\activities-maudu.blade.php ENDPATH**/ ?>