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
                <?php echo e(__('common.tambah_data_siswa')); ?>

            </h2>
            <a href="<?php echo e(route('admin.siswa.index')); ?>"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <?php echo e(__('common.back')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="<?php echo e(route('admin.siswa.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.personal_info')); ?></h3>

                                <!-- NIS -->
                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS
                                        *</label>
                                    <input type="text" name="nis" id="nis" value="<?php echo e(old('nis')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- NISN -->
                                <div>
                                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN
                                        *</label>
                                    <input type="text" name="nisn" id="nisn" value="<?php echo e(old('nisn')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.full_name_label')); ?> *</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                        value="<?php echo e(old('nama_lengkap')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.gender_label')); ?> *</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="L"
                                                <?php echo e(old('jenis_kelamin') == 'L' ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.laki_laki')); ?></span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="P"
                                                <?php echo e(old('jenis_kelamin') == 'P' ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.perempuan')); ?></span>
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Tanggal & Tempat Lahir -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="tanggal_lahir"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.birth_date')); ?> *</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            value="<?php echo e(old('tanggal_lahir')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                        <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label for="tempat_lahir"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.birth_place')); ?> *</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                                            value="<?php echo e(old('tempat_lahir')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                        <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.address_label')); ?> *</label>
                                    <textarea name="alamat" id="alamat" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required><?php echo e(old('alamat')); ?></textarea>
                                    <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Kontak -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No.
                                            Telepon</label>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            value="<?php echo e(old('no_telepon')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['no_telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['no_telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.whatsapp_number')); ?></label>
                                        <input type="text" name="no_wa" id="no_wa" value="<?php echo e(old('no_wa')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['no_wa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['no_wa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.email_label')); ?></label>
                                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Foto -->
                                <div>
                                    <label for="foto"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.photo')); ?></label>
                                    <input type="file" name="foto" id="foto" accept="image/*"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <p class="text-gray-500 text-xs mt-1">Max size: 2MB, Formats: JPEG, PNG, JPG, GIF
                                    </p>
                                    <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akademik</h3>

                                <!-- Kelas -->
                                <div>
                                    <label for="kelas"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.class_label')); ?></label>
                                    <div class="flex gap-2">
                                        <select name="kelas" id="kelas"
                                            class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value=""><?php echo e(__('common.select_class')); ?></option>
                                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($k); ?>"
                                                    <?php echo e(old('kelas') == $k ? 'selected' : ''); ?>><?php echo e($k); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button type="button" onclick="openKelasModal()"
                                            class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Jurusan -->
                                <div>
                                    <label for="jurusan"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.major')); ?></label>
                                    <div class="flex gap-2">
                                        <select name="jurusan" id="jurusan"
                                            class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value=""><?php echo e(__('common.select_major')); ?></option>
                                            <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($j); ?>"
                                                    <?php echo e(old('jurusan') == $j ? 'selected' : ''); ?>><?php echo e($j); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button type="button" onclick="openJurusanModal()"
                                            class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Tahun Masuk -->
                                <div>
                                    <label for="tahun_masuk"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.entry_year')); ?> *</label>
                                    <input type="number" name="tahun_masuk" id="tahun_masuk"
                                        value="<?php echo e(old('tahun_masuk')); ?>" min="2000" max="<?php echo e(date('Y')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tahun_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['tahun_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Tahun Lulus -->
                                <div>
                                    <label for="tahun_lulus"
                                        class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                                    <input type="number" name="tahun_lulus" id="tahun_lulus"
                                        value="<?php echo e(old('tahun_lulus')); ?>" min="2000" max="<?php echo e(date('Y')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tahun_lulus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['tahun_lulus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.current_status')); ?> *</label>
                                    <select name="status" id="status"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                        <option value=""><?php echo e(__('common.select_status')); ?></option>
                                        <option value="aktif" <?php echo e(old('status') == 'aktif' ? 'selected' : ''); ?>><?php echo e(__('common.active')); ?>

                                        </option>
                                        <option value="lulus" <?php echo e(old('status') == 'lulus' ? 'selected' : ''); ?>><?php echo e(__('common.graduated')); ?>

                                        </option>
                                        <option value="pindah" <?php echo e(old('status') == 'pindah' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.transferred')); ?></option>
                                        <option value="keluar" <?php echo e(old('status') == 'keluar' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.dropped_out')); ?></option>
                                        <option value="meninggal"
                                            <?php echo e(old('status') == 'meninggal' ? 'selected' : ''); ?>><?php echo e(__('common.deceased')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Ekstrakurikuler -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700">Ekstrakurikuler</label>
                                        <button type="button" onclick="openEkstrakurikulerModal()"
                                            class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <?php echo e(__('common.add')); ?>

                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-md p-2">
                                        <?php $__currentLoopData = $ekstrakurikuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="ekstrakurikuler[]"
                                                    value="<?php echo e($eks); ?>"
                                                    <?php echo e(in_array($eks, old('ekstrakurikuler', [])) ? 'checked' : ''); ?>

                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700"><?php echo e($eks); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php $__errorArgs = ['ekstrakurikuler'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Parent Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Orang Tua</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Ayah -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Ayah</h4>
                                    <div>
                                        <label for="nama_ayah"
                                            class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" id="nama_ayah"
                                            value="<?php echo e(old('nama_ayah')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ayah"
                                            class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah"
                                            value="<?php echo e(old('pekerjaan_ayah')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['pekerjaan_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['pekerjaan_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Ibu -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Ibu</h4>
                                    <div>
                                        <label for="nama_ibu"
                                            class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" id="nama_ibu"
                                            value="<?php echo e(old('nama_ibu')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label for="pekerjaan_ibu"
                                            class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu"
                                            value="<?php echo e(old('pekerjaan_ibu')); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['pekerjaan_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['pekerjaan_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak Orang Tua -->
                            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label for="no_telepon_ortu"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.parent_phone')); ?></label>
                                    <input type="text" name="no_telepon_ortu" id="no_telepon_ortu"
                                        value="<?php echo e(old('no_telepon_ortu')); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['no_telepon_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['no_telepon_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label for="alamat_ortu"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.parent_address')); ?></label>
                                    <textarea name="alamat_ortu" id="alamat_ortu" rows="2"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['alamat_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('alamat_ortu')); ?></textarea>
                                    <?php $__errorArgs = ['alamat_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.additional_information')); ?></h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Prestasi -->
                                <div>
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700 mb-1">Prestasi</label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['prestasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('prestasi')); ?></textarea>
                                    <?php $__errorArgs = ['prestasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('catatan')); ?></textarea>
                                    <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- User Account -->
                            <div class="mt-6">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User
                                    Account</label>
                                <div class="flex gap-2">
                                    <select name="user_id" id="user_id"
                                        class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value=""><?php echo e(__('common.select_user_account_optional')); ?></option>
                                        <?php if($users->count() > 0): ?>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>"
                                                    <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                                    <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <option value="" disabled><?php echo e(__('common.no_users_available')); ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="button" onclick="openUserModal()"
                                        class="px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <?php echo e(__('common.user_hint')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="<?php echo e(route('admin.siswa.index')); ?>"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.add_student_data')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelas -->
    <div id="kelasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e(__('common.manage_class_data')); ?></h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.add_new_class')); ?></label>
                        <div class="flex gap-2">
                            <input type="text" id="newKelas" placeholder="<?php echo e(__('common.enter_class_name')); ?>"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addKelas()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.class_list')); ?></h4>
                        <div id="kelasList" class="space-y-2">
                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm"><?php echo e($k); ?></span>
                                    <div class="flex gap-1">
                                        <button onclick="editKelas(<?php echo e(json_encode($k)); ?>)"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            <?php echo e(__('common.edit')); ?>

                                        </button>
                                        <button onclick="deleteKelas(<?php echo e(json_encode($k)); ?>)"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            <?php echo e(__('common.delete')); ?>

                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeKelasModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <?php echo e(__('common.close')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jurusan -->
    <div id="jurusanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e(__('common.manage_major_data')); ?></h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.add_new_major')); ?></label>
                        <div class="flex gap-2">
                            <input type="text" id="newJurusan" placeholder="<?php echo e(__('common.enter_major_name')); ?>"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addJurusan()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.major_list')); ?></h4>
                        <div id="jurusanList" class="space-y-2">
                            <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm"><?php echo e($j); ?></span>
                                    <div class="flex gap-1">
                                        <button onclick="editJurusan(<?php echo e(json_encode($j)); ?>)"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            <?php echo e(__('common.edit')); ?>

                                        </button>
                                        <button onclick="deleteJurusan(<?php echo e(json_encode($j)); ?>)"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            <?php echo e(__('common.delete')); ?>

                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeJurusanModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <?php echo e(__('common.close')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ekstrakurikuler -->
    <div id="ekstrakurikulerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e(__('common.manage_extracurricular_data')); ?></h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.add_new_extracurricular')); ?></label>
                        <div class="flex gap-2">
                            <input type="text" id="newEkstrakurikuler" placeholder="<?php echo e(__('common.enter_extracurricular_name')); ?>"
                                class="flex-1 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addEkstrakurikuler()"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.extracurricular_list')); ?></h4>
                        <div id="ekstrakurikulerList" class="space-y-2">
                            <?php $__currentLoopData = $ekstrakurikuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm"><?php echo e($eks); ?></span>
                                    <div class="flex gap-1">
                                        <button onclick="editEkstrakurikuler(<?php echo e(json_encode($eks)); ?>)"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button onclick="deleteEkstrakurikuler(<?php echo e(json_encode($eks)); ?>)"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeEkstrakurikulerModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal User Account -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e(__('common.manage_user_account')); ?></h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.add_new_user')); ?></label>
                        <div class="space-y-3">
                            <input type="text" id="newUserName" placeholder="<?php echo e(__('common.enter_full_name')); ?>"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="email" id="newUserEmail" placeholder="<?php echo e(__('common.enter_email')); ?>"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="password" id="newUserPassword" placeholder="<?php echo e(__('common.password')); ?>"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="bg-blue-50 border border-blue-200 rounded-md px-3 py-2">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-user-graduate text-blue-600 mr-2"></i>
                                    <span class="font-medium"><?php echo e(__('common.role')); ?>:</span> <?php echo e(__('common.student')); ?>

                                </p>
                            </div>
                            <button onclick="addUser()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add_user')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.user_list')); ?></h4>
                        <div id="userList" class="space-y-2">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <span class="text-sm font-medium"><?php echo e($user->name); ?></span>
                                        <span class="text-xs text-gray-500 ml-2">(<?php echo e($user->email); ?>)</span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button
                                            onclick="editUser(<?php echo e($user->id); ?>, <?php echo e(json_encode($user->name)); ?>, <?php echo e(json_encode($user->email)); ?>)"
                                            class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                            <?php echo e(__('common.edit')); ?>

                                        </button>
                                        <button onclick="deleteUser(<?php echo e($user->id); ?>)"
                                            class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            <?php echo e(__('common.delete')); ?>

                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeUserModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <?php echo e(__('common.close')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openKelasModal() {
            document.getElementById('kelasModal').classList.remove('hidden');
        }

        function closeKelasModal() {
            document.getElementById('kelasModal').classList.add('hidden');
        }

        function openJurusanModal() {
            document.getElementById('jurusanModal').classList.remove('hidden');
        }

        function closeJurusanModal() {
            document.getElementById('jurusanModal').classList.add('hidden');
        }

        function openEkstrakurikulerModal() {
            document.getElementById('ekstrakurikulerModal').classList.remove('hidden');
        }

        function closeEkstrakurikulerModal() {
            document.getElementById('ekstrakurikulerModal').classList.add('hidden');
        }

        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // Add functions
        function addKelas() {
            const newKelas = document.getElementById('newKelas').value;
            if (newKelas.trim()) {
                // Show loading
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '<?php echo e(__('common.loading')); ?>...';
                button.disabled = true;

                // Send AJAX request
                fetch('<?php echo e(route('admin.settings.data-management.kelas.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newKelas
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add to select dropdown
                            const select = document.getElementById('kelas');
                            const option = document.createElement('option');
                            option.value = data.data.nama;
                            option.textContent = data.data.nama;
                            select.appendChild(option);

                            // Add to list
                            const list = document.getElementById('kelasList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editKelas(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteKelas(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newKelas').value = '';
                            showSuccess('<?php echo e(__('common.class_added_success')); ?>');
                        } else {
                            showError(data.message || '<?php echo e(__('common.error_occurred_adding_class')); ?>');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('<?php echo e(__('common.error_occurred_adding_class')); ?>');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addJurusan() {
            const newJurusan = document.getElementById('newJurusan').value;
            if (newJurusan.trim()) {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '<?php echo e(__('common.loading')); ?>...';
                button.disabled = true;

                fetch('<?php echo e(route('admin.settings.data-management.jurusan.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newJurusan
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const select = document.getElementById('jurusan');
                            const option = document.createElement('option');
                            option.value = data.data.nama;
                            option.textContent = data.data.nama;
                            select.appendChild(option);

                            const list = document.getElementById('jurusanList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editJurusan(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteJurusan(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newJurusan').value = '';
                            showSuccess('<?php echo e(__('common.major_added_success')); ?>');
                        } else {
                            showError(data.message || '<?php echo e(__('common.error_occurred_adding_major')); ?>');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('<?php echo e(__('common.error_occurred_adding_major')); ?>');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addEkstrakurikuler() {
            const newEkstrakurikuler = document.getElementById('newEkstrakurikuler').value;
            if (newEkstrakurikuler.trim()) {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = '<?php echo e(__('common.loading')); ?>...';
                button.disabled = true;

                fetch('<?php echo e(route('admin.settings.data-management.ekstrakurikuler.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nama: newEkstrakurikuler
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const list = document.getElementById('ekstrakurikulerList');
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                            div.innerHTML = `
                            <span class="text-sm">${data.data.nama}</span>
                            <div class="flex gap-1">
                                <button onclick="editEkstrakurikuler(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteEkstrakurikuler(${JSON.stringify(data.data.nama)}, ${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                            list.appendChild(div);

                            document.getElementById('newEkstrakurikuler').value = '';
                            showSuccess('<?php echo e(__('common.extracurricular_added_success')); ?>');
                        } else {
                            showError(data.message || '<?php echo e(__('common.error_occurred_adding_extracurricular')); ?>');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('<?php echo e(__('common.error_occurred_adding_extracurricular')); ?>');
                    })
                    .finally(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    });
            }
        }

        function addUser() {
            const name = document.getElementById('newUserName').value;
            const email = document.getElementById('newUserEmail').value;
            const password = document.getElementById('newUserPassword').value;
            // Validation
            if (!name.trim()) {
                showError('Nama lengkap harus diisi');
                return;
            }
            if (!email.trim()) {
                showError('<?php echo e(__('common.email_required')); ?>');
                return;
            }
            if (!password.trim()) {
                showError('Password harus diisi');
                return;
            }
            if (password.length < 8) {
                showError('Password minimal 8 karakter');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Loading...';
            button.disabled = true;

            // Get siswa role ID
            fetch('<?php echo e(route('admin.superadmin.users.store')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        roles: ['siswa'] // Use role instead of user_type
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Add to select dropdown
                        const select = document.getElementById('user_id');
                        const option = document.createElement('option');
                        option.value = data.data.id;
                        option.textContent = `${data.data.name} (${data.data.email})`;
                        select.appendChild(option);

                        // Add to list
                        const list = document.getElementById('userList');
                        const div = document.createElement('div');
                        div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                        div.innerHTML = `
                            <div>
                                <span class="text-sm font-medium">${data.data.name}</span>
                                <span class="text-xs text-gray-500 ml-2">(${data.data.email})</span>
                            </div>
                            <div class="flex gap-1">
                                <button onclick="editUser(${data.data.id}, ${JSON.stringify(data.data.name)}, ${JSON.stringify(data.data.email)})" class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Edit</button>
                                <button onclick="deleteUser(${data.data.id})" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                            </div>
                        `;
                        list.appendChild(div);

                        // Clear form
                        document.getElementById('newUserName').value = '';
                        document.getElementById('newUserEmail').value = '';
                        document.getElementById('newUserPassword').value = '';
                        showSuccess('User berhasil ditambahkan!');
                    } else {
                        showError(data.message || 'Terjadi kesalahan saat menambahkan user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Handle validation errors
                    if (error.errors) {
                        let errorMessages = [];
                        for (let field in error.errors) {
                            errorMessages.push(...error.errors[field]);
                        }
                        showError(errorMessages.join('<br>'));
                    } else if (error.message) {
                        showError(error.message);
                    } else {
                        showError('Terjadi kesalahan saat menambahkan user');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        // Edit functions
        async function editKelas(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: '<?php echo e(__('common.edit_class')); ?>',
                input: 'text',
                inputLabel: '<?php echo e(__('common.class_name')); ?>',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__('common.save')); ?>',
                cancelButtonText: '<?php echo e(__('common.cancel')); ?>',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama kelas tidak boleh kosong!';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                // Update in select
                const select = document.getElementById('kelas');
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === oldValue) {
                        option.value = newValue;
                        option.textContent = newValue;
                    }
                });

                // Update in list
                const list = document.getElementById('kelasList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        // Update onclick attributes
                        const editBtn = item.querySelector('button[onclick*="editKelas"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteKelas"]');
                        editBtn.setAttribute('onclick', `editKelas(${JSON.stringify(newValue)})`);
                        deleteBtn.setAttribute('onclick', `deleteKelas(${JSON.stringify(newValue)})`);
                    }
                });
            }
        }

        async function editJurusan(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: '<?php echo e(__('common.edit_major')); ?>',
                input: 'text',
                inputLabel: '<?php echo e(__('common.major_name')); ?>',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__('common.save')); ?>',
                cancelButtonText: '<?php echo e(__('common.cancel')); ?>',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama jurusan tidak boleh kosong!';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                const select = document.getElementById('jurusan');
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === oldValue) {
                        option.value = newValue;
                        option.textContent = newValue;
                    }
                });

                const list = document.getElementById('jurusanList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        const editBtn = item.querySelector('button[onclick*="editJurusan"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteJurusan"]');
                        editBtn.setAttribute('onclick', `editJurusan(${JSON.stringify(newValue)})`);
                        deleteBtn.setAttribute('onclick', `deleteJurusan(${JSON.stringify(newValue)})`);
                    }
                });
            }
        }

        async function editEkstrakurikuler(oldValue) {
            const {
                value: newValue
            } = await Swal.fire({
                title: 'Edit Ekstrakurikuler',
                input: 'text',
                inputLabel: '<?php echo e(__('common.extracurricular_name')); ?>',
                inputValue: oldValue,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__('common.save')); ?>',
                cancelButtonText: '<?php echo e(__('common.cancel')); ?>',
                inputValidator: (value) => {
                    if (!value) {
                        return '<?php echo e(__('common.extracurricular_name_required')); ?>';
                    }
                }
            });

            if (newValue && newValue !== oldValue) {
                const list = document.getElementById('ekstrakurikulerList');
                const items = list.querySelectorAll('div');
                items.forEach(item => {
                    const span = item.querySelector('span');
                    if (span.textContent === oldValue) {
                        span.textContent = newValue;
                        const editBtn = item.querySelector('button[onclick*="editEkstrakurikuler"]');
                        const deleteBtn = item.querySelector('button[onclick*="deleteEkstrakurikuler"]');
                        editBtn.setAttribute('onclick', `editEkstrakurikuler('${newValue}')`);
                        deleteBtn.setAttribute('onclick', `deleteEkstrakurikuler('${newValue}')`);
                    }
                });
            }
        }

        async function editUser(id, name, email) {
            const {
                value: formValues
            } = await Swal.fire({
                title: '<?php echo e(__('common.edit_user')); ?>',
                html: `
                    <input id="swal-name" class="swal2-input" placeholder="<?php echo e(__('common.name')); ?>" value="${name}" required>
                    <input id="swal-email" class="swal2-input" placeholder="<?php echo e(__('common.email_label')); ?>" type="email" value="${email}" required>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(__('common.save')); ?>',
                cancelButtonText: '<?php echo e(__('common.cancel')); ?>',
                preConfirm: () => {
                    const nameInput = document.getElementById('swal-name');
                    const emailInput = document.getElementById('swal-email');
                    if (!nameInput.value || !emailInput.value) {
                        Swal.showValidationMessage('<?php echo e(__('common.name_email_required')); ?>');
                        return false;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                        Swal.showValidationMessage('Format email tidak valid!');
                        return false;
                    }
                    return {
                        name: nameInput.value,
                        email: emailInput.value
                    };
                }
            });

            if (formValues) {
                const newName = formValues.name;
                const newEmail = formValues.email;
                if (newName !== name || newEmail !== email) {
                    // Update in select
                    const select = document.getElementById('user_id');
                    const options = select.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value === id) {
                            option.textContent = `${newName} (${newEmail})`;
                        }
                    });

                    // Update in list
                    const list = document.getElementById('userList');
                    const items = list.querySelectorAll('div');
                    items.forEach(item => {
                        const span = item.querySelector('span');
                        if (span.textContent === name) {
                            span.textContent = newName;
                            const emailSpan = item.querySelector('.text-gray-500');
                            emailSpan.textContent = `(${newEmail})`;
                        }
                    });
                }
            }
        }

        // Delete functions
        function deleteKelas(value, id) {
            showConfirm('<?php echo e(__('common.delete_confirmation')); ?>', `<?php echo e(__('common.delete_class_confirmation')); ?>`.replace(':value', ${JSON.stringify(value)}), '<?php echo e(__('common.yes_delete')); ?>', '<?php echo e(__('common.cancel')); ?>').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/kelas/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
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
                                if (result.status === 404) {
                                    showError('<?php echo e(__('common.error')); ?>!', '<?php echo e(__('common.class_not_found')); ?>');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('<?php echo e(__('common.unauthorized')); ?>!',
                                        '<?php echo e(__('common.unauthorized_action')); ?>');
                                } else {
                                    showError('<?php echo e(__('common.error')); ?>!', result.data.message || '<?php echo e(__('common.failed_delete_class')); ?>');
                                }
                                return;
                            }

                            if (result.data.success) {
                                // Remove from select
                                const select = document.getElementById('kelas');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === value) {
                                        option.remove();
                                    }
                                });

                                // Remove from list
                                const list = document.getElementById('kelasList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('<?php echo e(__('common.class_deleted_success')); ?>');
                            } else {
                                showError(result.data.message || '<?php echo e(__('common.error_occurred_deleting_class')); ?>');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error_occurred_deleting_class')); ?>: ' + error.message);
                        });
                }
            });
        }

        function deleteJurusan(value, id) {
            showConfirm('<?php echo e(__('common.delete_confirmation')); ?>', `<?php echo e(__('common.delete_major_confirmation')); ?>`.replace(':value', ${JSON.stringify(value)}), '<?php echo e(__('common.yes_delete')); ?>', '<?php echo e(__('common.cancel')); ?>').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/jurusan/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
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
                                if (result.status === 404) {
                                    showError('<?php echo e(__('common.error')); ?>!', '<?php echo e(__('common.major_not_found')); ?>');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('<?php echo e(__('common.unauthorized')); ?>!',
                                        '<?php echo e(__('common.unauthorized_action')); ?>');
                                } else {
                                    showError('<?php echo e(__('common.error')); ?>!', result.data.message || '<?php echo e(__('common.failed_delete_major')); ?>');
                                }
                                return;
                            }

                            if (result.data.success) {
                                const select = document.getElementById('jurusan');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === value) {
                                        option.remove();
                                    }
                                });

                                const list = document.getElementById('jurusanList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('<?php echo e(__('common.major_deleted_success')); ?>');
                            } else {
                                showError(result.data.message || '<?php echo e(__('common.error_occurred_deleting_major')); ?>');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error_occurred_deleting_major')); ?>: ' + error.message);
                        });
                }
            });
        }

        function deleteEkstrakurikuler(value, id) {
            showConfirm('<?php echo e(__('common.delete_confirmation')); ?>', `<?php echo e(__('common.delete_extracurricular_confirmation')); ?>`.replace(':value', ${JSON.stringify(value)}), '<?php echo e(__('common.yes_delete')); ?>', '<?php echo e(__('common.cancel')); ?>').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/ekstrakurikuler/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
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
                                if (result.status === 404) {
                                    showError('<?php echo e(__('common.error')); ?>!', '<?php echo e(__('common.extracurricular_not_found')); ?>');
                                } else if (result.status === 401 || result.status === 403) {
                                    showError('<?php echo e(__('common.unauthorized')); ?>!',
                                        '<?php echo e(__('common.unauthorized_action')); ?>');
                                } else {
                                    showError('<?php echo e(__('common.error')); ?>!', result.data.message ||
                                        '<?php echo e(__('common.failed_delete_extracurricular')); ?>');
                                }
                                return;
                            }

                            if (result.data.success) {
                                const list = document.getElementById('ekstrakurikulerList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const span = item.querySelector('span');
                                    if (span.textContent === value) {
                                        item.remove();
                                    }
                                });
                                showSuccess('<?php echo e(__('common.extracurricular_deleted_success')); ?>');
                            } else {
                                showError(result.data.message ||
                                    '<?php echo e(__('common.error_occurred_deleting_extracurricular')); ?>');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error_occurred_deleting_extracurricular')); ?>: ' + error.message);
                        });
                }
            });
        }

        function deleteUser(id) {
            showConfirm('<?php echo e(__('common.delete_confirmation')); ?>', '<?php echo e(__('common.delete_user_confirmation')); ?>', '<?php echo e(__('common.yes_delete')); ?>', '<?php echo e(__('common.cancel')); ?>').then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const select = document.getElementById('user_id');
                                const options = select.querySelectorAll('option');
                                options.forEach(option => {
                                    if (option.value === id) {
                                        option.remove();
                                    }
                                });

                                const list = document.getElementById('userList');
                                const items = list.querySelectorAll('div');
                                items.forEach(item => {
                                    const button = item.querySelector(
                                        `button[onclick*="deleteUser('${id}')"]`);
                                    if (button) {
                                        item.remove();
                                    }
                                });
                                showSuccess('<?php echo e(__('common.user_deleted_success')); ?>');
                            } else {
                                showError(data.message || '<?php echo e(__('common.error_occurred_deleting_user')); ?>');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error_occurred_deleting_user')); ?>');
                        });
                }
            });
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bg-gray-600')) {
                closeKelasModal();
                closeJurusanModal();
                closeEkstrakurikulerModal();
                closeUserModal();
            }
        });
    </script>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\siswa\create.blade.php ENDPATH**/ ?>