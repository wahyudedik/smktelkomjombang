<?php
    $pageTitle = 'Semua Halaman';
?>

<?php $__env->startSection('content'); ?>
    <!-- Breadcrumb -->
    <section style="background: linear-gradient(135deg, #00529C 0%, #003d73 100%); padding: 80px 0; position: relative; overflow: hidden;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <h2 style="color: #fff; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            Semua Halaman
                        </h2>
                        <div style="color: #ffffff; opacity: 0.95; font-size: 1rem;">
                            <a href="/" style="color: #ffffff; text-decoration: none;">Home</a>
                            <span style="margin: 0 10px; opacity: 0.7;">/</span>
                            <span style="opacity: 0.95; font-weight: 500;">Semua Halaman</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative shapes -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    </section>

    <!-- Pages Area -->
    <section style="padding: 80px 0; background: #f8f9fa;">
        <div class="container">
            <!-- Search & Filter -->
            <div style="margin-bottom: 40px;">
                <form action="<?php echo e(route('pages.public.index')); ?>" method="GET">
                    <div style="background: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8 col-md-7">
                                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                    <i class="fas fa-search" style="color: #00529C; margin-right: 4px;"></i>
                                    Cari Halaman
                                </label>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Ketik judul halaman yang dicari..."
                                       style="width: 100%; padding: 12px 16px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; outline: none;"
                                       onfocus="this.style.borderColor='#00529C'"
                                       onblur="this.style.borderColor='#e9ecef'">
                            </div>

                            <?php if($categories->count() > 0): ?>
                                <div class="col-lg-3 col-md-3">
                                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                        <i class="fas fa-filter" style="color: #00529C; margin-right: 4px;"></i>
                                        Kategori
                                    </label>
                                    <select name="category"
                                            style="width: 100%; padding: 12px 16px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; background: #fff; transition: border-color 0.3s; outline: none; cursor: pointer;"
                                            onfocus="this.style.borderColor='#00529C'"
                                            onblur="this.style.borderColor='#e9ecef'">
                                        <option value="">Semua Kategori</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category); ?>"
                                                <?php echo e(request('category') == $category ? 'selected' : ''); ?>>
                                                <?php echo e($category); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="col-lg-1 col-md-2">
                                <button type="submit"
                                        style="width: 100%; padding: 12px; background: linear-gradient(135deg, #00529C, #003d73); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; transition: transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 12px rgba(0,82,156,0.3)'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Pages Grid -->
            <?php if($pages->count() > 0): ?>
                <div class="row">
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: transform 0.3s, box-shadow 0.3s; height: 100%; display: flex; flex-direction: column;"
                                 onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 12px rgba(0,0,0,0.06)'">
                                <!-- Image -->
                                <div style="position: relative; height: 200px; overflow: hidden;">
                                    <?php if($page->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($page->featured_image)); ?>" alt="<?php echo e($page->title); ?>"
                                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                             onmouseover="this.style.transform='scale(1.08)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #00529C 0%, #003d73 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-file-alt" style="font-size: 3rem; color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Category Badge -->
                                    <?php if($page->category): ?>
                                        <span style="position: absolute; top: 12px; left: 12px; padding: 5px 14px; background: linear-gradient(135deg, #00529C, #003d73); color: #fff; border-radius: 20px; font-size: 0.78rem; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            <?php echo e($page->category); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Content -->
                                <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                                    <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 10px; line-height: 1.4;">
                                        <a href="<?php echo e(route('pages.public.show', $page->slug)); ?>"
                                           style="color: #333; text-decoration: none; transition: color 0.3s;"
                                           onmouseover="this.style.color='#00529C'"
                                           onmouseout="this.style.color='#333'">
                                            <?php echo e($page->title); ?>

                                        </a>
                                    </h3>

                                    <?php if($page->excerpt): ?>
                                        <p style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin-bottom: 16px; flex: 1;">
                                            <?php echo e(Str::limit($page->excerpt, 120)); ?>

                                        </p>
                                    <?php else: ?>
                                        <div style="flex: 1;"></div>
                                    <?php endif; ?>

                                    <!-- Meta -->
                                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                                        <?php if($page->published_at): ?>
                                            <span style="display: inline-flex; align-items: center; gap: 6px; color: #6c757d; font-size: 0.85rem;">
                                                <i class="far fa-calendar-alt" style="color: #00529C;"></i>
                                                <?php echo e($page->published_at->format('d M Y')); ?>

                                            </span>
                                        <?php endif; ?>

                                        <a href="<?php echo e(route('pages.public.show', $page->slug)); ?>"
                                           style="display: inline-flex; align-items: center; gap: 6px; color: #00529C; font-weight: 600; font-size: 0.88rem; text-decoration: none; transition: gap 0.3s;"
                                           onmouseover="this.style.gap='10px'"
                                           onmouseout="this.style.gap='6px'">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 40px;">
                    <div style="background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
                            <!-- Stats -->
                            <div style="display: flex; align-items: center; gap: 16px; color: #6c757d; font-size: 0.9rem;">
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-file-alt" style="color: #00529C;"></i>
                                    <strong><?php echo e($pages->total()); ?></strong> Total
                                </span>
                                <span style="opacity: 0.5;">•</span>
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-eye"></i>
                                    <strong><?php echo e($pages->firstItem() ?? 0); ?></strong> - <strong><?php echo e($pages->lastItem() ?? 0); ?></strong>
                                </span>
                                <span style="opacity: 0.5;">•</span>
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-book-open"></i>
                                    Halaman <strong><?php echo e($pages->currentPage()); ?></strong> dari <strong><?php echo e($pages->lastPage()); ?></strong>
                                </span>
                            </div>

                            <!-- Pagination Links -->
                            <nav aria-label="Page navigation">
                                <?php echo e($pages->links('pagination::bootstrap-5')); ?>

                            </nav>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #e8f4fd 0%, #f0f7ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #00529C; opacity: 0.6;"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #333; margin-bottom: 12px;">Tidak Ada Halaman</h3>
                    <p style="color: #6c757d; font-size: 1rem; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                        <?php if(request('search') || request('category')): ?>
                            Tidak ada halaman yang sesuai dengan pencarian Anda.
                        <?php else: ?>
                            Belum ada halaman yang dipublikasikan.
                        <?php endif; ?>
                    </p>

                    <?php if(request('search') || request('category')): ?>
                        <a href="<?php echo e(route('pages.public.index')); ?>"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: linear-gradient(135deg, #00529C, #003d73); color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; transition: transform 0.2s, box-shadow 0.2s;"
                           onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 16px rgba(0,82,156,0.3)'"
                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'">
                            <i class="fas fa-list"></i>
                            Lihat Semua Halaman
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.telkom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\pages\public\index.blade.php ENDPATH**/ ?>