{{-- Blog Post Template --}}
<div class="page-template blog">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Article Header -->
        <header class="mb-12">
            <div class="mb-6">
                @if ($page->category)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-4">
                        {{ $page->category }}
                    </span>
                @endif

                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $page->title }}
                </h1>

                @if ($page->excerpt)
                    <p class="text-xl text-gray-600 leading-relaxed mb-6">
                        {{ $page->excerpt }}
                    </p>
                @endif

                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-semibold">{{ substr($page->user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $page->user->name }}</p>
                            <p class="text-gray-500">{{ $page->published_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($page->featured_image)
                <div class="mb-8">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                        class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                </div>
            @endif
        </header>

        <!-- Article Content -->
        <article class="prose prose-lg max-w-none">
            {!! $page->content !!}
        </article>

        <!-- Article Footer -->
        <footer class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Published {{ $page->published_at->format('M d, Y') }}</span>
                    @if ($page->category)
                        <span class="text-sm text-gray-500">•</span>
                        <span class="text-sm text-blue-600">{{ $page->category }}</span>
                    @endif
                </div>

                <div class="flex space-x-4">
                    <button class="text-gray-500 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button class="text-gray-500 hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </footer>
    </div>
</div>
