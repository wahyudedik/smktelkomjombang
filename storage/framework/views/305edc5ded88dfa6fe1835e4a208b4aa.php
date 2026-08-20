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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.edit_calon_osis')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e($calon->full_candidate_name); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.osis.calon.show', $calon)); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <?php echo e(__('common.view_details')); ?>

                </a>
                <a href="<?php echo e(route('admin.osis.calon.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.back')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="<?php echo e(route('admin.osis.calon.update', $calon)); ?>" enctype="multipart/form-data"
                class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Ketua Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.chairman_info')); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_ketua" class="form-label"><?php echo e(__('common.name')); ?> <?php echo e(__('common.ketua')); ?></label>
                            <select name="nama_ketua" id="nama_ketua" required
                                class="form-input <?php $__errorArgs = ['nama_ketua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('common.select_student_name')); ?></option>
                                <?php $__currentLoopData = $siswas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($siswa->nama_lengkap); ?>" data-nis="<?php echo e($siswa->nis); ?>"
                                        data-kelas="<?php echo e($siswa->kelas); ?>" data-email="<?php echo e($siswa->email); ?>"
                                        data-jenis-kelamin="<?php echo e($siswa->jenis_kelamin); ?>"
                                        <?php echo e(old('nama_ketua', $calon->nama_ketua) == $siswa->nama_lengkap ? 'selected' : ''); ?>>
                                        <?php echo e($siswa->nama_lengkap); ?> - <?php echo e($siswa->nis); ?> - <?php echo e($siswa->kelas); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['nama_ketua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="kelas_ketua" class="form-label"><?php echo e(__('common.select_class')); ?> <?php echo e(__('common.ketua')); ?></label>
                            <select name="kelas_ketua" id="kelas_ketua" required
                                class="form-input <?php $__errorArgs = ['kelas_ketua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('common.select_class')); ?></option>
                                <?php $__currentLoopData = $kelas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>"
                                        <?php echo e(old('kelas_ketua', $calon->kelas_ketua) == $k ? 'selected' : ''); ?>>
                                        <?php echo e($k); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kelas_ketua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="ketua_photo" class="form-label"><?php echo e(__('common.chairman_photo')); ?></label>
                            <input type="file" id="ketua_photo" name="ketua_photo" accept="image/*"
                                class="form-input <?php $__errorArgs = ['ketua_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['ketua_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if($calon->ketua_photo_url): ?>
                                <div class="mt-2">
                                    <img src="<?php echo e($calon->ketua_photo_url); ?>" alt="<?php echo e(__('common.current_photo')); ?>"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1"><?php echo e(__('common.current_photo')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Wakil Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.vice_chairman_info')); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_wakil" class="form-label"><?php echo e(__('common.vice_chairman_name')); ?></label>
                            <select name="nama_wakil" id="nama_wakil" required
                                class="form-input <?php $__errorArgs = ['nama_wakil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('common.select_student_name')); ?></option>
                                <?php $__currentLoopData = $siswas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($siswa->nama_lengkap); ?>" data-nis="<?php echo e($siswa->nis); ?>"
                                        data-kelas="<?php echo e($siswa->kelas); ?>" data-email="<?php echo e($siswa->email); ?>"
                                        data-jenis-kelamin="<?php echo e($siswa->jenis_kelamin); ?>"
                                        <?php echo e(old('nama_wakil', $calon->nama_wakil) == $siswa->nama_lengkap ? 'selected' : ''); ?>>
                                        <?php echo e($siswa->nama_lengkap); ?> - <?php echo e($siswa->nis); ?> - <?php echo e($siswa->kelas); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['nama_wakil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="kelas_wakil" class="form-label"><?php echo e(__('common.vice_chairman_class')); ?></label>
                            <select name="kelas_wakil" id="kelas_wakil" required
                                class="form-input <?php $__errorArgs = ['kelas_wakil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('common.select_class')); ?></option>
                                <?php $__currentLoopData = $kelas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k); ?>"
                                        <?php echo e(old('kelas_wakil', $calon->kelas_wakil) == $k ? 'selected' : ''); ?>>
                                        <?php echo e($k); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kelas_wakil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="wakil_photo" class="form-label"><?php echo e(__('common.vice_chairman_photo')); ?></label>
                            <input type="file" id="wakil_photo" name="wakil_photo" accept="image/*"
                                class="form-input <?php $__errorArgs = ['wakil_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['wakil_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if($calon->wakil_photo_url): ?>
                                <div class="mt-2">
                                    <img src="<?php echo e($calon->wakil_photo_url); ?>" alt="<?php echo e(__('common.current_photo')); ?>"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <p class="text-xs text-slate-500 mt-1"><?php echo e(__('common.current_photo')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Visi Misi Section -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.vision_mission')); ?></h3>
                    <div>
                        <label for="visi_misi" class="form-label"><?php echo e(__('common.vision_mission')); ?></label>
                        <textarea id="visi_misi" name="visi_misi" rows="8"
                            class="form-input <?php $__errorArgs = ['visi_misi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="<?php echo e(__('common.enter_vision_mission')); ?>"><?php echo e(old('visi_misi', $calon->visi_misi)); ?></textarea>
                        <?php $__errorArgs = ['visi_misi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Settings Section -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.settings')); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jenis_kelamin" class="form-label"><?php echo e(__('common.gender_required')); ?></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="form-input <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('common.select_gender')); ?></option>
                                <option value="L"
                                    <?php echo e(old('jenis_kelamin', $calon->jenis_kelamin) === 'L' ? 'selected' : ''); ?>>
                                    <?php echo e(__('common.laki_laki')); ?></option>
                                <option value="P"
                                    <?php echo e(old('jenis_kelamin', $calon->jenis_kelamin) === 'P' ? 'selected' : ''); ?>>
                                    <?php echo e(__('common.perempuan')); ?></option>
                            </select>
                            <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-sm text-slate-600 mt-1"><?php echo e(__('common.select_gender_hint')); ?></p>
                        </div>

                        <div>
                            <label for="pencalonan_type" class="form-label"><?php echo e(__('common.jenis_pencalonan')); ?></label>
                            <select id="pencalonan_type" name="pencalonan_type"
                                class="form-input <?php $__errorArgs = ['pencalonan_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="individu"
                                    <?php echo e(old('pencalonan_type', $calon->pencalonan_type) == 'individu' ? 'selected' : ''); ?>>
                                    <?php echo e(__('common.individual')); ?></option>
                                <option value="pasangan"
                                    <?php echo e(old('pencalonan_type', $calon->pencalonan_type) == 'pasangan' ? 'selected' : ''); ?>>
                                    <?php echo e(__('common.pasangan')); ?></option>
                            </select>
                            <?php $__errorArgs = ['pencalonan_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                <?php echo e(old('is_active', $calon->is_active) ? 'checked' : ''); ?>

                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-slate-700"><?php echo e(__('common.active_in_election')); ?></label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="<?php echo e(route('admin.osis.calon.show', $calon)); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <?php echo e(__('common.cancel')); ?>

                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <?php echo e(__('common.save_changes')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Session Flash Messages -->
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'calon_edit_success_' + '<?php echo e(md5(session('success') . time())); ?>';
                if (!sessionStorage.getItem(successKey) && typeof showSuccess !== 'undefined') {
                    showSuccess('<?php echo e(session('success')); ?>');
                    sessionStorage.setItem(successKey, 'shown');
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorKey = 'calon_edit_error_' + '<?php echo e(md5(session('error') . time())); ?>';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorsKey = 'calon_edit_errors_' + '<?php echo e(md5(json_encode($errors->all()) . time())); ?>';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError('<?php echo e($errors->first()); ?>');
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-fill kelas when nama_ketua is selected
            const namaKetuaSelect = document.getElementById('nama_ketua');
            const kelasKetuaSelect = document.getElementById('kelas_ketua');

            if (namaKetuaSelect && kelasKetuaSelect) {
                namaKetuaSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const kelas = selectedOption.getAttribute('data-kelas');
                        if (kelas) {
                            kelasKetuaSelect.value = kelas;
                        }
                    }
                });
            }

            // Auto-fill kelas when nama_wakil is selected
            const namaWakilSelect = document.getElementById('nama_wakil');
            const kelasWakilSelect = document.getElementById('kelas_wakil');

            if (namaWakilSelect && kelasWakilSelect) {
                namaWakilSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const kelas = selectedOption.getAttribute('data-kelas');
                        if (kelas) {
                            kelasWakilSelect.value = kelas;
                        }
                    }
                });
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\osis\calon\edit.blade.php ENDPATH**/ ?>