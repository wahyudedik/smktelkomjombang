<?php use Illuminate\Support\Facades\Storage; ?>
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
                <h1 class="text-2xl font-bold text-slate-900">Edit Maintenance</h1>
                <p class="text-slate-600 mt-1">Update maintenance record information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.sarpras.maintenance.show', $maintenance)); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
                <a href="<?php echo e(route('admin.sarpras.maintenance.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-slate-900">Maintenance Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.sarpras.maintenance.update', $maintenance)); ?>"
                    enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Type -->
                        <div>
                            <label for="jenis_item" class="form-label">Item Type</label>
                            <select id="jenis_item" name="jenis_item" required
                                class="form-input <?php $__errorArgs = ['jenis_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Select item type</option>
                                <option value="barang"
                                    <?php echo e(old('jenis_item', $maintenance->jenis_item) === 'barang' ? 'selected' : ''); ?>>
                                    Barang</option>
                                <option value="ruang"
                                    <?php echo e(old('jenis_item', $maintenance->jenis_item) === 'ruang' ? 'selected' : ''); ?>>
                                    Ruang
                                </option>
                            </select>
                            <?php $__errorArgs = ['jenis_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Item ID -->
                        <div>
                            <label for="item_id" class="form-label">Item</label>
                            <select id="item_id" name="item_id" required
                                class="form-input <?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Select item</option>
                                <?php if($maintenance->jenis_item === 'barang'): ?>
                                    <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($barang->id); ?>"
                                            <?php echo e(old('item_id', $maintenance->item_id) == $barang->id ? 'selected' : ''); ?>>
                                            <?php echo e($barang->nama_barang); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php elseif($maintenance->jenis_item === 'ruang'): ?>
                                    <?php $__currentLoopData = $ruangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ruang->id); ?>"
                                            <?php echo e(old('item_id', $maintenance->item_id) == $ruang->id ? 'selected' : ''); ?>>
                                            <?php echo e($ruang->nama_ruang); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Maintenance Type -->
                        <div>
                            <label for="jenis_maintenance" class="form-label">Maintenance Type</label>
                            <select id="jenis_maintenance" name="jenis_maintenance" required
                                class="form-input <?php $__errorArgs = ['jenis_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Select maintenance type</option>
                                <option value="rutin"
                                    <?php echo e(old('jenis_maintenance', $maintenance->jenis_maintenance) === 'rutin' ? 'selected' : ''); ?>>
                                    Rutin</option>
                                <option value="perbaikan"
                                    <?php echo e(old('jenis_maintenance', $maintenance->jenis_maintenance) === 'perbaikan' ? 'selected' : ''); ?>>
                                    Perbaikan</option>
                                <option value="pembersihan"
                                    <?php echo e(old('jenis_maintenance', $maintenance->jenis_maintenance) === 'pembersihan' ? 'selected' : ''); ?>>
                                    Pembersihan</option>
                                <option value="inspeksi"
                                    <?php echo e(old('jenis_maintenance', $maintenance->jenis_maintenance) === 'inspeksi' ? 'selected' : ''); ?>>
                                    Inspeksi</option>
                            </select>
                            <?php $__errorArgs = ['jenis_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" required
                                class="form-input <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Select status</option>
                                <option value="dijadwalkan"
                                    <?php echo e(old('status', $maintenance->status) === 'dijadwalkan' ? 'selected' : ''); ?>>
                                    Dijadwalkan</option>
                                <option value="sedang_dikerjakan"
                                    <?php echo e(old('status', $maintenance->status) === 'sedang_dikerjakan' ? 'selected' : ''); ?>>
                                    Sedang Dikerjakan</option>
                                <option value="dalam_proses"
                                    <?php echo e(old('status', $maintenance->status) === 'dalam_proses' ? 'selected' : ''); ?>>
                                    Dalam Proses</option>
                                <option value="selesai"
                                    <?php echo e(old('status', $maintenance->status) === 'selesai' ? 'selected' : ''); ?>>Selesai
                                </option>
                                <option value="dibatalkan"
                                    <?php echo e(old('status', $maintenance->status) === 'dibatalkan' ? 'selected' : ''); ?>>
                                    Dibatalkan</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        <!-- Cost -->
                        <div>
                            <label for="biaya" class="form-label">Cost (Rp)</label>
                            <input type="number" id="biaya" name="biaya"
                                value="<?php echo e(old('biaya', $maintenance->biaya)); ?>"
                                class="form-input <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Enter maintenance cost" min="0">
                            <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Technician -->
                        <div>
                            <label for="teknisi" class="form-label">Technician</label>
                            <input type="text" id="teknisi" name="teknisi"
                                value="<?php echo e(old('teknisi', $maintenance->teknisi)); ?>"
                                class="form-input <?php $__errorArgs = ['teknisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Enter technician name">
                            <?php $__errorArgs = ['teknisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Maintenance Date -->
                        <div>
                            <label for="tanggal_maintenance" class="form-label">Maintenance Date</label>
                            <input type="date" id="tanggal_maintenance" name="tanggal_maintenance"
                                value="<?php echo e(old('tanggal_maintenance', $maintenance->tanggal_maintenance?->format('Y-m-d'))); ?>"
                                class="form-input <?php $__errorArgs = ['tanggal_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['tanggal_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Completion Date -->
                        <div>
                            <label for="tanggal_selesai" class="form-label">Completion Date</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                value="<?php echo e(old('tanggal_selesai', $maintenance->tanggal_selesai?->format('Y-m-d'))); ?>"
                                class="form-input <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Problem Description -->
                    <div>
                        <label for="deskripsi_masalah" class="form-label">Problem Description</label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4"
                            class="form-input <?php $__errorArgs = ['deskripsi_masalah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Enter problem description"><?php echo e(old('deskripsi_masalah', $maintenance->deskripsi_masalah)); ?></textarea>
                        <?php $__errorArgs = ['deskripsi_masalah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Repair Action -->
                    <div>
                        <label for="tindakan_perbaikan" class="form-label">Repair Action</label>
                        <textarea id="tindakan_perbaikan" name="tindakan_perbaikan" rows="4"
                            class="form-input <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Enter repair action taken"><?php echo e(old('tindakan_perbaikan', $maintenance->tindakan_perbaikan)); ?></textarea>
                        <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="catatan" class="form-label">Notes</label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="form-input <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Enter maintenance notes"><?php echo e(old('catatan', $maintenance->catatan)); ?></textarea>
                        <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Before Photo -->
                    <div>
                        <label for="foto_sebelum" class="form-label">Before Photo</label>
                        <input type="file" id="foto_sebelum" name="foto_sebelum" accept="image/*"
                            class="form-input <?php $__errorArgs = ['foto_sebelum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['foto_sebelum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- After Photo -->
                    <div>
                        <label for="foto_sesudah" class="form-label">After Photo</label>
                        <input type="file" id="foto_sesudah" name="foto_sesudah" accept="image/*"
                            class="form-input <?php $__errorArgs = ['foto_sesudah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['foto_sesudah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="form-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Current Photos -->
                    <?php if($maintenance->foto_sebelum || $maintenance->foto_sesudah): ?>
                        <div>
                            <label class="form-label">Current Photos</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if($maintenance->foto_sebelum): ?>
                                    <div class="relative">
                                        <img src="<?php echo e(Storage::url($maintenance->foto_sebelum)); ?>" alt="Before photo"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <p class="text-sm text-slate-500 mt-1">Before Photo</p>
                                    </div>
                                <?php endif; ?>
                                <?php if($maintenance->foto_sesudah): ?>
                                    <div class="relative">
                                        <img src="<?php echo e(Storage::url($maintenance->foto_sesudah)); ?>" alt="After photo"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <p class="text-sm text-slate-500 mt-1">After Photo</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                        <a href="<?php echo e(route('admin.sarpras.maintenance.show', $maintenance)); ?>"
                            class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Maintenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Data untuk dropdown
        const barangs = <?php echo json_encode($barangs, 15, 512) ?>;
        const ruangs = <?php echo json_encode($ruangs, 15, 512) ?>;

        // Update item options based on item type
        document.getElementById('jenis_item').addEventListener('change', function() {
            const itemType = this.value;
            const itemSelect = document.getElementById('item_id');

            // Clear existing options
            itemSelect.innerHTML = '<option value="">Select item</option>';

            if (itemType === 'barang') {
                barangs.forEach(function(barang) {
                    itemSelect.innerHTML += '<option value="' + barang.id + '">' + barang.nama_barang +
                        '</option>';
                });
            } else if (itemType === 'ruang') {
                ruangs.forEach(function(ruang) {
                    itemSelect.innerHTML += '<option value="' + ruang.id + '">' + ruang.nama_ruang +
                        '</option>';
                });
            }
        });

        // Trigger change event on page load if jenis_item has a value
        document.addEventListener('DOMContentLoaded', function() {
            const itemTypeSelect = document.getElementById('jenis_item');
            const itemSelect = document.getElementById('item_id');
            const currentItemId = itemSelect.value; // Get current selected item ID

            if (itemTypeSelect.value) {
                // Trigger change event to populate dropdown
                itemTypeSelect.dispatchEvent(new Event('change'));

                // After a short delay, set the selected value
                setTimeout(function() {
                    if (currentItemId) {
                        itemSelect.value = currentItemId;
                    }
                }, 100);
            }
        });

        function removePhoto(photoName) {
            showConfirm(
                'Konfirmasi',
                'Apakah Anda yakin ingin menghapus foto ini?',
                'Ya, Hapus',
                'Batal'
            ).then((result) => {
                if (result.isConfirmed) {
                    // In a real implementation, this would make an AJAX call to remove the photo
                    console.log('Removing photo:', photoName);
                    showSuccess('Foto berhasil dihapus');
                }
            });
        }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\maintenance\edit.blade.php ENDPATH**/ ?>