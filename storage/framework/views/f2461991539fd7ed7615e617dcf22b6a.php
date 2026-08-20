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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Edit Format Surat')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg space-y-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Edit Format: <?php echo e($letterFormat->name); ?></h2>
                    <a href="<?php echo e(route('admin.letters.formats.index')); ?>" class="text-slate-500 hover:text-slate-700">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>

                <form method="POST"
                    action="<?php echo e(route('admin.letters.formats.update', ['format' => $letterFormat->id])); ?>"
                    class="space-y-6" id="format-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700">Jenis Surat</label>
                            <select id="type" name="type"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="out" <?php if(old('type', $letterFormat->type) === 'out'): echo 'selected'; endif; ?>>Keluar</option>
                                <option value="in" <?php if(old('type', $letterFormat->type) === 'in'): echo 'selected'; endif; ?>>Masuk</option>
                            </select>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('type')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('type'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <label for="period_mode" class="block text-sm font-medium text-slate-700">Reset
                                Counter</label>
                            <select id="period_mode" name="period_mode"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="year" <?php if(old('period_mode', $letterFormat->period_mode) === 'year'): echo 'selected'; endif; ?>>Tahunan</option>
                                <option value="month" <?php if(old('period_mode', $letterFormat->period_mode) === 'month'): echo 'selected'; endif; ?>>Bulanan</option>
                                <option value="all" <?php if(old('period_mode', $letterFormat->period_mode) === 'all'): echo 'selected'; endif; ?>>Tidak Reset</option>
                            </select>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('period_mode')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('period_mode'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <label for="counter_scope" class="block text-sm font-medium text-slate-700">Scope
                                Counter</label>
                            <select id="counter_scope" name="counter_scope"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="global" <?php if(old('counter_scope', $letterFormat->counter_scope) === 'global'): echo 'selected'; endif; ?>>Global</option>
                                <option value="unit" <?php if(old('counter_scope', $letterFormat->counter_scope) === 'unit'): echo 'selected'; endif; ?>>Per Unit</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Global: nomor berurutan untuk semua unit. Per Unit:
                                nomor terpisah per kode unit.</p>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('counter_scope')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('counter_scope'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Nama Format</label>
                            <input id="name" name="name" type="text"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="<?php echo e(old('name', $letterFormat->name)); ?>" required
                                placeholder="Contoh: Surat Keputusan" />
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description"
                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            rows="3"><?php echo e(old('description', $letterFormat->description)); ?></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                            class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            <?php echo e(old('is_active', $letterFormat->is_active) ? 'checked' : ''); ?>>
                        <label for="is_active" class="ml-2 text-sm text-slate-600">Format Aktif</label>
                    </div>

                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <label class="block text-sm font-medium text-slate-700">Segmen Format</label>
                            <button type="button" id="add-segment" class="text-sm text-blue-600 hover:underline">Tambah
                                Segmen</button>
                        </div>
                        <div class="mt-3 rounded-md border border-blue-100 bg-blue-50 p-3 text-sm text-blue-900">
                            <div class="font-semibold mb-1">Tips menyusun segmen</div>
                            <ul class="space-y-1 text-xs text-blue-900 list-disc list-inside">
                                <li><span class="font-semibold">sequence</span>: nomor urut (padding = jumlah digit,
                                    contoh 3 → 001)</li>
                                <li><span class="font-semibold">text</span>: teks statis seperti <code
                                        class="px-1 bg-white rounded">/SK/</code> atau <code
                                        class="px-1 bg-white rounded">/</code></li>
                                <li><span class="font-semibold">unit_code</span>: kode unit dari profil user (wajib jika
                                    counter per unit)</li>
                                <li><span class="font-semibold">day</span>: tanggal (01-31)</li>
                                <li><span class="font-semibold">month_roman</span>: bulan Romawi (I-XII)</li>
                                <li><span class="font-semibold">month_number</span>: bulan angka (01-12)</li>
                                <li><span class="font-semibold">year</span>: tahun angka (2026)</li>
                                <li><span class="font-semibold">year_roman</span>: tahun Romawi (MMXXVI)</li>
                            </ul>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Preview: <span id="format-preview"
                                class="font-semibold text-slate-800 bg-slate-100 px-2 py-1 rounded">-</span></p>
                        <div class="mt-2 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left text-slate-600 bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2 rounded-tl-lg">Tipe Segmen</th>
                                        <th class="px-4 py-2">Nilai (Teks)</th>
                                        <th class="px-4 py-2">Padding (Digit)</th>
                                        <th class="px-4 py-2 rounded-tr-lg text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="segments-body" class="divide-y divide-slate-200 bg-white">
                                    <?php $__currentLoopData = $letterFormat->segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="segment-row">
                                            <td class="px-4 py-2">
                                                <select name="segments[<?php echo e($index); ?>][kind]"
                                                    class="w-full rounded-md border-slate-300 segment-kind text-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">-- Pilih Tipe --</option>
                                                    <option value="sequence" <?php if($segment->type === 'sequence'): echo 'selected'; endif; ?>>Nomor Urut
                                                        (Sequence)
                                                    </option>
                                                    <option value="text" <?php if($segment->type === 'text'): echo 'selected'; endif; ?>>Teks Statis
                                                        (Text)</option>
                                                    <option value="unit_code" <?php if($segment->type === 'unit_code'): echo 'selected'; endif; ?>>Kode Unit
                                                    </option>
                                                    <option value="day" <?php if($segment->type === 'day'): echo 'selected'; endif; ?>>Tanggal
                                                        (01-31)</option>
                                                    <option value="month_roman" <?php if($segment->type === 'month_roman'): echo 'selected'; endif; ?>>Bulan
                                                        Romawi (I-XII)</option>
                                                    <option value="month_number" <?php if($segment->type === 'month_number'): echo 'selected'; endif; ?>>Bulan
                                                        Angka (01-12)</option>
                                                    <option value="year" <?php if($segment->type === 'year'): echo 'selected'; endif; ?>>Tahun (2025)
                                                    </option>
                                                    <option value="year_roman" <?php if($segment->type === 'year_roman'): echo 'selected'; endif; ?>>Tahun
                                                        Romawi</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input name="segments[<?php echo e($index); ?>][value]" type="text"
                                                    value="<?php echo e($segment->value); ?>"
                                                    class="w-full rounded-md border-slate-300 segment-value text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Hanya untuk Teks" />
                                            </td>
                                            <td class="px-4 py-2">
                                                <input name="segments[<?php echo e($index); ?>][padding]" type="number"
                                                    min="0" max="10" value="<?php echo e($segment->padding); ?>"
                                                    class="w-full rounded-md border-slate-300 segment-padding text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Untuk Sequence" />
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button"
                                                    class="text-red-500 hover:text-red-700 remove-segment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                        <a href="<?php echo e(route('admin.letters.formats.index')); ?>"
                            class="text-sm text-slate-600 hover:text-slate-800">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.getElementById('segments-body');
            const addButton = document.getElementById('add-segment');
            const preview = document.getElementById('format-preview');

            const monthRoman = (month) => {
                const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                return map[month - 1] ?? '';
            };

            const toRoman = (num) => {
                const map = [
                    [1000, 'M'],
                    [900, 'CM'],
                    [500, 'D'],
                    [400, 'CD'],
                    [100, 'C'],
                    [90, 'XC'],
                    [50, 'L'],
                    [40, 'XL'],
                    [10, 'X'],
                    [9, 'IX'],
                    [5, 'V'],
                    [4, 'IV'],
                    [1, 'I'],
                ];
                let result = '';
                let value = num;
                map.forEach(([n, r]) => {
                    while (value >= n) {
                        result += r;
                        value -= n;
                    }
                });
                return result;
            };

            const updatePreview = () => {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = String(now.getFullYear());

                const rows = Array.from(body.querySelectorAll('tr.segment-row'));
                if (rows.length === 0) {
                    preview.textContent = '-';
                    return;
                }

                const parts = rows.map((row) => {
                    const kind = row.querySelector('.segment-kind')?.value;
                    const value = row.querySelector('.segment-value')?.value || '';
                    const padding = parseInt(row.querySelector('.segment-padding')?.value || '0', 10);

                    switch (kind) {
                        case 'sequence':
                            return String(1).padStart(padding || 3, '0');
                        case 'text':
                            return value;
                        case 'unit_code':
                            return 'UNIT';
                        case 'day':
                            return day;
                        case 'month_roman':
                            return monthRoman(parseInt(month, 10));
                        case 'month_number':
                            return month;
                        case 'year':
                            return year;
                        case 'year_roman':
                            return toRoman(parseInt(year, 10));
                        default:
                            return '';
                    }
                }).filter(Boolean);

                preview.textContent = parts.length ? parts.join('') : '-';
            };

            const addRow = () => {
                const idx = new Date().getTime();
                const row = document.createElement('tr');
                row.classList.add('segment-row');
                row.innerHTML = `
                    <td class="px-4 py-2">
                        <select name="segments[${idx}][kind]" class="w-full rounded-md border-slate-300 segment-kind text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="sequence">Nomor Urut (Sequence)</option>
                            <option value="text">Teks Statis (Text)</option>
                            <option value="unit_code">Kode Unit</option>
                            <option value="day">Tanggal (01-31)</option>
                            <option value="month_roman">Bulan Romawi (I-XII)</option>
                            <option value="month_number">Bulan Angka (01-12)</option>
                            <option value="year">Tahun (2025)</option>
                            <option value="year_roman">Tahun Romawi</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input name="segments[${idx}][value]" type="text" class="w-full rounded-md border-slate-300 segment-value text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hanya untuk Teks" />
                    </td>
                    <td class="px-4 py-2">
                        <input name="segments[${idx}][padding]" type="number" min="0" max="10" class="w-full rounded-md border-slate-300 segment-padding text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Untuk Sequence" />
                    </td>
                    <td class="px-4 py-2 text-right">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-segment">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                body.appendChild(row);
                attachListeners(row);
                updatePreview();
            };

            const attachListeners = (row) => {
                row.querySelector('.remove-segment').addEventListener('click', () => {
                    row.remove();
                    updatePreview();
                });

                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
                });
            };

            // Attach listeners to existing rows (rendered by server)
            body.querySelectorAll('tr.segment-row').forEach(row => {
                attachListeners(row);
            });

            addButton.addEventListener('click', () => {
                addRow();
            });

            updatePreview();
        });
    </script>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\admin\letters\formats\edit.blade.php ENDPATH**/ ?>