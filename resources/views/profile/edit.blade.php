<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Profile updated success message
                @if (session('status') === 'profile-updated')
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('{{ __('common.profile_updated') }}',
                            '{{ __('common.profile_updated_success') }}');
                    }
                @endif

                // Password updated success message
                @if (session('status') === 'password-updated')
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('{{ __('common.password_updated') }}',
                            '{{ __('common.password_updated_success') }}');
                    }
                @endif

                // Verification link sent
                @if (session('status') === 'verification-link-sent')
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('{{ __('common.verification_email_sent') }}',
                            '{{ __('common.verification_link_sent') }}');
                    }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
