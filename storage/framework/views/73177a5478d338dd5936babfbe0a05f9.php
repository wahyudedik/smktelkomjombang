
<div class="page-template about">
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative">
            <?php if($page->featured_image): ?>
                <div class="h-64 md:h-96 bg-gray-900">
                    <img src="<?php echo e(Storage::url($page->featured_image)); ?>" alt="<?php echo e($page->title); ?>"
                        class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">
                        <?php echo e($page->title); ?>

                    </h1>
                    <?php if($page->excerpt): ?>
                        <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                            <?php echo e($page->excerpt); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-4xl mx-auto px-4 py-16">
            <div class="prose prose-lg max-w-none">
                <?php echo $page->content; ?>

            </div>
        </div>

        <!-- Team Section (if custom fields contain team data) -->
        <?php if(isset($page->custom_fields['team']) && is_array($page->custom_fields['team'])): ?>
            <div class="bg-gray-50 py-16">
                <div class="max-w-6xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Our Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php $__currentLoopData = $page->custom_fields['team']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                                <?php if(isset($member['photo'])): ?>
                                    <img src="<?php echo e($member['photo']); ?>" alt="<?php echo e($member['name']); ?>"
                                        class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                                <?php endif; ?>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($member['name']); ?></h3>
                                <p class="text-blue-600 mb-2"><?php echo e($member['position']); ?></p>
                                <?php if(isset($member['bio'])): ?>
                                    <p class="text-gray-600 text-sm"><?php echo e($member['bio']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH E:\PROJEKU\telkom\resources\views\pages\templates\about.blade.php ENDPATH**/ ?>