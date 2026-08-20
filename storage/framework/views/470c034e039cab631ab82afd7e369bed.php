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
                <h1 class="text-2xl font-bold text-slate-900"><?php echo e(__('common.user_management')); ?></h1>
                <p class="text-slate-600 mt-1"><?php echo e(__('common.manage_users_custom_roles')); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button onclick="showInviteUserModal()" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <?php echo e(__('common.invite_user')); ?>

                </button>
                <button onclick="showCreateUserModal()" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <?php echo e(__('common.create_user')); ?>

                </button>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('common.back_to_dashboard')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Users Table -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h3 class="text-lg font-semibold text-slate-900"><?php echo e(__('common.all_users')); ?></h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <input type="text" id="user-search" placeholder="<?php echo e(__('common.search_users')); ?>"
                                    class="form-input pl-10">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.user')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.email_label')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.role')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.status')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.created')); ?>

                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <?php echo e(__('common.actions')); ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="user-row" data-user-name="<?php echo e(strtolower($user->name)); ?>"
                                data-user-email="<?php echo e(strtolower($user->email)); ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900"><?php echo e($user->name); ?></div>
                                            <?php if($user->hasRole('superadmin')): ?>
                                                <div class="text-xs text-red-600 font-medium"><?php echo e(__('common.super_administrator')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?php echo e($user->email); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e(get_role_badge_color($role->name)); ?>">
                                            <?php echo e(get_role_display_name($role)); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php echo e(__('common.no_role')); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($user->is_verified_by_admin): ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo e(__('common.active')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <?php echo e(__('common.pending')); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?php echo e($user->created_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if(!$user->hasRole('superadmin')): ?>
                                            <button onclick="editUser(<?php echo e($user->id); ?>)"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="toggleUserStatus(<?php echo e($user->id); ?>)"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteUser(<?php echo e($user->id); ?>)"
                                                class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400"><?php echo e(__('common.protected')); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200">
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </div>

    <!-- Invite User Modal -->
    <div id="inviteUserModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.invite_new_user')); ?></h3>
                <form id="inviteUserForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.full_name')); ?></label>
                        <input type="text" id="inviteName" name="name" class="form-input"
                            placeholder="<?php echo e(__('common.enter_full_name')); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.email_label')); ?></label>
                        <input type="email" id="inviteEmail" name="email" class="form-input"
                            placeholder="<?php echo e(__('common.enter_email')); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.role')); ?></label>
                        <select id="inviteRole" name="role_id" class="form-select" required>
                            <option value=""><?php echo e(__('common.select_role')); ?></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>"><?php echo e(get_role_display_name($role)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="sendInvitation" name="send_invitation"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                            <span class="ml-2 text-sm text-gray-700"><?php echo e(__('common.send_invitation_email')); ?></span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeInviteUserModal()"
                            class="btn btn-secondary"><?php echo e(__('common.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('common.invite_user')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4"><?php echo e(__('common.create_new_user')); ?></h3>
                <form id="createUserForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.full_name')); ?></label>
                        <input type="text" id="createName" name="name" class="form-input"
                            placeholder="<?php echo e(__('common.enter_full_name')); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.email_label')); ?></label>
                        <input type="email" id="createEmail" name="email" class="form-input"
                            placeholder="<?php echo e(__('common.enter_email')); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.password')); ?></label>
                        <input type="password" id="createPassword" name="password" class="form-input"
                            placeholder="<?php echo e(__('common.enter_password')); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('common.role')); ?></label>
                        <select id="createRole" name="role_id" class="form-select" required>
                            <option value=""><?php echo e(__('common.select_role')); ?></option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>"><?php echo e(get_role_display_name($role)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateUserModal()"
                            class="btn btn-secondary"><?php echo e(__('common.cancel')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('common.create_user')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('user-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');

            rows.forEach(row => {
                const userName = row.dataset.userName;
                const userEmail = row.dataset.userEmail;
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Modal functions
        function showInviteUserModal() {
            document.getElementById('inviteUserModal').classList.remove('hidden');
        }

        function closeInviteUserModal() {
            document.getElementById('inviteUserModal').classList.add('hidden');
            document.getElementById('inviteUserForm').reset();
        }

        function showCreateUserModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }

        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
            document.getElementById('createUserForm').reset();
        }

        // Invite user form submission
        document.getElementById('inviteUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                role_id: formData.get('role_id'),
                send_invitation: document.getElementById('sendInvitation').checked
            };

            fetch('<?php echo e(route('admin.user-management.invite')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Unexpected response format. Status: ${response.status}`);
                    }
                    const data = await response.json();
                    return {
                        ok: response.ok,
                        status: response.status,
                        data
                    };
                })
                .then(result => {
                    if (!result.ok) {
                        if (result.status === 422) {
                            const errors = result.data.errors || {};
                            let errorMsg = '<?php echo e(__('common.validation_errors')); ?><br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('<?php echo e(__('common.validation_errors')); ?>', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('<?php echo e(__('common.unauthorized')); ?>', '<?php echo e(__('common.unauthorized_action')); ?>');
                        } else {
                            showError('<?php echo e(__('common.error')); ?>', result.data.message || '<?php echo e(__('common.failed_invite_user')); ?>');
                        }
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(__('common.user_invited_success', ['password' => ''])); ?>' + result.data.temp_password).then(() => {
                            location.reload();
                        });
                    } else {
                        showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_invite_user')); ?>: ' + (result.data.message ||
                            'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_invite_user')); ?>: ' + error.message);
                });
        });

        // Create user form submission
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                role_id: formData.get('role_id')
            };

            fetch('<?php echo e(route('admin.user-management.create')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Unexpected response format. Status: ${response.status}`);
                    }
                    const data = await response.json();
                    return {
                        ok: response.ok,
                        status: response.status,
                        data
                    };
                })
                .then(result => {
                    if (!result.ok) {
                        if (result.status === 422) {
                            const errors = result.data.errors || {};
                            let errorMsg = '<?php echo e(__('common.validation_errors')); ?><br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('<?php echo e(__('common.validation_errors')); ?>', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('<?php echo e(__('common.unauthorized')); ?>', '<?php echo e(__('common.unauthorized_action')); ?>');
                        } else {
                            showError('<?php echo e(__('common.error')); ?>', result.data.message || '<?php echo e(__('common.failed_create_user')); ?>');
                        }
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(__('common.user_created_success')); ?>').then(() => {
                            location.reload();
                        });
                    } else {
                        showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_create_user')); ?>: ' + (result.data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_create_user')); ?>: ' + error.message);
                });
        });

        // Edit user
        function editUser(userId) {
            // Redirect to edit page
            window.location.href = `<?php echo e(url('/admin/user-management/users')); ?>/${userId}/edit`;
        }

        // Toggle user status
        function toggleUserStatus(userId) {
            showConfirm(
                '<?php echo e(__('common.toggle_user_status')); ?>',
                '<?php echo e(__('common.toggle_status_confirmation')); ?>',
                '<?php echo e(__('common.yes_change')); ?>',
                '<?php echo e(__('common.cancel')); ?>'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('<?php echo e(__('common.changing_status')); ?>', '<?php echo e(__('common.please_wait')); ?>');
                    fetch(`/admin/user-management/users/${userId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            closeLoading();
                            if (!result.ok) {
                                if (result.status === 401 || result.status === 403) {
                                    showError('<?php echo e(__('common.unauthorized')); ?>',
                                        '<?php echo e(__('common.unauthorized_action')); ?>');
                                } else {
                                    showError('<?php echo e(__('common.error')); ?>', result.data.message || '<?php echo e(__('common.failed_change_status')); ?>');
                                }
                                return;
                            }

                            if (result.data.success) {
                                showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(__('common.status_changed_success')); ?>').then(() => {
                                    location.reload();
                                });
                            } else {
                                showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_change_status')); ?>: ' + (result.data.message ||
                                    'Unknown error'));
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_change_status')); ?>: ' + error.message);
                        });
                }
            });
        }

        // Delete user
        function deleteUser(userId) {
            showConfirm(
                '<?php echo e(__('common.delete_user')); ?>',
                '<?php echo e(__('common.delete_user_confirmation')); ?>',
                '<?php echo e(__('common.yes_delete')); ?>',
                '<?php echo e(__('common.cancel')); ?>'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('<?php echo e(__('common.deleting')); ?>', '<?php echo e(__('common.please_wait')); ?>');
                    fetch(`/admin/user-management/users/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error(`Unexpected response format. Status: ${response.status}`);
                            }
                            const data = await response.json();
                            return {
                                ok: response.ok,
                                status: response.status,
                                data
                            };
                        })
                        .then(result => {
                            closeLoading();
                            if (!result.ok) {
                                if (result.status === 401 || result.status === 403) {
                                    showError('<?php echo e(__('common.unauthorized')); ?>',
                                        '<?php echo e(__('common.unauthorized_action')); ?>');
                                } else {
                                    showError('<?php echo e(__('common.error')); ?>', result.data.message || '<?php echo e(__('common.failed_delete_user')); ?>');
                                }
                                return;
                            }

                            if (result.data.success) {
                                showSuccess('<?php echo e(__('common.success')); ?>', '<?php echo e(__('common.user_deleted_success')); ?>').then(() => {
                                    location.reload();
                                });
                            } else {
                                showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_delete_user')); ?>: ' + (result.data.message ||
                                    'Unknown error'));
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Error:', error);
                            showError('<?php echo e(__('common.error')); ?>', '<?php echo e(__('common.failed_delete_user')); ?>: ' + error.message);
                        });
                }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\user-management\index.blade.php ENDPATH**/ ?>