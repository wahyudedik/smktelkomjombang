<x-guest-layout>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side - Confirm Password Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-16 xl:px-20 bg-white">
            <div class="mx-auto w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <div
                        class="mx-auto lg:mx-0 h-16 w-16 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-4xl font-bold text-gray-900">Konfirmasi Password</h2>
                    <p class="mt-2 text-lg text-gray-700">Ini adalah area aman. Silakan konfirmasi password Anda untuk
                        melanjutkan.</p>
                </div>

                <!-- Confirm Password Form -->
                <div class="mt-10">
                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="form-input @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan password Anda">
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full btn btn-primary py-4 text-lg font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center lg:text-left">
                    <p class="text-base text-gray-700">
                        Kembali ke
                        <a href="{{ url()->previous() }}" class="font-medium text-blue-600 hover:text-blue-700">
                            halaman sebelumnya
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
                    <!-- Shield Icon -->
                    <div class="mb-8">
                        <div
                            class="w-32 h-32 bg-white bg-opacity-30 rounded-2xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-white drop-shadow-lg">Area Aman</h1>
                    <p class="text-xl lg:text-2xl text-white mb-10 max-w-md lg:max-w-lg drop-shadow-md">
                        Konfirmasi identitas Anda untuk mengakses fitur yang memerlukan keamanan ekstra
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 text-left max-w-sm lg:max-w-md">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Keamanan data terjamin</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Akses terkontrol</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Enkripsi end-to-end</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Audit trail lengkap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
