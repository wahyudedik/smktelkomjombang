# SMK Telekomunikasi Darul Ulum — Sistem Informasi Terpadu

Sistem informasi terpadu berbasis Laravel 12 untuk SMK yang mengelola landing page multi-tema, data siswa/guru, absensi terintegrasi ZKTeco, OSIS voting, sarana prasarana, surat-menyurat, Instagram integration, dan fitur operasional sekolah lainnya.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Landing Page Multi-Tema](#landing-page-multi-tema)
- [Absensi ZKTeco](#absensi-zkteco-iclock)
- [Teknologi](#teknologi-yang-digunakan)
- [Setup](#dokumentasi-setup)
- [Menambah Tema Baru](#menambah-tema-baru-step-by-step)

---

## Fitur Utama

### Landing Page Multi-Tema
- **Convention-based Theming**: Tambah tema baru tanpa ubah controller/routes
- **4-Tier Favicon/Logo Resolution**: Per-theme DB → Global setting → Registry default → Hardcoded
- **Dynamic Menu Navigation**: Menu dari config, bukan hardcoded di view
- **View Override Convention**: `{base}-{theme}.blade.php` untuk override per tema
- **Theme Registry**: [`config/themes.php`](config/themes.php) — central definition semua tema
- **Theme Settings Admin**: Preview, clone, import/export, comparison, analytics
- **Tema Tersedia**: Telkom, MAUDU (dapat ditambah via config)

### Absensi ZKTeco iClock
- **CRUD User Management**: Tambah/edit/hapus user absensi dari web
- **Auto-Sync ke Device**: User otomatis ter-sync ke perangkat ZKTeco via ADMS command queue
- **Biometric Enrollment**: Enroll fingerprint (10 jari), face recognition, dan RFID card
- **Rekap Harian**: Sistem membentuk rekap `first_in` dan `last_out` per hari
- **Log Viewer**: Lihat semua log absensi dengan filter tanggal dan user
- **Device Management**: Manajemen perangkat ZKTeco yang terhubung
- **Export Rekap**: Export data absensi ke Excel dengan berbagai format
- **Export PDF**: Report harian, periode, dan ringkasan dalam format PDF
- **Report Absensi**: Report harian, mingguan, bulanan, per-user, dan keterlambatan
- **Sistem Izin/Sakit**: CRUD izin/sakit dengan approve/reject + throttle middleware
- **Mark Alpha Otomatis**: Penandaaan alpha otomatis via `MarkAlphaCommand` (jam 23:00)
- **Notifikasi Absensi**: Notifikasi harian, keterlambatan, dan izin pending via `AttendanceNotifyCommand`
- **Config Terpusat**: Semua pengaturan absensi di `config/attendance.php` + env vars `ATTENDANCE_*`
- **Mapping PIN**: PIN perangkat dipetakan ke data user/guru/siswa
- **Status Sync**: Lihat status sync user ke device (pending/sent/done/failed)

**Perangkat yang Didukung:**
- ZKTeco MB20 (fingerprint + face + RFID)
- ZKTeco MB160 (face + finger + card)
- ZKTeco MB360 (akurasi tinggi)
- ZKTeco iFace 302 / iFace 402 (profesional)

### Modul Akademik
- **Guru Management**: Data lengkap guru dengan NIP, sertifikasi, PIN mapping absensi
- **Siswa Management**: Data siswa dengan NIS/NISN, kelas, jurusan, data orang tua
- **Kelas & Jurusan**: Organisasi siswa per kelas dan jurusan
- **Mata Pelajaran & Ekstrakurikuler**: CRUD lengkap
- **Jadwal Pelajaran**: CRUD + Calendar view + Conflict check
- **E-Lulus/Kelulusan**: CRUD + Certificate generation + Public check (`/check-graduation`)
- **Import/Export**: Excel, PDF, JSON, XML untuk semua modul akademik

### Dashboard & Analytics
- **Dashboard Interaktif**: Overview statistik dengan grafik real-time dan caching
- **Analytics Mendalam**: Tracking kehadiran, keterlambatan, dan trend absensi
- **Role-based Dashboard**: Dashboard khusus untuk setiap role (Admin, Guru, Siswa)
- **System Health Check**: Monitoring status server
- **Log Monitoring**: View/download/clear Laravel logs
- **Module Usage**: Persentase penggunaan per modul

### Manajemen User & Role
- **Multi-Role System**: superadmin, admin, guru, siswa, sarpras, osis
- **Spatie Permission**: RBAC granular dengan permission
- **User Management**: CRUD + invite system + bulk import (superadmin)
- **Role & Permission Management**: CRUD roles/permissions + bulk create
- **Email Verification**: Admin + auto verification
- **Audit Logging**: Tracking semua aktivitas penting

### Modul Lainnya
- **OSIS Voting**: Dashboard, CRUD calon/pemilih, anti-fraud (IP, user_agent, one-vote), analytics + export PDF
- **E-Surat**: Surat masuk/keluar, auto-numbering, blocking logic, print PDF
- **Sarana Prasarana**: Kategori barang, barcode/QR, ruang, sarana, maintenance, import/export
- **CMS Pages**: CRUD + versioning + menu management + 6 templates
- **Berita**: CRUD + public view (theme-aware)
- **Instagram Integration**: OAuth, webhook, feed, mock fallback, analytics
- **Testimoni**: Public submit + admin approve/reject + token-based links
- **Events**: School events CRUD
- **Push Notifications (VAPID)**: WebPush via Minishlink
- **Multi-Language**: EN, ID, AR + RTL support
- **PWA**: Installable + offline mode

### Keamanan
- **CSRF Protection**: Laravel default
- **XSS Protection**: Eloquent ORM + Blade escaping + ContentSanitizer
- **SQL Injection Protection**: Eloquent ORM
- **Role-Based Access Control**: Spatie Permission
- **Rate Limiting**: Import routes (10/minute) + throttle middleware
- **Security Headers**: Middleware `SecurityHeaders` (CSP, X-Frame-Options, HSTS)
- **Anti-Fraud Voting**: IP tracking, user_agent, one-vote
- **Token-Based Auth ZKTeco**: `ATTENDANCE_ICLOCK_SECRET`

---

## Teknologi yang Digunakan

### Backend
| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 12 |
| PHP | >= 8.2 |
| Database | MySQL 8.0 (`telkom_db`) |
| Auth | Laravel Breeze |
| Permission | Spatie Laravel-Permission |
| Import/Export | Maatwebsite Excel |
| PDF | barryvdh/laravel-dompdf |
| Barcode | milon/barcode |
| Push Notification | minishlink/web-push |

### Frontend
| Layer | Teknologi |
|-------|-----------|
| Admin Panel | Tailwind CSS 3 + Alpine.js |
| Landing Page | Bootstrap 5 + jQuery |
| JS Libraries | Alpine.js, jQuery, Owl Carousel, WOW.js, SweetAlert2, Chart.js |
| Icons | Font Awesome |
| Asset Bundler | Vite 7 |

### Integrasi
| Layer | Teknologi |
|-------|-----------|
| ZKTeco iClock | cdata, getrequest, devicecmd, biometric enrollment |
| Instagram | Meta Business Login Flow, Graph API, Webhooks |
| VAPID | Web Push API |

---

## Dokumentasi Setup

Untuk setup dan deployment, lihat dokumentasi lengkap:

- **[README-DOKUMENTASI.md](README-DOKUMENTASI.md)** — Panduan lengkap untuk menemukan dokumentasi
- **[SETUP-LENGKAP.md](SETUP-LENGKAP.md)** — Setup lengkap dari awal sampai selesai (~2 jam)
- **[vps_setup.md](vps_setup.md)** — VPS deployment untuk production (~1 jam)
- **[docs/absensi-zkteco-setup.md](docs/absensi-zkteco-setup.md)** — Dokumentasi teknis sistem absensi
- **[DEPLOYMENT-GUIDE.md](DEPLOYMENT-GUIDE.md)** — Master guide lengkap
- **[DOKUMENTASI-INDEX.md](DOKUMENTASI-INDEX.md)** — Index lengkap dokumentasi
- **[FEATURES.md](FEATURES.md)** — Daftar lengkap fitur sistem
- **[ROADMAP.md](ROADMAP.md)** — Roadmap pengembangan
- **[plans/theme-system-refactoring.md](plans/theme-system-refactoring.md)** — Plan refactoring theme system + panduan menambah tema baru
- **[AGENTS.md](AGENTS.md)** — AI context file

### Quick Links
- [Setup untuk Pemula](SETUP-LENGKAP.md)
- [VPS Deployment](vps_setup.md)
- [Dokumentasi Teknis](docs/absensi-zkteco-setup.md)
- [Troubleshooting](DEPLOYMENT-GUIDE.md#-troubleshooting)

---

## Menambah Tema Baru (Step-by-Step)

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

# Landing page: buat resources/views/smk_xyz.blade.php
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

## Perintah Umum

```bash
# Jalankan server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Database migration
php artisan migrate
php artisan db:seed

# Attendance commands
php artisan attendance:mark-alpha              # Tandai alpha otomatis (default: hari ini)
php artisan attendance:mark-alpha --date=2026-08-15  # Tandai alpha untuk tanggal tertentu
php artisan attendance:notify --summary        # Kirim rekap harian ke admin
php artisan attendance:notify --late           # Kirim notifikasi keterlambatan
php artisan attendance:notify --excuse         # Kirim notifikasi izin pending

# Scheduler (tambahkan di crontab production)
# * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## License

Distributed under the MIT License. See `LICENSE` for more information.

---

## Credits

**SMK Telekomunikasi Darul Ulum** — Sistem Informasi Terpadu

Dibuat dengan untuk kemajuan pendidikan Indonesia.

© 2025 SMK Telekomunikasi Darul Ulum. All rights reserved.
