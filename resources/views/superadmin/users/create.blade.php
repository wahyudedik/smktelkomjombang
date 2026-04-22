<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.create_new_user') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.add_user_description') }}</p>
            </div>
            <a href="{{ route('admin.superadmin.users') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('common.back_to_users') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">{{ __('common.user_information') }}</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.superadmin.users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="form-label">{{ __('common.full_name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="{{ __('common.enter_full_name') }}">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="form-label">{{ __('common.email_label') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="{{ __('common.enter_email') }}">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="form-label">{{ __('common.password') }}</label>
                        <input id="password" type="password" name="password" required
                            class="form-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="{{ __('common.enter_password') }}">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="form-label">{{ __('common.confirm_password') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="form-input" placeholder="{{ __('common.confirm_password') }}">
                    </div>

                    <!-- Roles -->
                    <div>
                        <label class="form-label">{{ __('common.assign_roles') }}</label>
                        <div class="space-y-2">
                            @foreach ($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-slate-700">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.superadmin.users') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('common.create_user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
