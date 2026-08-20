<?php
    // Pass menu data to layout
    $headerMenus = \App\Models\Page::where('is_menu', true)
        ->where('menu_position', 'header')
        ->whereNull('parent_id')
        ->orderBy('menu_sort_order')
        ->with('children')
        ->get();

    $footerMenus = \App\Models\Page::where('is_menu', true)
        ->where('menu_position', 'footer')
        ->whereNull('parent_id')
        ->orderBy('menu_sort_order')
        ->with('children')
        ->get();
?>

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
                <h1 class="text-2xl font-bold text-slate-900">
                    <i class="fab fa-instagram text-pink-600 mr-2"></i>
                    <?php echo e(__('common.instagram_analytics')); ?>

                </h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.instagram_analytics_description')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button id="refreshAnalytics" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span id="refreshText"><?php echo e(__('common.refresh_data')); ?></span>
                </button>
                <a href="<?php echo e(route('admin.superadmin.instagram-settings')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <?php echo e(__('common.settings')); ?>

                </a>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.dashboard')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Last Updated Info -->
        <div class="mb-6 text-right">
            <span class="text-sm text-gray-500">
                <i class="fas fa-clock mr-1"></i>
                <?php echo e(__('common.last_updated')); ?>: <span id="lastUpdated"><?php echo e($analytics['last_updated']->format('d M Y H:i')); ?></span>
            </span>
        </div>

        <!-- Metrics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Posts -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_posts')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($analytics['total_posts']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Likes -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heart text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_likes')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($analytics['total_likes'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Comments -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-comment text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.total_comments')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e(number_format($analytics['total_comments'])); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Engagement Rate -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600"><?php echo e(__('common.engagement_rate')); ?></p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($analytics['engagement_rate']); ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Posts by Day Chart -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.posts_by_day')); ?></h3>
                <canvas id="postsByDayChart" width="400" height="200"></canvas>
            </div>

            <!-- Engagement Chart -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.engagement_metrics')); ?></h3>
                <canvas id="engagementChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Top Performing Posts -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.top_performing_posts')); ?></h3>
            <div class="space-y-4">
                <?php $__currentLoopData = $analytics['top_posts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="flex items-center space-x-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e($post['media_url']); ?>" alt="Post <?php echo e($index + 1); ?>"
                                class="w-16 h-16 object-cover rounded-lg">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-900 font-medium truncate">
                                <?php echo e(Str::limit($post['caption'], 80)); ?>

                            </p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="flex items-center text-sm text-slate-500">
                                    <i class="fas fa-heart text-red-500 mr-1"></i>
                                    <?php echo e(number_format($post['like_count'])); ?>

                                </span>
                                <span class="flex items-center text-sm text-slate-500">
                                    <i class="fas fa-comment text-blue-500 mr-1"></i>
                                    <?php echo e(number_format($post['comment_count'])); ?>

                                </span>
                                <span class="text-xs text-slate-400">
                                    <?php echo e($post['timestamp']->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="<?php echo e($post['permalink']); ?>" target="_blank"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <?php echo e(__('common.view_post')); ?>

                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4"><?php echo e(__('common.account_information')); ?></h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($accountInfo['followers_count'] ?? 0); ?></div>
                    <div class="text-sm text-slate-600"><?php echo e(__('common.followers')); ?></div>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($accountInfo['media_count'] ?? 0); ?></div>
                    <div class="text-sm text-slate-600"><?php echo e(__('common.media_count')); ?></div>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($accountInfo['following_count'] ?? 0); ?></div>
                    <div class="text-sm text-slate-600"><?php echo e(__('common.following')); ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Posts by Day Chart
                const postsByDayCtx = document.getElementById('postsByDayChart').getContext('2d');
                const postsByDayData = <?php echo json_encode($analytics['posts_by_day'], 15, 512) ?>;

                new Chart(postsByDayCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(postsByDayData),
                        datasets: [{
                            label: 'Posts',
                            data: Object.values(postsByDayData),
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Engagement Chart
                const engagementCtx = document.getElementById('engagementChart').getContext('2d');

                new Chart(engagementCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Likes', 'Comments'],
                        datasets: [{
                            data: [<?php echo e($analytics['total_likes']); ?>,
                                <?php echo e($analytics['total_comments']); ?>

                            ],
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.5)',
                                'rgba(59, 130, 246, 0.5)'
                            ],
                            borderColor: [
                                'rgba(239, 68, 68, 1)',
                                'rgba(59, 130, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Refresh Analytics
                document.getElementById('refreshAnalytics').addEventListener('click', function() {
                    const btn = this;
                    const text = document.getElementById('refreshText');
                    const lastUpdated = document.getElementById('lastUpdated');

                    btn.disabled = true;
                    text.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><?php echo e(__('common.refreshing')); ?>';

                    fetch('/instagram/analytics/refresh', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(
                                    `Unexpected response format. Status: ${response.status}`);
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
                                showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.analytics_refresh_failed')); ?>: ' + (result.data
                                    .message || 'Status ' + result.status));
                                return;
                            }

                            if (result.data.success) {
                                lastUpdated.textContent = new Date().toLocaleString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                // Show success notification
                                showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(__('common.analytics_refresh_success')); ?>').then(() => {
                                    // Reload page after 1 second
                                    setTimeout(() => window.location.reload(), 1000);
                                });
                            } else {
                                showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.analytics_refresh_failed')); ?>: ' + (result.data
                                    .message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.analytics_refresh_failed')); ?>: ' + error.message);
                        })
                        .finally(() => {
                            btn.disabled = false;
                            text.innerHTML = '<i class="fas fa-sync-alt mr-2"></i><?php echo e(__('common.refresh_data')); ?>';
                        });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\instagram\analytics.blade.php ENDPATH**/ ?>