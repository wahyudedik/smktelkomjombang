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
                <h1 class="text-2xl font-bold text-slate-900">Laporan Sarana</h1>
                <p class="text-slate-600 mt-1">Trace data kelas/room, barang rusak/hilang/baik, dan perbaikan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.sarpras.reports.exportPdf', request()->all())); ?>" target="_blank" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Export PDF
                </a>
                <a href="<?php echo e(route('admin.sarpras.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Filter Laporan</h3>
            <form method="GET" action="<?php echo e(route('admin.sarpras.reports')); ?>" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label class="form-label">Ruang</label>
                    <select name="ruang_id" class="form-input">
                        <option value="">Semua Ruang</option>
                        <?php $__currentLoopData = $ruangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ruang->id); ?>" <?php echo e(request('ruang_id') == $ruang->id ? 'selected' : ''); ?>>
                                <?php echo e($ruang->nama_ruang); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-input">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kategori->id); ?>" <?php echo e(request('kategori_id') == $kategori->id ? 'selected' : ''); ?>>
                                <?php echo e($kategori->nama_kategori); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-input">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" <?php echo e(request('kondisi') == 'baik' ? 'selected' : ''); ?>>Baik</option>
                        <option value="rusak" <?php echo e(request('kondisi') == 'rusak' ? 'selected' : ''); ?>>Rusak</option>
                        <option value="hilang" <?php echo e(request('kondisi') == 'hilang' ? 'selected' : ''); ?>>Hilang</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Sumber Dana</label>
                    <select name="sumber_dana" class="form-input">
                        <option value="">Semua Sumber Dana</option>
                        <?php $__currentLoopData = $sumberDanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dana); ?>" <?php echo e(request('sumber_dana') == $dana ? 'selected' : ''); ?>>
                                <?php echo e($dana); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="<?php echo e(request('tanggal_dari')); ?>" class="form-input">
                </div>
                <div>
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="<?php echo e(request('tanggal_sampai')); ?>" class="form-input">
                </div>
                <div class="md:col-span-3 lg:col-span-6 flex items-center space-x-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="<?php echo e(route('admin.sarpras.reports')); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Sarana</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['total_sarana']); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Barang</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['total_barang']); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Nilai</p>
                        <p class="text-2xl font-bold text-slate-900">Rp <?php echo e(number_format($stats['total_nilai'], 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Barang Rusak</p>
                        <p class="text-2xl font-bold text-red-600"><?php echo e($stats['kondisi_rusak']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Condition Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Kondisi Baik</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e($stats['kondisi_baik']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Kondisi Rusak</p>
                        <p class="text-2xl font-bold text-red-600"><?php echo e($stats['kondisi_rusak']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Kondisi Hilang</p>
                        <p class="text-2xl font-bold text-gray-600"><?php echo e($stats['kondisi_hilang']); ?></p>
                    </div>
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Perlu Perbaikan -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Barang Perlu Perbaikan</h3>
                <span class="badge badge-red"><?php echo e($barangPerluPerbaikan->count()); ?> barang</span>
            </div>
            <?php if($barangPerluPerbaikan->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Ruang</th>
                                <th>Jumlah</th>
                                <th>Sumber Dana</th>
                                <th>Kode Inventaris</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $barangPerluPerbaikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><span class="font-mono text-sm"><?php echo e($item['kode_barang']); ?></span></td>
                                    <td><?php echo e($item['nama_barang']); ?></td>
                                    <td><?php echo e($item['ruang']); ?></td>
                                    <td><?php echo e($item['jumlah']); ?></td>
                                    <td>
                                        <div>
                                            <span class="font-medium"><?php echo e($item['sumber_dana'] ?? '-'); ?></span>
                                            <?php if($item['kode_sumber_dana']): ?>
                                                <br><span class="text-xs text-slate-500"><?php echo e($item['kode_sumber_dana']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><span class="font-mono text-sm"><?php echo e($item['kode_inventaris']); ?></span></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-500">Tidak ada barang yang perlu perbaikan</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Data Sarana -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Data Sarana</h3>
                <span class="badge badge-blue"><?php echo e($saranas->count()); ?> sarana</span>
            </div>
            <?php if($saranas->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Inventaris</th>
                                <th>Ruang</th>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Sumber Dana</th>
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                                <th>Total Nilai</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $saranas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sarana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $totalNilaiSarana = 0;
                                    $kondisiCount = ['baik' => 0, 'rusak' => 0, 'hilang' => 0];
                                ?>
                                <?php $__currentLoopData = $sarana->barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $jumlah = $barang->pivot->jumlah;
                                        $harga = $barang->harga_beli ?? 0;
                                        $totalNilaiSarana += $harga * $jumlah;
                                        $kondisiCount[$barang->pivot->kondisi] += $jumlah;
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.sarpras.sarana.show', $sarana)); ?>" class="text-blue-600 hover:underline font-mono text-sm">
                                            <?php echo e($sarana->kode_inventaris); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($sarana->ruang->nama_ruang ?? '-'); ?></td>
                                    <td>
                                        <?php if($sarana->barang->count() > 0): ?>
                                            <?php echo e($sarana->barang->first()->nama_barang); ?>

                                            <?php if($sarana->barang->count() > 1): ?>
                                                <span class="text-xs text-slate-500">+<?php echo e($sarana->barang->count() - 1); ?> lainnya</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($sarana->barang->count() > 0): ?>
                                            <?php
                                                $kategoris = $sarana->barang->pluck('kategori.nama_kategori')->filter()->unique();
                                            ?>
                                            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-blue"><?php echo e($kat); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="font-medium"><?php echo e($sarana->sumber_dana ?? '-'); ?></span>
                                            <?php if($sarana->kode_sumber_dana): ?>
                                                <br><span class="text-xs text-slate-500"><?php echo e($sarana->kode_sumber_dana); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo e($sarana->barang->sum('pivot.jumlah')); ?></td>
                                    <td>
                                        <div class="flex flex-col gap-1">
                                            <?php if($kondisiCount['baik'] > 0): ?>
                                                <span class="badge badge-green text-xs">Baik (<?php echo e($kondisiCount['baik']); ?>)</span>
                                            <?php endif; ?>
                                            <?php if($kondisiCount['rusak'] > 0): ?>
                                                <span class="badge badge-red text-xs">Rusak (<?php echo e($kondisiCount['rusak']); ?>)</span>
                                            <?php endif; ?>
                                            <?php if($kondisiCount['hilang'] > 0): ?>
                                                <span class="badge badge-gray text-xs">Hilang (<?php echo e($kondisiCount['hilang']); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="font-semibold">Rp <?php echo e(number_format($totalNilaiSarana, 0, ',', '.')); ?></td>
                                    <td><?php echo e($sarana->tanggal->format('d/m/Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-slate-500">Tidak ada data sarana</p>
                </div>
            <?php endif; ?>
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

<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\reports\index.blade.php ENDPATH**/ ?>