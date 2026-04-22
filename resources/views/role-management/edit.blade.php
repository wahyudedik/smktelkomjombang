<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.edit_role_title') }}
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
                            {{ __('common.back_to_roles') }}
                        </a>
                    </div>

                    <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="editRoleForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role Information -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.role_information') }}</h3>

                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.role_name_required') }}</label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $role->name) }}" required
                                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @else border-gray-300 @enderror">
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @if ($role->display_name)
                                        <div class="mb-4">
                                            <label for="display_name"
                                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.display_name') }}</label>
                                            <input type="text" name="display_name" id="display_name"
                                                value="{{ old('display_name', $role->display_name) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    @endif

                                    @if ($role->description)
                                        <div class="mb-4">
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.description') }}</label>
                                            <textarea name="description" id="description" rows="3"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $role->description) }}</textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.permissions') }}</h3>
                                    <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-md p-4">
                                        @foreach ($permissions as $module => $modulePermissions)
                                            <div class="mb-4">
                                                <h4 class="text-sm font-medium text-gray-700 mb-2">
                                                    {{ ucfirst($module) }}</h4>
                                                <div class="space-y-2">
                                                    @foreach ($modulePermissions as $permission)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" name="permissions[]"
                                                                value="{{ $permission->name }}"
                                                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <span
                                                                class="ml-2 text-sm text-gray-700">{{ $permission->display_name ?? $permission->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('common.update_role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const permissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                .map(input => input.value);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = '{{ __('common.updating') }}';
            submitBtn.disabled = true;

            fetch(this.action, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        display_name: formData.get('display_name'),
                        description: formData.get('description'),
                        permissions: permissions
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
                            let errorMsg = '{{ __('common.validation_errors') }}<br>';
                            for (const [field, fieldErrors] of Object.entries(errors)) {
                                errorMsg +=
                                    `<strong>${field}:</strong> ${Array.isArray(fieldErrors) ? fieldErrors.join(', ') : fieldErrors}<br>`;
                            }
                            showError('{{ __('common.validation_errors') }}', errorMsg);
                        } else if (result.status === 401 || result.status === 403) {
                            showError('{{ __('common.unauthorized') }}', '{{ __('common.unauthorized_action') }}');
                        } else {
                            showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_update_role') }}');
                        }
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        return;
                    }

                    if (result.data.success) {
                        showSuccess('{{ __('common.success') }}', '{{ __('common.role_updated_success') }}').then(() => {
                            window.location.href = '{{ route('admin.roles.index') }}';
                        });
                    } else {
                        showError('{{ __('common.error') }}', result.data.message || '{{ __('common.failed_update_role') }}');
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    closeLoading();
                    const errorMessage = error.message || '{{ str_replace(':action', 'mengupdate', __('common.error_occurred')) }}';
                    showError('{{ __('common.error') }}', errorMessage);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        });
    </script>
</x-app-layout>
