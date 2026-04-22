<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.create_new_page') }}
            </h2>
            <a href="{{ route('admin.pages.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('common.back_to_pages') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Main Content -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.title') }}
                                        *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                                    @error('title')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.content') }}
                                        *</label>
                                    <textarea name="content" id="content" rows="15"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                        style="display: none;">{{ old('content') }}</textarea>
                                    <div id="content-editor-wrapper"></div>
                                    @error('content')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div>
                                    <label for="excerpt"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.excerpt') }}</label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                                    <p class="text-gray-500 text-xs mt-1">{{ __('common.brief_description') }}</p>
                                    @error('excerpt')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="space-y-6">
                                <!-- Publish Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.publish_settings') }}</h3>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.status') }} *</label>
                                        <select name="status" id="status" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('common.draft') }}
                                            </option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                                {{ __('common.published') }}</option>
                                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                                {{ __('common.archived') }}</option>
                                        </select>
                                        @error('status')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Featured -->
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_featured" value="1"
                                                {{ old('is_featured') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('common.featured_page') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Page Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.page_settings') }}</h3>

                                    <!-- Template -->
                                    <div class="mb-4">
                                        <label for="template"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.template') }} *</label>
                                        <select name="template" id="template" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('template') border-red-500 @enderror">
                                            @foreach ($templates as $key => $name)
                                                <option value="{{ $key }}"
                                                    {{ old('template') == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('template')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="mb-4">
                                        <label for="category"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.category') }}</label>
                                        <input type="text" name="category" id="category"
                                            value="{{ old('category') }}" list="categories"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                                        <datalist id="categories">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category }}">
                                            @endforeach
                                        </datalist>
                                        @error('category')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Featured Image -->
                                    <div class="mb-4">
                                        <label for="featured_image"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.featured_image') }}</label>
                                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('featured_image') border-red-500 @enderror">
                                        <p class="text-gray-500 text-xs mt-1">{{ __('common.max_size_formats') }}</p>
                                        @error('featured_image')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- SEO Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.seo_settings') }}</h3>

                                    <!-- SEO Title -->
                                    <div class="mb-4">
                                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.seo_title') }}</label>
                                        <input type="text" name="seo_title" id="seo_title"
                                            value="{{ old('seo_title') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('seo_title') border-red-500 @enderror">
                                        <p class="text-gray-500 text-xs mt-1">{{ __('common.max_characters') }}</p>
                                        @error('seo_title')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- SEO Description -->
                                    <div class="mb-4">
                                        <label for="seo_description"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.seo_description') }}</label>
                                        <textarea name="seo_description" id="seo_description" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('seo_description') border-red-500 @enderror">{{ old('seo_description') }}</textarea>
                                        <p class="text-gray-500 text-xs mt-1">{{ __('common.max_description_characters') }}</p>
                                        @error('seo_description')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- SEO Keywords -->
                                    <div class="mb-4">
                                        <label for="seo_keywords"
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.seo_keywords') }}</label>
                                        <input type="text" name="seo_keywords" id="seo_keywords"
                                            value="{{ old('seo_keywords') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('seo_keywords') border-red-500 @enderror"
                                            placeholder="keyword1, keyword2, keyword3">
                                        <p class="text-gray-500 text-xs mt-1">{{ __('common.comma_separated_keywords') }}</p>
                                        @error('seo_keywords')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Menu Settings -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('common.menu_settings') }}</h3>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_menu" id="is_menu" value="1"
                                                {{ old('is_menu') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('common.add_to_menu') }}</span>
                                        </label>
                                    </div>

                                    <div id="menu-settings" class="space-y-4" style="display: none;">
                                        <div>
                                            <label for="menu_title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.menu_title') }}</label>
                                            <input type="text" name="menu_title" id="menu_title"
                                                value="{{ old('menu_title') }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <p class="text-sm text-gray-500 mt-1">{{ __('common.leave_empty_use_page_title') }}</p>
                                        </div>

                                        <div>
                                            <label for="menu_position"
                                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.menu_position') }}</label>
                                            <select name="menu_position" id="menu_position"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="header"
                                                    {{ old('menu_position') == 'header' ? 'selected' : '' }}>{{ __('common.header') }}</option>
                                                <option value="footer"
                                                    {{ old('menu_position') == 'footer' ? 'selected' : '' }}>{{ __('common.footer') }}</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.parent_menu') }}</label>
                                            <select name="parent_id" id="parent_id"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="">{{ __('common.main_menu_item') }}</option>
                                                @foreach ($parentPages as $parentPage)
                                                    <option value="{{ $parentPage->id }}"
                                                        {{ old('parent_id') == $parentPage->id ? 'selected' : '' }}>
                                                        {{ $parentPage->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="menu_icon" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.menu_icon') }}</label>
                                            <input type="text" name="menu_icon" id="menu_icon"
                                                value="{{ old('menu_icon') }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="fas fa-home">
                                            <p class="text-sm text-gray-500 mt-1">{{ __('common.fontawesome_icon_class') }}</p>
                                        </div>

                                        <div>
                                            <label for="menu_url" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.custom_url') }}</label>
                                            <input type="text" name="menu_url" id="menu_url"
                                                value="{{ old('menu_url') }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                placeholder="/custom-url or https://external.com">
                                            <p class="text-sm text-gray-500 mt-1">{{ __('common.leave_empty_use_page_url') }}</p>
                                        </div>

                                        <div>
                                            <label for="menu_sort_order"
                                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.sort_order') }}</label>
                                            <input type="number" name="menu_sort_order" id="menu_sort_order"
                                                value="{{ old('menu_sort_order', 0) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" name="menu_target_blank" id="menu_target_blank"
                                                value="1" {{ old('menu_target_blank') ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <label for="menu_target_blank" class="ml-2 block text-sm text-gray-700">
                                                {{ __('common.open_in_new_tab') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.pages.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.create_page') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CKEditor 5 (Rich Text Editor - No API key required) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let contentEditor = null;

            // Initialize CKEditor on wrapper div instead of textarea
            ClassicEditor
                .create(document.querySelector('#content-editor-wrapper'), {
                    initialData: document.querySelector('#content').value,
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    height: 400,
                    language: '{{ app()->getLocale() }}'
                })
                .then(editor => {
                    contentEditor = editor;
                    window.contentEditor = editor;

                    // Listen for content changes
                    editor.model.document.on('change:data', () => {
                        document.getElementById('content').value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                    // Fallback: show textarea if editor fails
                    document.querySelector('#content').style.display = 'block';
                    document.querySelector('#content-editor-wrapper').style.display = 'none';
                });

            // Menu settings toggle
            const menuCheckbox = document.getElementById('is_menu');
            const menuSettings = document.getElementById('menu-settings');

            function toggleMenuSettings() {
                if (menuCheckbox.checked) {
                    menuSettings.style.display = 'block';
                } else {
                    menuSettings.style.display = 'none';
                }
            }

            menuCheckbox.addEventListener('change', toggleMenuSettings);
            toggleMenuSettings(); // Initial check

            // Update textarea before form submit and validate
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (contentEditor) {
                        const editorData = contentEditor.getData();
                        const plainText = editorData.replace(/<[^>]*>/g, '').trim();

                        // Custom validation
                        if (!plainText || plainText === '') {
                            e.preventDefault();
                            if (typeof showError !== 'undefined') {
                                showError('{{ __('common.content_required') }}',
                                    '{{ __('common.please_enter_content') }}');
                            } else {
                                alert('{{ __('common.content_required') }}. {{ __('common.please_enter_content') }}');
                            }
                            return false;
                        }

                        // Sync content to textarea
                        document.getElementById('content').value = editorData;
                        // Remove required attribute to prevent browser validation
                        document.getElementById('content').removeAttribute('required');
                    }
                });
            }
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Show success message if page was created
                @if (session('success'))
                    if (typeof showSuccess !== 'undefined') {
                        showSuccess('{{ __('common.success') }}', '{{ session('success') }}');
                    }
                @endif

                @if (session('error'))
                    if (typeof showError !== 'undefined') {
                        showError('{{ __('common.error') }}', '{{ session('error') }}');
                    }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
