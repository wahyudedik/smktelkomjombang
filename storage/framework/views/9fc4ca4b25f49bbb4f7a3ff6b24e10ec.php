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
                <div class="flex items-center space-x-2 text-sm text-slate-500 mb-1">
                    <a href="<?php echo e(route('admin.log-monitoring.index')); ?>" class="hover:text-blue-600 transition-colors">Log Monitoring</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-medium"><?php echo e($logData['filename']); ?></span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Log File Viewer</h1>
                <p class="text-slate-600 mt-1">Detail isi log file: <?php echo e($logData['filename']); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.log-monitoring.download', $logData['filename'])); ?>"
                   class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download
                </a>
                <a href="<?php echo e(route('admin.log-monitoring.index')); ?>" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- File Info -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wide">File</p>
                <p class="text-sm font-semibold text-slate-900 mt-1 truncate"><?php echo e($logData['filename']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wide">Size</p>
                <p class="text-sm font-semibold text-slate-900 mt-1"><?php echo e($logData['size']); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wide">Lines</p>
                <p class="text-sm font-semibold text-slate-900 mt-1"><?php echo e(number_format($logData['total_lines'])); ?></p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wide">Last Modified</p>
                <p class="text-sm font-semibold text-slate-900 mt-1"><?php echo e($logData['modified']); ?></p>
            </div>
        </div>

        <!-- Level Counts -->
        <div class="grid grid-cols-2 md:grid-cols-8 gap-3 mb-6">
            <?php $__currentLoopData = $logData['levelCounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelName => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.log-monitoring.show', $logData['filename'])); ?>?level=<?php echo e($levelName); ?>"
                   class="bg-white rounded-lg border border-slate-200 p-3 text-center hover:shadow-md transition-all
                          <?php echo e($level === $levelName ? 'ring-2 ring-blue-500 border-blue-300' : ''); ?>">
                    <p class="text-lg font-bold
                        <?php echo e(in_array($levelName, ['emergency', 'alert', 'critical', 'error']) ? 'text-red-600' : ''); ?>

                        <?php echo e($levelName === 'warning' ? 'text-yellow-600' : ''); ?>

                        <?php echo e(in_array($levelName, ['notice', 'info']) ? 'text-green-600' : ''); ?>

                        <?php echo e($levelName === 'debug' ? 'text-slate-600' : ''); ?>">
                        <?php echo e($count); ?>

                    </p>
                    <p class="text-xs text-slate-500 capitalize mt-0.5"><?php echo e($levelName); ?></p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
            <form method="GET" action="<?php echo e(route('admin.log-monitoring.show', $logData['filename'])); ?>"
                  class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Filter by Level</label>
                    <select name="level" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Level</option>
                        <option value="emergency" <?php echo e($level === 'emergency' ? 'selected' : ''); ?>>Emergency</option>
                        <option value="alert" <?php echo e($level === 'alert' ? 'selected' : ''); ?>>Alert</option>
                        <option value="critical" <?php echo e($level === 'critical' ? 'selected' : ''); ?>>Critical</option>
                        <option value="error" <?php echo e($level === 'error' ? 'selected' : ''); ?>>Error</option>
                        <option value="warning" <?php echo e($level === 'warning' ? 'selected' : ''); ?>>Warning</option>
                        <option value="notice" <?php echo e($level === 'notice' ? 'selected' : ''); ?>>Notice</option>
                        <option value="info" <?php echo e($level === 'info' ? 'selected' : ''); ?>>Info</option>
                        <option value="debug" <?php echo e($level === 'debug' ? 'selected' : ''); ?>>Debug</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Search in Log</label>
                    <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari dalam log..."
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="<?php echo e(route('admin.log-monitoring.show', $logData['filename'])); ?>"
                       class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-semibold"><?php echo e(number_format(count($logData['lines']))); ?></span> baris
                <?php if($level || $search): ?>
                    (filtered)
                <?php endif; ?>
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <button onclick="toggleAutoScroll()" id="autoScrollBtn"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                               bg-green-100 text-green-700 hover:bg-green-200">
                    <svg class="w-3.5 h-3.5 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Auto Scroll
                </button>
                <button onclick="scrollToTop()"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Top
                </button>
                <button onclick="scrollToBottom()"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    Bottom
                </button>
            </div>
        </div>

        <!-- Log Content -->
        <div class="bg-slate-900 rounded-xl border border-slate-700 overflow-hidden">
            <div class="px-4 py-3 bg-slate-800 border-b border-slate-700 flex items-center justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-sm text-slate-400 ml-2"><?php echo e($logData['filename']); ?></span>
                </div>
                <button onclick="copyLogContent()" class="text-xs text-slate-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy All
                </button>
            </div>
            <div id="logContent" class="p-4 overflow-auto" style="max-height: 70vh;">
                <pre class="text-sm text-green-400 font-mono whitespace-pre-wrap break-words leading-relaxed" id="logPre"><code><?php $__currentLoopData = $logData['lines']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lineNum => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<span class="inline-block w-12 text-right text-slate-500 mr-4 select-none"><?php echo e($lineNum + 1); ?></span><span class="<?php if(stripos($line, 'level.error') !== false || stripos($line, 'level.critical') !== false || stripos($line, 'level.alert') !== false || stripos($line, 'level.emergency') !== false): ?>text-red-400@elseif(stripos($line, 'level.warning') !== false)text-yellow-400@elseif(stripos($line, 'level.info') !== false || stripos($line, 'level.notice') !== false)text-green-400@elseif(stripos($line, 'level.debug') !== false)text-slate-400@else text-green-400 <?php endif; ?>"><?php echo e(e($line)); ?></span>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></code></pre>
            </div>
        </div>

        <!-- Clear Log Action -->
        <div class="mt-6 flex items-center justify-between">
            <a href="<?php echo e(route('admin.log-monitoring.index')); ?>" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Log
            </a>
            <form action="<?php echo e(route('admin.log-monitoring.clear', $logData['filename'])); ?>"
                  method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan log file <?php echo e($logData['filename']); ?>? Semua isi log akan dihapus.')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Kosongkan Log
                </button>
            </form>
        </div>
    </div>

    <script>
        let autoScrollEnabled = false;

        function toggleAutoScroll() {
            autoScrollEnabled = !autoScrollEnabled;
            const btn = document.getElementById('autoScrollBtn');
            if (autoScrollEnabled) {
                btn.classList.remove('bg-green-100', 'text-green-700');
                btn.classList.add('bg-blue-500', 'text-white');
                scrollToBottom();
            } else {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-green-100', 'text-green-700');
            }
        }

        function scrollToTop() {
            document.getElementById('logContent').scrollTop = 0;
        }

        function scrollToBottom() {
            const el = document.getElementById('logContent');
            el.scrollTop = el.scrollHeight;
        }

        function copyLogContent() {
            const logText = document.getElementById('logPre').innerText;
            navigator.clipboard.writeText(logText).then(function() {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm';
                toast.textContent = 'Log copied to clipboard!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+F to focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
            }
            // Escape to clear search
            if (e.key === 'Escape') {
                document.querySelector('input[name="search"]').value = '';
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
<?php /**PATH E:\PROJEKU\telkom\resources\views\log-monitoring\show.blade.php ENDPATH**/ ?>