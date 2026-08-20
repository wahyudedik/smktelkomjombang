<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Bandingkan Tema</h1>
                                <p class="text-gray-600 mt-2">Perbandingan side-by-side pengaturan antara dua tema</p>
                            </div>
                            <a href="{{ route('admin.themes.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Theme Selector -->
                    <form method="GET" action="{{ route('admin.themes.compare') }}" class="mb-8">
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tema 1 (Kiri)</label>
                                <select name="theme1"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    @foreach ($themes as $key => $info)
                                        <option value="{{ $key }}" {{ $theme1 === $key ? 'selected' : '' }}>
                                            {{ $info['name'] }} ({{ $key }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center pb-1">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tema 2 (Kanan)</label>
                                <select name="theme2"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    @foreach ($themes as $key => $info)
                                        <option value="{{ $key }}" {{ $theme2 === $key ? 'selected' : '' }}>
                                            {{ $info['name'] }} ({{ $key }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-purple-600 text-white rounded-md text-sm font-semibold hover:bg-purple-700 transition">
                                Bandingkan
                            </button>
                        </div>
                    </form>

                    <!-- Summary Stats -->
                    @php
                        $totalKeys = count($comparison);
                        $diffCount = collect($comparison)->where('is_different', true)->count();
                        $sameCount = $totalKeys - $diffCount;
                        $onlyIn1 = collect($comparison)->where('theme1_exists', true)->where('theme2_exists', false)->count();
                        $onlyIn2 = collect($comparison)->where('theme1_exists', false)->where('theme2_exists', true)->count();
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $totalKeys }}</div>
                            <div class="text-xs text-gray-500">Total Keys</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $sameCount }}</div>
                            <div class="text-xs text-green-600">Sama</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $diffCount }}</div>
                            <div class="text-xs text-red-600">Berbeda</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $onlyIn1 }}</div>
                            <div class="text-xs text-blue-600">Hanya di {{ $themes[$theme1]['short_name'] ?? $theme1 }}</div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $onlyIn2 }}</div>
                            <div class="text-xs text-orange-600">Hanya di {{ $themes[$theme2]['short_name'] ?? $theme2 }}</div>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="mb-4 flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700">Filter:</label>
                        <select id="compareFilter" onchange="filterComparison()"
                            class="rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all">Semua ({{ $totalKeys }})</option>
                            <option value="different">Berbeda saja ({{ $diffCount }})</option>
                            <option value="same">Sama saja ({{ $sameCount }})</option>
                            <option value="theme1-only">Hanya di {{ $themes[$theme1]['short_name'] ?? $theme1 }} ({{ $onlyIn1 }})</option>
                            <option value="theme2-only">Hanya di {{ $themes[$theme2]['short_name'] ?? $theme2 }} ({{ $onlyIn2 }})</option>
                        </select>
                    </div>

                    <!-- Comparison Table by Group -->
                    @foreach ($groupedComparison as $groupName => $items)
                        <div class="mb-6 comparison-group" data-group="{{ $groupName }}">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                <span
                                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-gray-600 text-xs mr-2">
                                    {{ $items->count() }}
                                </span>
                                {{ ucfirst($groupName) }}
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 20%">Key</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 35%">
                                                {{ $themes[$theme1]['short_name'] ?? $theme1 }}
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 35%">
                                                {{ $themes[$theme2]['short_name'] ?? $theme2 }}
                                            </th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                                                style="width: 10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($items as $item)
                                            <tr class="compare-row {{ $item['is_different'] ? 'bg-yellow-50' : '' }}"
                                                data-different="{{ $item['is_different'] ? '1' : '0' }}"
                                                data-only1="{{ !$item['theme2_exists'] ? '1' : '0' }}"
                                                data-only2="{{ !$item['theme1_exists'] ? '1' : '0' }}">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    <code
                                                        class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $item['key'] }}</code>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    @if ($item['theme1_exists'])
                                                        @if ($item['type'] === 'json')
                                                            <span
                                                                class="text-xs text-gray-500 italic">[JSON]</span>
                                                            <pre
                                                                class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-auto max-h-32">{{ @json_decode($item['theme1_value'], true) ? json_encode(json_decode($item['theme1_value'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $item['theme1_value'] }}</pre>
                                                        @elseif ($item['type'] === 'image')
                                                            @if ($item['theme1_value'])
                                                                <span class="text-xs text-gray-500">{{ $item['theme1_value'] }}</span>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">—</span>
                                                            @endif
                                                        @else
                                                            <span class="break-words">{{ Str::limit($item['theme1_value'] ?? '—', 100) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-red-400 italic">Tidak ada</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    @if ($item['theme2_exists'])
                                                        @if ($item['type'] === 'json')
                                                            <span
                                                                class="text-xs text-gray-500 italic">[JSON]</span>
                                                            <pre
                                                                class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-auto max-h-32">{{ @json_decode($item['theme2_value'], true) ? json_encode(json_decode($item['theme2_value'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $item['theme2_value'] }}</pre>
                                                        @elseif ($item['type'] === 'image')
                                                            @if ($item['theme2_value'])
                                                                <span class="text-xs text-gray-500">{{ $item['theme2_value'] }}</span>
                                                            @else
                                                                <span class="text-xs text-gray-400 italic">—</span>
                                                            @endif
                                                        @else
                                                            <span class="break-words">{{ Str::limit($item['theme2_value'] ?? '—', 100) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-red-400 italic">Tidak ada</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if (!$item['theme1_exists'] || !$item['theme2_exists'])
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Unique
                                                        </span>
                                                    @elseif ($item['is_different'])
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ≠
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            =
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function filterComparison() {
            const filter = document.getElementById('compareFilter').value;
            const rows = document.querySelectorAll('.compare-row');

            rows.forEach(row => {
                const isDifferent = row.dataset.different === '1';
                const only1 = row.dataset.only1 === '1';
                const only2 = row.dataset.only2 === '1';

                let show = true;
                switch (filter) {
                    case 'different':
                        show = isDifferent || only1 || only2;
                        break;
                    case 'same':
                        show = !isDifferent && !only1 && !only2;
                        break;
                    case 'theme1-only':
                        show = only1;
                        break;
                    case 'theme2-only':
                        show = only2;
                        break;
                    default:
                        show = true;
                }

                row.style.display = show ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>
