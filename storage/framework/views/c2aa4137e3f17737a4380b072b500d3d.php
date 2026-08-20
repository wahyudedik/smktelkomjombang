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
                <h1 class="text-2xl font-bold text-slate-900">Bulk Create Permissions</h1>
                <p class="text-slate-600 mt-1">Buat multiple permissions sekaligus untuk satu module</p>
            </div>
            <div>
                <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200">
                <form action="<?php echo e(route('admin.permissions.bulk-store')); ?>" method="POST" class="p-6">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Module -->
                        <div>
                            <label for="module" class="block text-sm font-medium text-slate-700 mb-2">
                                Module <span class="text-red-500">*</span>
                            </label>
                            <select name="module" id="module" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['module'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih Module</option>
                                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(old('module') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($value); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['module'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Module Display Name -->
                        <div>
                            <label for="module_display_name" class="block text-sm font-medium text-slate-700 mb-2">
                                Module Display Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="module_display_name" id="module_display_name" required
                                value="<?php echo e(old('module_display_name')); ?>" placeholder="Contoh: Manajemen Guru"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['module_display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['module_display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Actions <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center">
                                    <input type="checkbox" name="actions[]" value="<?php echo e($key); ?>"
                                        <?php echo e(in_array($key, old('actions', [])) ? 'checked' : ''); ?>

                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700"><?php echo e($value); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['actions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Guard Name -->
                    <div class="mt-6">
                        <label for="guard_name" class="block text-sm font-medium text-slate-700 mb-2">
                            Guard Name <span class="text-red-500">*</span>
                        </label>
                        <select name="guard_name" id="guard_name" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['guard_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="web" <?php echo e(old('guard_name', 'web') == 'web' ? 'selected' : ''); ?>>Web
                            </option>
                            <option value="api" <?php echo e(old('guard_name') == 'api' ? 'selected' : ''); ?>>API</option>
                        </select>
                        <?php $__errorArgs = ['guard_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Roles Assignment -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Assign to Roles
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center">
                                    <input type="checkbox" name="assign_to_roles[]" value="<?php echo e($role->id); ?>"
                                        <?php echo e(in_array($role->id, old('assign_to_roles', [])) ? 'checked' : ''); ?>

                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700"><?php echo e($role->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['assign_to_roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Preview -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Preview Permissions
                        </label>
                        <div class="bg-slate-50 border border-slate-200 rounded-md p-4">
                            <div id="permissions-preview" class="space-y-2">
                                <p class="text-slate-500 text-sm">Pilih module dan actions untuk melihat preview...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex items-center justify-end space-x-3">
                        <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Buat Permissions
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Templates -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-slate-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Quick Templates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <button type="button" onclick="fillTemplate('guru')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">Guru Management</h4>
                            <p class="text-sm text-slate-500 mt-1">view, create, edit, delete, export, import</p>
                        </button>
                        <button type="button" onclick="fillTemplate('siswa')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">Siswa Management</h4>
                            <p class="text-sm text-slate-500 mt-1">view, create, edit, delete, export, import</p>
                        </button>
                        <button type="button" onclick="fillTemplate('osis')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">OSIS System</h4>
                            <p class="text-sm text-slate-500 mt-1">view, create, edit, delete, manage</p>
                        </button>
                        <button type="button" onclick="fillTemplate('sarpras')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">Sarpras Management</h4>
                            <p class="text-sm text-slate-500 mt-1">view, create, edit, delete, export, import</p>
                        </button>
                        <button type="button" onclick="fillTemplate('pages')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">Page Management</h4>
                            <p class="text-sm text-slate-500 mt-1">view, create, edit, delete, publish, unpublish</p>
                        </button>
                        <button type="button" onclick="fillTemplate('reports')"
                            class="p-4 border border-slate-200 rounded-lg hover:bg-slate-50 text-left">
                            <h4 class="font-medium text-slate-900">Reports System</h4>
                            <p class="text-sm text-slate-500 mt-1">view, export, manage</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moduleSelect = document.getElementById('module');
            const moduleDisplayNameInput = document.getElementById('module_display_name');
            const actionsCheckboxes = document.querySelectorAll('input[name="actions[]"]');
            const previewDiv = document.getElementById('permissions-preview');

            const actions = <?php echo json_encode($actions, 15, 512) ?>;
            const modules = <?php echo json_encode($modules, 15, 512) ?>;

            function updatePreview() {
                const module = moduleSelect.value;
                const moduleDisplayName = moduleDisplayNameInput.value || modules[module] || '';
                const selectedActions = Array.from(actionsCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (module && selectedActions.length > 0) {
                    let html = '';
                    selectedActions.forEach(action => {
                        const actionText = actions[action] || action;
                        const permissionName = `${module}.${action}`;
                        const displayName = `${moduleDisplayName} - ${actionText}`;

                        html += `
                            <div class="flex items-center justify-between py-2 px-3 bg-white border border-slate-200 rounded">
                                <div>
                                    <div class="text-sm font-medium text-slate-900">${displayName}</div>
                                    <div class="text-xs text-slate-500">${permissionName}</div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${action}
                                </span>
                            </div>
                        `;
                    });
                    previewDiv.innerHTML = html;
                } else {
                    previewDiv.innerHTML =
                        '<p class="text-slate-500 text-sm">Pilih module dan actions untuk melihat preview...</p>';
                }
            }

            moduleSelect.addEventListener('change', function() {
                const moduleDisplayName = modules[this.value] || '';
                moduleDisplayNameInput.value = moduleDisplayName;
                updatePreview();
            });

            moduleDisplayNameInput.addEventListener('input', updatePreview);

            actionsCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updatePreview);
            });

            // Template functions
            window.fillTemplate = function(template) {
                const templates = {
                    'guru': {
                        module: 'guru',
                        module_display_name: 'Manajemen Guru',
                        actions: ['view', 'create', 'edit', 'delete', 'export', 'import']
                    },
                    'siswa': {
                        module: 'siswa',
                        module_display_name: 'Manajemen Siswa',
                        actions: ['view', 'create', 'edit', 'delete', 'export', 'import']
                    },
                    'osis': {
                        module: 'osis',
                        module_display_name: 'Sistem OSIS',
                        actions: ['view', 'create', 'edit', 'delete', 'manage']
                    },
                    'sarpras': {
                        module: 'sarpras',
                        module_display_name: 'Sarana Prasarana',
                        actions: ['view', 'create', 'edit', 'delete', 'export', 'import']
                    },
                    'pages': {
                        module: 'pages',
                        module_display_name: 'Manajemen Halaman',
                        actions: ['view', 'create', 'edit', 'delete', 'publish', 'unpublish']
                    },
                    'reports': {
                        module: 'reports',
                        module_display_name: 'Sistem Laporan',
                        actions: ['view', 'export', 'manage']
                    }
                };

                const template = templates[template];
                if (template) {
                    moduleSelect.value = template.module;
                    moduleDisplayNameInput.value = template.module_display_name;

                    // Clear all checkboxes first
                    actionsCheckboxes.forEach(cb => cb.checked = false);

                    // Check selected actions
                    template.actions.forEach(action => {
                        const checkbox = document.querySelector(
                            `input[name="actions[]"][value="${action}"]`);
                        if (checkbox) checkbox.checked = true;
                    });

                    updatePreview();
                }
            };
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\permissions\bulk-create.blade.php ENDPATH**/ ?>