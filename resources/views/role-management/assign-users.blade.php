<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Users to Role: ') . $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Roles
                        </a>
                    </div>

                    <form action="{{ route('admin.roles.sync-users', $role) }}" method="POST" id="assignUsersForm">
                        @csrf
                        @method('POST')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Role Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Role Name</label>
                                        <p class="text-sm text-gray-900">{{ $role->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Display Name</label>
                                        <p class="text-sm text-gray-900">
                                            @if ($role->display_name)
                                                {{ $role->display_name }}
                                            @else
                                                <span class="text-yellow-600 italic">Not set - will use:
                                                    {{ ucfirst($role->name) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Current Users</label>
                                        <p class="text-sm text-gray-900">{{ $role->users->count() }} users</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Users</h3>
                            <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-md p-4">
                                <div class="space-y-2">
                                    @forelse ($users as $user)
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                {{ in_array($user->id, $roleUsers) ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        @php
                                                            $userRoles = $user->roles;
                                                            $primaryRole = $userRoles->first();
                                                            $roleName = $primaryRole ? $primaryRole->name : 'user';
                                                            $badgeColor = get_role_badge_color($roleName);
                                                        @endphp
                                                        <div class="flex flex-col items-end">
                                                            @if ($userRoles->count() > 1)
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mb-1">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                    Multiple Roles ({{ $userRoles->count() }})
                                                                </span>
                                                            @endif
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                                                {{ get_role_display_name($primaryRole ?? $roleName) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fas fa-users text-4xl mb-2"></i>
                                            <p>No users available to assign to this role.</p>
                                            @if (!is_core_role($role->name) || strtolower($role->name) !== 'superadmin')
                                                <p class="text-sm mt-1">Superadmin users are automatically excluded as
                                                    they already have all permissions.</p>
                                            @endif
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update User Assignments
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('assignUsersForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const userIds = Array.from(document.querySelectorAll('input[name="user_ids[]"]:checked'))
                .map(input => input.value);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        user_ids: userIds
                    })
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
                            let errorMsg = 'Validation errors:<br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('Error Validasi!', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('Unauthorized!', 'Anda tidak memiliki izin untuk melakukan aksi ini.');
                        } else {
                            showError('Error!', result.data.message || 'Gagal mengupdate user assignments');
                        }
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('Berhasil!', 'User assignments berhasil diupdate').then(() => {
                            window.location.href = '{{ route('admin.roles.index') }}';
                        });
                    } else {
                        showError('Error updating user assignments: ' + (result.data.message ||
                            'Unknown error'));
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorMessage = error.message || 'Unknown error occurred';
                    showError('Error updating user assignments: ' + errorMessage);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        });
    </script>
</x-app-layout>
