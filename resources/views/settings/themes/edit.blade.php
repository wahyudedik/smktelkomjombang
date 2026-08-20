<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                                    <a href="{{ route('admin.themes.index') }}" class="hover:text-blue-600">Theme
                                        Settings</a>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="text-gray-900 font-medium">{{ $themes[$theme]['name'] }}</span>
                                </div>
                                <h1 class="text-3xl font-bold text-gray-900">Edit: {{ $themes[$theme]['name'] }}</h1>
                                <p class="text-gray-600 mt-2">
                                    {{ $themes[$theme]['description'] ?? 'Pengaturan tema landing page' }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.themes.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <h4 class="font-bold mb-2">Terjadi kesalahan:</h4>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Priority Info -->
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
                                    <strong>Prioritas:</strong> Nilai yang disimpan di database ini akan menimpa config
                                    file.
                                    Kosongkan field untuk menggunakan nilai dari config file.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8" id="theme-tabs">
                            @php $firstTab = true; @endphp
                            @foreach ($groups as $groupKey => $groupInfo)
                                @if (isset($grouped[$groupKey]) && $grouped[$groupKey]->count() > 0)
                                    <button onclick="showGroup('{{ $groupKey }}')"
                                        class="group-tab py-2 px-1 border-b-2 font-medium text-sm {{ $firstTab ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                        data-group="{{ $groupKey }}">
                                        <i class="{{ $groupInfo['icon'] }} mr-1"></i>
                                        {{ $groupInfo['label'] }}
                                        <span
                                            class="ml-1 text-xs bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $grouped[$groupKey]->count() }}</span>
                                    </button>
                                    @php $firstTab = false; @endphp
                                @endif
                            @endforeach
                        </nav>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.themes.update', $theme) }}" method="POST"
                        enctype="multipart/form-data" id="theme-settings-form">
                        @csrf
                        @method('PUT')

                        @php $firstGroup = true; @endphp
                        @foreach ($groups as $groupKey => $groupInfo)
                            @if (isset($grouped[$groupKey]) && $grouped[$groupKey]->count() > 0)
                                <div class="group-section" id="group-{{ $groupKey }}"
                                    style="{{ $firstGroup ? '' : 'display:none;' }}">
                                    <div class="bg-white rounded-lg shadow p-6 mb-6 border">
                                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                            <i class="{{ $groupInfo['icon'] }} mr-2 text-blue-600"></i>
                                            {{ $groupInfo['label'] }}
                                        </h2>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @foreach ($grouped[$groupKey] as $setting)
                                                @php
                                                    // Determine input type from setting type or key name
                                                    $inputType = $setting->type ?? 'text';
                                                    if (
                                                        in_array($setting->key, [
                                                            'logo',
                                                            'logo_light',
                                                            'favicon',
                                                            'headmaster_photo',
                                                            'video_thumbnail',
                                                            'campus_life_headmaster_photo',
                                                        ])
                                                    ) {
                                                        $inputType = 'file';
                                                    }
                                                    if (in_array($setting->key, ['hero_images'])) {
                                                        $inputType = 'file-multiple';
                                                    }
                                                    if (
                                                        $inputType === 'textarea' ||
                                                        in_array($setting->key, [
                                                            'site_description',
                                                            'about_text',
                                                            'video_description',
                                                            'cta_description',
                                                        ])
                                                    ) {
                                                        $inputType = 'textarea';
                                                    }
                                                    // Check if setting has a current value
                                                    $hasValue = !empty($setting->value);
                                                @endphp

                                                @if ($inputType === 'file')
                                                    <div
                                                        class="{{ in_array($setting->key, ['logo', 'logo_light']) ? 'md:col-span-1' : '' }}">
                                                        <label for="{{ $setting->key }}"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $setting->key }}
                                                            @if ($hasValue)
                                                                <span class="text-green-600 text-xs">(terisi)</span>
                                                            @endif
                                                        </label>
                                                        <input type="file" id="{{ $setting->key }}"
                                                            name="{{ $setting->key }}" accept="image/*"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                        @if ($hasValue)
                                                            <div class="mt-2">
                                                                <p class="text-xs text-gray-500 mb-1">Current:</p>
                                                                <img src="{{ Storage::url($setting->value) }}"
                                                                    alt="{{ $setting->key }}"
                                                                    class="h-12 w-auto border rounded">
                                                                <label
                                                                    class="inline-flex items-center mt-2 text-xs text-red-600">
                                                                    <input type="checkbox"
                                                                        name="delete_{{ $setting->key }}"
                                                                        value="1" class="mr-1">
                                                                    Hapus gambar
                                                                </label>
                                                            </div>
                                                        @endif
                                                        <input type="hidden" name="existing_{{ $setting->key }}"
                                                            value="{{ $setting->value }}">
                                                    </div>
                                                @elseif ($inputType === 'file-multiple')
                                                    <div class="md:col-span-2">
                                                        <label for="{{ $setting->key }}"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $setting->key }} (Multiple images)
                                                        </label>
                                                        <input type="file" id="{{ $setting->key }}"
                                                            name="{{ $setting->key }}[]" accept="image/*" multiple
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                        @if ($hasValue)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ count(json_decode($setting->value, true) ?? []) }}
                                                                gambar tersimpan</p>
                                                        @endif
                                                    </div>
                                                @elseif ($inputType === 'textarea')
                                                    <div
                                                        class="{{ in_array($setting->key, ['about_text', 'video_description', 'cta_description', 'site_description']) ? 'md:col-span-2' : '' }}">
                                                        <label for="{{ $setting->key }}"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $setting->key }}
                                                        </label>
                                                        <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" rows="3"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                                            placeholder="Kosongkan untuk menggunakan nilai config file">{{ $setting->value }}</textarea>
                                                    </div>
                                                @else
                                                    <div>
                                                        <label for="{{ $setting->key }}"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            {{ $setting->key }}
                                                        </label>
                                                        <input type="text" id="{{ $setting->key }}"
                                                            name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                                            placeholder="Kosongkan untuk menggunakan nilai config file">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php $firstGroup = false; @endphp
                            @endif
                        @endforeach

                        <!-- Save Button -->
                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <a href="{{ route('admin.themes.index') }}"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition text-sm font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium font-bold shadow-lg">
                                💾 Simpan Semua Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showGroup(groupKey) {
            // Hide all groups
            document.querySelectorAll('.group-section').forEach(el => {
                el.style.display = 'none';
            });

            // Show selected group
            const target = document.getElementById('group-' + groupKey);
            if (target) {
                target.style.display = '';
            }

            // Update tab styles
            document.querySelectorAll('.group-tab').forEach(tab => {
                if (tab.dataset.group === groupKey) {
                    tab.classList.remove('border-transparent', 'text-gray-500');
                    tab.classList.add('border-blue-500', 'text-blue-600');
                } else {
                    tab.classList.remove('border-blue-500', 'text-blue-600');
                    tab.classList.add('border-transparent', 'text-gray-500');
                }
            });
        }
    </script>
</x-app-layout>
