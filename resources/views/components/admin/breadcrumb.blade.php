{{--
    Admin Breadcrumb Component
    Usage:
    <x-admin.breadcrumb
        title="Dashboard"
        :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Siswa', 'url' => route('admin.siswa.index')],
        ]"
    />

    The last item is auto-marked as active (non-clickable).
    If no items provided, only title is shown.
--}}

@props(['title' => '', 'items' => []])

<nav class="mb-4" aria-label="Breadcrumb">
    <ol class="flex items-center flex-wrap text-sm text-slate-500 dark:text-dark-400 space-x-1">
        {{-- Home icon --}}
        <li class="flex items-center">
            <a href="{{ route('admin.dashboard') }}"
               class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
               title="Dashboard">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
        </li>

        @foreach($items as $index => $item)
            <li class="flex items-center">
                {{-- Separator --}}
                <svg class="w-4 h-4 mx-1 text-slate-300 dark:text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                @if($loop->last)
                    {{-- Active item (last) — clickable link --}}
                    <a href="{{ $item['url'] ?? '#' }}"
                       class="font-medium text-slate-700 dark:text-dark-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        {{ $item['label'] ?? '' }}
                    </a>
                @else
                    <span class="text-slate-400 dark:text-dark-500">
                        {{ $item['label'] ?? '' }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>

    {{-- Page title --}}
    @if($title)
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $title }}</h1>
    @endif
</nav>
