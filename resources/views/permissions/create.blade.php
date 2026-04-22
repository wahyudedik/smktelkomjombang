<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Permission</h1>
                <p class="text-slate-600 mt-1">Buat permission baru untuk sistem</p>
            </div>
            <div>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200">
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Module -->
                        <div>
                            <label for="module" class="block text-sm font-medium text-slate-700 mb-2">
                                Module <span class="text-red-500">*</span>
                            </label>
                            <select name="module" id="module" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('module') border-red-500 @enderror">
                                <option value="">Pilih Module</option>
                                @foreach ($modules as $key => $value)
                                    <option value="{{ $key }}" {{ old('module') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('module')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action -->
                        <div>
                            <label for="action" class="block text-sm font-medium text-slate-700 mb-2">
                                Action <span class="text-red-500">*</span>
                            </label>
                            <select name="action" id="action" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('action') border-red-500 @enderror">
                                <option value="">Pilih Action</option>
                                @foreach ($actions as $key => $value)
                                    <option value="{{ $key }}" {{ old('action') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('action')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Auto-generated Name Preview -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Permission Name (Auto-generated)
                        </label>
                        <div class="px-3 py-2 bg-slate-100 border border-slate-300 rounded-md">
                            <span id="permission-name-preview" class="text-slate-600">module.action</span>
                        </div>
                    </div>

                    <!-- Display Name -->
                    <div class="mt-6">
                        <label for="display_name" class="block text-sm font-medium text-slate-700 mb-2">
                            Display Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="display_name" id="display_name" required
                            value="{{ old('display_name') }}" placeholder="Contoh: Guru - Tambah Data"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('display_name') border-red-500 @enderror">
                        @error('display_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" placeholder="Deskripsi permission..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guard Name -->
                    <div class="mt-6">
                        <label for="guard_name" class="block text-sm font-medium text-slate-700 mb-2">
                            Guard Name <span class="text-red-500">*</span>
                        </label>
                        <select name="guard_name" id="guard_name" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('guard_name') border-red-500 @enderror">
                            <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web
                            </option>
                            <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @error('guard_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles Assignment -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Assign to Roles
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate permission name and display name
        document.addEventListener('DOMContentLoaded', function() {
            const moduleSelect = document.getElementById('module');
            const actionSelect = document.getElementById('action');
            const displayNameInput = document.getElementById('display_name');
            const namePreview = document.getElementById('permission-name-preview');

            function updatePreview() {
                const module = moduleSelect.value;
                const action = actionSelect.value;

                if (module && action) {
                    const permissionName = `${module}.${action}`;
                    namePreview.textContent = permissionName;

                    // Auto-generate display name if empty
                    if (!displayNameInput.value) {
                        const moduleText = moduleSelect.options[moduleSelect.selectedIndex].text;
                        const actionText = actionSelect.options[actionSelect.selectedIndex].text;
                        displayNameInput.value = `${moduleText} - ${actionText}`;
                    }
                } else {
                    namePreview.textContent = 'module.action';
                }
            }

            moduleSelect.addEventListener('change', updatePreview);
            actionSelect.addEventListener('change', updatePreview);
        });
    </script>
</x-app-layout>
