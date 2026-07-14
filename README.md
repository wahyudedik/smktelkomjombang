# Absensi ZKTeco - Sistem Manajemen Absensi Terintegrasi

Sistem manajemen absensi berbasis web yang terintegrasi dengan perangkat ZKTeco untuk tracking kehadiran real-time dengan fingerprint, face recognition, dan RFID.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📑 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Fitur Absensi ZKTeco](#-fitur-absensi-zkteco)
- [Fitur Tambahan](#-fitur-tambahan)
- [Dokumentasi Setup](#-dokumentasi-setup)
- [Teknologi](#-teknologi-yang-digunakan)

---

## 🚀 Fitur Utama

### ⏱️ Absensi Fingerprint/Face/RFID (ZKTeco)
- **CRUD User Management**: Tambah/edit/hapus user absensi dari web
- **Auto-Sync ke Device**: User otomatis ter-sync ke perangkat ZKTeco via ADMS command queue
- **Biometric Enrollment**: Enroll fingerprint (10 jari), face recognition, dan RFID card
- **Rekap Harian**: Sistem membentuk rekap `first_in` dan `last_out` per hari
- **Log Viewer**: Lihat semua log absensi dengan filter tanggal dan user
- **Device Management**: Manajemen perangkat ZKTeco yang terhubung
- **Export Rekap**: Export data absensi ke Excel dengan berbagai format
- **Report Absensi**: Report harian, mingguan, bulanan, per-user, dan keterlambatan
- **Mapping PIN**: PIN perangkat dipetakan ke data user/guru/siswa
- **Status Sync**: Lihat status sync user ke device (pending/sent/done/failed)

**Perangkat yang Didukung:**
- ZKTeco MB20 (fingerprint + face + RFID)
- ZKTeco MB160 (face + finger + card)
- ZKTeco MB360 (akurasi tinggi)
- ZKTeco iFace 302 / iFace 402 (profesional)

### 📊 Dashboard & Analytics
- **Dashboard Interaktif**: Overview statistik absensi dengan grafik real-time
- **Analytics Mendalam**: Tracking kehadiran, keterlambatan, dan trend absensi
- **Role-based Dashboard**: Dashboard khusus untuk setiap role (Admin, Guru, Siswa)
- **Statistik Kehadiran**: Persentase kehadiran, rata-rata jam kerja, keterlambatan

### 👥 Manajemen User & Role
- **Multi-Role System**: Admin, Guru, Siswa dengan permission granular
- **User Management**: CRUD lengkap dengan email verification
- **Role & Permission**: Sistem permission yang fleksibel dan aman
- **Audit Logging**: Tracking semua aktivitas penting dalam sistem

### 🏫 Manajemen Data Akademik
- **Guru Management**: Data lengkap guru dengan NIP dan sertifikasi
- **Siswa Management**: Data siswa dengan NIS/NISN, kelas, dan jurusan
- **Kelas Management**: Organisasi siswa per kelas dan jurusan
- **Import/Export**: Excel import/export untuk data bulk

### 📱 Fitur Tambahan
- **Real-time Notifications**: Notifikasi sistem yang komprehensif
- **Email Notifications**: Integrasi dengan email untuk notifikasi penting
- **Multi-language Support**: Dukungan multi-bahasa (EN, ID, AR)
- **RTL Language Support**: Dukungan bahasa RTL (Arab)
- **Multi-currency Support**: Dukungan multi-currency
- **Timezone Support**: Dukungan multiple timezone
- **Progressive Web App**: Instalasi sebagai aplikasi native
- **Offline Mode**: Akses data saat tidak ada koneksi internet
- **Push Notifications**: Notifikasi real-time di mobile devices
- **Responsive Design**: Desain responsif untuk semua perangkat

### 🔒 Security & Authorization
- **CSRF Protection**: Perlindungan dari serangan CSRF
- **XSS Protection**: Filter input untuk mencegah XSS
- **SQL Injection Protection**: Menggunakan Eloquent ORM
- **Role-Based Access Control**: Permission granular dengan policies
- **Audit Logging**: Tracking semua aktivitas penting
- **Rate Limiting**: Pembatasan request untuk mencegah abuse

### 🎨 Multi-Theme System
- **Convention-based Theming**: Tambah tema baru tanpa ubah controller/routes
- **4-Tier Favicon/Logo Resolution**: Per-theme DB → Global setting → Registry default → Hardcoded
- **Dynamic Menu Navigation**: Menu dari config, bukan hardcoded di view
- **View Override Convention**: `{base}-{theme}.blade.php` untuk override per tema
- **Theme Registry**: [`config/themes.php`](config/themes.php) — central definition semua tema

---

## 🎨 Menambah Tema Baru (Step-by-Step)

> **Prinsip**: Menambah tema baru = buat 4 file + set 1 env variable. Tidak perlu mengubah controller, routes, atau ThemeHelper.

### Step 1: Buat Config Tema

```bash
cp config/themes/telkom.php config/themes/smk_xyz.php
```

Edit `config/themes/smk_xyz.php` — sesuaikan name, contact, social media, jurusan, menu, dll:

```php
return [
    'name'        => 'SMK XYZ',
    'short_name'  => 'SMK XYZ',
    'type'        => 'SMK',
    'tagline'     => 'Sekolah Unggulan',
    'address'     => 'Jl. Contoh No.1, Kota',
    'phone'       => '0812-3456-7890',
    'whatsapp'    => '6281234567890',
    'email'       => 'info@smkxyz.sch.id',
    'assets_path' => 'assets_smk_xyz',
    'favicon'     => 'assets_smk_xyz/images/favicon.png',
    'logo'        => 'assets_smk_xyz/images/logo.png',
    'logo_light'  => 'assets_smk_xyz/images/logo-light.png',

    // Menu Navigasi (URL pakai format 'route:name')
    'menu' => [
        ['label' => 'Berita', 'url' => 'route:berita.public.index'],
        ['label' => 'E-Lulus', 'url' => 'route:public.graduation.check'],
        // ... tambah menu lainnya
    ],
    // ... config lainnya
];
```

### Step 2: Register di Theme Registry

Buka [`config/themes.php`](config/themes.php), tambah entry:

```php
'smk_xyz' => [
    'name'        => 'SMK XYZ',
    'view'        => 'smk_xyz',
    'layout'      => 'layouts.smk_xyz',
    'assets_path' => 'assets_smk_xyz',
    'colors'      => ['primary' => '#ff0000', 'secondary' => '#cc0000'],
    'defaults'    => [
        'favicon'    => 'assets_smk_xyz/images/favicon.png',
        'logo'       => 'assets_smk_xyz/images/logo.png',
        'logo_light' => 'assets_smk_xyz/images/logo-light.png',
    ],
],
```

### Step 3: Buat Layout & Landing Page

```bash
# Layout (copy dari telkom, lalu customize)
cp resources/views/layouts/telkom.blade.php resources/views/layouts/smk_xyz.blade.php

# Landing page
# Buat resources/views/smk_xyz.blade.php
```

Layout harus include `theme_image()` untuk favicon:
```html
<link rel="icon" type="image/x-icon" href="{{ theme_image('favicon', theme_info('defaults.favicon')) }}">
```

### Step 4: Buat Header & Footer Components

```bash
mkdir -p resources/views/components/smk_xyz
```

Buat `header.blade.php` dengan menu dari `theme_config('menu')`:
```html
@foreach(theme_config('menu', []) as $item)
    @if(isset($item['children']) && count($item['children']) > 0)
        {{-- dropdown menu --}}
    @else
        <a href="{{ resolve_theme_url($item['url'] ?? '#') }}">{{ $item['label'] }}</a>
    @endif
@endforeach
```

Logo otomatis benar via `theme_image()`:
```html
<img src="{{ theme_image('logo', theme_info('defaults.logo')) }}" alt="{{ theme_config('name') }}">
```

### Step 5: Buat Assets

```bash
mkdir -p public/assets_smk_xyz/css public/assets_smk_xyz/js public/assets_smk_xyz/images
# Copy CSS/JS dari tema lain, lalu customize
# Upload favicon/logo via admin: Theme Settings → SMK XYZ
```

### Step 6: Set Environment & Clear Cache

```env
# .env
DEFAULT_THEME=smk_xyz
```

```bash
php artisan config:clear && php artisan view:clear && php artisan cache:clear
```

### Step 7: Test

Buka browser dan test:
- [ ] Landing page (`GET /`)
- [ ] Berita, Pages, Kegiatan, E-Lulus
- [ ] Header/footer navigation
- [ ] Favicon di landing page, dashboard admin, halaman login
- [ ] Responsive design

> **Detail lengkap**: Lihat [`plans/theme-system-refactoring.md`](plans/theme-system-refactoring.md) atau [`AGENTS.md`](AGENTS.md)

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12**: Framework PHP modern dengan fitur terbaru
- **MySQL 8.0**: Database relasional yang powerful
- **Spatie Permission**: Sistem role dan permission yang robust
- **Laravel Excel**: Import/Export data Excel
- **DomPDF**: Generate PDF reports
- **Laravel Notifications**: Sistem notifikasi yang fleksibel

### Frontend
- **Tailwind CSS**: Framework CSS utility-first untuk admin panel
- **Bootstrap 5**: Framework CSS untuk landing page
- **Alpine.js**: JavaScript framework yang ringan
- **Chart.js**: Library grafik interaktif
- **Owl Carousel**: Slider dan carousel yang responsif
- **SweetAlert2**: Modal dialog yang cantik

### Integrasi
- **ZKTeco iClock API**: Integrasi dengan perangkat ZKTeco
- **Laravel Sanctum**: API authentication
- **Vite**: Modern asset bundler 

---

## 📚 Dokumentasi Setup

Untuk setup dan deployment, lihat dokumentasi lengkap:

- **[README-DOKUMENTASI.md](README-DOKUMENTASI.md)** - Panduan lengkap untuk menemukan dokumentasi
- **[SETUP-LENGKAP.md](SETUP-LENGKAP.md)** - Setup lengkap dari awal sampai selesai (~2 jam)
- **[vps_setup.md](vps_setup.md)** - VPS deployment untuk production (~1 jam)
- **[docs/absensi-zkteco-setup.md](docs/absensi-zkteco-setup.md)** - Dokumentasi teknis sistem
- **[DEPLOYMENT-GUIDE.md](DEPLOYMENT-GUIDE.md)** - Master guide lengkap
- **[DOKUMENTASI-INDEX.md](DOKUMENTASI-INDEX.md)** - Index lengkap dokumentasi
- **[plans/theme-system-refactoring.md](plans/theme-system-refactoring.md)** - Plan refactoring theme system + panduan menambah tema baru
- **[AGENTS.md](AGENTS.md)** - AI context file (juga berguna untuk developer manusia)

---

## 📞 Support & Documentation

### Bantuan & Pertanyaan
- **Email**: support@sekolah.com
- **Documentation**: [Wiki Repository](https://github.com/wahyudedik/ig-to-web/wiki)
- **Issues**: [GitHub Issues](https://github.com/wahyudedik/ig-to-web/issues)

### Quick Links
- [Setup untuk Pemula](SETUP-LENGKAP.md)
- [VPS Deployment](vps_setup.md)
- [Dokumentasi Teknis](docs/absensi-zkteco-setup.md)
- [Troubleshooting](DEPLOYMENT-GUIDE.md#-troubleshooting)

---

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## ❤️ Credits

**Absensi ZKTeco** - Sistem Manajemen Absensi Terintegrasi dengan ZKTeco

Dibuat dengan ❤️ untuk kemajuan pendidikan Indonesia

© 2025 Absensi ZKTeco. All rights reserved.
