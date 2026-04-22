{{-- Gallery Page Template --}}
<div class="page-template gallery">
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="bg-gray-900 py-16">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    {{ $page->title }}
                </h1>
                @if ($page->excerpt)
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        {{ $page->excerpt }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Gallery Content -->
        <div class="max-w-6xl mx-auto px-4 py-16">
            <!-- Description -->
            <div class="prose prose-lg max-w-none mb-12">
                {!! $page->content !!}
            </div>

            <!-- Gallery Grid -->
            @if (isset($page->custom_fields['gallery_images']) && is_array($page->custom_fields['gallery_images']))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($page->custom_fields['gallery_images'] as $index => $image)
                        <div
                            class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                            <img src="{{ $image['url'] }}" alt="{{ $image['caption'] ?? 'Gallery Image' }}"
                                class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">

                            @if (isset($image['caption']) && $image['caption'])
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-end">
                                    <div
                                        class="p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                        <p class="text-sm font-medium">{{ $image['caption'] }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Overlay for lightbox effect -->
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Fallback: Use featured image if no gallery images -->
                @if ($page->featured_image)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                                class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>
                @endif
            @endif

            <!-- Gallery Categories (if custom fields contain categories) -->
            @if (isset($page->custom_fields['gallery_categories']) && is_array($page->custom_fields['gallery_categories']))
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Gallery Categories</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($page->custom_fields['gallery_categories'] as $category)
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                @if (isset($category['icon']))
                                    <div
                                        class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="{{ $category['icon'] }} text-2xl text-blue-600"></i>
                                    </div>
                                @endif
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category['name'] }}</h3>
                                @if (isset($category['description']))
                                    <p class="text-gray-600">{{ $category['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
