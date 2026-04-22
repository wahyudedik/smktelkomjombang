{{-- Default Page Template --}}
<div class="page-template default">
    <div class="container mx-auto px-4 py-8">
        @if ($page->featured_image)
            <div class="mb-8">
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                    class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <header class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ $page->title }}
                </h1>

                @if ($page->excerpt)
                    <p class="text-xl text-gray-600 leading-relaxed">
                        {{ $page->excerpt }}
                    </p>
                @endif

                <div class="mt-6 flex items-center text-sm text-gray-500 space-x-4">
                    <span>By {{ $page->user->name }}</span>
                    <span>•</span>
                    <span>{{ $page->published_at->format('M d, Y') }}</span>
                    @if ($page->category)
                        <span>•</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $page->category }}
                        </span>
                    @endif
                </div>
            </header>

            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
