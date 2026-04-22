<x-guest-layout>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side - Resend Verification Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-16 xl:px-20 bg-white">
            <div class="mx-auto w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <div
                        class="mx-auto lg:mx-0 h-16 w-16 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-4xl font-bold text-gray-900">Kirim Ulang Verifikasi</h2>
                    <p class="mt-2 text-lg text-gray-700">Masukkan email Anda untuk menerima link verifikasi baru</p>
                </div>

                <!-- Messages -->
                @if (session('success'))
                    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="mt-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Form -->
                <div class="mt-10">
                    <form action="{{ route('verification.resend-guest') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Masukkan email Anda">
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="w-full btn btn-primary py-4 text-lg font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Link Verifikasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center lg:text-left">
                    <p class="text-base text-gray-700">
                        Sudah terverifikasi?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">
                            Login di sini
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
                    <!-- Email Icon -->
                    <div class="mb-8">
                        <div
                            class="w-32 h-32 bg-white bg-opacity-30 rounded-2xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-white drop-shadow-lg">Verifikasi Email</h1>
                    <p class="text-xl lg:text-2xl text-white mb-10 max-w-md lg:max-w-lg drop-shadow-md">
                        Dapatkan link verifikasi baru untuk mengaktifkan akun Anda
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 text-left max-w-sm lg:max-w-md">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Link verifikasi aman</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Proses cepat dan mudah</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Aktivasi instan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
