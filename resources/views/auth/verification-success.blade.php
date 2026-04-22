<x-guest-layout>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side - Verification Success -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-4 sm:px-6 lg:px-16 xl:px-20 bg-white">
            <div class="mx-auto w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <div
                        class="mx-auto lg:mx-0 h-16 w-16 bg-green-600 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-4xl font-bold text-gray-900">Verifikasi Berhasil!</h2>
                    <p class="mt-2 text-lg text-gray-700">Selamat! Email Anda telah terverifikasi</p>
                </div>

                <!-- Success Message -->
                <div class="mt-10">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('message', 'Email Anda berhasil diverifikasi!') }}
                    </div>

                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Akun Anda sudah aktif dan siap digunakan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 space-y-4">
                        <a href="{{ route('login') }}"
                            class="w-full btn btn-primary py-4 text-lg font-medium block text-center">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login Sekarang
                        </a>

                        <a href="{{ route('pages.public.index') }}"
                            class="w-full btn btn-outline py-4 text-lg font-medium block text-center">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Visual Section -->
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-center lg:px-8">
            <div class="relative h-full">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-green-700 to-teal-800">
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
                    <!-- Success Icon -->
                    <div class="mb-8">
                        <div
                            class="w-32 h-32 bg-white bg-opacity-30 rounded-2xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-white drop-shadow-lg">Selamat Datang!</h1>
                    <p class="text-xl lg:text-2xl text-white mb-10 max-w-md lg:max-w-lg drop-shadow-md">
                        Akun Anda sudah aktif dan siap digunakan
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 text-left max-w-sm lg:max-w-md">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Akses penuh ke semua fitur</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Keamanan terjamin</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Siap digunakan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
