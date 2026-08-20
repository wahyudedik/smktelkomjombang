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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Sarana</h1>
                <p class="text-slate-600 mt-1"><?php echo e($sarana->kode_inventaris); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.sarpras.sarana.edit', $sarana)); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="<?php echo e(route('admin.sarpras.sarana.printInvoice', $sarana)); ?>" target="_blank" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Invoice
                </a>
                <a href="<?php echo e(route('admin.sarpras.sarana.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Sarana Info -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Informasi Sarana</h3>
                        <span class="font-mono text-sm font-semibold text-blue-600">
                            <?php echo e($sarana->kode_inventaris); ?>

                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Ruang</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($sarana->ruang->nama_ruang ?? '-'); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($sarana->ruang->kode_ruang ?? ''); ?></p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Tanggal</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($sarana->formatted_tanggal); ?></p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Sumber Dana</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($sarana->sumber_dana ?? '-'); ?></p>
                            <?php if($sarana->kode_sumber_dana): ?>
                                <p class="text-sm text-slate-500"><?php echo e($sarana->kode_sumber_dana); ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Total Jumlah</h4>
                            <p class="text-lg font-semibold text-slate-900"><?php echo e($sarana->total_jumlah); ?></p>
                        </div>
                    </div>

                    <?php if($sarana->catatan): ?>
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Catatan</h4>
                            <p class="text-slate-900"><?php echo e($sarana->catatan); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Barang List -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Daftar Barang</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $grandTotal = 0;
                                ?>
                                <?php $__currentLoopData = $sarana->barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $hargaBeli = $barang->harga_beli ?? 0;
                                        $jumlah = $barang->pivot->jumlah;
                                        $totalItem = $hargaBeli * $jumlah;
                                        $grandTotal += $totalItem;
                                    ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <p class="font-medium text-slate-900"><?php echo e($barang->nama_barang); ?></p>
                                        </td>
                                        <td>
                                            <span class="font-mono text-sm text-slate-600"><?php echo e($barang->kode_barang); ?></span>
                                        </td>
                                        <td>
                                            <?php if($barang->kategori): ?>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?php echo e($barang->kategori->nama_kategori); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="font-semibold text-slate-900"><?php echo e($jumlah); ?></span>
                                        </td>
                                        <td>
                                            <span class="text-slate-900">Rp <?php echo e(number_format($hargaBeli, 0, ',', '.')); ?></span>
                                        </td>
                                        <td>
                                            <span class="font-semibold text-slate-900">Rp <?php echo e(number_format($totalItem, 0, ',', '.')); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                                $badgeColor = match ($barang->pivot->kondisi) {
                                                    'baik' => 'green',
                                                    'rusak' => 'red',
                                                    'hilang' => 'gray',
                                                    default => 'gray',
                                                };
                                                $kondisiText = match ($barang->pivot->kondisi) {
                                                    'baik' => 'Baik',
                                                    'rusak' => 'Rusak',
                                                    'hilang' => 'Hilang',
                                                    default => 'Tidak Diketahui',
                                                };
                                            ?>
                                            <span class="badge badge-<?php echo e($badgeColor); ?>"><?php echo e($kondisiText); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-blue-50">
                                    <td colspan="6" class="text-right font-bold text-slate-900">Grand Total:</td>
                                    <td class="font-bold text-blue-600">Rp <?php echo e(number_format($grandTotal, 0, ',', '.')); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- History / Audit Trail -->
                <?php if($auditLogs && $auditLogs->count() > 0): ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">History Perubahan</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                    <div class="flex-shrink-0">
                                        <?php
                                            $actionConfig = match ($log->action) {
                                                'create' => [
                                                    'color' => 'green',
                                                    'bg' => 'bg-green-100',
                                                    'text' => 'text-green-600',
                                                    'badge' => 'bg-green-100 text-green-800',
                                                    'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
                                                ],
                                                'update' => [
                                                    'color' => 'blue',
                                                    'bg' => 'bg-blue-100',
                                                    'text' => 'text-blue-600',
                                                    'badge' => 'bg-blue-100 text-blue-800',
                                                    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                                                ],
                                                'delete' => [
                                                    'color' => 'red',
                                                    'bg' => 'bg-red-100',
                                                    'text' => 'text-red-600',
                                                    'badge' => 'bg-red-100 text-red-800',
                                                    'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                                                ],
                                                default => [
                                                    'color' => 'gray',
                                                    'bg' => 'bg-gray-100',
                                                    'text' => 'text-gray-600',
                                                    'badge' => 'bg-gray-100 text-gray-800',
                                                    'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                                ],
                                            };
                                        ?>
                                        <div class="w-10 h-10 <?php echo e($actionConfig['bg']); ?> rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 <?php echo e($actionConfig['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($actionConfig['icon']); ?>" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-medium text-slate-900">
                                                    <?php echo e(ucfirst($log->action)); ?> 
                                                    <?php if($log->user): ?>
                                                        oleh <span class="text-blue-600"><?php echo e($log->user->name); ?></span>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    <?php echo e($log->created_at->format('d M Y, H:i')); ?> 
                                                    (<?php echo e($log->created_at->diffForHumans()); ?>)
                                                </p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($actionConfig['badge']); ?>">
                                                <?php echo e(ucfirst($log->action)); ?>

                                            </span>
                                        </div>
                                        
                                        <?php if($log->action === 'update' && $log->old_values && $log->new_values): ?>
                                            <div class="mt-3 space-y-2">
                                                <?php
                                                    $changedFields = [];
                                                    foreach ($log->new_values as $key => $newValue) {
                                                        $oldValue = $log->old_values[$key] ?? null;
                                                        if ($oldValue != $newValue) {
                                                            $changedFields[$key] = [
                                                                'old' => $oldValue,
                                                                'new' => $newValue,
                                                            ];
                                                        }
                                                    }
                                                ?>
                                                
                                                <?php if(count($changedFields) > 0): ?>
                                                    <div class="text-xs font-medium text-slate-700 mb-2">Perubahan:</div>
                                                    <div class="space-y-1">
                                                        <?php $__currentLoopData = $changedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="flex items-start space-x-2 text-xs">
                                                                <span class="font-medium text-slate-600 capitalize"><?php echo e(str_replace('_', ' ', $field)); ?>:</span>
                                                                <div class="flex-1">
                                                                    <div class="flex flex-wrap items-center gap-2">
                                                                        <span class="text-red-600 line-through"><?php echo e($values['old'] ?? '-'); ?></span>
                                                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                        </svg>
                                                                        <span class="text-green-600 font-medium"><?php echo e($values['new'] ?? '-'); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif($log->action === 'create' && $log->new_values): ?>
                                            <div class="mt-2 text-xs text-slate-600">
                                                <span class="font-medium">Data yang dibuat:</span>
                                                <ul class="list-disc list-inside mt-1 space-y-1">
                                                    <?php $__currentLoopData = array_slice($log->new_values, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <span class="capitalize"><?php echo e(str_replace('_', ' ', $key)); ?>:</span>
                                                            <span class="font-medium"><?php echo e($value ?? '-'); ?></span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(count($log->new_values) > 5): ?>
                                                        <li class="text-slate-400">... dan <?php echo e(count($log->new_values) - 5); ?> field lainnya</li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if($log->ip_address): ?>
                                            <div class="mt-2 text-xs text-slate-400">
                                                IP: <?php echo e($log->ip_address); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">History Perubahan</h3>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-slate-500">Belum ada history perubahan</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.sarpras.sarana.edit', $sarana)); ?>"
                            class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Edit Sarana</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="<?php echo e(route('admin.sarpras.sarana.printInvoice', $sarana)); ?>" target="_blank"
                            class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-slate-900">Cetak Invoice</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Total Barang</span>
                            <span class="text-sm font-semibold text-slate-900"><?php echo e($sarana->barang->count()); ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Total Jumlah</span>
                            <span class="text-sm font-semibold text-slate-900"><?php echo e($sarana->total_jumlah); ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Dibuat</span>
                            <span class="text-sm text-slate-900"><?php echo e($sarana->created_at->format('d M Y')); ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <span class="text-sm text-slate-600">Diperbarui</span>
                            <span class="text-sm text-slate-900"><?php echo e($sarana->updated_at->format('d M Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\sarana\show.blade.php ENDPATH**/ ?>