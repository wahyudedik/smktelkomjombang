<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Bandingkan Tema</h1>
                                <p class="text-gray-600 mt-2">Perbandingan side-by-side pengaturan antara dua tema</p>
                            </div>
                            <a href="<?php echo e(route('admin.themes.index')); ?>"
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
                    <form method="GET" action="<?php echo e(route('admin.themes.compare')); ?>" class="mb-8">
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tema 1 (Kiri)</label>
                                <select name="theme1"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($theme1 === $key ? 'selected' : ''); ?>>
                                            <?php echo e($info['name']); ?> (<?php echo e($key); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($theme2 === $key ? 'selected' : ''); ?>>
                                            <?php echo e($info['name']); ?> (<?php echo e($key); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-purple-600 text-white rounded-md text-sm font-semibold hover:bg-purple-700 transition">
                                Bandingkan
                            </button>
                        </div>
                    </form>

                    <!-- Summary Stats -->
                    <?php
                        $totalKeys = count($comparison);
                        $diffCount = collect($comparison)->where('is_different', true)->count();
                        $sameCount = $totalKeys - $diffCount;
                        $onlyIn1 = collect($comparison)->where('theme1_exists', true)->where('theme2_exists', false)->count();
                        $onlyIn2 = collect($comparison)->where('theme1_exists', false)->where('theme2_exists', true)->count();
                    ?>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-900"><?php echo e($totalKeys); ?></div>
                            <div class="text-xs text-gray-500">Total Keys</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($sameCount); ?></div>
                            <div class="text-xs text-green-600">Sama</div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600"><?php echo e($diffCount); ?></div>
                            <div class="text-xs text-red-600">Berbeda</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($onlyIn1); ?></div>
                            <div class="text-xs text-blue-600">Hanya di <?php echo e($themes[$theme1]['short_name'] ?? $theme1); ?></div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-orange-600"><?php echo e($onlyIn2); ?></div>
                            <div class="text-xs text-orange-600">Hanya di <?php echo e($themes[$theme2]['short_name'] ?? $theme2); ?></div>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="mb-4 flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700">Filter:</label>
                        <select id="compareFilter" onchange="filterComparison()"
                            class="rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all">Semua (<?php echo e($totalKeys); ?>)</option>
                            <option value="different">Berbeda saja (<?php echo e($diffCount); ?>)</option>
                            <option value="same">Sama saja (<?php echo e($sameCount); ?>)</option>
                            <option value="theme1-only">Hanya di <?php echo e($themes[$theme1]['short_name'] ?? $theme1); ?> (<?php echo e($onlyIn1); ?>)</option>
                            <option value="theme2-only">Hanya di <?php echo e($themes[$theme2]['short_name'] ?? $theme2); ?> (<?php echo e($onlyIn2); ?>)</option>
                        </select>
                    </div>

                    <!-- Comparison Table by Group -->
                    <?php $__currentLoopData = $groupedComparison; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-6 comparison-group" data-group="<?php echo e($groupName); ?>">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                <span
                                    class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-gray-600 text-xs mr-2">
                                    <?php echo e($items->count()); ?>

                                </span>
                                <?php echo e(ucfirst($groupName)); ?>

                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 20%">Key</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 35%">
                                                <?php echo e($themes[$theme1]['short_name'] ?? $theme1); ?>

                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                                style="width: 35%">
                                                <?php echo e($themes[$theme2]['short_name'] ?? $theme2); ?>

                                            </th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase"
                                                style="width: 10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="compare-row <?php echo e($item['is_different'] ? 'bg-yellow-50' : ''); ?>"
                                                data-different="<?php echo e($item['is_different'] ? '1' : '0'); ?>"
                                                data-only1="<?php echo e(!$item['theme2_exists'] ? '1' : '0'); ?>"
                                                data-only2="<?php echo e(!$item['theme1_exists'] ? '1' : '0'); ?>">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    <code
                                                        class="bg-gray-100 px-1.5 py-0.5 rounded text-xs"><?php echo e($item['key']); ?></code>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    <?php if($item['theme1_exists']): ?>
                                                        <?php if($item['type'] === 'json'): ?>
                                                            <span
                                                                class="text-xs text-gray-500 italic">[JSON]</span>
                                                            <pre
                                                                class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-auto max-h-32"><?php echo e(@json_decode($item['theme1_value'], true) ? json_encode(json_decode($item['theme1_value'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $item['theme1_value']); ?></pre>
                                                        <?php elseif($item['type'] === 'image'): ?>
                                                            <?php if($item['theme1_value']): ?>
                                                                <span class="text-xs text-gray-500"><?php echo e($item['theme1_value']); ?></span>
                                                            <?php else: ?>
                                                                <span class="text-xs text-gray-400 italic">—</span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="break-words"><?php echo e(Str::limit($item['theme1_value'] ?? '—', 100)); ?></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-xs text-red-400 italic">Tidak ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    <?php if($item['theme2_exists']): ?>
                                                        <?php if($item['type'] === 'json'): ?>
                                                            <span
                                                                class="text-xs text-gray-500 italic">[JSON]</span>
                                                            <pre
                                                                class="mt-1 text-xs bg-gray-50 p-2 rounded overflow-auto max-h-32"><?php echo e(@json_decode($item['theme2_value'], true) ? json_encode(json_decode($item['theme2_value'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $item['theme2_value']); ?></pre>
                                                        <?php elseif($item['type'] === 'image'): ?>
                                                            <?php if($item['theme2_value']): ?>
                                                                <span class="text-xs text-gray-500"><?php echo e($item['theme2_value']); ?></span>
                                                            <?php else: ?>
                                                                <span class="text-xs text-gray-400 italic">—</span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="break-words"><?php echo e(Str::limit($item['theme2_value'] ?? '—', 100)); ?></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-xs text-red-400 italic">Tidak ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <?php if(!$item['theme1_exists'] || !$item['theme2_exists']): ?>
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Unique
                                                        </span>
                                                    <?php elseif($item['is_different']): ?>
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            ≠
                                                        </span>
                                                    <?php else: ?>
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            =
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\PROJEKU\telkom\resources\views/settings/themes/compare.blade.php ENDPATH**/ ?>