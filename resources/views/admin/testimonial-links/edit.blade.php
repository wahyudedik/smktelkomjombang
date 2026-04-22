<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Testimonial Link') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.testimonial-links.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Testimonial Links
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Edit Testimonial Link</h3>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.testimonial-links.update', $testimonialLink) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $testimonialLink->title) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., Testimonial Alumni 2024">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Optional description for this testimonial link">{{ old('description', $testimonialLink->description) }}</textarea>
                        </div>

                        <!-- Target Audience -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_audience[]" value="Siswa"
                                        {{ in_array('Siswa', old('target_audience', $testimonialLink->target_audience)) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Siswa</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_audience[]" value="Guru"
                                        {{ in_array('Guru', old('target_audience', $testimonialLink->target_audience)) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Guru</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_audience[]" value="Alumni"
                                        {{ in_array('Alumni', old('target_audience', $testimonialLink->target_audience)) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Alumni</span>
                                </label>
                            </div>
                        </div>

                        <!-- Active Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="active_from" class="block text-sm font-medium text-gray-700 mb-2">Active
                                    From *</label>
                                <input type="datetime-local" name="active_from" id="active_from"
                                    value="{{ old('active_from', $testimonialLink->active_from->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="active_until" class="block text-sm font-medium text-gray-700 mb-2">Active
                                    Until *</label>
                                <input type="datetime-local" name="active_until" id="active_until"
                                    value="{{ old('active_until', $testimonialLink->active_until->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Max Submissions -->
                        <div>
                            <label for="max_submissions" class="block text-sm font-medium text-gray-700 mb-2">Max
                                Submissions (Optional)</label>
                            <input type="number" name="max_submissions" id="max_submissions"
                                value="{{ old('max_submissions', $testimonialLink->max_submissions) }}" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Leave empty for unlimited submissions">
                            <p class="text-sm text-gray-500 mt-1">Leave empty for unlimited submissions</p>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $testimonialLink->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-1">Uncheck to disable this testimonial link</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.testimonial-links.index') }}"
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>Update Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Link Info Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    <i class="fas fa-link mr-2"></i>
                    Link Information
                </h3>
                <div class="space-y-2 text-blue-800">
                    <p><strong>Token:</strong> {{ $testimonialLink->token }}</p>
                    <p><strong>Public URL:</strong>
                        <a href="{{ $testimonialLink->getPublicUrl() }}" target="_blank"
                            class="text-blue-600 hover:underline">
                            {{ $testimonialLink->getPublicUrl() }}
                        </a>
                    </p>
                    <p><strong>Current Submissions:</strong> {{ $testimonialLink->current_submissions }}</p>
                    <p><strong>Created by:</strong> {{ $testimonialLink->created_by }}</p>
                    <p><strong>Created at:</strong> {{ $testimonialLink->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
