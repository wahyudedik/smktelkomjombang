<!-- Contact Section Start -->
<div id="rs-contact" class="rs-contact style2 pt-94 pb-100 md-pt-64 md-pb-70" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="sec-title mb-60 text-center">
            <div class="sub-title primary"><i class="fas fa-envelope"></i> {{ $siteSettings['contact_section_subtitle'] ?? 'Hubungi Kami' }}</div>
            <h2 class="title mb-0">{{ $siteSettings['contact_section_title'] ?? 'Kontak' }} <span>{{ $siteSettings['site_name'] ?? 'SMK Telekomunikasi' }}</span></h2>
            <p>{{ $siteSettings['contact_section_description'] ?? 'Jangan ragu untuk menghubungi kami jika memiliki pertanyaan' }}</p>
        </div>

        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-5 md-mb-30">
                <div class="contact-widget mb-30">
                    <div class="widget-title">
                        <h4 class="title">Informasi Kontak</h4>
                    </div>
                    <div class="contact-widget-list pt-20">
                        <div class="contact-widget-item">
                            <div class="icon-part">
                                <i class="flaticon-location"></i>
                            </div>
                            <div class="content-part">
                                <h4 class="title">Alamat</h4>
                                <p>{{ $siteSettings['contact_address'] ?? 'Ponpes Darul Ulum, Jl. Wahid Hasyim No.128, Kedunglosari, Kedungrejo, Kec. Bandar Kedungmulyo, Kabupaten Jombang, Jawa Timur 61462' }}</p>
                            </div>
                        </div>
                        <div class="contact-widget-item">
                            <div class="icon-part">
                                <i class="flaticon-call"></i>
                            </div>
                            <div class="content-part">
                                <h4 class="title">Telepon</h4>
                                <p>
                                    @if(!empty($siteSettings['contact_phone']))
                                        <a href="tel:{{ $siteSettings['contact_phone'] }}">{{ $siteSettings['contact_phone'] }}</a>
                                    @else
                                        <a href="tel:085649400339">0856-4940-0339</a><br>
                                        <a href="tel:03218681888">(0321) 8681-888</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="contact-widget-item">
                            <div class="icon-part">
                                <i class="flaticon-email"></i>
                            </div>
                            <div class="content-part">
                                <h4 class="title">Email</h4>
                                <p><a href="mailto:{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}">{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}</a></p>
                            </div>
                        </div>
                        <div class="contact-widget-item">
                            <div class="icon-part">
                                <i class="flaticon-clock"></i>
                            </div>
                            <div class="content-part">
                                <h4 class="title">Jam Operasional</h4>
                                <p>{{ $siteSettings['contact_operational_hours'] ?? 'Senin - Sabtu: 07.00 - 16.00 WIB' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Contact Buttons -->
                <div class="row">
                    <div class="col-sm-6 mb-20">
                        <a href="{{ $siteSettings['social_whatsapp'] ?? 'https://wa.me/6285649400339' }}" target="_blank" class="readon2" style="width: 100%; text-align: center; background-color: #25D366; border-color: #25D366;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                    <div class="col-sm-6 mb-20">
                        <a href="mailto:{{ $siteSettings['contact_email'] ?? 'smktelkomdujbg@gmail.com' }}" class="readon2" style="width: 100%; text-align: center;">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Google Maps & Form -->
            <div class="col-lg-7">
                <!-- Google Maps - Lokasi SMK Telekomunikasi Darul Ulum Jombang -->
                <div class="contact-map mb-30" style="border-radius: 8px; overflow: hidden; box-shadow: 0 5px 30px rgba(0,0,0,0.1);">
                    <iframe
                        src="{{ $siteSettings['contact_map_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.8547!2d112.2327!3d-7.5469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e2c1e8e8e8e9%3A0x1234567890abcdef!2sSMK%20Telekomunikasi%20Darul%20Ulum!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid' }}"
                        width="100%"
                        height="300"
                        style="border:0; border-radius: 8px;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi SMK Telekomunikasi Darul Ulum">
                    </iframe>
                </div>

                <!-- Form Kontak Sederhana -->
                <div class="contact-form-widget" style="background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.06);">
                    <h4 class="mb-3"><i class="fas fa-paper-plane me-2" style="color: #007bff;"></i>Kirim Pesan</h4>
                    <p class="text-muted mb-4" style="font-size: 14px;">Isi form di bawah ini, pesan akan dikirim via WhatsApp atau Email.</p>
                    <form id="contactForm" onsubmit="return handleContactSubmit(event)">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactName" class="form-label" style="font-weight: 600;">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contactName" placeholder="Masukkan nama Anda" required
                                    style="border-radius: 8px; border: 1px solid #e0e0e0; padding: 10px 15px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactEmail" class="form-label" style="font-weight: 600;">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="contactEmail" placeholder="Masukkan email Anda" required
                                    style="border-radius: 8px; border: 1px solid #e0e0e0; padding: 10px 15px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="contactSubject" class="form-label" style="font-weight: 600;">Subjek</label>
                            <select class="form-select" id="contactSubject"
                                style="border-radius: 8px; border: 1px solid #e0e0e0; padding: 10px 15px;">
                                <option value="">Pilih Subjek</option>
                                <option value="Informasi PPDB">Informasi PPDB</option>
                                <option value="Program Keahlian">Program Keahlian</option>
                                <option value="Kerjasama">Kerjasama</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label" style="font-weight: 600;">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="contactMessage" rows="4" placeholder="Tulis pesan Anda di sini..." required
                                style="border-radius: 8px; border: 1px solid #e0e0e0; padding: 10px 15px;"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <button type="submit" class="btn btn-primary w-100" id="btnWhatsApp"
                                    style="border-radius: 8px; background-color: #25D366; border-color: #25D366; padding: 10px;">
                                    <i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp
                                </button>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <button type="submit" class="btn btn-outline-primary w-100" id="btnEmail"
                                    style="border-radius: 8px; padding: 10px;">
                                    <i class="fas fa-envelope me-2"></i>Kirim via Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleContactSubmit(e) {
    e.preventDefault();
    var name = document.getElementById('contactName').value;
    var email = document.getElementById('contactEmail').value;
    var subject = document.getElementById('contactSubject').value || 'Tanpa Subjek';
    var message = document.getElementById('contactMessage').value;
    var submitter = e.submitter;

    if (!name || !email || !message) {
        alert('Mohon lengkapi nama, email, dan pesan.');
        return false;
    }

    if (submitter && submitter.id === 'btnEmail') {
        var mailtoSubject = encodeURIComponent('[Kontak Website] ' + subject);
        var mailtoBody = encodeURIComponent('Nama: ' + name + '\nEmail: ' + email + '\nSubjek: ' + subject + '\nPesan:\n' + message);
        window.location.href = 'mailto:{{ $siteSettings["contact_email"] ?? "smktelkomdujbg@gmail.com" }}?subject=' + mailtoSubject + '&body=' + mailtoBody;
    } else {
        var waMessage = encodeURIComponent(
            '*Pesan dari Website SMK Telekomunikasi*\n\n' +
            'Nama: ' + name + '\n' +
            'Email: ' + email + '\n' +
            'Subjek: ' + subject + '\n\n' +
            'Pesan:\n' + message
        );
        window.location.href = '{{ $siteSettings["social_whatsapp"] ?? "https://wa.me/6285649400339" }}?text=' + waMessage;
    }
    return false;
}
</script>
<!-- Contact Section End -->
