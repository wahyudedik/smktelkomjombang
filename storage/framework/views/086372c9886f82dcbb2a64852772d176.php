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
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('common.detail_data_guru')); ?>

            </h2>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('admin.guru.edit', $guru)); ?>"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <?php echo e(__('common.edit')); ?>

                </a>
                <a href="<?php echo e(route('admin.guru.index')); ?>"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <?php echo e(__('common.back')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Profile Header -->
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 mb-8">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <?php if($guru->foto): ?>
                                <img class="h-32 w-32 rounded-full object-cover" src="<?php echo e($guru->photo_url); ?>"
                                    alt="<?php echo e($guru->nama_lengkap); ?>">
                            <?php else: ?>
                                <div class="h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span
                                        class="text-gray-600 text-4xl font-medium"><?php echo e(substr($guru->nama_lengkap, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Basic Info -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900"><?php echo e($guru->full_name); ?></h1>
                            <p class="text-lg text-gray-600"><?php echo e($guru->jabatan ?? 'Guru'); ?></p>
                            <p class="text-sm text-gray-500">NIP: <?php echo e($guru->nip); ?></p>

                            <!-- Status Badges -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php if($guru->status_badge_color === 'green'): ?> bg-green-100 text-green-800
                                    <?php elseif($guru->status_badge_color === 'red'): ?> bg-red-100 text-red-800
                                    <?php elseif($guru->status_badge_color === 'blue'): ?> bg-blue-100 text-blue-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $guru->status_aktif))); ?>

                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php if($guru->employment_badge_color === 'green'): ?> bg-green-100 text-green-800
                                    <?php elseif($guru->employment_badge_color === 'blue'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($guru->employment_badge_color === 'yellow'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($guru->employment_badge_color === 'orange'): ?> bg-orange-100 text-orange-800
                                    <?php elseif($guru->employment_badge_color === 'red'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e($guru->status_kepegawaian); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi
                                Personal</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <?php echo e($guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->tanggal_lahir->format('d F Y')); ?>

                                        (<?php echo e($guru->age); ?> tahun)</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->tempat_lahir); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->alamat); ?></p>
                                </div>

                                <?php if($guru->no_telepon): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->no_telepon); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if($guru->no_wa): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->no_wa); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if($guru->email): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->email); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi
                                Profesional</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->tanggal_masuk->format('d F Y')); ?>

                                        (<?php echo e($guru->years_of_service); ?> tahun)</p>
                                </div>

                                <?php if($guru->tanggal_keluar): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo e($guru->tanggal_keluar->format('d F Y')); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->pendidikan_terakhir); ?></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Universitas</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->universitas); ?>

                                        (<?php echo e($guru->tahun_lulus); ?>)</p>
                                </div>

                                <?php if($guru->sertifikasi): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Sertifikasi</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($guru->sertifikasi); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $guru->mata_pelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($subject); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <?php if($guru->prestasi || $guru->catatan): ?>
                        <div class="mt-8 space-y-6">
                            <?php if($guru->prestasi): ?>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                        Prestasi</h3>
                                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                                        <p class="text-sm text-gray-900 whitespace-pre-line"><?php echo e($guru->prestasi); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($guru->catatan): ?>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                        Catatan</h3>
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-900 whitespace-pre-line"><?php echo e($guru->catatan); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- User Account Information -->
                    <?php if($guru->user): ?>
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">User Account
                            </h3>
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?php echo e($guru->user->name); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo e($guru->user->email); ?></p>
                                    </div>
                                    <div class="ml-auto">
                                        <?php if($guru->user && $guru->user->roles->count() > 0): ?>
                                            <?php $__currentLoopData = $guru->user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                                    <?php echo e($role->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                No role
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\guru\show.blade.php ENDPATH**/ ?>