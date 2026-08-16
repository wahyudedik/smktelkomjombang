# Rencana Update Hero Slider — Landing Page Telkom

## Status: CSS Sudah Benar, Perlu Verifikasi & Penyesuaian Kecil

Berdasarkan analisis mendalam, **CSS di `style.css` sudah memiliki styling yang benar** untuk hero slider sesuai template HTML. Berikut detailnya:

---

## 1. Border-Radius (Rounded Corners) — ✅ Sudah Ada

CSS di [`style.css`](public/assets_telkom/style.css:4638) sudah mendefinisikan:

```css
.rs-slider.style1 .slider-item {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}
.rs-slider.style1 .slider-img img {
    border-radius: 20px;
}
.rs-slider.style1 .slider-overlay {
    border-radius: 20px;
}
```

**Tidak perlu perubahan CSS** — Sudah sesuai template.

---

## 2. Navigation Arrows (Chevron) — ✅ Sudah Ada

CSS di [`style.css`](public/assets_telkom/style.css:4762) sudah mendefinisikan:

```css
.rs-slider.style1 .owl-nav .owl-next,
.rs-slider.style1 .owl-nav .owl-prev {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #21a7d0;
    /* ... */
}
.rs-slider.style1 .owl-nav .owl-prev i:before {
    content: "\f053" !important;  /* fa-chevron-left */
}
.rs-slider.style1 .owl-nav .owl-next i:before {
    content: "\f054" !important;  /* fa-chevron-right */
}
```

Blade component [`hero-slider.blade.php`](resources/views/components/telkom/hero-slider.blade.php:12) sudah menggunakan `data-nav="true"` yang mengaktifkan arrows.

**Tidak perlu perubahan CSS/HTML** — Sudah sesuai template.

---

## 3. Pagination Dots — ✅ Sudah Dinonaktifkan

Template HTML menggunakan `data-dots="false"` yang menonaktifkan dots.
Blade component juga sudah menggunakan `data-dots="false"`.

**Tidak perlu perubahan** — Sudah sesuai template.

---

## 🔍 Masalah Yang Mungkin Terjadi

Berdasarkan screenshot yang diberikan, live site (telkom.test) menunjukkan:
1. **Konten MAUDU theme** ("Slide 1", "Daftar Sekarang") bukan konten TELKOM
2. **"LINK TERKAIT" text broken/wrapping** di header

Ini menunjukkan kemungkinan:
- `DEFAULT_THEME` di `.env` mungkin masih set ke `maudu`
- Atau ada caching yang menyimpan theme lama

---

## Rencana Aksi

### Jika masalah hanya theme configuration:
1. **Periksa `.env`** — Pastikan `DEFAULT_THEME=telkom`
2. **Clear cache** — `php artisan config:clear && php artisan view:clear && php artisan cache:clear`

### Jika ada perubahan CSS yang diperlukan:
Berdasarkan template HTML, CSS sudah 100% match. Tidak ada perubahan CSS yang diperlukan untuk hero slider.

### Bagian lain yang perlu disesuaikan (dari analisis sebelumnya):
Lihat [`plans/telkom-design-update.md`](plans/telkom-design-update.md) untuk perubahan pada:
1. Events component — Default link "Detail Kegiatan" ke `#`
2. Testimonials — Sembunyikan "Kirim Testimoni" dari default
3. Blog — Tambah item ke-4, sembunyikan "Lihat Semua Berita"
4. Footer social — Match social icons template
5. Config — Tambah field social baru
6. LandingController — Tambah field backend baru

---

## Kesimpulan

**Hero slider CSS sudah sesuai template HTML.** Perubahan yang diperlukan adalah:
1. Verifikasi `DEFAULT_THEME=telkom` di `.env`
2. Clear cache
3. Perubahan kecil pada komponen lain (bukan hero slider)
