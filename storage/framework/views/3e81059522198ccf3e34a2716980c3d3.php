<!-- Blog Section Start -->
<div id="rs-blog" class="rs-blog style2 pt-94 pb-100 md-pt-64 md-pb-70">
    <div class="container">
        <div class="sec-title mb-60 text-center">
            <div class="sub-title primary">News Update</div>
            <h2 class="title mb-0">Latest News & Events</h2>
        </div>
        <div class="rs-carousel owl-carousel" data-loop="true" data-items="3" data-margin="30"
            data-autoplay="true" data-hoverpause="true" data-autoplay-timeout="5000" data-smart-speed="800"
            data-dots="false" data-nav="false" data-nav-speed="false" data-center-mode="false"
            data-mobile-device="1" data-mobile-device-nav="false" data-mobile-device-dots="false"
            data-ipad-device="2" data-ipad-device-nav="false" data-ipad-device-dots="false"
            data-ipad-device2="1" data-ipad-device-nav2="false" data-ipad-device-dots2="false"
            data-md-device="3" data-md-device-nav="false" data-md-device-dots="false">
            <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="blog-item">
                    <div class="image-part">
                        <?php if($blog->featured_image): ?>
                            <img src="<?php echo e(Storage::url($blog->featured_image)); ?>" alt="<?php echo e($blog->title ?? 'Blog'); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('assets_telkom/assets/images/blog/style2/' . (($loop->index % 3) + 1) . '.jpg')); ?>" alt="<?php echo e($blog->title ?? 'Blog'); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> <?php echo e($blog->user->name ?? 'Admin'); ?></li>
                            <li><i class="fa fa-calendar"></i> <?php echo e($blog->published_at ? $blog->published_at->format('M d, Y') : 'N/A'); ?></li>
                        </ul>
                        <h3 class="title"><a href="<?php echo e(route('berita.public.show', $blog->slug)); ?>"><?php echo e($blog->title ?? 'Blog Title'); ?></a></h3>
                        <div class="desc"><?php echo e(Str::limit($blog->excerpt ?: strip_tags($blog->content ?? ''), 100)); ?></div>
                        <ul class="blog-bottom">
                            <li class="btn-part"><a class="readon-arrow" href="<?php echo e(route('berita.public.show', $blog->slug)); ?>">Read More</a></li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="<?php echo e(asset('assets_telkom/assets/images/blog/style2/1.jpg')); ?>" alt="">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> Admin</li>
                            <li><i class="fa fa-calendar"></i>June 15, 2019</li>
                        </ul>
                        <h3 class="title"><a href="blog-single.html">University While The Lovely Valley Team Work</a></h3>
                        <div class="desc">the acquisition of knowledge, skills, values befs, and habits. Educational methods include teach ing, training, storytelling</div>
                        <ul class="blog-bottom">
                            <li class="cmnt-part"><a href="#">(12) Comments</a></li>
                            <li class="btn-part"><a class="readon-arrow" href="#">Read More</a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="<?php echo e(asset('assets_telkom/assets/images/blog/style2/2.jpg')); ?>" alt="">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> Admin</li>
                            <li><i class="fa fa-calendar"></i>June 15, 2019</li>
                        </ul>
                        <h3 class="title"><a href="blog-single.html">High School Program Starting Soon 2021</a></h3>
                        <div class="desc">the acquisition of knowledge, skills, values befs, and habits. Educational methods include teach ing, training, storytelling</div>
                        <ul class="blog-bottom">
                            <li class="cmnt-part"><a href="#">(12) Comments</a></li>
                            <li class="btn-part"><a class="readon-arrow" href="#">Read More</a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="image-part">
                        <img src="<?php echo e(asset('assets_telkom/assets/images/blog/style2/3.jpg')); ?>" alt="">
                    </div>
                    <div class="blog-content new-style">
                        <ul class="blog-meta">
                            <li><i class="fa fa-user-o"></i> Admin</li>
                            <li><i class="fa fa-calendar"></i>June 15, 2019</li>
                        </ul>
                        <h3 class="title"><a href="blog-single.html">Modern School The Lovely Valley Team Work</a></h3>
                        <div class="desc">the acquisition of knowledge, skills, values befs, and habits. Educational methods include teach ing, training, storytelling</div>
                        <ul class="blog-bottom">
                            <li class="cmnt-part"><a href="#">(12) Comments</a></li>
                            <li class="btn-part"><a class="readon-arrow" href="#">Read More</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- View All Button -->
        <div class="text-center mt-50">
            <a class="readon2" href="<?php echo e(route('berita.public.index')); ?>">
                Lihat Semua Berita &nbsp;<i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- Blog Section End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/telkom/blog.blade.php ENDPATH**/ ?>