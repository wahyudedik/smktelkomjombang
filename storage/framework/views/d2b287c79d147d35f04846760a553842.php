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
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Detail Jadwal Pelajaran')); ?>

            </h2>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.jadwal-pelajaran.edit', $jadwalPelajaran)); ?>"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="<?php echo e(route('admin.jadwal-pelajaran.index')); ?>"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mata Pelajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mata Pelajaran</label>
                            <p class="mt-1 text-lg font-semibold"><?php echo e($jadwalPelajaran->mataPelajaran->nama ?? '-'); ?></p>
                        </div>

                        <!-- Guru -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Guru Pengajar</label>
                            <p class="mt-1 text-lg font-semibold"><?php echo e($jadwalPelajaran->guru->full_name ?? '-'); ?></p>
                            <p class="text-sm text-gray-600">NIP: <?php echo e($jadwalPelajaran->guru->nip ?? '-'); ?></p>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kelas</label>
                            <p class="mt-1 text-lg font-semibold"><?php echo e($jadwalPelajaran->kelas->nama ?? '-'); ?></p>
                        </div>

                        <!-- Hari -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Hari</label>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo e($jadwalPelajaran->hari_badge_color); ?>-100 text-<?php echo e($jadwalPelajaran->hari_badge_color); ?>-800">
                                    <?php echo e($jadwalPelajaran->hari); ?>

                                </span>
                            </p>
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Waktu</label>
                            <p class="mt-1 text-lg font-semibold"><?php echo e($jadwalPelajaran->time_range); ?></p>
                            <p class="text-sm text-gray-600">Durasi: <?php echo e($jadwalPelajaran->duration); ?> menit</p>
                        </div>

                        <!-- Ruang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Ruang Kelas</label>
                            <p class="mt-1 text-lg"><?php echo e($jadwalPelajaran->ruang ?? '-'); ?></p>
                        </div>

                        <!-- Tahun Ajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tahun Ajaran</label>
                            <p class="mt-1 text-lg"><?php echo e($jadwalPelajaran->tahun_ajaran); ?></p>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Semester</label>
                            <p class="mt-1 text-lg"><?php echo e($jadwalPelajaran->semester); ?></p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?php echo e($jadwalPelajaran->status_badge_color); ?>-100 text-<?php echo e($jadwalPelajaran->status_badge_color); ?>-800">
                                    <?php echo e(ucfirst($jadwalPelajaran->status)); ?>

                                </span>
                            </p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <p class="mt-1 text-sm text-gray-700">
                                <?php echo e($jadwalPelajaran->created_at->format('d M Y H:i')); ?></p>
                        </div>

                        <!-- Catatan -->
                        <?php if($jadwalPelajaran->catatan): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Catatan</label>
                                <p class="mt-1 text-gray-700"><?php echo e($jadwalPelajaran->catatan); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <form action="<?php echo e(route('admin.jadwal-pelajaran.destroy', $jadwalPelajaran)); ?>" method="POST"
                            data-confirm="Yakin ingin menghapus jadwal ini?">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Hapus Jadwal
                            </button>
                        </form>

                        <a href="<?php echo e(route('admin.jadwal-pelajaran.edit', $jadwalPelajaran)); ?>"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Jadwal
                        </a>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\jadwal-pelajaran\show.blade.php ENDPATH**/ ?>