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
            <?php echo e(__('Hasil Pengecekan Status Kelulusan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full 
                            <?php if($kelulusan->status === 'lulus'): ?> bg-green-100
                            <?php elseif($kelulusan->status === 'tidak_lulus'): ?> bg-red-100
                            <?php else: ?> bg-yellow-100 <?php endif; ?> mb-4">
                            <?php if($kelulusan->status === 'lulus'): ?>
                                <span class="text-3xl">🎉</span>
                            <?php elseif($kelulusan->status === 'tidak_lulus'): ?>
                                <span class="text-3xl">😔</span>
                            <?php else: ?>
                                <span class="text-3xl">⏳</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">
                            <?php echo e($kelulusan->graduation_message); ?>

                        </h3>

                        <!-- Check Statistics -->
                        <?php if($kelulusan->check_count > 0): ?>
                            <div class="bg-blue-50 rounded-lg p-4 max-w-md mx-auto">
                                <p class="text-sm text-blue-800">
                                    📊 Ini adalah pengecekan ke-<?php echo e($kelulusan->check_count); ?> Anda
                                </p>
                                <?php if($kelulusan->last_checked_at): ?>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Terakhir dicek: <?php echo e($kelulusan->last_checked_at->format('d/m/Y H:i')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Student Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Siswa</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium"><?php echo e($kelulusan->nama); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">NISN:</span>
                                    <span class="font-medium"><?php echo e($kelulusan->nisn); ?></span>
                                </div>
                                <?php if($kelulusan->nis): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">NIS:</span>
                                        <span class="font-medium"><?php echo e($kelulusan->nis); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jurusan:</span>
                                    <span class="font-medium"><?php echo e($kelulusan->major_display); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tahun Ajaran:</span>
                                    <span class="font-medium"><?php echo e($kelulusan->graduation_year_display); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php if($kelulusan->status_badge_color == 'green'): ?> bg-green-100 text-green-800
                                        <?php elseif($kelulusan->status_badge_color == 'red'): ?> bg-red-100 text-red-800
                                        <?php elseif($kelulusan->status_badge_color == 'yellow'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e($kelulusan->status_display); ?>

                                    </span>
                                </div>
                                <?php if($kelulusan->tanggal_lulus): ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Lulus:</span>
                                        <span
                                            class="font-medium"><?php echo e($kelulusan->tanggal_lulus->format('d F Y')); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="flex flex-col items-center">
                            <?php if($kelulusan->foto): ?>
                                <img src="<?php echo e($kelulusan->photo_url); ?>" alt="<?php echo e($kelulusan->nama); ?>"
                                    class="h-48 w-48 rounded-full object-cover mb-4">
                            <?php else: ?>
                                <div class="h-48 w-48 rounded-full bg-gray-300 flex items-center justify-center mb-4">
                                    <span
                                        class="text-gray-600 text-4xl font-medium"><?php echo e(substr($kelulusan->nama, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Current Activity -->
                    <?php if($kelulusan->tempat_kuliah || $kelulusan->tempat_kerja): ?>
                        <div class="mt-8 bg-blue-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Saat Ini</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php if($kelulusan->tempat_kuliah): ?>
                                    <div>
                                        <h5 class="font-medium text-gray-700 mb-2">Pendidikan Lanjutan</h5>
                                        <p class="text-gray-600"><?php echo e($kelulusan->education_path); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($kelulusan->tempat_kerja): ?>
                                    <div>
                                        <h5 class="font-medium text-gray-700 mb-2">Pekerjaan</h5>
                                        <p class="text-gray-600"><?php echo e($kelulusan->career_path); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information -->
                    <?php if($kelulusan->no_hp || $kelulusan->no_wa || $kelulusan->alamat): ?>
                        <div class="mt-8 bg-green-50 rounded-lg p-6">
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
                    <?php endif; ?>

                    <!-- Achievements -->
                    <?php if($kelulusan->prestasi): ?>
                        <div class="mt-8 bg-yellow-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Prestasi</h4>
                            <p class="text-gray-600"><?php echo e($kelulusan->prestasi); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Notes -->
                    <?php if($kelulusan->catatan): ?>
                        <div class="mt-8 bg-purple-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h4>
                            <p class="text-gray-600"><?php echo e($kelulusan->catatan); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-center space-x-4">
                        <a href="<?php echo e(route('admin.lulus.check')); ?>"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Cek Status Lain
                        </a>
                        <?php if($kelulusan->status === 'lulus'): ?>
                            <button onclick="window.print()"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Cetak Hasil
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-white {
                box-shadow: none !important;
            }
        }
    </style>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\lulus\result.blade.php ENDPATH**/ ?>