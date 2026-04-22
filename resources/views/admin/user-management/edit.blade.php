<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
                <p class="text-slate-600 mt-1">{{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.user-management.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">User Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.user-management.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="form-label">Full Name *</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                            required
                            class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter full name">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">Email Address *</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                            required
                            class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter email address">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input id="password" type="password" name="password"
                            class="form-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Enter new password (min 8 characters)">
                        <p class="text-sm text-gray-500 mt-1">Leave blank if you don't want to change the password</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-input" placeholder="Confirm new password">
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role_id" class="form-label">Role *</label>
                        <select id="role_id" name="role_id" required
                            class="form-input @error('role_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Select role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>
                                    {{ get_role_display_name($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-slate-200">
                        <a href="{{ route('admin.user-management.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show success message if user was updated
                @if (session('success'))
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('{{ __('Success') }}', '{{ session('success') }}');
                    }
                @endif

                @if (session('error'))
                    if (typeof showError !== 'undefined') {
                        showError('{{ __('Error') }}', '{{ session('error') }}');
                    }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
