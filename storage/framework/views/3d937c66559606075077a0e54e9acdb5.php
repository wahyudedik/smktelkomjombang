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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.osis_teacher_view_title')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.teacher_view_description')); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.osis.results')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <?php echo e(__('common.view_results')); ?>

                </a>
                <a href="<?php echo e(route('admin.osis.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.back')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php if($calons->count() > 0): ?>
            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800"><?php echo e(__('common.teacher_info_title')); ?></h3>
                        <p class="text-sm text-blue-700 mt-1">
                            <?php echo e(__('common.teacher_info_description')); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Candidates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-semibold"><?php echo e($calon->full_candidate_name); ?></h3>
                                    <p class="text-blue-100 text-sm"><?php echo e($calon->pencalonan_type_display); ?></p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php echo e($calon->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'); ?>">
                                        <?php echo e($calon->gender_display); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Photos -->
                            <div class="flex justify-center space-x-4 mb-4">
                                <?php if($calon->foto_ketua): ?>
                                    <div class="text-center">
                                        <img src="<?php echo e($calon->ketua_photo_url); ?>" alt="<?php echo e($calon->nama_ketua); ?>"
                                            class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-slate-200">
                                        <p class="text-xs text-slate-600"><?php echo e(__('common.ketua')); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if($calon->foto_wakil && $calon->nama_wakil): ?>
                                    <div class="text-center">
                                        <img src="<?php echo e($calon->wakil_photo_url); ?>" alt="<?php echo e($calon->nama_wakil); ?>"
                                            class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-slate-200">
                                        <p class="text-xs text-slate-600"><?php echo e(__('common.wakil')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Visi Misi Preview -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-slate-900 mb-2"><?php echo e(__('common.vision_mission')); ?></h4>
                                <p class="text-sm text-slate-600 line-clamp-3">
                                    <?php echo e(Str::limit($calon->visi_misi, 150)); ?>

                                </p>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <?php echo e($calon->votings_count ?? 0); ?> <?php echo e(__('common.votes')); ?>

                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    <?php echo e($calon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($calon->is_active ? __('common.status_active') : __('common.status_inactive')); ?>

                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('admin.osis.calon.show', $calon)); ?>"
                                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                        <?php echo e(__('common.view_details')); ?>

                                    </a>
                                    <?php if(Auth::user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.osis.calon.edit', $calon)); ?>"
                                            class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                            <?php echo e(__('common.edit')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Summary -->
            <div class="mt-8 bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.candidate_summary')); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600"><?php echo e($calons->count()); ?></div>
                        <div class="text-sm text-blue-700"><?php echo e(__('common.total_calon')); ?></div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600"><?php echo e($calons->where('is_active', true)->count()); ?>

                        </div>
                        <div class="text-sm text-green-700"><?php echo e(__('common.active_candidates')); ?></div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600"><?php echo e($calons->sum('votings_count')); ?></div>
                        <div class="text-sm text-purple-700"><?php echo e(__('common.total_suara')); ?></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-2"><?php echo e(__('common.no_candidates')); ?></h3>
                <p class="text-slate-600 mb-6"><?php echo e(__('common.no_candidates_message')); ?></p>
                <?php if(Auth::user()->hasRole('superadmin')): ?>
                    <a href="<?php echo e(route('admin.osis.calon.create')); ?>" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <?php echo e(__('common.add_first_candidate')); ?>

                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Session Flash Messages -->
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'osis_teacher_view_success_' + '<?php echo e(md5(session('success') . time())); ?>';
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
                const errorKey = 'osis_teacher_view_error_' + '<?php echo e(md5(session('error') . time())); ?>';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <script>
            const infoKey = 'osis_teacher_view_info_' + '<?php echo e(md5(session('info') . time())); ?>';
            if (!sessionStorage.getItem(infoKey)) {
                showAlert('Info', '<?php echo e(session('info')); ?>', 'info');
                sessionStorage.setItem(infoKey, 'shown');
            }
        </script>
    <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\osis\teacher-view.blade.php ENDPATH**/ ?>