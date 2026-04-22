{{-- Contact Page Template --}}
<div class="page-template contact">
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="bg-blue-600 py-16">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    {{ $page->title }}
                </h1>
                @if ($page->excerpt)
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        {{ $page->excerpt }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Contact Content -->
        <div class="max-w-6xl mx-auto px-4 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Get in Touch</h2>

                    <div class="prose prose-lg max-w-none mb-8">
                        {!! $page->content !!}
                    </div>

                    <!-- Contact Details (from custom fields) -->
                    @if (isset($page->custom_fields['contact_info']))
                        <div class="space-y-6">
                            @if (isset($page->custom_fields['contact_info']['phone']))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-lg text-gray-700">{{ $page->custom_fields['contact_info']['phone'] }}</span>
                                </div>
                            @endif

                            @if (isset($page->custom_fields['contact_info']['email']))
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span
                                        class="text-lg text-gray-700">{{ $page->custom_fields['contact_info']['email'] }}</span>
                                </div>
                            @endif

                            @if (isset($page->custom_fields['contact_info']['address']))
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-600 mr-4 mt-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span
                                        class="text-lg text-gray-700">{{ $page->custom_fields['contact_info']['address'] }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-lg">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" name="subject" id="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" id="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
