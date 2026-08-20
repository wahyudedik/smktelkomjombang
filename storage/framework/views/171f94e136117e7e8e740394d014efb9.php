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
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900"><?php echo e(__('common.seo_settings_title')); ?></h1>
                <p class="text-gray-600 mt-2"><?php echo e(__('common.manage_seo_settings_description')); ?></p>
            </div>

            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.settings.seo.update')); ?>" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                <?php echo csrf_field(); ?>

                <!-- Page Selection -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('common.select_page')); ?></h2>
                    <div>
                        <label for="page_id" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.choose_page')); ?></label>
                        <select id="page_id" name="page_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value=""><?php echo e(__('common.select_page_configure_seo')); ?></option>
                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($page->id); ?>" <?php echo e(old('page_id') == $page->id ? 'selected' : ''); ?>>
                                    <?php echo e($page->title); ?> (<?php echo e($page->slug); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <!-- Meta Tags -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('common.meta_tags')); ?></h2>
                    <div class="space-y-6">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.meta_title')); ?></label>
                            <input type="text" id="meta_title" name="meta_title" value="<?php echo e(old('meta_title')); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Page title for search engines (max 60 characters)" maxlength="60">
                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.recommended_characters')); ?></p>
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.meta_description')); ?></label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Brief description of the page content (max 160 characters)" maxlength="160"><?php echo e(old('meta_description')); ?></textarea>
                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.recommended_description_characters')); ?></p>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.meta_keywords')); ?></label>
                            <input type="text" id="meta_keywords" name="meta_keywords"
                                value="<?php echo e(old('meta_keywords')); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="sekolah, pendidikan, madrasah (comma separated)">
                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.separate_keywords_commas')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Open Graph -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('common.open_graph')); ?></h2>
                    <div class="space-y-6">
                        <div>
                            <label for="og_title" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.og_title')); ?></label>
                            <input type="text" id="og_title" name="og_title" value="<?php echo e(old('og_title')); ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Title for social media sharing">
                        </div>

                        <div>
                            <label for="og_description" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.og_description')); ?></label>
                            <textarea id="og_description" name="og_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Description for social media sharing"><?php echo e(old('og_description')); ?></textarea>
                        </div>

                        <div>
                            <label for="og_image" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.og_image')); ?></label>
                            <input type="file" id="og_image" name="og_image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.recommended_size')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- SEO Tips -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3"><?php echo e(__('common.seo_best_practices')); ?></h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Use descriptive, keyword-rich titles
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Write compelling meta descriptions that encourage clicks
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Include relevant keywords naturally in your content
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Use high-quality images for Open Graph sharing
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <?php echo e(__('common.save_seo_settings')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-populate form when page is selected
        document.getElementById('page_id').addEventListener('change', function() {
            const pageId = this.value;
            if (pageId) {
                // You can add AJAX call here to fetch existing SEO data for the selected page
                // For now, we'll just clear the form
                document.getElementById('meta_title').value = '';
                document.getElementById('meta_description').value = '';
                document.getElementById('meta_keywords').value = '';
                document.getElementById('og_title').value = '';
                document.getElementById('og_description').value = '';
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\settings\seo.blade.php ENDPATH**/ ?>