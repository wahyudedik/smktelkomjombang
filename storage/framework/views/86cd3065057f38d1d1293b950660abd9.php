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
                <?php echo e(__('common.edit_data_guru')); ?>

            </h2>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('admin.guru.show', $guru)); ?>"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <?php echo e(__('common.view_details')); ?>

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
                    <form method="POST" action="<?php echo e(route('admin.guru.update', $guru)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.personal_information')); ?></h3>

                                <!-- NIP -->
                                <div>
                                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.nip')); ?>

                                        *</label>
                                    <input type="text" name="nip" id="nip"
                                        value="<?php echo e(old('nip', $guru->nip)); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['nip'];
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
                                        value="<?php echo e(old('nama_lengkap', $guru->nama_lengkap)); ?>"
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

                                <!-- Gelar -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="gelar_depan"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.title_prefix')); ?></label>
                                        <input type="text" name="gelar_depan" id="gelar_depan"
                                            value="<?php echo e(old('gelar_depan', $guru->gelar_depan)); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['gelar_depan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['gelar_depan'];
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
                                        <label for="gelar_belakang"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.title_suffix')); ?></label>
                                        <input type="text" name="gelar_belakang" id="gelar_belakang"
                                            value="<?php echo e(old('gelar_belakang', $guru->gelar_belakang)); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['gelar_belakang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['gelar_belakang'];
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

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.gender_label')); ?> *</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="L"
                                                <?php echo e(old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.laki_laki')); ?></span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="jenis_kelamin" value="P"
                                                <?php echo e(old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'checked' : ''); ?>

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
                                            value="<?php echo e(old('tanggal_lahir', $guru->tanggal_lahir->format('Y-m-d'))); ?>"
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
                                            value="<?php echo e(old('tempat_lahir', $guru->tempat_lahir)); ?>"
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
                                        required><?php echo e(old('alamat', $guru->alamat)); ?></textarea>
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
                                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.phone_number_label')); ?></label>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            value="<?php echo e(old('no_telepon', $guru->no_telepon)); ?>"
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
                                        <input type="text" name="no_wa" id="no_wa"
                                            value="<?php echo e(old('no_wa', $guru->no_wa)); ?>"
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
                                    <input type="email" name="email" id="email"
                                        value="<?php echo e(old('email', $guru->email)); ?>"
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
                                    <?php if($guru->foto): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo e($guru->photo_url); ?>" alt="Current photo"
                                                class="h-20 w-20 rounded-full object-cover">
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.current_photo')); ?></p>
                                        </div>
                                    <?php endif; ?>
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

                            <!-- Professional Information -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.professional_information')); ?></h3>

                                <!-- Status Kepegawaian -->
                                <div>
                                    <label for="status_kepegawaian"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.employment_status')); ?>

                                        *</label>
                                    <select name="status_kepegawaian" id="status_kepegawaian"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['status_kepegawaian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                        <option value=""><?php echo e(__('common.select_status')); ?></option>
                                        <option value="PNS"
                                            <?php echo e(old('status_kepegawaian', $guru->status_kepegawaian) == 'PNS' ? 'selected' : ''); ?>>
                                            PNS</option>
                                        <option value="CPNS"
                                            <?php echo e(old('status_kepegawaian', $guru->status_kepegawaian) == 'CPNS' ? 'selected' : ''); ?>>
                                            CPNS</option>
                                        <option value="GTT"
                                            <?php echo e(old('status_kepegawaian', $guru->status_kepegawaian) == 'GTT' ? 'selected' : ''); ?>>
                                            GTT</option>
                                        <option value="GTY"
                                            <?php echo e(old('status_kepegawaian', $guru->status_kepegawaian) == 'GTY' ? 'selected' : ''); ?>>
                                            GTY</option>
                                        <option value="Honorer"
                                            <?php echo e(old('status_kepegawaian', $guru->status_kepegawaian) == 'Honorer' ? 'selected' : ''); ?>>
                                            Honorer</option>
                                    </select>
                                    <?php $__errorArgs = ['status_kepegawaian'];
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

                                <!-- Jabatan -->
                                <div>
                                    <label for="jabatan"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.position')); ?></label>
                                    <input type="text" name="jabatan" id="jabatan"
                                        value="<?php echo e(old('jabatan', $guru->jabatan)); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['jabatan'];
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

                                <!-- Tanggal Masuk -->
                                <div>
                                    <label for="tanggal_masuk"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.entry_date')); ?> *</label>
                                    <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                        value="<?php echo e(old('tanggal_masuk', $guru->tanggal_masuk->format('Y-m-d'))); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tanggal_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['tanggal_masuk'];
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

                                <!-- Tanggal Keluar -->
                                <div>
                                    <label for="tanggal_keluar"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.exit_date')); ?></label>
                                    <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                        value="<?php echo e(old('tanggal_keluar', $guru->tanggal_keluar?->format('Y-m-d'))); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tanggal_keluar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['tanggal_keluar'];
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

                                <!-- Status Aktif -->
                                <div>
                                    <label for="status_aktif"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.active_status')); ?> *</label>
                                    <select name="status_aktif" id="status_aktif"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['status_aktif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                        <option value=""><?php echo e(__('common.select_status')); ?></option>
                                        <option value="aktif"
                                            <?php echo e(old('status_aktif', $guru->status_aktif) == 'aktif' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.active')); ?></option>
                                        <option value="tidak_aktif"
                                            <?php echo e(old('status_aktif', $guru->status_aktif) == 'tidak_aktif' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.inactive')); ?></option>
                                        <option value="pensiun"
                                            <?php echo e(old('status_aktif', $guru->status_aktif) == 'pensiun' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.retired')); ?></option>
                                        <option value="meninggal"
                                            <?php echo e(old('status_aktif', $guru->status_aktif) == 'meninggal' ? 'selected' : ''); ?>>
                                            <?php echo e(__('common.deceased')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['status_aktif'];
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

                                <!-- Pendidikan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="pendidikan_terakhir"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.education_level')); ?>

                                            *</label>
                                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir"
                                            value="<?php echo e(old('pendidikan_terakhir', $guru->pendidikan_terakhir)); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['pendidikan_terakhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                        <?php $__errorArgs = ['pendidikan_terakhir'];
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
                                        <label for="tahun_lulus"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.education_year')); ?> *</label>
                                        <input type="text" name="tahun_lulus" id="tahun_lulus"
                                            value="<?php echo e(old('tahun_lulus', $guru->tahun_lulus)); ?>"
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['tahun_lulus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
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
                                </div>

                                <!-- Universitas -->
                                <div>
                                    <label for="universitas"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.education_institution')); ?> *</label>
                                    <input type="text" name="universitas" id="universitas"
                                        value="<?php echo e(old('universitas', $guru->universitas)); ?>"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['universitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['universitas'];
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

                                <!-- Sertifikasi -->
                                <div>
                                    <label for="sertifikasi"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.certification')); ?></label>
                                    <textarea name="sertifikasi" id="sertifikasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['sertifikasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('sertifikasi', $guru->sertifikasi)); ?></textarea>
                                    <?php $__errorArgs = ['sertifikasi'];
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

                                <!-- Mata Pelajaran -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-sm font-medium text-gray-700"><?php echo e(__('common.subjects')); ?> *</label>
                                        <button type="button" onclick="openMataPelajaranModal()"
                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <?php echo e(__('common.create')); ?>

                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-md p-2">
                                        <?php if(count($subjects) > 0): ?>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="mata_pelajaran[]"
                                                        value="<?php echo e($subject); ?>"
                                                        <?php echo e(in_array($subject, old('mata_pelajaran', $guru->mata_pelajaran ?? [])) ? 'checked' : ''); ?>

                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <span
                                                        class="ml-2 text-sm text-gray-700"><?php echo e($subject); ?></span>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="col-span-2 text-center text-gray-500 py-4">
                                                <?php echo e(__('common.no_subjects_available')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php $__errorArgs = ['mata_pelajaran'];
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
                                        <?php echo e(__('common.select_subjects_taught')); ?>

                                    </p>
                                </div>

                                <!-- Prestasi -->
                                <div>
                                    <label for="prestasi"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.achievement')); ?></label>
                                    <textarea name="prestasi" id="prestasi" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['prestasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('prestasi', $guru->prestasi)); ?></textarea>
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
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.notes')); ?></label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('catatan', $guru->catatan)); ?></textarea>
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

                                <!-- User Account -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700"><?php echo e(__('common.user_account')); ?></label>
                                        <button type="button" onclick="openUserModal()"
                                            class="px-3 py-1 bg-green-500 text-white text-sm rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <?php echo e(__('common.create')); ?>

                                        </button>
                                    </div>
                                    <select name="user_id" id="user_id"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['user_id'];
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
                                                    <?php echo e(old('user_id', $guru->user_id) == $user->id ? 'selected' : ''); ?>>
                                                    <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <option value="" disabled><?php echo e(__('common.no_users_available')); ?></option>
                                        <?php endif; ?>
                                    </select>
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
                                        <?php echo e(__('common.only_unused_users_teacher')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="<?php echo e(route('admin.guru.show', $guru)); ?>"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.update_teacher_data')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mata Pelajaran -->
    <div id="mataPelajaranModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><?php echo e(__('common.manage_subjects')); ?></h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.add_new_subject')); ?></label>
                        <div class="space-y-3">
                            <input type="text" id="newMataPelajaran" placeholder="<?php echo e(__('common.enter_subject_name')); ?>"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addMataPelajaran()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add_subject')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.subject_list')); ?></h4>
                        <div id="mataPelajaranList" class="space-y-2 max-h-40 overflow-y-auto">
                            <!-- List will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closeMataPelajaranModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <?php echo e(__('common.close')); ?>

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
                            <input type="password" id="newUserPassword" placeholder="Password"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="addUser()"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <?php echo e(__('common.add_new_user')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.user_list')); ?></h4>
                        <div id="userList" class="space-y-2 max-h-40 overflow-y-auto">
                            <!-- List will be populated by JavaScript -->
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
        // Mata Pelajaran Functions
        function openMataPelajaranModal() {
            document.getElementById('mataPelajaranModal').classList.remove('hidden');
            loadMataPelajaranList();
        }

        function closeMataPelajaranModal() {
            document.getElementById('mataPelajaranModal').classList.add('hidden');
        }

        function addMataPelajaran() {
            const newMataPelajaran = document.getElementById('newMataPelajaran').value;

            if (!newMataPelajaran.trim()) {
                showError('<?php echo e(__('common.subject_name_required')); ?>');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '<?php echo e(__('common.loading')); ?>...';
            button.disabled = true;

            fetch('<?php echo e(route('admin.guru.addSubject')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        nama: newMataPelajaran
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
                        // Add to checkbox list
                        const container = document.querySelector('.grid.grid-cols-2.gap-2');
                        const label = document.createElement('label');
                        label.className = 'flex items-center';
                        label.innerHTML = `
                            <input type="checkbox" name="mata_pelajaran[]" value="${data.data.nama}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">${data.data.nama}</span>
                        `;
                        container.appendChild(label);

                        // Update list in modal
                        loadMataPelajaranList();

                        document.getElementById('newMataPelajaran').value = '';
                        showSuccess('<?php echo e(__('common.subject_added_success')); ?>');
                    } else {
                        showError(data.message || '<?php echo e(__('common.error_occurred_adding_subject')); ?>');
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
                        showError('<?php echo e(__('common.error_occurred_adding_subject')); ?>');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        function loadMataPelajaranList() {
            // This would typically fetch from an API endpoint
            // For now, we'll just show a placeholder
            document.getElementById('mataPelajaranList').innerHTML = '<p class="text-gray-500 text-sm"><?php echo e(__('common.loading')); ?>...</p>';
        }

        // User Functions
        function openUserModal() {
            document.getElementById('userModal').classList.remove('hidden');
            loadUserList();
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function addUser() {
            const name = document.getElementById('newUserName').value;
            const email = document.getElementById('newUserEmail').value;
            const password = document.getElementById('newUserPassword').value;
            // Validation
            if (!name.trim()) {
                showError('<?php echo e(__('common.full_name_required')); ?>');
                return;
            }
            if (!email.trim()) {
                showError('<?php echo e(__('common.email_required')); ?>');
                return;
            }
            if (!password.trim()) {
                showError('<?php echo e(__('common.password_required')); ?>');
                return;
            }
            if (password.length < 8) {
                showError('<?php echo e(__('common.password_min_length')); ?>');
                return;
            }

            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '<?php echo e(__('common.loading')); ?>...';
            button.disabled = true;

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
                        roles: ['guru'] // Use role instead of user_type
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

                        // Update list in modal
                        loadUserList();

                        // Clear form
                        document.getElementById('newUserName').value = '';
                        document.getElementById('newUserEmail').value = '';
                        document.getElementById('newUserPassword').value = '';

                        showSuccess('<?php echo e(__('common.user_added_success')); ?>');
                    } else {
                        showError(data.message || '<?php echo e(__('common.error_occurred_adding_user')); ?>');
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
                        showError('<?php echo e(__('common.error_occurred_adding_user')); ?>');
                    }
                })
                .finally(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        function loadUserList() {
            // This would typically fetch from an API endpoint
            // For now, we'll just show a placeholder
            document.getElementById('userList').innerHTML = '<p class="text-gray-500 text-sm"><?php echo e(__('common.loading')); ?>...</p>';
        }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\guru\edit.blade.php ENDPATH**/ ?>