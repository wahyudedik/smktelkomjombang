<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">User Details</h1>
                <p class="text-slate-600 mt-1">{{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.superadmin.users.edit', $user) }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('admin.superadmin.users') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">User Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-slate-500">Full Name</label>
                                <p class="mt-1 text-slate-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Email Address</label>
                                <p class="mt-1 text-slate-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Email Status</label>
                                <p class="mt-1">
                                    <span
                                        class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Created At</label>
                                <p class="mt-1 text-slate-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Last Updated</label>
                                <p class="mt-1 text-slate-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Assigned Roles</h3>
                    </div>
                    <div class="card-body">
                        @if ($user->roles->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->roles as $role)
                                    <span class="badge badge-success">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-500">No roles assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Module Access -->
                <div class="card mt-6">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900">Module Access</h3>
                            <a href="{{ route('admin.superadmin.users.module-access', $user) }}"
                                class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Manage Access
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($user->moduleAccess->count() > 0)
                            <div class="space-y-3">
                                @foreach ($user->moduleAccess as $access)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-slate-900">{{ ucfirst($access->module_name) }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                @if ($access->can_access)
                                                    Access: Yes |
                                                    Create: {{ $access->can_create ? 'Yes' : 'No' }} |
                                                    Read: {{ $access->can_read ? 'Yes' : 'No' }} |
                                                    Update: {{ $access->can_update ? 'Yes' : 'No' }} |
                                                    Delete: {{ $access->can_delete ? 'Yes' : 'No' }}
                                                @else
                                                    No access
                                                @endif
                                            </p>
                                        </div>
                                        <span
                                            class="badge {{ $access->can_access ? 'badge-success' : 'badge-danger' }}">
                                            {{ $access->can_access ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-500">No module access configured</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Avatar -->
                <div class="card">
                    <div class="card-body text-center">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h3>
                        <p class="text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <a href="{{ route('admin.superadmin.users.edit', $user) }}"
                            class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="font-medium text-slate-900">Edit User</span>
                        </a>
                        <a href="{{ route('admin.superadmin.users.module-access', $user) }}"
                            class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z" />
                            </svg>
                            <span class="font-medium text-slate-900">Manage Access</span>
                        </a>
                        @if (!$user->hasRole('superadmin'))
                            <form method="POST" action="{{ route('admin.superadmin.users.destroy', $user) }}"
                                data-confirm="Are you sure you want to delete this user?">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-left">
                                    <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="font-medium text-slate-900">Delete User</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
