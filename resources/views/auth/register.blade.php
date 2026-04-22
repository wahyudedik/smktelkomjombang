<x-guest-layout>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side - Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-16 xl:px-20 bg-white">
            <div class="mx-auto w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <div
                        class="mx-auto lg:mx-0 h-16 w-16 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-4xl font-bold text-gray-900">{{ __('common.register_new_account') }}</h2>
                    <p class="mt-2 text-lg text-gray-700">{{ __('common.join_with') }} {{ config('app.name') }}</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Register Form -->
                <div class="mt-10">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label">{{ __('common.full_name') }}</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                autofocus autocomplete="name"
                                class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_full_name') }}">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="form-label">{{ __('common.email_label') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="username"
                                class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_email') }}">
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="form-label">{{ __('common.user_type') }}</label>
                            <select id="role" name="role" required
                                class="form-input @error('role') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <option value="">{{ __('common.select_user_type') }}</option>
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>{{ __('common.teacher') }}</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>{{ __('common.student') }}</option>
                                <option value="sarpras" {{ old('role') == 'sarpras' ? 'selected' : '' }}>{{ __('common.sarpras') }}</option>
                            </select>
                            @error('role')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">{{ __('common.password') }}</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="form-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="{{ __('common.enter_password') }}">
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="form-label">{{ __('common.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password" class="form-input" placeholder="{{ __('common.confirm_password') }}">
                        </div>

                        <!-- Terms -->
                        <div class="flex items-center">
                            <input id="terms" type="checkbox" name="terms" value="1" required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 text-sm text-gray-700">
                                {{ __('common.agree_terms') }} <a href="#" class="text-blue-600 hover:text-blue-700">{{ __('common.terms_and_conditions') }}</a>
                                {{ __('common.and') }} <a href="#" class="text-blue-600 hover:text-blue-700">{{ __('common.privacy_policy') }}</a>
                            </label>
                        </div>
                        @error('terms')
                            <p class="form-error">{{ $message }}</p>
                        @enderror

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full btn btn-primary py-4 text-lg font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                {{ __('common.register_now') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center lg:text-left">
                    <p class="text-base text-gray-700">
                        {{ __('common.already_have_account') }}
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">
                            {{ __('common.login_here') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Visual Section -->
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-center lg:px-8">
            <div class="relative h-full">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
                    <div class="absolute inset-0 bg-black opacity-20"></div>
                    <!-- Decorative Elements -->
                    <div class="absolute top-20 left-20 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute top-40 right-20 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute bottom-20 left-32 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute bottom-40 right-32 w-28 h-28 bg-white opacity-10 rounded-full"></div>
                </div>

                <!-- Content -->
                <div
                    class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-16">
                    <!-- School Icon -->
                    <div class="mb-8">
                        <div
                            class="w-32 h-32 bg-white bg-opacity-30 rounded-2xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-white drop-shadow-lg">{{ __('common.join_now') }}</h1>
                    <p class="text-xl lg:text-2xl text-white mb-10 max-w-md lg:max-w-lg drop-shadow-md">
                        {{ __('common.register_and_enjoy') }}
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 text-left max-w-sm lg:max-w-md">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">{{ __('common.access_all_features') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">{{ __('common.data_security_guaranteed') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">{{ __('common.user_friendly_interface') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">{{ __('common.support_24_7') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
