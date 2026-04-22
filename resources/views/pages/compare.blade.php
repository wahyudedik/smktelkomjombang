<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Compare Versions</h1>
                <p class="text-slate-600 mt-1">{{ $page->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.pages.versions', $page) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Versions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Version 1 -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Version {{ $version1->version_number }}</h3>
                    <p class="text-slate-600 mt-1">
                        {{ $version1->change_summary ?? 'Version ' . $version1->version_number }}</p>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-500">Title</label>
                            <p class="mt-1 text-slate-900">{{ $version1->title }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Content</label>
                            <div class="mt-1 prose max-w-none">
                                {!! $version1->content !!}
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Status</label>
                            <p class="mt-1">
                                <span class="badge badge-info">{{ ucfirst($version1->status) }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Created</label>
                            <p class="mt-1 text-slate-900">{{ $version1->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version 2 -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-slate-900">Version {{ $version2->version_number }}</h3>
                    <p class="text-slate-600 mt-1">
                        {{ $version2->change_summary ?? 'Version ' . $version2->version_number }}</p>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-500">Title</label>
                            <p class="mt-1 text-slate-900">{{ $version2->title }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Content</label>
                            <div class="mt-1 prose max-w-none">
                                {!! $version2->content !!}
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Status</label>
                            <p class="mt-1">
                                <span class="badge badge-info">{{ ucfirst($version2->status) }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Created</label>
                            <p class="mt-1 text-slate-900">{{ $version2->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions</h3>
            <div class="flex items-center space-x-4">
                <form method="POST" action="{{ route('admin.pages.versions.restore', [$page, $version1]) }}"
                    class="inline"
                    data-confirm="Are you sure you want to restore version {{ $version1->version_number }}?">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Restore Version {{ $version1->version_number }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.pages.versions.restore', [$page, $version2]) }}"
                    class="inline"
                    data-confirm="Are you sure you want to restore version {{ $version2->version_number }}?">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Restore Version {{ $version2->version_number }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
