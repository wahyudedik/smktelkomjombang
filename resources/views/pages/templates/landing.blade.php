{{-- Landing Page Template --}}
<div class="page-template landing">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            @if ($page->featured_image)
                <div class="absolute inset-0">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                </div>
            @endif

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        {{ $page->title }}
                    </h1>

                    @if ($page->excerpt)
                        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
                            {{ $page->excerpt }}
                        </p>
                    @endif

                    <div class="flex justify-center space-x-4">
                        <a href="#content"
                            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Learn More
                        </a>
                        <a href="#contact"
                            class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div id="content" class="py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
