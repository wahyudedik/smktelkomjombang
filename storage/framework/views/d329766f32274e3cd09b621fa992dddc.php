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
                <h1 class="text-2xl font-bold text-slate-900">Pengaturan Notifikasi</h1>
                <p class="text-slate-600 mt-1">Kelola preferensi notifikasi Anda</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.notifications')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.notifications.preferences.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Channel Preferences -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">📢 Saluran Notifikasi</h2>
                <p class="text-sm text-slate-500 mb-4">Pilih saluran mana yang ingin Anda aktifkan</p>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[all]" value="1"
                                <?php echo e(($preferences['all'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">Semua Notifikasi</span>
                        </div>
                        <span class="text-xs text-slate-400">Nonaktifkan semua notifikasi</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[email]" value="1"
                                <?php echo e(($preferences['email'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">📧 Email</span>
                        </div>
                        <span class="text-xs text-slate-400">Terima notifikasi via email</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[push]" value="1"
                                <?php echo e(($preferences['push'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">🔔 Push Notification</span>
                        </div>
                        <span class="text-xs text-slate-400">Terima push notification di browser</span>
                    </label>
                </div>
            </div>

            <!-- Category Preferences -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">📁 Kategori Notifikasi</h2>
                <p class="text-sm text-slate-500 mb-4">Pilih kategori notifikasi mana yang ingin Anda terima</p>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[general]" value="1"
                                <?php echo e(($preferences['general'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">📋 Umum</span>
                        </div>
                        <span class="text-xs text-slate-400">Notifikasi umum sistem</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[security]" value="1"
                                <?php echo e(($preferences['security'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">🔒 Keamanan</span>
                        </div>
                        <span class="text-xs text-slate-400">Perubahan password, login baru, dll</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[graduation]" value="1"
                                <?php echo e(($preferences['graduation'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">🎓 Kelulusan</span>
                        </div>
                        <span class="text-xs text-slate-400">Status kelulusan dan informasi terkait</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[voting]" value="1"
                                <?php echo e(($preferences['voting'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">🗳️ Voting OSIS</span>
                        </div>
                        <span class="text-xs text-slate-400">Informasi pemilihan OSIS</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[sarpras]" value="1"
                                <?php echo e(($preferences['sarpras'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">🏫 Sarana & Prasarana</span>
                        </div>
                        <span class="text-xs text-slate-400">Alert inventaris, maintenance, dll</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[announcement]" value="1"
                                <?php echo e(($preferences['announcement'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">📣 Pengumuman</span>
                        </div>
                        <span class="text-xs text-slate-400">Pengumuman dari admin</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[reminder]" value="1"
                                <?php echo e(($preferences['reminder'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">⏰ Pengingat</span>
                        </div>
                        <span class="text-xs text-slate-400">Pengingat tugas dan jadwal</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[approval]" value="1"
                                <?php echo e(($preferences['approval'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">✅ Persetujuan</span>
                        </div>
                        <span class="text-xs text-slate-400">Status persetujuan permohonan</span>
                    </label>

                    <label class="flex items-center justify-between p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <input type="checkbox" name="preferences[data_change]" value="1"
                                <?php echo e(($preferences['data_change'] ?? true) ? 'checked' : ''); ?>

                                class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm font-medium text-slate-700">📝 Perubahan Data</span>
                        </div>
                        <span class="text-xs text-slate-400">Notifikasi saat data Anda diubah</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <form action="<?php echo e(route('admin.notifications.preferences.reset')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <button type="submit" class="px-4 py-2 text-sm text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition"
                        onclick="return confirm('Reset pengaturan ke default?')">
                        🔄 Reset ke Default
                    </button>
                </form>

                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    💾 Simpan Pengaturan
                </button>
            </div>
        </form>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\notifications\preferences.blade.php ENDPATH**/ ?>