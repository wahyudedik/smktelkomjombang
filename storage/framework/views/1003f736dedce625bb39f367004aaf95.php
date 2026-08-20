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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Manage Testimonial Links')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Header Actions -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                <h3 class="text-lg font-medium text-gray-900">Testimonial Links</h3>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-links.create')): ?>
                    <a href="<?php echo e(route('admin.testimonial-links.create')); ?>"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Create New Link
                    </a>
                <?php endif; ?>
            </div>

            <!-- Links Table -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <?php if($links->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Target Audience</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Active Period</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Submissions</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($link->title); ?>

                                                    </div>
                                                    <?php if($link->description): ?>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo e(Str::limit($link->description, 50)); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-wrap gap-1">
                                                    <?php $__currentLoopData = $link->target_audience; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            <?php if($audience === 'Siswa'): ?> bg-blue-100 text-blue-800
                                                            <?php elseif($audience === 'Guru'): ?> bg-green-100 text-green-800
                                                            <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                                            <?php echo e($audience); ?>

                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>
                                                    <div>From: <?php echo e($link->active_from->format('M d, Y H:i')); ?></div>
                                                    <div>Until: <?php echo e($link->active_until->format('M d, Y H:i')); ?></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    <?php if($link->isCurrentlyActive()): ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Active
                                                        </span>
                                                    <?php elseif($link->active_until < now()): ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-clock mr-1"></i>Expired
                                                        </span>
                                                    <?php else: ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-pause mr-1"></i>Inactive
                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if(!$link->is_active): ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-ban mr-1"></i>Disabled
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>
                                                    <div><?php echo e($link->current_submissions); ?> submissions</div>
                                                    <?php if($link->max_submissions): ?>
                                                        <div class="text-xs">Max: <?php echo e($link->max_submissions); ?></div>
                                                    <?php else: ?>
                                                        <div class="text-xs">Unlimited</div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Copy Link Button -->
                                                    <button onclick="copyLink('<?php echo e($link->getPublicUrl()); ?>')"
                                                        class="text-blue-600 hover:text-blue-900" title="Copy Link">
                                                        <i class="fas fa-copy"></i>
                                                    </button>

                                                    <!-- View Link Button -->
                                                    <a href="<?php echo e($link->getPublicUrl()); ?>" target="_blank"
                                                        class="text-green-600 hover:text-green-900" title="View Link">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-links.edit')): ?>
                                                        <a href="<?php echo e(route('admin.testimonial-links.edit', $link)); ?>"
                                                            class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <!-- Toggle Active Button -->
                                                    <form method="POST"
                                                        action="<?php echo e(route('admin.testimonial-links.toggle-active', $link)); ?>"
                                                        class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit"
                                                            class="<?php echo e($link->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'); ?>"
                                                            title="<?php echo e($link->is_active ? 'Disable' : 'Enable'); ?>">
                                                            <i
                                                                class="fas fa-<?php echo e($link->is_active ? 'pause' : 'play'); ?>"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Delete Button -->
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('testimonial-links.delete')): ?>
                                                        <form method="POST"
                                                            action="<?php echo e(route('admin.testimonial-links.destroy', $link)); ?>"
                                                            class="inline"
                                                            data-confirm="Are you sure you want to delete this testimonial link?">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <?php echo e($links->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-link text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No testimonial links found</h3>
                            <p class="text-gray-500 mb-4">Create your first testimonial link to start collecting
                                testimonials.</p>
                            <a href="<?php echo e(route('admin.testimonial-links.create')); ?>"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Create New Link
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                showSuccess('Berhasil!', 'Link berhasil disalin ke clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showSuccess('Berhasil!', 'Link berhasil disalin ke clipboard');
            });
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\testimonial-links\index.blade.php ENDPATH**/ ?>