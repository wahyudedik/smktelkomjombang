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
                <?php echo e(__('Kalender Jadwal Pelajaran')); ?>

            </h2>
            <a href="<?php echo e(route('admin.jadwal-pelajaran.index')); ?>"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke List
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form method="GET" action="<?php echo e(route('admin.jadwal-pelajaran.calendar')); ?>"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kelas->id); ?>" <?php echo e($kelasId == $kelas->id ? 'selected' : ''); ?>>
                                    <?php echo e($kelas->nama); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="<?php echo e($tahunAjaran); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="2024/2025">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="Ganjil" <?php echo e($semester == 'Ganjil' ? 'selected' : ''); ?>>Ganjil</option>
                            <option value="Genap" <?php echo e($semester == 'Genap' ? 'selected' : ''); ?>>Genap</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <?php
                            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $colors = ['blue', 'indigo', 'green', 'yellow', 'orange', 'red'];
                        ?>

                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded-lg">
                                <div
                                    class="bg-<?php echo e($colors[$index]); ?>-500 text-white p-3 font-bold text-center rounded-t-lg">
                                    <?php echo e($hari); ?>

                                </div>
                                <div class="p-2 space-y-2">
                                    <?php if(isset($jadwals[$hari])): ?>
                                        <?php $__currentLoopData = $jadwals[$hari]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="bg-gray-50 p-2 rounded border-l-4 border-<?php echo e($colors[$index]); ?>-500 hover:bg-gray-100 cursor-pointer"
                                                onclick="window.location='<?php echo e(route('admin.jadwal-pelajaran.show', $jadwal)); ?>'">
                                                <div class="text-xs font-semibold text-gray-700">
                                                    <?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?> -
                                                    <?php echo e(\Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')); ?>

                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo e($jadwal->mataPelajaran->nama ?? '-'); ?>

                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    <?php echo e($jadwal->guru->nama_lengkap ?? '-'); ?>

                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php echo e($jadwal->kelas->nama ?? '-'); ?> • <?php echo e($jadwal->ruang ?? '-'); ?>

                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <div class="text-center text-gray-400 text-sm py-4">
                                            Tidak ada jadwal
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\jadwal-pelajaran\calendar.blade.php ENDPATH**/ ?>