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
                <h1 class="text-2xl font-bold text-slate-900">Riwayat Notifikasi</h1>
                <p class="text-slate-600 mt-1">Lihat semua notifikasi yang telah dikirim</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.notifications.preferences')); ?>" class="btn btn-secondary">
                    ⚙️ Pengaturan
                </a>
                <a href="<?php echo e(route('admin.notifications')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Total</p>
                <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['total']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Terkirim</p>
                <p class="text-2xl font-bold text-green-600"><?php echo e($stats['sent']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Gagal</p>
                <p class="text-2xl font-bold text-red-600"><?php echo e($stats['failed']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-sm text-slate-500">Hari Ini</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo e($stats['today']); ?></p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
            <form action="<?php echo e(route('admin.notifications.history')); ?>" method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari notifikasi..."
                    class="flex-1 min-w-[200px] px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">

                <select name="channel"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Saluran</option>
                    <option value="in_app" <?php echo e(request('channel') === 'in_app' ? 'selected' : ''); ?>>In-App</option>
                    <option value="email" <?php echo e(request('channel') === 'email' ? 'selected' : ''); ?>>Email</option>
                    <option value="push" <?php echo e(request('channel') === 'push' ? 'selected' : ''); ?>>Push</option>
                </select>

                <select name="status"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="sent" <?php echo e(request('status') === 'sent' ? 'selected' : ''); ?>>Terkirim</option>
                    <option value="failed" <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>Gagal</option>
                </select>

                <select name="type"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="info" <?php echo e(request('type') === 'info' ? 'selected' : ''); ?>>Info</option>
                    <option value="success" <?php echo e(request('type') === 'success' ? 'selected' : ''); ?>>Success</option>
                    <option value="warning" <?php echo e(request('type') === 'warning' ? 'selected' : ''); ?>>Warning</option>
                    <option value="error" <?php echo e(request('type') === 'error' ? 'selected' : ''); ?>>Error</option>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                    🔍 Filter
                </button>

                <?php if(request()->hasAny(['search', 'channel', 'status', 'type'])): ?>
                    <a href="<?php echo e(route('admin.notifications.history')); ?>"
                        class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm hover:bg-slate-200 transition">
                        Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <?php if($history->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Notifikasi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Saluran</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($item->title); ?></div>
                                        <div class="text-sm text-slate-500 mt-1 line-clamp-1"><?php echo e($item->message); ?>

                                        </div>
                                        <?php if($item->error_message): ?>
                                            <div class="text-xs text-red-500 mt-1">⚠️
                                                <?php echo e(Str::limit($item->error_message, 80)); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($item->channel === 'in_app'): ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                📱 In-App
                                            </span>
                                        <?php elseif($item->channel === 'email'): ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                📧 Email
                                            </span>
                                        <?php elseif($item->channel === 'push'): ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                🔔 Push
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($item->isSent()): ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Terkirim
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Gagal
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                        <div class="text-xs text-slate-400">
                                            <?php echo e($item->created_at->format('d M Y H:i')); ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    <?php echo e($history->withQueryString()->links()); ?>

                </div>
            <?php else: ?>
                <div class="px-6 py-12 text-center">
                    <div class="text-4xl mb-4">📭</div>
                    <p class="text-slate-500">Belum ada riwayat notifikasi</p>
                </div>
            <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\notifications\history.blade.php ENDPATH**/ ?>