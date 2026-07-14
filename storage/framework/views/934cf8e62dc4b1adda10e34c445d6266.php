<!-- Contact Area -->
<div class="contact-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <div class="site-heading">
                    <span class="sub-title">Kontak</span>
                    <h2 class="title">Hubungi Kami</h2>
                    <p class="desc">Jangan ragu untuk menghubungi kami</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            
            <div class="col-lg-5">
                <div class="contact-info-wrap">
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Alamat</h5>
                            <p><?php echo e(theme_config('address')); ?></p>
                        </div>
                    </div>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone-alt fa-2x text-primary"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Telepon</h5>
                            <p><a href="tel:<?php echo e(theme_config('phone')); ?>"><?php echo e(theme_config('phone')); ?></a></p>
                            <?php if(theme_config('phone_secondary')): ?>
                                <p><a
                                        href="tel:<?php echo e(theme_config('phone_secondary')); ?>"><?php echo e(theme_config('phone_secondary')); ?></a>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Email</h5>
                            <p><a href="mailto:<?php echo e(theme_config('email')); ?>"><?php echo e(theme_config('email')); ?></a></p>
                        </div>
                    </div>
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                        <div class="contact-text">
                            <h5>Jam Operasional</h5>
                            <p><?php echo e(theme_config('working_hours.days', 'Sabtu - Kamis')); ?><br>
                                <?php echo e(theme_config('working_hours.hours', '08:00 - 16:00 WIB')); ?></p>
                        </div>
                    </div>

                    
                    <a href="https://wa.me/<?php echo e(theme_config('whatsapp')); ?>" target="_blank"
                        class="btn btn-success btn-lg w-100 mt-3">
                        <i class="fab fa-whatsapp me-2"></i> Chat via WhatsApp
                    </a>
                </div>
            </div>

            
            <div class="col-lg-7">
                
                <div class="contact-map mb-4 rounded overflow-hidden shadow-sm" style="height: 300px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5!2d112.3!3d-7.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzAnMDAuMCJTIDExMsKwMTgnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890"
                        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                
                <div class="contact-form-wrap">
                    <h4 class="mb-4">Kirim Pesan</h4>
                    <form action="#" method="POST" class="contact-form">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap *"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Email *"
                                    required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="subject" class="form-control" placeholder="Subjek">
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="5" placeholder="Pesan *" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Area End -->
<?php /**PATH E:\PROJEKU\telkom\resources\views/components/maudu/contact.blade.php ENDPATH**/ ?>