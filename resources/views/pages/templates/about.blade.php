{{-- About Page Template --}}
<div class="page-template about">
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative">
            @if ($page->featured_image)
                <div class="h-64 md:h-96 bg-gray-900">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                        class="w-full h-full object-cover">
                </div>
            @endif

            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">
                        {{ $page->title }}
                    </h1>
                    @if ($page->excerpt)
                        <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-4xl mx-auto px-4 py-16">
            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>

        <!-- Team Section (if custom fields contain team data) -->
        @if (isset($page->custom_fields['team']) && is_array($page->custom_fields['team']))
            <div class="bg-gray-50 py-16">
                <div class="max-w-6xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Our Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($page->custom_fields['team'] as $member)
                            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                                @if (isset($member['photo']))
                                    <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}"
                                        class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                                @endif
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                                <p class="text-blue-600 mb-2">{{ $member['position'] }}</p>
                                @if (isset($member['bio']))
                                    <p class="text-gray-600 text-sm">{{ $member['bio'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
