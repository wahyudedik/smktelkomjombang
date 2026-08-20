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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.election_results')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.election_results_description')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <?php if(Auth::user()->hasRole('siswa')): ?>
                    <a href="<?php echo e(route('admin.osis.voting')); ?>" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?php echo e(__('common.voting')); ?>

                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('admin.osis.voting')); ?>" class="btn btn-secondary"
                        title="<?php echo e(__('common.only_students_can_vote')); ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?php echo e(__('common.voting_student')); ?>

                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.osis.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.back_to_osis')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_voters')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($totalPemilih); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.already_voted')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($sudahMemilih); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.not_voted_yet')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($belumMemilih); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.participation')); ?></p>
                        <p class="text-2xl font-bold text-slate-900">
                            <?php echo e($totalPemilih > 0 ? round(($sudahMemilih / $totalPemilih) * 100, 1) : 0); ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-slate-900"><?php echo e(__('common.election_results_title')); ?></h2>

            <?php if($calons->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $calons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl border border-slate-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full font-semibold">
                                        <?php echo e($index + 1); ?>

                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">
                                            <?php echo e($candidate->full_candidate_name); ?></h3>
                                        <p class="text-sm text-slate-600"><?php echo e($candidate->pencalonan_type_display); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-slate-900"><?php echo e($candidate->total_votes); ?></p>
                                    <p class="text-sm text-slate-600"><?php echo e(__('common.votes')); ?> (<?php echo e($candidate->vote_percentage); ?>%)</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                                        style="width: <?php echo e($candidate->vote_percentage); ?>%"></div>
                                </div>
                            </div>

                            <!-- Candidate Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Ketua -->
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                                        <?php if($candidate->ketua_photo_url): ?>
                                            <img src="<?php echo e($candidate->ketua_photo_url); ?>"
                                                alt="<?php echo e($candidate->nama_ketua); ?>"
                                                class="w-16 h-16 rounded-full object-cover">
                                        <?php else: ?>
                                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-900"><?php echo e($candidate->nama_ketua); ?></h4>
                                        <p class="text-sm text-slate-600"><?php echo e(__('common.ketua_osis')); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo e($candidate->kelas_ketua); ?></p>
                                    </div>
                                </div>

                                <!-- Wakil -->
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                        <?php if($candidate->wakil_photo_url): ?>
                                            <img src="<?php echo e($candidate->wakil_photo_url); ?>"
                                                alt="<?php echo e($candidate->nama_wakil); ?>"
                                                class="w-16 h-16 rounded-full object-cover">
                                        <?php else: ?>
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-900"><?php echo e($candidate->nama_wakil); ?></h4>
                                        <p class="text-sm text-slate-600"><?php echo e(__('common.wakil_ketua_osis')); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo e($candidate->kelas_wakil); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-slate-500"><?php echo e(__('common.no_election_results')); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Votes -->
        <?php if($recentVotes->count() > 0): ?>
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-4"><?php echo e(__('common.recent_voting')); ?></h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="space-y-3">
                        <?php $__currentLoopData = $recentVotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900"><?php echo e($vote->pemilih->nama); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($vote->created_at->diffForHumans()); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Session Flash Messages -->
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'osis_results_success_' + '<?php echo e(md5(session('success') . time())); ?>';
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
                const errorKey = 'osis_results_error_' + '<?php echo e(md5(session('error') . time())); ?>';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('<?php echo e(session('error')); ?>');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <script>
            const infoKey = 'osis_results_info_' + '<?php echo e(md5(session('info') . time())); ?>';
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\osis\results.blade.php ENDPATH**/ ?>