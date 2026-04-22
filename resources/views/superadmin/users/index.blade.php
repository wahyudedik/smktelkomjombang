<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.user_management') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_users_description') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.superadmin.users.import') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ __('common.import') }}
                </a>
                <a href="{{ route('admin.superadmin.users.export') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('common.export') }}
                </a>
                <a href="{{ route('admin.superadmin.users.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('common.add_new_user') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">{{ __('common.all_users') }}</h3>
            </div>
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('common.name') }}</th>
                                <th>{{ __('common.email_label') }}</th>
                                <th>{{ __('common.roles') }}</th>
                                <th>{{ __('common.status') }}</th>
                                <th>{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                                <p class="text-sm text-slate-500">ID: {{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-slate-900">{{ $user->email }}</td>
                                    <td>
                                        @if ($user->roles->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($user->roles as $role)
                                                    <span class="badge badge-success">{{ $role->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-500">{{ __('common.no_roles') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }}">
                                            {{ $user->email_verified_at ? __('common.verified') : __('common.unverified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.superadmin.users.show', $user) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.superadmin.users.edit', $user) }}"
                                                class="text-green-600 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.superadmin.users.module-access', $user) }}"
                                                class="text-purple-600 hover:text-purple-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                            </a>
                                            @if (!$user->hasRole('superadmin'))
                                                <form method="POST"
                                                    action="{{ route('admin.superadmin.users.destroy', $user) }}"
                                                    class="inline"
                                                    data-confirm="{{ __('common.delete_user_confirmation') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <p class="text-slate-500">{{ __('common.no_users_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
