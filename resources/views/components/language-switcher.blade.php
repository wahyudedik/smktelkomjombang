@php
    $currentLocale = app()->getLocale();
    $availableLocales = function_exists('get_available_locales') ? get_available_locales() : config('i18n.locales', []);
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
        <span>{{ $availableLocales[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="hidden md:inline">{{ strtoupper($currentLocale) }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ function_exists('is_rtl') && is_rtl() ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-slate-200">
        <div class="py-1">
            @foreach ($availableLocales as $code => $locale)
                <a href="{{ route('locale.switch', $code) }}"
                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors {{ $code === $currentLocale ? 'bg-slate-50 font-medium' : '' }}">
                    <span class="mr-3">{{ $locale['flag'] }}</span>
                    <span>{{ $locale['native'] }}</span>
                    @if ($code === $currentLocale)
                        <svg class="w-4 h-4 ml-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
