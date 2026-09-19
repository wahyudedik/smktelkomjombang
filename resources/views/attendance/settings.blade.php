<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">⚙️ Pengaturan Absensi</h1>
                <p class="text-slate-600 mt-1">Kelola konfigurasi sistem absensi</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2" role="alert">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <form method="POST" action="{{ route('admin.absensi.settings.reset') }}" class="inline"
                onsubmit="return confirm('Reset semua pengaturan ke default? Pengaturan yang sudah diubah akan hilang.');">
                @csrf
                <button type="submit" class="btn btn-danger text-sm">
                    <i class="fas fa-undo mr-1"></i> Reset Semua ke Default
                </button>
            </form>

            <a href="{{ route('admin.absensi.settings.export') }}" class="btn btn-secondary text-sm" download>
                <i class="fas fa-download mr-1"></i> Export JSON
            </a>

            <button type="button" class="btn btn-secondary text-sm" onclick="document.getElementById('importModal').classList.remove('hidden')">
                <i class="fas fa-upload mr-1"></i> Import JSON
            </button>
        </div>

        {{-- Tabs Navigation --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <nav class="flex flex-wrap border-b border-slate-200 bg-slate-50" role="tablist">
                @php $first = true; @endphp
                @foreach ($categories as $catKey => $category)
                    <button
                        class="px-4 py-3 text-sm font-medium border-b-2 transition-colors
                            {{ $first ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
                        role="tab"
                        data-tab="{{ $catKey }}"
                        onclick="switchTab('{{ $catKey }}')">
                        <i class="{{ $category['icon'] }} mr-1"></i>
                        {{ $category['label'] }}
                    </button>
                    @php $first = false; @endphp
                @endforeach
            </nav>

            <form method="POST" action="{{ route('admin.absensi.settings.update') }}" id="settingsForm">
                @csrf
                @method('PUT')

                @php $first = true; @endphp
                @foreach ($categories as $catKey => $category)
                    <div
                        id="tab-{{ $catKey }}"
                        class="tab-content p-6 {{ $first ? '' : 'hidden' }}"
                        role="tabpanel">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-slate-900">
                                <i class="{{ $category['icon'] }} mr-2 text-blue-500"></i>
                                {{ $category['label'] }}
                            </h2>
                            <button type="submit" class="btn btn-primary text-sm">
                                <i class="fas fa-save mr-1"></i> Simpan {{ $category['label'] }}
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($category['fields'] as $fieldKey => $field)
                                <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <label for="settings_{{ $fieldKey }}" class="block text-sm font-medium text-slate-700">
                                                {{ $field['label'] }}
                                            </label>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $field['desc'] }}</p>
                                        </div>
                                        @if ($field['in_db'] ?? false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-2 whitespace-nowrap">
                                                DB
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 ml-2 whitespace-nowrap">
                                                Default
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        @if ($field['type'] === 'boolean')
                                            <div class="flex items-center gap-3">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox"
                                                        name="settings[{{ $fieldKey }}]"
                                                        value="1"
                                                        class="sr-only peer"
                                                        {{ !empty($field['value']) ? 'checked' : '' }}>
                                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                                <span class="text-sm text-slate-600">
                                                    {{ !empty($field['value']) ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </div>

                                        @elseif ($field['type'] === 'time')
                                            <input type="time"
                                                id="settings_{{ $fieldKey }}"
                                                name="settings[{{ $fieldKey }}]"
                                                value="{{ $field['value'] }}"
                                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">

                                        @elseif ($field['type'] === 'integer')
                                            <input type="number"
                                                id="settings_{{ $fieldKey }}"
                                                name="settings[{{ $fieldKey }}]"
                                                value="{{ $field['value'] }}"
                                                min="0"
                                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">

                                        @elseif ($field['type'] === 'select')
                                            <select
                                                id="settings_{{ $fieldKey }}"
                                                name="settings[{{ $fieldKey }}]"
                                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                @foreach ($field['options'] as $optValue => $optLabel)
                                                    <option value="{{ $optValue }}" {{ $field['value'] === $optValue ? 'selected' : '' }}>
                                                        {{ $optLabel }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        @elseif ($field['type'] === 'password')
                                            <div class="relative">
                                                <input type="password"
                                                    id="settings_{{ $fieldKey }}"
                                                    name="settings[{{ $fieldKey }}]"
                                                    value="{{ $field['value'] }}"
                                                    class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pr-10">
                                                <button type="button"
                                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600"
                                                    onclick="togglePassword('settings_{{ $fieldKey }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                        @else
                                            <input type="text"
                                                id="settings_{{ $fieldKey }}"
                                                name="settings[{{ $fieldKey }}]"
                                                value="{{ $field['value'] }}"
                                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan {{ $category['label'] }}
                            </button>
                        </div>
                    </div>
                    @php $first = false; @endphp
                @endforeach
            </form>
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Import Pengaturan</h3>
            <form method="POST" action="{{ route('admin.absensi.settings.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">File JSON</label>
                    <input type="file" name="settings_file" accept=".json" required
                        class="block w-full text-sm text-slate-700 border border-slate-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" class="btn btn-secondary text-sm"
                        onclick="document.getElementById('importModal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary text-sm">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function switchTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

            // Deactivate all nav buttons
            document.querySelectorAll('[data-tab]').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600', 'bg-white');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            // Show selected tab
            const tab = document.getElementById('tab-' + tabId);
            if (tab) tab.classList.remove('hidden');

            // Activate nav button
            const btn = document.querySelector('[data-tab="' + tabId + '"]');
            if (btn) {
                btn.classList.add('border-blue-500', 'text-blue-600', 'bg-white');
                btn.classList.remove('border-transparent', 'text-slate-500');
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Update boolean toggle label
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('.flex').querySelector('span.text-sm');
                if (label) {
                    label.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
