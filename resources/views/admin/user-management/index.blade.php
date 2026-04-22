<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.user_management') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_users_custom_roles') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="showInviteUserModal()" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('common.invite_user') }}
                </button>
                <button onclick="showCreateUserModal()" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('common.create_user') }}
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('common.back_to_dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Users Table -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">{{ __('common.all_users') }}</h3>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="user-search" placeholder="{{ __('common.search_users') }}"
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
                                {{ __('common.user') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                {{ __('common.email_label') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                {{ __('common.role') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                {{ __('common.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                {{ __('common.created') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                {{ __('common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach ($users as $user)
                            <tr class="user-row" data-user-name="{{ strtolower($user->name) }}"
                                data-user-email="{{ strtolower($user->email) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                            @if ($user->hasRole('superadmin'))
                                                <div class="text-xs text-red-600 font-medium">{{ __('common.super_administrator') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse ($user->roles as $role)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ get_role_badge_color($role->name) }}">
                                            {{ get_role_display_name($role) }}
                                        </span>
                                    @empty
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ __('common.no_role') }}
                                        </span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->is_verified_by_admin)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('common.active') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ __('common.pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @if (!$user->hasRole('superadmin'))
                                            <button onclick="editUser({{ $user->id }})"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="toggleUserStatus({{ $user->id }})"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteUser({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400">{{ __('common.protected') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Invite User Modal -->
    <div id="inviteUserModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.invite_new_user') }}</h3>
                <form id="inviteUserForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.full_name') }}</label>
                        <input type="text" id="inviteName" name="name" class="form-input"
                            placeholder="{{ __('common.enter_full_name') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.email_label') }}</label>
                        <input type="email" id="inviteEmail" name="email" class="form-input"
                            placeholder="{{ __('common.enter_email') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.role') }}</label>
                        <select id="inviteRole" name="role_id" class="form-select" required>
                            <option value="">{{ __('common.select_role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ get_role_display_name($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="sendInvitation" name="send_invitation"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                            <span class="ml-2 text-sm text-gray-700">{{ __('common.send_invitation_email') }}</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeInviteUserModal()"
                            class="btn btn-secondary">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('common.invite_user') }}</button>
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.create_new_user') }}</h3>
                <form id="createUserForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.full_name') }}</label>
                        <input type="text" id="createName" name="name" class="form-input"
                            placeholder="{{ __('common.enter_full_name') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.email_label') }}</label>
                        <input type="email" id="createEmail" name="email" class="form-input"
                            placeholder="{{ __('common.enter_email') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.password') }}</label>
                        <input type="password" id="createPassword" name="password" class="form-input"
                            placeholder="{{ __('common.enter_password') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.role') }}</label>
                        <select id="createRole" name="role_id" class="form-select" required>
                            <option value="">{{ __('common.select_role') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ get_role_display_name($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateUserModal()"
                            class="btn btn-secondary">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('common.create_user') }}</button>
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

            fetch('{{ route('admin.user-management.invite') }}', {
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
                            let errorMsg = '{{ __('common.validation_errors') }}<br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('{{ __('common.validation_errors') }}', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('{{ __('common.unauthorized') }}', '{{ __('common.unauthorized_action') }}');
                        } else {
                            showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_invite_user') }}');
                        }
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('{{ __('common.success') }}', '{{ __('common.user_invited_success', ['password' => '']) }}' + result.data.temp_password).then(() => {
                            location.reload();
                        });
                    } else {
                        showError('{{ __('common.error') }}', '{{ __('common.failed_invite_user') }}: ' + (result.data.message ||
                            'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('{{ __('common.error') }}', '{{ __('common.failed_invite_user') }}: ' + error.message);
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

            fetch('{{ route('admin.user-management.create') }}', {
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
                            let errorMsg = '{{ __('common.validation_errors') }}<br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('{{ __('common.validation_errors') }}', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('{{ __('common.unauthorized') }}', '{{ __('common.unauthorized_action') }}');
                        } else {
                            showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_create_user') }}');
                        }
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('{{ __('common.success') }}', '{{ __('common.user_created_success') }}').then(() => {
                            location.reload();
                        });
                    } else {
                        showError('{{ __('common.error') }}', '{{ __('common.failed_create_user') }}: ' + (result.data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('{{ __('common.error') }}', '{{ __('common.failed_create_user') }}: ' + error.message);
                });
        });

        // Edit user
        function editUser(userId) {
            // Redirect to edit page
            window.location.href = `{{ url('/admin/user-management/users') }}/${userId}/edit`;
        }

        // Toggle user status
        function toggleUserStatus(userId) {
            showConfirm(
                '{{ __('common.toggle_user_status') }}',
                '{{ __('common.toggle_status_confirmation') }}',
                '{{ __('common.yes_change') }}',
                '{{ __('common.cancel') }}'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('{{ __('common.changing_status') }}', '{{ __('common.please_wait') }}');
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
                                    showError('{{ __('common.unauthorized') }}',
                                        '{{ __('common.unauthorized_action') }}');
                                } else {
                                    showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_change_status') }}');
                                }
                                return;
                            }

                            if (result.data.success) {
                                showSuccess('{{ __('common.success') }}', '{{ __('common.status_changed_success') }}').then(() => {
                                    location.reload();
                                });
                            } else {
                                showError('{{ __('common.error') }}', '{{ __('common.failed_change_status') }}: ' + (result.data.message ||
                                    'Unknown error'));
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Error:', error);
                            showError('{{ __('common.error') }}', '{{ __('common.failed_change_status') }}: ' + error.message);
                        });
                }
            });
        }

        // Delete user
        function deleteUser(userId) {
            showConfirm(
                '{{ __('common.delete_user') }}',
                '{{ __('common.delete_user_confirmation') }}',
                '{{ __('common.yes_delete') }}',
                '{{ __('common.cancel') }}'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('{{ __('common.deleting') }}', '{{ __('common.please_wait') }}');
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
                                    showError('{{ __('common.unauthorized') }}',
                                        '{{ __('common.unauthorized_action') }}');
                                } else {
                                    showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_delete_user') }}');
                                }
                                return;
                            }

                            if (result.data.success) {
                                showSuccess('{{ __('common.success') }}', '{{ __('common.user_deleted_success') }}').then(() => {
                                    location.reload();
                                });
                            } else {
                                showError('{{ __('common.error') }}', '{{ __('common.failed_delete_user') }}: ' + (result.data.message ||
                                    'Unknown error'));
                            }
                        })
                        .catch(error => {
                            closeLoading();
                            console.error('Error:', error);
                            showError('{{ __('common.error') }}', '{{ __('common.failed_delete_user') }}: ' + error.message);
                        });
                }
            });
        }
    </script>
</x-app-layout>
