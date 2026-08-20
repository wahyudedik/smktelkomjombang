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
                <h1 class="text-2xl font-bold text-slate-900">Tambah Maintenance Sarpras</h1>
                <p class="text-slate-600 mt-1">Tambah data maintenance dan perawatan sarana prasarana</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.sarpras.maintenance.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="<?php echo e(route('admin.sarpras.maintenance.store')); ?>" enctype="multipart/form-data"
                class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Basic Information -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jenis_maintenance" class="form-label">Jenis Maintenance</label>
                            <select id="jenis_maintenance" name="jenis_maintenance"
                                class="form-input <?php $__errorArgs = ['jenis_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                                <option value="">Pilih Jenis Maintenance</option>
                                <option value="rutin" <?php echo e(old('jenis_maintenance') == 'rutin' ? 'selected' : ''); ?>>Rutin
                                </option>
                                <option value="perbaikan"
                                    <?php echo e(old('jenis_maintenance') == 'perbaikan' ? 'selected' : ''); ?>>Perbaikan</option>
                                <option value="pembersihan"
                                    <?php echo e(old('jenis_maintenance') == 'pembersihan' ? 'selected' : ''); ?>>Pembersihan
                                </option>
                                <option value="inspeksi" <?php echo e(old('jenis_maintenance') == 'inspeksi' ? 'selected' : ''); ?>>
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

                        <div>
                            <label for="tanggal_maintenance" class="form-label">Tanggal Maintenance</label>
                            <input type="date" id="tanggal_maintenance" name="tanggal_maintenance"
                                value="<?php echo e(old('tanggal_maintenance')); ?>"
                                class="form-input <?php $__errorArgs = ['tanggal_maintenance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
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

                        <div>
                            <label for="jenis_item" class="form-label">Tipe Item</label>
                            <select id="jenis_item" name="jenis_item"
                                class="form-input <?php $__errorArgs = ['jenis_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                                <option value="">Pilih Tipe Item</option>
                                <option value="barang" <?php echo e(old('jenis_item') == 'barang' ? 'selected' : ''); ?>>Barang
                                </option>
                                <option value="ruang" <?php echo e(old('jenis_item') == 'ruang' ? 'selected' : ''); ?>>Ruang
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

                        <div>
                            <label for="item_id" class="form-label">Item</label>
                            <select id="item_id" name="item_id"
                                class="form-input <?php $__errorArgs = ['item_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                                <option value="">Pilih Item</option>
                                <!-- Options will be populated by JavaScript based on item_type -->
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
                    </div>
                </div>

                <!-- Details -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Detail Maintenance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status"
                                class="form-input <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="dijadwalkan" <?php echo e(old('status') == 'dijadwalkan' ? 'selected' : ''); ?>>
                                    Dijadwalkan</option>
                                <option value="sedang_dikerjakan"
                                    <?php echo e(old('status') == 'sedang_dikerjakan' ? 'selected' : ''); ?>>Sedang Dikerjakan
                                </option>
                                <option value="dalam_proses" <?php echo e(old('status') == 'dalam_proses' ? 'selected' : ''); ?>>
                                    Dalam Proses</option>
                                <option value="selesai" <?php echo e(old('status') == 'selesai' ? 'selected' : ''); ?>>Selesai
                                </option>
                                <option value="dibatalkan" <?php echo e(old('status') == 'dibatalkan' ? 'selected' : ''); ?>>
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

                        <div>
                            <label for="biaya" class="form-label">Biaya (Rp)</label>
                            <input type="number" id="biaya" name="biaya" value="<?php echo e(old('biaya')); ?>"
                                class="form-input <?php $__errorArgs = ['biaya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Masukkan biaya maintenance" min="0">
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


                        <div>
                            <label for="foto_sebelum" class="form-label">Foto Sebelum</label>
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

                        <div>
                            <label for="foto_sesudah" class="form-label">Foto Sesudah</label>
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
                    </div>
                </div>

                <!-- Description -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Deskripsi</h3>
                    <div>
                        <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah</label>
                        <textarea id="deskripsi_masalah" name="deskripsi_masalah" rows="4"
                            class="form-input <?php $__errorArgs = ['deskripsi_masalah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Masukkan deskripsi masalah"><?php echo e(old('deskripsi_masalah')); ?></textarea>
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

                    <div class="mt-4">
                        <label for="tindakan_perbaikan" class="form-label">Tindakan Perbaikan</label>
                        <textarea id="tindakan_perbaikan" name="tindakan_perbaikan" rows="4"
                            class="form-input <?php $__errorArgs = ['tindakan_perbaikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 focus:border-red-500 focus:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Masukkan tindakan perbaikan"><?php echo e(old('tindakan_perbaikan')); ?></textarea>
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
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="<?php echo e(route('admin.sarpras.maintenance.index')); ?>" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data untuk dropdown
        const barangs = <?php echo json_encode($barangs, 15, 512) ?>;
        const ruangs = <?php echo json_encode($ruangs, 15, 512) ?>;

        document.getElementById('jenis_item').addEventListener('change', function() {
            const itemType = this.value;
            const itemSelect = document.getElementById('item_id');

            // Clear existing options
            itemSelect.innerHTML = '<option value="">Pilih Item</option>';

            if (itemType === 'barang') {
                barangs.forEach(function(barang) {
                    itemSelect.innerHTML += '<option value="' + barang.id + '">' + barang.nama_barang + '</option>';
                });
            } else if (itemType === 'ruang') {
                ruangs.forEach(function(ruang) {
                    itemSelect.innerHTML += '<option value="' + ruang.id + '">' + ruang.nama_ruang + '</option>';
                });
            }
        });

        // Trigger change event on page load if jenis_item has a value
        document.addEventListener('DOMContentLoaded', function() {
            const itemTypeSelect = document.getElementById('jenis_item');
            if (itemTypeSelect.value) {
                itemTypeSelect.dispatchEvent(new Event('change'));
            }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\maintenance\create.blade.php ENDPATH**/ ?>