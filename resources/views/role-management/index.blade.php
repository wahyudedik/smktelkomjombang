<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('common.role_management') }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('common.manage_roles_description') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>{{ __('common.create_new_role') }}
                </a>
                <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-key mr-2"></i>{{ __('common.permission_manager') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.role_name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.users_count') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.permissions') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($roles as $role)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <span class="font-semibold">{{ get_role_display_name($role) }}</span>
                                        @if (is_core_role($role->name))
                                            <span class="ml-2 text-xs text-gray-500">
                                                <i class="fas fa-lock"></i> {{ __('common.core') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($role->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($role->description, 50) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $role->users_count }} {{ __('common.users') }}</td>
                                <td class="px-6 py-4">{{ $role->permissions->count() }} {{ __('common.permissions_count') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                            class="text-blue-600 hover:text-blue-900" title="{{ __('common.edit_role') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.assign-users', $role) }}"
                                            class="text-green-600 hover:text-green-900" title="{{ __('common.assign_users') }}">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        @if (!is_core_role($role->name))
                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                                class="inline"
                                                data-confirm="{{ str_replace(':role', $role->name, __('common.delete_role_confirmation')) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="{{ __('common.delete_role') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400" title="{{ __('common.core_role_cannot_delete') }}">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    <p class="text-sm">{{ __('common.no_roles_found') }}</p>
                                    <a href="{{ route('admin.roles.create') }}"
                                        class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800">
                                        {{ __('common.create_new_role_link') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Display flash messages
                @if (session('success'))
                    showSuccess('{{ __('common.success') }}', '{{ session('success') }}');
                @endif

                @if (session('error'))
                    showError('{{ __('common.error') }}', '{{ session('error') }}');
                @endif
            });
        </script>
    @endpush
</x-app-layout>
