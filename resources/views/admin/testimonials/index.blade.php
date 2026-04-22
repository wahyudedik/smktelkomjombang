<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.manage_testimonials') }}
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

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('common.total_testimonials') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $testimonials->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('common.approved') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $testimonials->where('is_approved', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-star text-2xl text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('common.featured') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $testimonials->where('is_featured', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-2xl text-orange-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('common.pending') }}</p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $testimonials->where('is_approved', false)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.status') }}</label>
                            <select name="status" id="status" class="rounded-md border-gray-300">
                                <option value="">{{ __('common.all') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                    {{ __('common.approved') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('common.pending') }}
                                </option>
                                <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>
                                    {{ __('common.featured') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.position') }}</label>
                            <select name="position" id="position" class="rounded-md border-gray-300">
                                <option value="">{{ __('common.all') }}</option>
                                <option value="Siswa" {{ request('position') == 'Siswa' ? 'selected' : '' }}>{{ __('common.student') }}
                                </option>
                                <option value="Guru" {{ request('position') == 'Guru' ? 'selected' : '' }}>{{ __('common.teacher') }}
                                </option>
                                <option value="Alumni" {{ request('position') == 'Alumni' ? 'selected' : '' }}>{{ __('common.alumni') }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.search') }}</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="{{ __('common.search') }}" class="rounded-md border-gray-300">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>{{ __('common.filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Testimonials Table -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('common.testimonials') }}</h3>
                        <div class="text-sm text-gray-500">
                            {{ str_replace([':first', ':last', ':total'], [$testimonials->firstItem(), $testimonials->lastItem(), $testimonials->total()], __('common.showing_results')) }}
                        </div>
                    </div>

                    @if ($testimonials->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.testimonial') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.author') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.status') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.rating') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.date') }}</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($testimonials as $testimonial)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="max-w-xs">
                                                    <p class="text-sm text-gray-900 truncate">
                                                        {{ Str::limit($testimonial->testimonial, 100) }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if ($testimonial->photo)
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="{{ Storage::url($testimonial->photo) }}"
                                                            alt="{{ $testimonial->name }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <i class="fas fa-user text-gray-600"></i>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $testimonial->name }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            @if ($testimonial->position === 'Alumni')
                                                                {{ __('common.alumni') }} {{ $testimonial->graduation_year }}
                                                            @elseif($testimonial->position === 'Siswa')
                                                                {{ $testimonial->class }}
                                                            @else
                                                                {{ $testimonial->position }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col space-y-1">
                                                    @if ($testimonial->is_approved)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>{{ __('common.approved') }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>{{ __('common.pending') }}
                                                        </span>
                                                    @endif
                                                    @if ($testimonial->is_featured)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            <i class="fas fa-star mr-1"></i>{{ __('common.featured') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star text-sm {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                    <span
                                                        class="ml-2 text-sm text-gray-500">{{ $testimonial->rating }}/5</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $testimonial->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- View Button -->
                                                    <button onclick="viewTestimonial({{ $testimonial->id }})"
                                                        class="text-blue-600 hover:text-blue-900" title="{{ __('common.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Approve/Reject Buttons -->
                                                    @can('testimonials.edit')
                                                        @if (!$testimonial->is_approved)
                                                            <form method="POST"
                                                                action="{{ route('admin.testimonials.approve', $testimonial) }}"
                                                                class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="text-green-600 hover:text-green-900"
                                                                    title="{{ __('common.approved') }}">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('admin.testimonials.reject', $testimonial) }}"
                                                                class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="text-yellow-600 hover:text-yellow-900"
                                                                    title="{{ __('common.reject') }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    <!-- Featured Toggle -->
                                                    @can('testimonials.edit')
                                                        <form method="POST"
                                                            action="{{ route('admin.testimonials.toggle-featured', $testimonial) }}"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="{{ $testimonial->is_featured ? 'text-purple-600 hover:text-purple-900' : 'text-gray-400 hover:text-purple-600' }}"
                                                                title="{{ $testimonial->is_featured ? __('common.remove_from_featured') : __('common.add_to_featured') }}">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    <!-- Delete Button -->
                                                    @can('testimonials.delete')
                                                        <form method="POST"
                                                            action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                                            class="inline"
                                                            data-confirm="{{ __('common.delete_testimonial_confirmation') }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                title="{{ __('common.delete') }}">
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
                            {{ $testimonials->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('common.no_testimonials_found') }}</h3>
                            <p class="text-gray-500">{{ __('common.no_testimonials_matching') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- View Testimonial Modal -->
    <div id="testimonialModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('common.testimonial_details') }}</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="testimonialContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewTestimonial(id) {
            // Show loading state
            document.getElementById('testimonialContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">{{ __('common.loading_testimonial_details') }}</p>
                </div>
            `;
            document.getElementById('testimonialModal').classList.remove('hidden');

            // Fetch testimonial details via AJAX
            fetch(`/admin/testimonials/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Unexpected response format. Status: ${response.status}`);
                    }
                    const data = await response.json();
                    return {
                        ok: response.ok,
                        status: response.status,
                        data
                    };
                })
                .then(result => {
                        if (!result.ok) {
                            if (result.status === 404) {
                                showError('{{ __('common.error') }}!', '{{ __('common.testimonial_not_found') }}');
                            } else if (result.status === 401 || result.status === 403) {
                                showError('{{ __('common.unauthorized') }}!', '{{ __('common.unauthorized_action') }}');
                            } else {
                                showError('{{ __('common.error') }}!', result.data.message || '{{ __('common.error_occurred') }}');
                            }
                            document.getElementById('testimonialModal').classList.add('hidden');
                            return;
                        }

                        if (result.data.success) {
                            const testimonial = result.data.testimonial;
                            document.getElementById('testimonialContent').innerHTML = `
                            <div class="space-y-4">
                                <!-- Author Info -->
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">${testimonial.name}</h4>
                                        <p class="text-sm text-gray-600">${testimonial.position} ${testimonial.graduation_year || testimonial.class || ''}</p>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium">{{ __('common.rating') }}:</span>
                                    <div class="flex items-center">
                                        ${Array.from({length: 5}, (_, i) => 
                                            `<i class="fas fa-star text-sm ${i < testimonial.rating ? 'text-yellow-400' : 'text-gray-300'}"></i>`
                                        ).join('')}
                                    </div>
                                    <span class="text-sm text-gray-500">${testimonial.rating}/5</span>
                                </div>

                                <!-- Testimonial Content -->
                                <div>
                                    <p class="text-gray-900 bg-gray-50 rounded p-3">${testimonial.testimonial}</p>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center space-x-2">
                                    ${testimonial.is_approved ?
                                        '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">{{ __('common.approved') }}</span>' :
                                        '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">{{ __('common.pending') }}</span>'
                                    }
                                    ${testimonial.is_featured ?
                                        '<span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">{{ __('common.featured') }}</span>' : ''
                                    }
                                </div>
                            </div>
                            `;
                        } else {
                            document.getElementById('testimonialContent').innerHTML = `
                                <div class="text-center py-8">
                                    <i class="fas fa-exclamation-triangle text-2xl text-red-400 mb-4"></i>
                                    <p class="text-red-500">{{ __('common.error_occurred') }}</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('testimonialContent').innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-2xl text-red-400 mb-4"></i>
                                <p class="text-red-500">{{ __('common.error_occurred') }}</p>
                            </div>
                        `;
                    });
        }

        function closeModal() {
            document.getElementById('testimonialModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('testimonialModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>
