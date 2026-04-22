<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Testimonial Links') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header Actions -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Testimonial Links</h3>
                @can('testimonial-links.create')
                    <a href="{{ route('admin.testimonial-links.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Create New Link
                    </a>
                @endcan
            </div>

            <!-- Links Table -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($links->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Target Audience</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Active Period</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Submissions</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($links as $link)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $link->title }}
                                                    </div>
                                                    @if ($link->description)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($link->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($link->target_audience as $audience)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if ($audience === 'Siswa') bg-blue-100 text-blue-800
                                                            @elseif($audience === 'Guru') bg-green-100 text-green-800
                                                            @else bg-purple-100 text-purple-800 @endif">
                                                            {{ $audience }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>
                                                    <div>From: {{ $link->active_from->format('M d, Y H:i') }}</div>
                                                    <div>Until: {{ $link->active_until->format('M d, Y H:i') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    @if ($link->isCurrentlyActive())
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Active
                                                        </span>
                                                    @elseif($link->active_until < now())
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-clock mr-1"></i>Expired
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-pause mr-1"></i>Inactive
                                                        </span>
                                                    @endif

                                                    @if (!$link->is_active)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-ban mr-1"></i>Disabled
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div>
                                                    <div>{{ $link->current_submissions }} submissions</div>
                                                    @if ($link->max_submissions)
                                                        <div class="text-xs">Max: {{ $link->max_submissions }}</div>
                                                    @else
                                                        <div class="text-xs">Unlimited</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Copy Link Button -->
                                                    <button onclick="copyLink('{{ $link->getPublicUrl() }}')"
                                                        class="text-blue-600 hover:text-blue-900" title="Copy Link">
                                                        <i class="fas fa-copy"></i>
                                                    </button>

                                                    <!-- View Link Button -->
                                                    <a href="{{ $link->getPublicUrl() }}" target="_blank"
                                                        class="text-green-600 hover:text-green-900" title="View Link">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    @can('testimonial-links.edit')
                                                        <a href="{{ route('admin.testimonial-links.edit', $link) }}"
                                                            class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    <!-- Toggle Active Button -->
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonial-links.toggle-active', $link) }}"
                                                        class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="{{ $link->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}"
                                                            title="{{ $link->is_active ? 'Disable' : 'Enable' }}">
                                                            <i
                                                                class="fas fa-{{ $link->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Delete Button -->
                                                    @can('testimonial-links.delete')
                                                        <form method="POST"
                                                            action="{{ route('admin.testimonial-links.destroy', $link) }}"
                                                            class="inline"
                                                            data-confirm="Are you sure you want to delete this testimonial link?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $links->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-link text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No testimonial links found</h3>
                            <p class="text-gray-500 mb-4">Create your first testimonial link to start collecting
                                testimonials.</p>
                            <a href="{{ route('admin.testimonial-links.create') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Create New Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                showSuccess('Berhasil!', 'Link berhasil disalin ke clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showSuccess('Berhasil!', 'Link berhasil disalin ke clipboard');
            });
        }
    </script>
</x-app-layout>
