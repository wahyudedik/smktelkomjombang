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
                <h1 class="text-2xl font-bold text-slate-900">Import Users</h1>
                <p class="text-slate-600 mt-1">Import user data from Excel file</p>
            </div>
            <a href="<?php echo e(route('admin.superadmin.users')); ?>" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Import User Data</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Upload Form -->
                    <div>
                        <h4 class="text-md font-medium text-slate-900 mb-4">Upload Excel File</h4>
                        <form action="<?php echo e(route('admin.superadmin.users.processImport')); ?>" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-4">
                                <div>
                                    <label for="file" class="form-label">Select Excel File</label>
                                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv"
                                        class="form-input <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="form-error"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Import Users
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Instructions -->
                    <div>
                        <h4 class="text-md font-medium text-slate-900 mb-4">Instructions</h4>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-medium text-blue-900 mb-2">Before importing:</h5>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Download the template first</li>
                                <li>• Fill in the data according to the template format</li>
                                <li>• Ensure email addresses are unique</li>
                                <li>• User type must be: superadmin, admin, guru, siswa, or sarpras</li>
                                <li>• Password will be automatically generated if not provided</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a href="<?php echo e(route('admin.superadmin.users.downloadTemplate')); ?>" class="btn btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sample Data -->
                <div class="mt-8">
                    <h4 class="text-md font-medium text-slate-900 mb-4">Template Format</h4>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Password</th>
                                    <th>Email Verified At</th>
                                    <th>Is Verified By Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john.doe@sekolah.com</td>
                                    <td>guru</td>
                                    <td>password123</td>
                                    <td>2024-01-01 10:00:00</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>jane.smith@sekolah.com</td>
                                    <td>siswa</td>
                                    <td></td>
                                    <td></td>
                                    <td>No</td>
                                </tr>
                            </tbody>
                        </table>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\superadmin\users\import.blade.php ENDPATH**/ ?>