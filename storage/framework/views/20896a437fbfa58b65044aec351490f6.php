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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(str_replace(':title', $page->title, __('common.edit_page'))); ?>

            </h2>
            <a href="<?php echo e(route('admin.pages.index')); ?>"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <?php echo e(__('common.back_to_pages')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="<?php echo e(route('admin.pages.update', $page)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Main Content -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.title')); ?>

                                        *</label>
                                    <input type="text" name="title" id="title"
                                        value="<?php echo e(old('title', $page->title)); ?>"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.content')); ?>

                                        *</label>
                                    <textarea name="content" id="content" rows="15"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        style="display: none;"><?php echo e(old('content', $page->content)); ?></textarea>
                                    <div id="content-editor-wrapper"></div>
                                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Excerpt -->
                                <div>
                                    <label for="excerpt"
                                        class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.excerpt')); ?></label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('excerpt', $page->excerpt)); ?></textarea>
                                    <p class="text-gray-500 text-xs mt-1"><?php echo e(__('common.brief_description')); ?></p>
                                    <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="space-y-6">
                                <!-- Publish Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.publish_settings')); ?></h3>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.status')); ?> *</label>
                                        <select name="status" id="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                            <option value="draft"
                                                <?php echo e(old('status', $page->status) == 'draft' ? 'selected' : ''); ?>><?php echo e(__('common.draft')); ?>

                                            </option>
                                            <option value="published"
                                                <?php echo e(old('status', $page->status) == 'published' ? 'selected' : ''); ?>>
                                                <?php echo e(__('common.published')); ?></option>
                                            <option value="archived"
                                                <?php echo e(old('status', $page->status) == 'archived' ? 'selected' : ''); ?>>
                                                <?php echo e(__('common.archived')); ?></option>
                                        </select>
                                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Featured -->
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_featured" value="1"
                                                <?php echo e(old('is_featured', $page->is_featured) ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.featured_page')); ?></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Page Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.page_settings')); ?></h3>

                                    <!-- Template -->
                                    <div class="mb-4">
                                        <label for="template"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.template')); ?> *</label>
                                        <select name="template" id="template"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(old('template', $page->template) == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Category -->
                                    <div class="mb-4">
                                        <label for="category"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.category')); ?></label>
                                        <input type="text" name="category" id="category"
                                            value="<?php echo e(old('category', $page->category)); ?>" list="categories"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <datalist id="categories">
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category); ?>">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </datalist>
                                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Featured Image -->
                                    <div class="mb-4">
                                        <label for="featured_image"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.featured_image')); ?></label>
                                        <?php if($page->featured_image): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo e(Storage::url($page->featured_image)); ?>"
                                                    alt="Current featured image" class="h-20 w-20 object-cover rounded">
                                                <p class="text-xs text-gray-500 mt-1"><?php echo e(__('common.current_image')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <p class="text-gray-500 text-xs mt-1"><?php echo e(__('common.max_size_formats')); ?></p>
                                        <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- SEO Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.seo_settings')); ?></h3>

                                    <!-- SEO Title -->
                                    <div class="mb-4">
                                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.seo_title')); ?></label>
                                        <input type="text" name="seo_title" id="seo_title"
                                            value="<?php echo e(old('seo_title', $page->seo_meta['title'] ?? '')); ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['seo_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <p class="text-gray-500 text-xs mt-1"><?php echo e(__('common.max_characters')); ?></p>
                                        <?php $__errorArgs = ['seo_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- SEO Description -->
                                    <div class="mb-4">
                                        <label for="seo_description"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.seo_description')); ?></label>
                                        <textarea name="seo_description" id="seo_description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('seo_description', $page->seo_meta['description'] ?? '')); ?></textarea>
                                        <p class="text-gray-500 text-xs mt-1"><?php echo e(__('common.max_description_characters')); ?></p>
                                        <?php $__errorArgs = ['seo_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- SEO Keywords -->
                                    <div class="mb-4">
                                        <label for="seo_keywords"
                                            class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.seo_keywords')); ?></label>
                                        <input type="text" name="seo_keywords" id="seo_keywords"
                                            value="<?php echo e(old('seo_keywords', $page->seo_meta['keywords'] ?? '')); ?>"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['seo_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="keyword1, keyword2, keyword3">
                                        <p class="text-gray-500 text-xs mt-1"><?php echo e(__('common.comma_separated_keywords')); ?></p>
                                        <?php $__errorArgs = ['seo_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Menu Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.menu_settings')); ?></h3>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_menu" id="is_menu" value="1"
                                                <?php echo e(old('is_menu', $page->is_menu) ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.add_to_menu')); ?></span>
                                        </label>
                                    </div>

                                    <div id="menu-settings" class="space-y-4"
                                        style="display: <?php echo e(old('is_menu', $page->is_menu) ? 'block' : 'none'); ?>;">
                                        <div>
                                            <label for="menu_title"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.menu_title')); ?></label>
                                            <input type="text" name="menu_title" id="menu_title"
                                                value="<?php echo e(old('menu_title', $page->menu_title)); ?>"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.leave_empty_use_page_title')); ?></p>
                                        </div>

                                        <div>
                                            <label for="menu_position"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.menu_position')); ?></label>
                                            <select name="menu_position" id="menu_position"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="header"
                                                    <?php echo e(old('menu_position', $page->menu_position) == 'header' ? 'selected' : ''); ?>>
                                                    <?php echo e(__('common.header')); ?></option>
                                                <option value="footer"
                                                    <?php echo e(old('menu_position', $page->menu_position) == 'footer' ? 'selected' : ''); ?>>
                                                    <?php echo e(__('common.footer')); ?></option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="parent_id"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.parent_menu')); ?></label>
                                            <select name="parent_id" id="parent_id"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value=""><?php echo e(__('common.main_menu_item')); ?></option>
                                                <?php $__currentLoopData = $parentPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($parentPage->id); ?>"
                                                        <?php echo e(old('parent_id', $page->parent_id) == $parentPage->id ? 'selected' : ''); ?>>
                                                        <?php echo e($parentPage->title); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="menu_icon"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.menu_icon')); ?></label>
                                            <input type="text" name="menu_icon" id="menu_icon"
                                                value="<?php echo e(old('menu_icon', $page->menu_icon)); ?>"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="fas fa-home">
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.fontawesome_icon_class')); ?></p>
                                        </div>

                                        <div>
                                            <label for="menu_url"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.custom_url')); ?></label>
                                            <input type="text" name="menu_url" id="menu_url"
                                                value="<?php echo e(old('menu_url', $page->menu_url)); ?>"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="/custom-url or https://external.com">
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e(__('common.leave_empty_use_page_url')); ?></p>
                                        </div>

                                        <div>
                                            <label for="menu_sort_order"
                                                class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('common.sort_order')); ?></label>
                                            <input type="number" name="menu_sort_order" id="menu_sort_order"
                                                value="<?php echo e(old('menu_sort_order', $page->menu_sort_order ?? 0)); ?>"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" name="menu_target_blank" id="menu_target_blank"
                                                value="1"
                                                <?php echo e(old('menu_target_blank', $page->menu_target_blank) ? 'checked' : ''); ?>

                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <label for="menu_target_blank" class="ml-2 block text-sm text-gray-700">
                                                <?php echo e(__('common.open_in_new_tab')); ?>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="<?php echo e(route('admin.pages.index')); ?>"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <?php echo e(__('common.update_page')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CKEditor 5 (Rich Text Editor - No API key required) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let contentEditor = null;

            // Initialize CKEditor on wrapper div instead of textarea
            ClassicEditor
                .create(document.querySelector('#content-editor-wrapper'), {
                    initialData: document.querySelector('#content').value,
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    height: 400,
                    language: '<?php echo e(app()->getLocale()); ?>'
                })
                .then(editor => {
                    contentEditor = editor;
                    window.contentEditor = editor;

                    // Listen for content changes
                    editor.model.document.on('change:data', () => {
                        document.getElementById('content').value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                    // Fallback: show textarea if editor fails
                    document.querySelector('#content').style.display = 'block';
                    document.querySelector('#content-editor-wrapper').style.display = 'none';
                });

            // Menu settings toggle
            const menuCheckbox = document.getElementById('is_menu');
            const menuSettings = document.getElementById('menu-settings');

            function toggleMenuSettings() {
                if (menuCheckbox.checked) {
                    menuSettings.style.display = 'block';
                } else {
                    menuSettings.style.display = 'none';
                }
            }

            menuCheckbox.addEventListener('change', toggleMenuSettings);
            toggleMenuSettings(); // Initial check

            // Update textarea before form submit and validate
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (contentEditor) {
                        const editorData = contentEditor.getData();
                        const plainText = editorData.replace(/<[^>]*>/g, '').trim();

                        // Custom validation
                        if (!plainText || plainText === '') {
                            e.preventDefault();
                            if (typeof showError !== 'undefined') {
                                showError('<?php echo e(__('common.content_required')); ?>',
                                    '<?php echo e(__('common.please_enter_content')); ?>');
                            } else {
                                alert('<?php echo e(__('common.content_required')); ?>. <?php echo e(__('common.please_enter_content')); ?>');
                            }
                            return false;
                        }

                        // Sync content to textarea
                        document.getElementById('content').value = editorData;
                        // Remove required attribute to prevent browser validation
                        document.getElementById('content').removeAttribute('required');
                    }
                });
            }
        });
    </script>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show success message if page was updated
                <?php if(session('success')): ?>
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(session('success')); ?>');
                    }
                <?php endif; ?>

                <?php if(session('error')): ?>
                    if (typeof showError !== 'undefined') {
                        showError('<?php echo e(__('common.error')); ?>', '<?php echo e(session('error')); ?>');
                    }
                <?php endif; ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\pages\edit.blade.php ENDPATH**/ ?>