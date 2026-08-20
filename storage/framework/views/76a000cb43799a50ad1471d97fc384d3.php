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
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <?php echo e(__('Audit Log Details')); ?>

                </h2>
                <p class="text-sm text-gray-600 mt-1">Viewing audit log #<?php echo e($auditLog->id); ?></p>
            </div>
            <a href="<?php echo e(route('admin.audit-logs.index')); ?>" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Action</h3>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php if($auditLog->action == 'create'): ?> bg-green-100 text-green-800
                                    <?php elseif($auditLog->action == 'update'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($auditLog->action == 'delete'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($auditLog->action)); ?>

                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Time</h3>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($auditLog->created_at->format('Y-m-d H:i:s')); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">User</h3>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($auditLog->user?->name ?? 'System'); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">IP Address</h3>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($auditLog->ip_address); ?></p>
                        </div>
                        <?php if($auditLog->model_type): ?>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Model Type</h3>
                                <p class="mt-1 text-sm text-gray-900"><?php echo e(class_basename($auditLog->model_type)); ?></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Model ID</h3>
                                <p class="mt-1 text-sm text-gray-900">#<?php echo e($auditLog->model_id); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- User Agent -->
                    <?php if($auditLog->user_agent): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">User Agent</h3>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded"><?php echo e($auditLog->user_agent); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Old Values -->
                    <?php if($auditLog->old_values): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Old Values</h3>
                            <div class="bg-red-50 p-4 rounded">
                                <pre class="text-sm text-gray-700"><?php echo e(json_encode($auditLog->old_values, JSON_PRETTY_PRINT)); ?></pre>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- New Values -->
                    <?php if($auditLog->new_values): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">New Values</h3>
                            <div class="bg-green-50 p-4 rounded">
                                <pre class="text-sm text-gray-700"><?php echo e(json_encode($auditLog->new_values, JSON_PRETTY_PRINT)); ?></pre>
                            </div>
                        </div>
                    <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\audit-logs\show.blade.php ENDPATH**/ ?>