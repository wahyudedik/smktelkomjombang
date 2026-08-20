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
                <h1 class="text-2xl font-bold text-slate-900">User Details</h1>
                <p class="text-slate-600 mt-1"><?php echo e($user->name); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.superadmin.users.edit', $user)); ?>" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                </a>
                <a href="<?php echo e(route('admin.superadmin.users')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">User Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-slate-500">Full Name</label>
                                <p class="mt-1 text-slate-900"><?php echo e($user->name); ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Email Address</label>
                                <p class="mt-1 text-slate-900"><?php echo e($user->email); ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Email Status</label>
                                <p class="mt-1">
                                    <span
                                        class="badge <?php echo e($user->email_verified_at ? 'badge-success' : 'badge-warning'); ?>">
                                        <?php echo e($user->email_verified_at ? 'Verified' : 'Unverified'); ?>

                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Created At</label>
                                <p class="mt-1 text-slate-900"><?php echo e($user->created_at->format('M d, Y H:i')); ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Last Updated</label>
                                <p class="mt-1 text-slate-900"><?php echo e($user->updated_at->format('M d, Y H:i')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Assigned Roles</h3>
                    </div>
                    <div class="card-body">
                        <?php if($user->roles->count() > 0): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-success"><?php echo e($role->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-500">No roles assigned</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Role & Permissions -->
                <div class="card mt-6">
                    <div class="card-header">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h3 class="text-lg font-semibold text-slate-900">Role & Permissions</h3>
                            <a href="<?php echo e(route('admin.role-permissions.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-shield-alt mr-1"></i> Manage Roles
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Current Role -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Current Role</p>
                            <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-shield mr-1"></i> <?php echo e($role->display_name ?? ucfirst($role->name)); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-sm text-slate-500 italic">No role assigned</span>
                            <?php endif; ?>
                        </div>
                        <!-- Permissions grouped by module -->
                        <div>
                            <p class="text-sm font-medium text-slate-700 mb-2">Permissions via Role</p>
                            <?php
                                $userPermissions = $user->getAllPermissions()->groupBy('module');
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $userPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-slate-500 uppercase mb-1"><?php echo e(ucfirst($module ?? 'other')); ?></p>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <?php echo e($perm->action ?? $perm->name); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-slate-500 italic">No permissions assigned</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Avatar -->
                <div class="card">
                    <div class="card-body text-center">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white"><?php echo e(substr($user->name, 0, 1)); ?></span>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900"><?php echo e($user->name); ?></h3>
                        <p class="text-slate-500"><?php echo e($user->email); ?></p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <a href="<?php echo e(route('admin.superadmin.users.edit', $user)); ?>"
                            class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="font-medium text-slate-900">Edit User</span>
                        </a>
                        <?php if(!$user->hasRole('superadmin')): ?>
                            <form method="POST" action="<?php echo e(route('admin.superadmin.users.destroy', $user)); ?>"
                                data-confirm="Are you sure you want to delete this user?">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                    class="w-full flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-left">
                                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="font-medium text-slate-900">Delete User</span>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\superadmin\users\show.blade.php ENDPATH**/ ?>