<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Detail Data Kelulusan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <a href="<?php echo e(route('admin.lulus.index')); ?>"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali ke Daftar
                    </a>
                </div>
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('admin.lulus.edit', $kelulusan)); ?>"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit Data
                    </a>
                    <?php if($kelulusan->status === 'lulus'): ?>
                        <a href="<?php echo e(route('admin.lulus.certificate', $kelulusan)); ?>" target="_blank"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Generate Sertifikat
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Student Photo and Basic Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <?php if($kelulusan->foto): ?>
                                <img src="<?php echo e($kelulusan->photo_url); ?>" alt="<?php echo e($kelulusan->nama); ?>"
                                    class="h-48 w-48 rounded-full object-cover mx-auto mb-4">
                            <?php else: ?>
                                <div
                                    class="h-48 w-48 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
                                    <span
                                        class="text-gray-600 text-6xl font-medium"><?php echo e(substr($kelulusan->nama, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($kelulusan->nama); ?></h3>

                            <div class="mb-4">
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    <?php if($kelulusan->status_badge_color == 'green'): ?> bg-green-100 text-green-800
                                    <?php elseif($kelulusan->status_badge_color == 'red'): ?> bg-red-100 text-red-800
                                    <?php elseif($kelulusan->status_badge_color == 'yellow'): ?> bg-yellow-100 text-yellow-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e($kelulusan->status_display); ?>

                                </span>
                            </div>

                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>NISN:</strong> <?php echo e($kelulusan->nisn); ?></p>
                                <?php if($kelulusan->nis): ?>
                                    <p><strong>NIS:</strong> <?php echo e($kelulusan->nis); ?></p>
                                <?php endif; ?>
                                <p><strong>Jurusan:</strong> <?php echo e($kelulusan->major_display); ?></p>
                                <p><strong>Tahun Ajaran:</strong> <?php echo e($kelulusan->graduation_year_display); ?></p>
                                <?php if($kelulusan->tanggal_lulus): ?>
                                    <p><strong>Tanggal Lulus:</strong> <?php echo e($kelulusan->tanggal_lulus->format('d F Y')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Current Activity -->
                    <?php if($kelulusan->tempat_kuliah || $kelulusan->tempat_kerja): ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Saat Ini</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php if($kelulusan->tempat_kuliah): ?>
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <h5 class="font-medium text-blue-900 mb-2">Pendidikan Lanjutan</h5>
                                            <p class="text-blue-800"><?php echo e($kelulusan->education_path); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($kelulusan->tempat_kerja): ?>
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <h5 class="font-medium text-green-900 mb-2">Pekerjaan</h5>
                                            <p class="text-green-800"><?php echo e($kelulusan->career_path); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information -->
                    <?php if($kelulusan->no_hp || $kelulusan->no_wa || $kelulusan->alamat): ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php if($kelulusan->contact_info): ?>
                                        <div>
                                            <h5 class="font-medium text-gray-700 mb-2">Kontak</h5>
                                            <p class="text-gray-600"><?php echo e($kelulusan->contact_info); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($kelulusan->alamat): ?>
                                        <div>
                                            <h5 class="font-medium text-gray-700 mb-2">Alamat</h5>
                                            <p class="text-gray-600"><?php echo e($kelulusan->alamat); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Achievements -->
                    <?php if($kelulusan->prestasi): ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Prestasi</h4>
                                <div class="bg-yellow-50 rounded-lg p-4">
                                    <p class="text-yellow-800"><?php echo e($kelulusan->prestasi); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Notes -->
                    <?php if($kelulusan->catatan): ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h4>
                                <div class="bg-purple-50 rounded-lg p-4">
                                    <p class="text-purple-800"><?php echo e($kelulusan->catatan); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Academic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akademik</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Jurusan</h5>
                                    <p class="text-gray-600"><?php echo e($kelulusan->major_display); ?></p>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Tahun Ajaran</h5>
                                    <p class="text-gray-600"><?php echo e($kelulusan->graduation_year_display); ?></p>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Status Kelulusan</h5>
                                    <p class="text-gray-600"><?php echo e($kelulusan->status_display); ?></p>
                                </div>
                                <?php if($kelulusan->tanggal_lulus): ?>
                                    <div>
                                        <h5 class="font-medium text-gray-700 mb-2">Tanggal Lulus</h5>
                                        <p class="text-gray-600"><?php echo e($kelulusan->tanggal_lulus->format('d F Y')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views\lulus\show.blade.php ENDPATH**/ ?>