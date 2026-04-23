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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Berita</h1>
                <p class="text-slate-600 mt-1">Buat artikel berita baru untuk landing page</p>
            </div>
            <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('admin.berita.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <!-- Judul -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="<?php echo e(old('title')); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: Siswa SMK Telkom Raih Juara Nasional">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Ringkasan -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Ringkasan <span class="text-slate-400 text-xs">(maks 500 karakter)</span>
                        </label>
                        <textarea name="excerpt" rows="2" maxlength="500"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Ringkasan singkat berita yang tampil di kartu..."><?php echo e(old('excerpt')); ?></textarea>
                        <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Konten -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Konten Berita <span class="text-red-500">*</span>
                        </label>
                        <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mb-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <!-- Quill Editor -->
                        <div id="quill-editor" style="min-height: 350px; font-size: 15px; background: white;"></div>
                        <!-- Hidden textarea yang dikirim ke server -->
                        <textarea name="content" id="content" class="hidden"><?php echo e(old('content')); ?></textarea>
                    </div>

                    <!-- Gambar & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Gambar Utama</label>
                            <input type="file" name="featured_image" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                onchange="previewImage(this)">
                            <p class="text-xs text-slate-500 mt-1">JPG, PNG, WEBP. Maks 2MB.</p>
                            <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="image-preview" class="mt-3 hidden">
                                <img id="preview-img" src="" alt="Preview"
                                    class="w-full h-40 object-cover rounded-lg border border-slate-200">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="draft" <?php echo e(old('status', 'draft') === 'draft' ? 'selected' : ''); ?>>
                                        Draft (belum tampil)
                                    </option>
                                    <option value="published" <?php echo e(old('status') === 'published' ? 'selected' : ''); ?>>
                                        Publish (tampil di landing page)
                                    </option>
                                    <option value="archived" <?php echo e(old('status') === 'archived' ? 'selected' : ''); ?>>
                                        Arsip
                                    </option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                    <?php echo e(old('is_featured') ? 'checked' : ''); ?>

                                    class="w-4 h-4 text-yellow-500 rounded">
                                <label for="is_featured" class="text-sm font-medium text-slate-700 cursor-pointer">
                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                    Tandai sebagai Berita Unggulan
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
                        <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Berita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        #quill-editor { border: 1px solid #d1d5db; border-radius: 0 0 8px 8px; }
        .ql-toolbar { border: 1px solid #d1d5db; border-radius: 8px 8px 0 0; background: #f8fafc; }
        .ql-container { font-family: inherit; font-size: 15px; }
        .ql-editor { min-height: 350px; line-height: 1.8; }
        .ql-editor p { margin-bottom: 0.8rem; }
        .ql-editor blockquote {
            border-left: 4px solid #1c3988;
            padding: 10px 20px;
            background: #f0f4ff;
            color: #555;
            font-style: italic;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        // Init Quill
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Tulis isi berita di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, false] }],
                    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        var contentTextarea = document.getElementById('content');

        // Load existing content (old() on validation fail)
        if (contentTextarea.value.trim()) {
            quill.root.innerHTML = contentTextarea.value;
        }

        // Sync REAL-TIME setiap kali konten berubah
        quill.on('text-change', function () {
            var html = quill.root.innerHTML;
            // Jika hanya berisi <p><br></p> (editor kosong), kosongkan textarea
            contentTextarea.value = (html === '<p><br></p>') ? '' : html;
        });

        // Fallback: sync juga saat tombol submit diklik
        document.querySelector('button[type="submit"]').addEventListener('click', function () {
            var html = quill.root.innerHTML;
            contentTextarea.value = (html === '<p><br></p>') ? '' : html;
        });

        // Image upload handler
        var toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function () {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = function () {
                var file = input.files[0];
                if (!file) return;
                var formData = new FormData();
                formData.append('file', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                fetch('/admin/berita/upload-image', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.location) {
                            var range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', data.location);
                        }
                    })
                    .catch(() => alert('Upload gambar gagal.'));
            };
        });

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const img = document.getElementById('preview-img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
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
<?php /**PATH E:\PROJEKU\telkom\resources\views/berita/create.blade.php ENDPATH**/ ?>