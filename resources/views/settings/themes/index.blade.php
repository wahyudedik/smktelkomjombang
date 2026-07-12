<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Theme Settings</h1>
                                <p class="text-gray-600 mt-2">Kelola pengaturan visual dan konten untuk setiap tema
                                    landing page</p>
                            </div>
                            <a href="{{ route('admin.settings.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Info Banner -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Cara kerja:</strong> Pengaturan dibaca dari Database → Config File →
                                    Default.
                                    Jika ada di database, nilai database yang digunakan. Jika kosong, fallback ke config
                                    file.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Theme Indicator -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Tema Aktif:</strong> <span
                                        class="font-bold">{{ strtoupper(config('app.default_theme', 'telkom')) }}</span>
                                    — Ditentukan oleh variabel <code
                                        class="bg-yellow-100 px-1 rounded">DEFAULT_THEME</code> di file <code
                                        class="bg-yellow-100 px-1 rounded">.env</code>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($themes as $themeKey => $themeInfo)
                            @php
                                $isActive = config('app.default_theme', 'telkom') === $themeKey;
                                $settingsCount = $themeStats[$themeKey] ?? 0;
                            @endphp
                            <div
                                class="border rounded-lg overflow-hidden {{ $isActive ? 'border-green-400 ring-2 ring-green-200' : 'border-gray-200' }}">
                                <!-- Card Header -->
                                <div class="p-6 {{ $isActive ? 'bg-green-50' : 'bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if ($isActive)
                                                    <span
                                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-500 text-white">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-300 text-white">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-xl font-bold text-gray-900">{{ $themeInfo['name'] }}
                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    {{ $themeInfo['description'] ?? 'Landing page ' . $themeInfo['name'] }}
                                                </p>
                                            </div>
                                        </div>
                                        @if ($isActive)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                AKTIF
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6 border-t">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-semibold text-gray-900">{{ $settingsCount }}</span>
                                            pengaturan tersimpan di database
                                        </div>
                                    </div>

                                    <!-- School Info -->
                                    @if (isset($themeInfo['school']))
                                        <div class="text-sm text-gray-500 mb-4">
                                            <p><strong>Sekolah:</strong> {{ $themeInfo['school'] }}</p>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.themes.edit', $themeKey) }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Settings
                                        </a>

                                        <form action="{{ route('admin.themes.seed-defaults', $themeKey) }}"
                                            method="POST"
                                            onsubmit="return confirm('Import default settings dari config file? Data yang sudah ada tidak akan ditimpa.')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Seed Defaults
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.themes.reset-defaults', $themeKey) }}"
                                            method="POST"
                                            onsubmit="return confirm('⚠️ PERINGATAN: Semua pengaturan tema ini akan DIHAPUS dan diganti dengan default dari config file. Lanjutkan?')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Reset
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
