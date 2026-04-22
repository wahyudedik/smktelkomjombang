<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Module Access Management</h1>
                <p class="text-slate-600 mt-1">{{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.superadmin.users.show', $user) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Module Permissions</h3>
                <p class="text-slate-600 mt-1">Configure which modules this user can access and what actions they can
                    perform.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.superadmin.users.module-access.update', $user) }}"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    @foreach ($modules as $module)
                        @php
                            $userAccess = $user->moduleAccess->where('module_name', $module)->first();
                        @endphp
                        <div class="border border-slate-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-900">{{ ucfirst($module) }} Module</h4>
                                    <p class="text-sm text-slate-600">
                                        @switch($module)
                                            @case('instagram')
                                                Control access to Instagram integration and activities management
                                            @break

                                            @case('pages')
                                                Control access to page management and content creation
                                            @break

                                            @case('guru')
                                                Control access to teacher and staff management
                                            @break

                                            @case('siswa')
                                                Control access to student data management
                                            @break

                                            @case('osis')
                                                Control access to OSIS election and management
                                            @break

                                            @case('lulus')
                                                Control access to graduation and certificate management
                                            @break

                                            @case('sarpras')
                                                Control access to facilities and infrastructure management
                                            @break

                                            @case('settings')
                                                Control access to system settings, landing page, and SEO configuration
                                            @break

                                            @default
                                                Control access to {{ $module }} functionality
                                        @endswitch
                                    </p>
                                </div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="modules[{{ $module }}][can_access]"
                                        value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
                                        {{ $userAccess && $userAccess->can_access ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium text-slate-700">Enable Access</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="modules[{{ $module }}][can_create]"
                                        value="1"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded"
                                        {{ $userAccess && $userAccess->can_create ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">Create</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="modules[{{ $module }}][can_read]" value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
                                        {{ $userAccess && $userAccess->can_read ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">Read</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="modules[{{ $module }}][can_update]"
                                        value="1"
                                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-slate-300 rounded"
                                        {{ $userAccess && $userAccess->can_update ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">Update</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="modules[{{ $module }}][can_delete]"
                                        value="1"
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-slate-300 rounded"
                                        {{ $userAccess && $userAccess->can_delete ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">Delete</span>
                                </label>
                            </div>

                            <!-- Hidden field for module name -->
                            <input type="hidden" name="modules[{{ $module }}][module_name]"
                                value="{{ $module }}">
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                        <a href="{{ route('admin.superadmin.users.show', $user) }}"
                            class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
