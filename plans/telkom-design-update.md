# Rencana Update Desain Landing Page Telkom

## Ringkasan Analisis

Saya sudah membandingkan **template HTML asli** (`public/assets_telkom/telkom.html`) dengan **implementasi Blade saat ini** (komponen di `resources/views/components/telkom/`).

**Kabar baik**: Implementasi Blade saat ini sudah **sangat mendekati** template HTML. Struktur, CSS, JS, dan aset sudah lengkap. Perubahan yang dibutuhkan relatif kecil dan bersifat **presisi**.

---

## Perbandingan: Template HTML vs Blade Saat Ini

| Section | Template HTML | Blade Saat Ini | Status |
|---------|--------------|----------------|--------|
| Header/Topbar | Email, Phone, Login btn, Logo, Nav menu | Sudah match + backend override | ✅ Sama |
| Hero Slider | 2 slides + overlay | Sudah match + 3rd slide optional | ✅ Sama |
| Services/Jurusan | 4 kartu jurusan dengan icon | Sudah match | ✅ Sama |
| About/Kepsek | Kepsek + Counter + Grid | Sudah match + backend override | ✅ Sama |
| Degree/Kerjasama | 5 item kerjasama industri | Sudah match + fallback | ✅ Sama |
| CTA | Video + Pendaftaran | Sudah match | ✅ Sama |
| Events | 3 event + Detail button | ⚠️ Button pakai route, bukan `#` | 🔧 Perlu ubah |
| Partners | 6 logo partner carousel | Sudah match | ✅ Sama |
| Testimonials | Featured alumni + 2 testimoni | ⚠️ Ada tombol "Kirim Testimoni" extra | 🔧 Perlu ubah |
| Blog | 4 blog items carousel | ⚠️ Ada tombol "Lihat Semua Berita" extra + cuma 3 item default | 🔧 Perlu ubah |
| Footer | Jurusan, Link Terkait, Address, Social (fb/tw/ig/g+/pin) | ⚠️ Social links beda (fb/ig/yt/wa) | 🔧 Perlu ubah |

---

## Detail Perubahan yang Dibutuhkan

### 1. 🔧 Events Component — Default link "Detail Kegiatan"

**File**: `resources/views/components/telkom/events.blade.php`

**Masalah**: Saat ini tombol "Detail Kegiatan" selalu pakai `route('public.kegiatan')`, tapi di template HTML default-nya `href="#"`.

**Solusi**: Default ke `#`, tapi jika admin sudah set link kegiatan di backend, baru pakai route.

```blade
{{-- Saat ini --}}
<a href="{{ route('public.kegiatan') }}">Detail Kegiatan</a>

{{-- Sesuai template (default = #, configurable) --}}
<a href="{{ $siteSettings['events_detail_url'] ?? '#' }}">Detail Kegiatan</a>
```

---

### 2. 🔧 Testimonials Component — Hapus tombol "Kirim Testimoni" dari default

**File**: `resources/views/components/telkom/testimonials.blade.php`

**Masalah**: Template HTML **tidak** punya tombol "Kirim Testimoni". Saat ini ada tombol extra.

**Solusi**: Sembunyikan tombol "Kirim Testimoni" sebagai default, tampilkan hanya jika admin mengaktifkannya di backend.

```blade
{{-- Saat ini --}}
<a class="readon2 mod">Profil Alumni</a>
<a class="readon2" href="{{ route('testimonials.create') }}" style="margin-left: 10px;">
    <i class="fa fa-paper-plane"></i> Kirim Testimoni
</a>

{{-- Sesuai template (default tanpa "Kirim Testimoni") --}}
<a class="readon2 mod">Profil Alumni</a>
@if(!empty($siteSettings['show_testimonial_form']))
<a class="readon2" href="{{ route('testimonials.create') }}" style="margin-left: 10px;">
    <i class="fa fa-paper-plane"></i> {{ $siteSettings['testimonial_form_text'] ?? 'Kirim Testimoni' }}
</a>
@endif
```

---

### 3. 🔧 Blog Component — Hapus "Lihat Semua Berita" + tambah 4 item default

**File**: `resources/views/components/telkom/blog.blade.php`

**Masalah**: 
- Template HTML **tidak** punya tombol "Lihat Semua Berita" di bawah carousel
- Template HTML menampilkan **4 blog items**, Blade saat ini cuma **3 default**

**Solusi**: 
- Sembunyikan tombol "Lihat Semua Berita" sebagai default
- Tambah item blog default ke-4 agar match template
- Tombol muncul hanya jika admin mengaktifkannya di backend

```blade
{{-- Tambah item blog ke-4 --}}
<div class="blog-item">
    <div class="image-part">
        <img src="{{ asset('assets_telkom/assets/images/blog/style2/2.jpg') }}" alt="Blog Default">
    </div>
    <div class="blog-content new-style">
        <ul class="blog-meta">
            <li><i class="fa fa-user-o"></i> {{ $siteSettings['site_name'] ?? 'Admin' }}</li>
            <li><i class="fa fa-calendar"></i> {{ now()->format('M d, Y') }}</li>
        </ul>
        <h3 class="title"><a href="{{ route('berita.public.index') }}">Pengumuman & Informasi Sekolah</a></h3>
        <div class="desc">Pantau terus pengumuman dan informasi penting dari SMK Telekomunikasi Darul Ulum Jombang.</div>
        <ul class="blog-bottom">
            <li class="btn-part"><a class="readon-arrow" href="{{ route('berita.public.index') }}">Read More</a></li>
        </ul>
    </div>
</div>

{{-- Tombol "Lihat Semua Berita" — configurable --}}
@if(!empty($siteSettings['show_view_all_news']))
<div class="text-center mt-50">
    <a class="readon2" href="{{ route('berita.public.index') }}">
        {{ $siteSettings['view_all_news_text'] ?? 'Lihat Semua Berita' }} &nbsp;<i class="fa fa-arrow-right"></i>
    </a>
</div>
@endif
```

---

### 4. 🔧 Footer Social — Match template HTML

**File**: `resources/views/components/telkom/footer.blade.php`

**Masalah**: Template HTML punya social: Facebook, Twitter, Instagram, Google+, Pinterest. Saat ini Blade pakai: Facebook, Instagram, YouTube, WhatsApp.

**Solusi**: Default social icons sesuai template (fb, tw, ig, g+, pinterest), configurable via backend.

```blade
{{-- Social links — default sesuai template --}}
<ul class="footer-social">
    @if (theme_config('facebook_url'))
        <li><a href="{{ theme_config('facebook_url') }}" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a></li>
    @endif
    @if (theme_config('twitter_url') || theme_config('twitter'))
        <li><a href="{{ theme_config('twitter_url') ?? '#' }}" target="_blank" rel="noopener"><i class="fa fa-twitter"></i></a></li>
    @endif
    @if (theme_config('instagram_url'))
        <li><a href="{{ theme_config('instagram_url') }}" target="_blank" rel="noopener"><i class="fa fa-instagram"></i></a></li>
    @endif
    @if (theme_config('google_plus_url'))
        <li><a href="{{ theme_config('google_plus_url') }}" target="_blank" rel="noopener"><i class="fa fa-google-plus"></i></a></li>
    @endif
    @if (theme_config('pinterest_url'))
        <li><a href="{{ theme_config('pinterest_url') }}" target="_blank" rel="noopener"><i class="fa fa-pinterest-p"></i></a></li>
    @endif
</ul>
```

---

### 5. 🔧 Config Theme — Tambah field social baru

**File**: `config/themes/telkom.php`

**Masalah**: Config belum punya `twitter_url`, `google_plus_url`, `pinterest_url`.

**Solusi**: Tambah field social media baru agar match template.

```php
// Social Media
'twitter' => '',
'twitter_url' => '',
'google_plus_url' => '',
'pinterest_url' => '',
```

---

### 6. 🔧 LandingController — Tambah field backend baru

**File**: `app/Http/Controllers/LandingController.php`

**Masalah**: `getSiteSettings()` belum punya field untuk fitur baru.

**Solusi**: Tambah field:
- `events_detail_url` — URL custom untuk "Detail Kegiatan"
- `show_testimonial_form` — Toggle tampilkan tombol "Kirim Testimoni"
- `show_view_all_news` — Toggle tampilkan tombol "Lihat Semua Berita"
- `view_all_news_text` — Custom text tombol berita

---

## Yang TIDAK Perlu Diubah

Bagian-bagian yang sudah **sesuai template** dan **tidak perlu diubah**:

| Komponen | Alasan |
|----------|--------|
| `header.blade.php` | Sudah match template + dynamic via `theme_config('menu')` |
| `hero-slider.blade.php` | Sudah match template + configurable via `$siteSettings` |
| `services.blade.php` | Sudah match template (4 jurusan hardcoded sesuai template) |
| `about.blade.php` | Sudah match template + configurable via `$siteSettings` |
| `programs.blade.php` | Sudah match template + fallback 5 item kerjasama |
| `cta.blade.php` | Sudah match template + configurable via `$siteSettings` |
| `partners.blade.php` | Sudah match template + dynamic via `$partners` |
| `layouts/telkom.blade.php` | Sudah match template (CSS, JS, inline styles) |
| Semua CSS/JS assets | Sudah lengkap di `public/assets_telkom/` |
| Semua image assets | Sudah lengkap (slider, services, about, degrees, event, partner, testimonial, blog) |

---

## Konsep Dinamis yang Dimaksud User

```
Template HTML (telkom.html)
    ↓
Blade Components (default = template, configurable via backend)
    ↓
Backend Dashboard (Theme Settings / Site Settings)
    ↓
Override display (jika admin sudah set → tampilkan custom)
```

**Prinsip**: 
- **Default** = sesuai template HTML (tidak diubah)
- **Custom** = hanya muncul jika admin sudah update di dashboard
- **Tema lain** = template masa depan juga遵循 prinsip yang sama

---

## File yang Perlu Diubah (Ringkasan)

1. `resources/views/components/telkom/events.blade.php` — Default link `#`
2. `resources/views/components/telkom/testimonials.blade.php` — Sembunyikan "Kirim Testimoni"
3. `resources/views/components/telkom/blog.blade.php` — Tambah item ke-4, semunyikan "Lihat Semua Berita"
4. `resources/views/components/telkom/footer.blade.php` — Match social icons template
5. `config/themes/telkom.php` — Tambah field social baru
6. `app/Http/Controllers/LandingController.php` — Tambah field backend baru

**Total: 6 file diubah, semua perubahan kecil dan presisi.**
