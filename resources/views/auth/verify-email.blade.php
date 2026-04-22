<x-guest-layout>
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left Side - Verify Email Form -->
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
                    <h2 class="mt-6 text-4xl font-bold text-gray-900">Verifikasi Email</h2>
                    <p class="mt-2 text-lg text-gray-700">Halo, {{ auth()->user()->name }}!</p>
                </div>

                <!-- Content -->
                <div class="mt-10 text-center">
                    <div class="mb-6">
                        <div class="mx-auto h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-gray-600 mb-6">
                        Kami telah mengirimkan link verifikasi ke email Anda:
                        <br>
                        <strong class="text-gray-900">{{ auth()->user()->email }}</strong>
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-blue-800 text-sm">
                                Silakan cek folder <strong>Inbox</strong> atau <strong>Spam</strong> di email Anda.
                                Klik link verifikasi untuk mengaktifkan akun.
                            </p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-green-800 text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-red-800 text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="space-y-4">
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn btn-primary py-4 text-lg font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <a href="{{ route('login') }}" class="w-full btn btn-outline py-4 text-lg font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Login
                        </a>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Tidak menerima email?
                            <a href="{{ route('verification.resend-guest') }}"
                                class="font-medium text-blue-600 hover:text-blue-700">
                                Kirim ulang untuk email lain
                            </a>
                        </p>
                    </div>
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
                        Aktifkan akun Anda dengan mengklik link verifikasi yang telah kami kirim
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
                            <span class="text-white drop-shadow-sm text-lg">Keamanan akun terjamin</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Notifikasi penting via email</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-300 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-white drop-shadow-sm text-lg">Pemulihan akun yang mudah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
