<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.555a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.43 0l5.01-2.147a1 1 0 00.71-.739 1 1 0 00-.71-1.26l-5.01-2.147a3 3 0 00-2.43 0L7 8.5V5.5a1 1 0 00-1.5-.5L3.5 6.5a1 1 0 00-.5 1.5v8a1 1 0 001.5.5L7 14.5v-1.5a1 1 0 011.5-.5L9.3 16.573z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900">Sekolah</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="text-sm font-medium <?php echo e(request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900'); ?> transition-colors">
                        <?php echo e(__('common.dashboard')); ?>

                    </a>

                    <!-- Academic Management -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin', 'sarpras'])): ?>
                        <div class="relative group">
                            <button
                                class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors flex items-center">
                                Academic
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-1 w-56 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                                Auth::user()->can('guru.view') ||
                                                Auth::user()->can('guru.read'))): ?>
                                        <a href="<?php echo e(route('admin.guru.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-chalkboard-teacher mr-2"></i>Guru Management
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                                Auth::user()->can('siswa.view') ||
                                                Auth::user()->can('siswa.read'))): ?>
                                        <a href="<?php echo e(route('admin.siswa.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-user-graduate mr-2"></i>Siswa Management
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                                Auth::user()->can('jadwal.read') ||
                                                Auth::user()->can('jadwal.view'))): ?>
                                        <a href="<?php echo e(route('admin.jadwal-pelajaran.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-calendar-alt mr-2"></i>Jadwal Pelajaran
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) || Auth::user()->can('attendance.view'))): ?>
                                        <a href="<?php echo e(route('admin.absensi.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-user-check mr-2"></i>Absensi
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['sarpras', 'admin', 'superadmin']) ||
                                                Auth::user()->can('sarpras.view') ||
                                                Auth::user()->can('sarpras.read'))): ?>
                                        <a href="<?php echo e(route('admin.sarpras.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-building mr-2"></i>Sarpras Management
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- E-Services -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin', 'guru', 'osis'])): ?>
                        <div class="relative group">
                            <button
                                class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors flex items-center">
                                E-Services
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['admin', 'superadmin', 'osis']) ||
                                                Auth::user()->can('osis.read') ||
                                                Auth::user()->can('osis.view'))): ?>
                                        <a href="<?php echo e(route('admin.osis.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-vote-yea mr-2"></i>E-OSIS Voting
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin', 'guru'])): ?>
                                        <a href="<?php echo e(route('admin.lulus.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-graduation-cap mr-2"></i>E-Lulus Graduation
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() &&
                                            (Auth::user()->hasAnyRole(['admin', 'superadmin', 'guru', 'sarpras']) || Auth::user()->can('surat.view'))): ?>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <a href="<?php echo e(route('admin.letters.out.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-paper-plane mr-2"></i>Surat Keluar
                                        </a>
                                        <a href="<?php echo e(route('admin.letters.in.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-inbox mr-2"></i>Surat Masuk
                                        </a>
                                        <?php if(Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                                            <a href="<?php echo e(route('admin.letters.formats.index')); ?>"
                                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                <i class="fas fa-file-contract mr-2"></i>Format Surat
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Student Menu (Siswa) -->
                    <?php if(Auth::check() && Auth::user()->hasRole('siswa')): ?>
                        <div class="relative group">
                            <button
                                class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors flex items-center">
                                Student
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('osis.vote'))): ?>
                                        <a href="<?php echo e(route('admin.osis.voting')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-vote-yea mr-2"></i>OSIS Voting
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('osis.results'))): ?>
                                        <a href="<?php echo e(route('admin.osis.results')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-chart-bar mr-2"></i>Voting Results
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('jadwal.read'))): ?>
                                        <a href="<?php echo e(route('admin.jadwal-pelajaran.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-calendar-alt mr-2"></i>Jadwal Pelajaran
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('lulus.read'))): ?>
                                        <a href="<?php echo e(route('admin.lulus.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-graduation-cap mr-2"></i>Kelulusan
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Content Management -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                        <div class="relative group">
                            <button
                                class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors flex items-center">
                                Content
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="<?php echo e(route('landing')); ?>"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <i class="fas fa-globe mr-2"></i>Landing Page
                                    </a>
                                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                                        <a href="<?php echo e(route('admin.pages.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-file-alt mr-2"></i>Page Management
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && Auth::user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.superadmin.instagram-settings')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fab fa-instagram mr-2"></i><?php echo e(__('common.instagram_settings')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- System Management (Superadmin & Admin) -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['superadmin', 'admin'])): ?>
                        <div class="relative group">
                            <button
                                class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors flex items-center">
                                System
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <?php if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))): ?>
                                        <a href="<?php echo e(route('admin.superadmin.users')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-users-cog mr-2"></i>User Management
                                        </a>
                                    <?php elseif(auth()->user() &&
                                            (auth()->user()->can('users.view') ||
                                                auth()->user()->can('users.create') ||
                                                auth()->user()->can('users.edit') ||
                                                auth()->user()->can('users.delete'))): ?>
                                        <a href="<?php echo e(route('admin.user-management.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-users mr-2"></i>User Management
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.roles.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-user-shield mr-2"></i>Role Management
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.role-permissions.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-shield-alt mr-2"></i>Role & Permissions
                                        </a>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->hasRole('superadmin')): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', App\Models\Permission::class)): ?>
                                            <a href="<?php echo e(route('admin.permissions.index')); ?>"
                                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                <i class="fas fa-key mr-2"></i>Permission Management
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.audit-logs.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-history mr-2"></i>Audit Logs
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAnalytics', App\Models\User::class)): ?>
                                        <a href="<?php echo e(route('admin.analytics')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-chart-line mr-2"></i><?php echo e(__('common.analytics_dashboard')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewSystemHealth', App\Models\User::class)): ?>
                                        <a href="<?php echo e(route('admin.system.health')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-heartbeat mr-2"></i><?php echo e(__('common.system_health')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewNotifications', App\Models\User::class)): ?>
                                        <a href="<?php echo e(route('admin.notifications')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-bell mr-2"></i><?php echo e(__('common.notification_center')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['testimonials.view', 'testimonials.create', 'testimonials.edit',
                                        'testimonials.delete'])): ?>
                                        <a href="<?php echo e(route('admin.testimonials.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-comments mr-2"></i><?php echo e(__('common.manage_testimonials')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-links.view')): ?>
                                        <a href="<?php echo e(route('admin.testimonial-links.index')); ?>"
                                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <i class="fas fa-link mr-2"></i><?php echo e(__('common.testimonial_links')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('admin.settings.index')); ?>"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <i class="fas fa-cog mr-2"></i><?php echo e(__('common.system_settings')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- User Profile Dropdown -->
            <?php if(auth()->guard()->check()): ?>
                <div class="flex items-center space-x-4">
                    <!-- Notifications Dropdown -->
                    <?php
                        $unreadNotifications = DB::table('notifications')
                            ->where('notifiable_id', Auth::id())
                            ->where('notifiable_type', 'App\Models\User')
                            ->whereNull('read_at')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                        $unreadCount = $unreadNotifications->count();
                    ?>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-2 text-slate-600 hover:text-slate-900 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <?php if($unreadCount > 0): ?>
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                    <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

                                </span>
                            <?php endif; ?>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-slate-200 z-50"
                            style="display: none;">

                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-slate-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                                    <?php if($unreadCount > 0): ?>
                                        <form action="<?php echo e(route('admin.notifications.mark-all-read')); ?>" method="POST"
                                            class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                                Mark all as read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Notification List -->
                            <div class="max-h-96 overflow-y-auto">
                                <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $data = json_decode($notification->data);
                                        $notifType = $data->type ?? 'info';
                                        $iconColor = match ($notifType) {
                                            'success' => 'text-green-600 bg-green-100',
                                            'warning' => 'text-yellow-600 bg-yellow-100',
                                            'error' => 'text-red-600 bg-red-100',
                                            default => 'text-blue-600 bg-blue-100',
                                        };
                                    ?>
                                    <a href="<?php echo e(route('admin.notifications')); ?>"
                                        class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 rounded-full <?php echo e($iconColor); ?> flex items-center justify-center">
                                                    <i class="fas fa-bell text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 truncate">
                                                    <?php echo e($data->title ?? 'Notification'); ?>

                                                </p>
                                                <p class="text-xs text-slate-600 line-clamp-2 mt-1">
                                                    <?php echo e($data->message ?? 'No message'); ?>

                                                </p>
                                                <p class="text-xs text-slate-400 mt-1">
                                                    <?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="px-4 py-8 text-center">
                                        <i class="fas fa-bell-slash text-3xl text-slate-300 mb-2"></i>
                                        <p class="text-sm text-slate-600"><?php echo e(__('common.no_new_notifications')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
                                <a href="<?php echo e(route('admin.notifications')); ?>"
                                    class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-medium text-slate-900"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-slate-500">
                                    <?php echo e(ucfirst(Auth::user()->getRoleNames()->first() ?? 'User')); ?></p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-slate-200 z-50">
                            <div class="py-2">
                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-medium text-slate-900"><?php echo e(Auth::user()->name); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo e(Auth::user()->email); ?></p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        <?php echo e(ucfirst(Auth::user()->getRoleNames()->first() ?? 'User')); ?></p>
                                </div>

                                <!-- Profile Actions -->
                                <div class="py-2">
                                    <a href="<?php echo e(route('admin.profile.edit')); ?>"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php echo e(__('common.profile')); ?> <?php echo e(__('common.settings')); ?>

                                    </a>
                                    <a href="<?php echo e(route('landing')); ?>" target="_blank"
                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        <?php echo e(__('common.view_website')); ?>

                                    </a>
                                </div>

                                <!-- Language Switcher -->
                                <div class="py-2 border-t border-slate-100">
                                    <div class="px-4 py-2">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                            <?php echo e(__('common.language')); ?>

                                        </p>
                                    </div>
                                    <?php
                                        $currentLocale = app()->getLocale();
                                        $availableLocales = function_exists('get_available_locales')
                                            ? get_available_locales()
                                            : config('i18n.locales', []);
                                    ?>
                                    <?php $__currentLoopData = $availableLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('locale.switch', $code)); ?>"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors <?php echo e($code === $currentLocale ? 'bg-slate-50 font-medium' : ''); ?>">
                                            <span class="mr-3"><?php echo e($locale['flag']); ?></span>
                                            <span><?php echo e($locale['native']); ?></span>
                                            <?php if($code === $currentLocale): ?>
                                                <svg class="w-4 h-4 ml-auto text-blue-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- Timezone Switcher -->
                                <div class="py-2 border-t border-slate-100">
                                    <div class="px-4 py-2">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                            <?php echo e(__('common.timezone')); ?>

                                        </p>
                                    </div>
                                    <?php
                                        $currentTimezone = session(
                                            'timezone',
                                            Auth::check() && Auth::user()
                                                ? Auth::user()->timezone ??
                                                    config('i18n.default_timezone', 'Asia/Jakarta')
                                                : config('i18n.default_timezone', 'Asia/Jakarta'),
                                        );
                                        $availableTimezones = config('i18n.timezones', []);
                                    ?>
                                    <form method="POST" action="<?php echo e(route('timezone.switch')); ?>" id="timezone-form">
                                        <?php echo csrf_field(); ?>
                                        <select name="timezone"
                                            onchange="document.getElementById('timezone-form').submit();"
                                            class="w-full px-4 py-2 text-sm text-slate-700 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <?php $__currentLoopData = $availableTimezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region => $timezones): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($region); ?>">
                                                    <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($tz); ?>"
                                                            <?php echo e($tz === $currentTimezone ? 'selected' : ''); ?>>
                                                            <?php echo e($label); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </form>
                                </div>

                                <!-- Quick Settings -->
                                <div class="py-2 border-t border-slate-100">
                                    <div class="px-4 py-2">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                            <?php echo e(__('common.quick_access')); ?>

                                        </p>
                                    </div>
                                    <?php if(Auth::check() && Auth::user()->hasRole('superadmin')): ?>
                                        <a href="<?php echo e(route('admin.superadmin.instagram-settings')); ?>"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001z" />
                                            </svg>
                                            Instagram Settings
                                        </a>
                                    <?php endif; ?>
                                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                                        <a href="<?php echo e(route('admin.settings.index')); ?>"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            System Settings
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Logout -->
                                <div class="py-2 border-t border-slate-100">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <?php echo e(__('common.logout')); ?>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <?php if(auth()->guard()->check()): ?>
            <div x-show="open" x-transition class="md:hidden border-t border-slate-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'); ?>">
                        Dashboard
                    </a>

                    <!-- Academic Management -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin', 'sarpras'])): ?>
                        <div class="px-3 py-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Academic
                                Management
                            </div>
                            <div class="space-y-1 ml-2">
                                <?php if(Auth::check() &&
                                        (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                            Auth::user()->can('guru.view') ||
                                            Auth::user()->can('guru.read'))): ?>
                                    <a href="<?php echo e(route('admin.guru.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-chalkboard-teacher mr-2"></i>Guru Management
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() &&
                                        (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                            Auth::user()->can('siswa.view') ||
                                            Auth::user()->can('siswa.read'))): ?>
                                    <a href="<?php echo e(route('admin.siswa.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-user-graduate mr-2"></i>Siswa Management
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() &&
                                        (Auth::user()->hasAnyRole(['guru', 'admin', 'superadmin']) ||
                                            Auth::user()->can('jadwal.read') ||
                                            Auth::user()->can('jadwal.view'))): ?>
                                    <a href="<?php echo e(route('admin.jadwal-pelajaran.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Pelajaran
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() &&
                                        (Auth::user()->hasAnyRole(['sarpras', 'admin', 'superadmin']) ||
                                            Auth::user()->can('sarpras.view') ||
                                            Auth::user()->can('sarpras.read'))): ?>
                                    <a href="<?php echo e(route('admin.sarpras.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-building mr-2"></i>Sarpras Management
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- E-Services -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin', 'guru', 'osis'])): ?>
                        <div class="px-3 py-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">E-Services</div>
                            <div class="space-y-1 ml-2">
                                <?php if(Auth::check() &&
                                        (Auth::user()->hasAnyRole(['admin', 'superadmin', 'osis']) ||
                                            Auth::user()->can('osis.read') ||
                                            Auth::user()->can('osis.view'))): ?>
                                    <a href="<?php echo e(route('admin.osis.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-vote-yea mr-2"></i>E-OSIS Voting
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin', 'guru'])): ?>
                                    <a href="<?php echo e(route('admin.lulus.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-graduation-cap mr-2"></i>E-Lulus Graduation
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Student Menu (Siswa) -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['siswa'])): ?>
                        <div class="px-3 py-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Student</div>
                            <div class="space-y-1 ml-2">
                                <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('osis.vote'))): ?>
                                    <a href="<?php echo e(route('admin.osis.voting')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-vote-yea mr-2"></i>OSIS Voting
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() && (Auth::user()->hasRole('siswa') || Auth::user()->can('osis.results'))): ?>
                                    <a href="<?php echo e(route('admin.osis.results')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-chart-bar mr-2"></i>Voting Results
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() && Auth::user()->hasRole('siswa')): ?>
                                    <a href="<?php echo e(route('admin.siswa.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-user-graduate mr-2"></i>View Students
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Content Management -->
                    <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                        <div class="px-3 py-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Content
                                Management
                            </div>
                            <div class="space-y-1 ml-2">
                                <a href="<?php echo e(route('landing')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-globe mr-2"></i>Landing Page
                                </a>
                                <?php if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'superadmin'])): ?>
                                    <a href="<?php echo e(route('admin.pages.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-file-alt mr-2"></i>Page Management
                                    </a>
                                <?php endif; ?>
                                <?php if(Auth::check() && Auth::user()->hasRole('superadmin')): ?>
                                    <a href="<?php echo e(route('admin.superadmin.instagram-settings')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fab fa-instagram mr-2"></i>Instagram Settings
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- System Management -->
                    <?php if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))): ?>
                        <div class="px-3 py-2">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">System
                                Management</div>
                            <div class="space-y-1 ml-2">
                                <?php if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('admin'))): ?>
                                    <a href="<?php echo e(route('admin.superadmin.users')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-users-cog mr-2"></i>User Management
                                    </a>
                                <?php elseif(auth()->user() &&
                                        (auth()->user()->can('users.view') ||
                                            auth()->user()->can('users.create') ||
                                            auth()->user()->can('users.edit') ||
                                            auth()->user()->can('users.delete'))): ?>
                                    <a href="<?php echo e(route('admin.user-management.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-users mr-2"></i>User Management
                                    </a>
                                <?php endif; ?>
                                <?php if(auth()->user()->hasRole('superadmin')): ?>
                                    <a href="<?php echo e(route('admin.roles.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-user-shield mr-2"></i>Role Management
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.role-permissions.index')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-shield-alt mr-2"></i>Role & Permissions
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', App\Models\Permission::class)): ?>
                                    <a href="<?php echo e(route('admin.permissions.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-key mr-2"></i>Permission Management
                                    </a>
                                <?php endif; ?>
                                <?php if(auth()->user()->hasRole('superadmin')): ?>
                                    <a href="<?php echo e(route('admin.audit-logs.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-history mr-2"></i>Audit Logs
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.analytics')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-chart-line mr-2"></i>Analytics Dashboard
                                </a>
                                <a href="<?php echo e(route('admin.system.health')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-heartbeat mr-2"></i>System Health
                                </a>
                                <a href="<?php echo e(route('admin.notifications')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-bell mr-2"></i>Notification Center
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['testimonials.view', 'testimonials.create', 'testimonials.edit',
                                    'testimonials.delete'])): ?>
                                    <a href="<?php echo e(route('admin.testimonials.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-comments mr-2"></i>Manage Testimonials
                                    </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-links.view')): ?>
                                    <a href="<?php echo e(route('admin.testimonial-links.index')); ?>"
                                        class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <i class="fas fa-link mr-2"></i>Testimonial Links
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.settings.index')); ?>"
                                    class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg">
                                    <i class="fas fa-cog mr-2"></i>System Settings
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php /**PATH D:\PROJECT\LARAVEL\ig-to-web\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>