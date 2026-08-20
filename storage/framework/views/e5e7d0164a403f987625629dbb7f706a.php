<?php $__env->startSection('content'); ?>
    <!-- breadcrumb -->
    <div class="site-breadcrumb" style="background: url(<?php echo e(asset('assets/img/breadcrumb/01.jpg')); ?>)">
        <div class="container">
            <h2 class="breadcrumb-title"><?php echo e($link->title); ?></h2>
            <ul class="breadcrumb-menu">
                <li><a href="/">Home</a></li>
                <li class="active">Testimonial</li>
            </ul>
        </div>
    </div>
    <!-- breadcrumb end -->

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Info Header -->
                    <div class="text-center mb-4">
                        <?php if($link->description): ?>
                            <p class="lead text-muted"><?php echo e($link->description); ?></p>
                        <?php endif; ?>
                        <div class="text-muted small mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Active until: <?php echo e($link->active_until->format('M d, Y H:i')); ?>

                        </div>
                    </div>

                    <!-- Success Message -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Message -->
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form action="<?php echo e(route('testimonials.public.store', $link->token)); ?>" method="POST"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="card-title border-bottom pb-2 mb-3">Personal Information</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position *</label>
                                        <select class="form-select <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="position"
                                            name="position" required>
                                            <option value="">Select Position</option>
                                            <?php $__currentLoopData = $link->target_audience; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($audience); ?>"
                                                    <?php echo e(old('position') == $audience ? 'selected' : ''); ?>>
                                                    <?php echo e($audience); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3" id="graduation_year_field" style="display: none;">
                                        <label for="graduation_year" class="form-label">Graduation Year</label>
                                        <input type="number"
                                            class="form-control <?php $__errorArgs = ['graduation_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="graduation_year" name="graduation_year"
                                            value="<?php echo e(old('graduation_year')); ?>" min="1900"
                                            max="<?php echo e(date('Y') + 10); ?>">
                                        <?php $__errorArgs = ['graduation_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3" id="class_field" style="display: none;">
                                        <label for="class" class="form-label">Class</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="class" name="class" value="<?php echo e(old('class')); ?>">
                                        <?php $__errorArgs = ['class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo (Optional)</label>
                                        <input type="file" class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="photo" name="photo" accept="image/*">
                                        <div class="form-text">Max size: 2MB (JPEG, PNG, JPG, GIF)</div>
                                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Testimonial -->
                                <div class="mb-4">
                                    <h5 class="card-title border-bottom pb-2 mb-3">Your Testimonial</h5>

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating *</label>
                                        <div class="star-rating" id="star-rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star star" data-rating="<?php echo e($i); ?>"
                                                    style="cursor: pointer; font-size: 2rem; color: #ddd;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" id="rating" name="rating" value="<?php echo e(old('rating')); ?>"
                                            required>
                                        <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger small"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="testimonial" class="form-label">Your Testimonial *</label>
                                        <textarea class="form-control <?php $__errorArgs = ['testimonial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="testimonial" name="testimonial"
                                            rows="5" required><?php echo e(old('testimonial')); ?></textarea>
                                        <div class="form-text">Share your experience with us</div>
                                        <?php $__errorArgs = ['testimonial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Submit Testimonial
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card shadow-sm mt-4 bg-light">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="fas fa-info-circle mr-2"></i>
                                Information
                            </h6>
                            <p class="card-text small">
                                Your testimonial will be reviewed by admin before appearing on the website.
                                Thank you for your feedback!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Position field change handler
                document.getElementById('position').addEventListener('change', function() {
                    const graduationField = document.getElementById('graduation_year_field');
                    const classField = document.getElementById('class_field');

                    if (this.value === 'Alumni') {
                        graduationField.style.display = 'block';
                        classField.style.display = 'none';
                        document.getElementById('graduation_year').required = true;
                        document.getElementById('class').required = false;
                    } else if (this.value === 'Siswa') {
                        graduationField.style.display = 'none';
                        classField.style.display = 'block';
                        document.getElementById('graduation_year').required = false;
                        document.getElementById('class').required = true;
                    } else {
                        graduationField.style.display = 'none';
                        classField.style.display = 'none';
                        document.getElementById('graduation_year').required = false;
                        document.getElementById('class').required = false;
                    }
                });

                // Star rating
                const stars = document.querySelectorAll('.star');
                const ratingInput = document.getElementById('rating');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        ratingInput.value = rating;

                        // Update star colors
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.style.color = '#fbbf24'; // yellow
                            } else {
                                s.style.color = '#ddd'; // gray
                            }
                        });
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\testimonials\create.blade.php ENDPATH**/ ?>