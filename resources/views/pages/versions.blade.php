<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Page Versions</h1>
                <p class="text-slate-600 mt-1">{{ $page->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pages.public.show', $page->slug) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Page
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Version History</h3>
                <p class="text-slate-600 mt-1">Track changes and restore previous versions</p>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($versions as $version)
                        <div class="border border-slate-200 rounded-lg p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span
                                            class="text-sm font-bold text-blue-600">v{{ $version->version_number }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-900">
                                            {{ $version->change_summary ?? 'Version ' . $version->version_number }}</h4>
                                        <p class="text-sm text-slate-500">
                                            Created by {{ $version->user->name ?? 'System' }} •
                                            {{ $version->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('pages.public.show', $page->slug) }}"
                                        class="btn btn-sm btn-secondary">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        View
                                    </a>
                                    <form method="POST"
                                        action="{{ route('admin.pages.versions.restore', [$page, $version]) }}"
                                        class="inline" data-confirm="Are you sure you want to restore this version?">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Restore
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-slate-500">No versions found</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $versions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
