<?php $__env->startSection('content'); ?>
    
    <?php if (isset($component)) { $__componentOriginal9993066b1b38333cdd5fd75ea58dea13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.maudu.breadcrumb','data' => ['title' => 'E-Lulus','items' => [
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Layanan', 'url' => '#'],
        ['label' => 'E-Lulus', 'url' => route('public.graduation.check')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('maudu.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'E-Lulus','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Beranda', 'url' => route('landing')],
        ['label' => 'Layanan', 'url' => '#'],
        ['label' => 'E-Lulus', 'url' => route('public.graduation.check')],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $attributes = $__attributesOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__attributesOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13)): ?>
<?php $component = $__componentOriginal9993066b1b38333cdd5fd75ea58dea13; ?>
<?php unset($__componentOriginal9993066b1b38333cdd5fd75ea58dea13); ?>
<?php endif; ?>

    
    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-10">

                    
                    <div style="text-align: center; margin-bottom: 50px;">
                        <span
                            style="color: #1a5632; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Layanan
                            Digital</span>
                        <h2 style="font-size: 2rem; font-weight: 700; color: #1a2e1a; margin: 10px 0 15px;">Cek Status
                            Kelulusan</h2>
                        <p style="color: #666; font-size: 15px; line-height: 1.7;">
                            Masukkan NISN atau NIS untuk mengecek status kelulusan kamu secara online.
                        </p>
                    </div>

                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert"
                            style="border-radius: 8px; border-left: 4px solid #dc3545; background: #fff5f5; color: #721c24; padding: 16px 20px; margin-bottom: 30px;">
                            <i class="fa fa-exclamation-circle me-2" style="font-size: 18px; margin-right: 10px;"></i>
                            <div><?php echo e(session('error')); ?></div>
                        </div>
                    <?php endif; ?>

                    
                    <div
                        style="background: #fff; border-radius: 12px; box-shadow: 0 8px 40px rgba(0,0,0,0.10); padding: 48px 44px; border: 1px solid #e8f5e9;">

                        
                        <div
                            style="background: #e8f5e9; border-left: 4px solid #1a5632; border-radius: 8px; padding: 16px 20px; margin-bottom: 32px;">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <i class="fa fa-info-circle" style="color: #1a5632; font-size: 18px; margin-top: 2px;"></i>
                                <div>
                                    <p style="font-weight: 600; color: #1a2e1a; margin-bottom: 6px; font-size: 14px;">
                                        Informasi Penting
                                    </p>
                                    <ul
                                        style="margin: 0; padding-left: 16px; color: #555; font-size: 13px; line-height: 1.8;">
                                        <li>Masukkan NISN <em>atau</em> NIS — cukup salah satu</li>
                                        <li>Pastikan nomor yang dimasukkan sudah benar</li>
                                        <li>Jika data tidak ditemukan, hubungi admin sekolah</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="<?php echo e(route('public.graduation.check.process')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <div style="margin-bottom: 24px;">
                                <label for="nisn"
                                    style="font-weight: 600; color: #1a2e1a; font-size: 14px; margin-bottom: 8px; display: block;">
                                    NISN <span style="color: #999; font-weight: 400;">(Nomor Induk Siswa Nasional)</span>
                                </label>
                                <input type="text" name="nisn" id="nisn" value="<?php echo e(old('nisn')); ?>"
                                    placeholder="Contoh: 0075823566"
                                    style="width: 100%; padding: 13px 16px; border: 1.5px solid #c8e6c9; border-radius: 8px; font-size: 15px; color: #333; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#1a5632'" onblur="this.style.borderColor='#c8e6c9'">
                                <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p style="color: #dc3545; font-size: 12px; margin-top: 5px;"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div style="display: flex; align-items: center; gap: 12px; margin: 20px 0;">
                                <div style="flex: 1; height: 1px; background: #c8e6c9;"></div>
                                <span style="color: #999; font-size: 13px; font-weight: 500;">atau</span>
                                <div style="flex: 1; height: 1px; background: #c8e6c9;"></div>
                            </div>

                            
                            <div style="margin-bottom: 24px;">
                                <label for="nis"
                                    style="font-weight: 600; color: #1a2e1a; font-size: 14px; margin-bottom: 8px; display: block;">
                                    NIS <span style="color: #999; font-weight: 400;">(Nomor Induk Siswa)</span>
                                </label>
                                <input type="text" name="nis" id="nis" value="<?php echo e(old('nis')); ?>"
                                    placeholder="Contoh: 4009/353.067"
                                    style="width: 100%; padding: 13px 16px; border: 1.5px solid #c8e6c9; border-radius: 8px; font-size: 15px; color: #333; outline: none; transition: border-color 0.2s;"
                                    onfocus="this.style.borderColor='#1a5632'" onblur="this.style.borderColor='#c8e6c9'">
                                <?php $__errorArgs = ['nis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p style="color: #dc3545; font-size: 12px; margin-top: 5px;"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div style="margin-top: 32px; text-align: center;">
                                <button type="submit"
                                    style="background: linear-gradient(135deg, #1a5632 0%, #2d8a4e 100%); color: #fff; border: none; padding: 14px 48px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; letter-spacing: 0.5px; transition: opacity 0.2s; width: 100%;"
                                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                    <i class="fa fa-search" style="margin-right: 8px;"></i>
                                    Cek Status Kelulusan
                                </button>
                            </div>
                        </form>
                    </div>

                    
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="<?php echo e(route('landing')); ?>" style="color: #1a5632; font-size: 14px; text-decoration: none;">
                            <i class="fa fa-arrow-left" style="margin-right: 6px;"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.maudu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\PROJEKU\telkom\resources\views\public\elulus\check-maudu.blade.php ENDPATH**/ ?>