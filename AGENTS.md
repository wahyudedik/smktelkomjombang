# AGENTS.md — AI Context untuk Project SMK Telekomunikasi

> File ini membantu AI assistant memahami project ini saat memulai chat baru atau pindah ke chat baru.
> Mirip dengan `CLAUDE.md`, `.cursorrules`, atau `copilot-instructions.md`.

---

## 🏫 Ringkasan Project

**SMK Telekomunikasi Darul Ulum** — Sistem informasi terpadu untuk SMK berbasis Laravel 12.
Project ini mengelola: landing page, data siswa/guru, absensi, OSIS voting, sarpras, surat-menyurat, Instagram integration, dan multi-theme support.

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------| 
| Framework | Laravel 12 |
| PHP | ≥ 8.2 |
| Database | MySQL (`telkom_db`) |
| Frontend | Blade templates + Bootstrap 5 |
| CSS Framework | Tailwind (admin), Bootstrap (landing) |
| JS | Alpine.js, jQuery, Owl Carousel, WOW.js |
| Auth | Laravel Breeze (Jetstream-style) |
| Permission | Spatie Laravel-Permission |
| Autoload | PSR-4 (`App\` → `app/`) |

---

## 📁 Struktur Direktori Penting

```
app/
├── Http/Controllers/          # 47 controller
│   ├── LandingController.php  # ⭐ Controller utama landing page (generic, supports all themes)
│   ├── BeritaController.php   # Berita public (theme-aware)
│   ├── PageController.php     # Pages public (theme-aware)
│   ├── InstagramController.php # Kegiatan (theme-aware)
│   ├── KelulusanController.php # E-Lulus (theme-aware)
│   ├── DashboardController.php
│   ├── SettingsController.php
│   └── ThemeSettingController.php
├── Models/                    # 41 model Eloquent
├── Services/                  # Service classes
│   ├── InstagramService.php   # Instagram Graph API integration
│   ├── StaticPageGenerator.php # Generate halaman statis landing page
│   ├── WebPushService.php     # Push notification
│   ├── AttendanceExportService.php
│   └── ZKTeco/               # ZKTeco iClock attendance integration
├── Helpers/                   # Custom helper functions
│   ├── ThemeHelper.php        # ⭐ theme_info(), theme_image(), theme_view(), theme_config(), current_theme(), resolve_theme_url(), available_themes()
│   ├── helpers.php            # General helpers
│   ├── RoleHelper.php         # Role-based helpers
│   ├── NotificationHelper.php # Notification dispatch helpers
│   └── i18n.php              # Internationalization helpers
config/
├── themes.php                 # ⭐ Theme registry — central definition semua tema
├── themes/                    # ⭐ Theme config files per tema
│   ├── telkom.php             #   (menu, contact, social, jurusan, dll)
│   └── maudu.php
database/
├── migrations/                # 60+ migration files
resources/
├── views/
│   ├── telkom.blade.php       # Landing page telkom
│   ├── maudu.blade.php        # Landing page maudu
│   ├── layouts/
│   │   ├── telkom.blade.php   # Layout telkom
│   │   ├── maudu.blade.php    # Layout maudu
│   │   ├── landing.blade.php  # Layout generic landing
│   │   ├── app.blade.php      # Admin layout
│   │   └── guest.blade.php    # Auth layout
│   ├── berita/public/         # Berita views (convention: index.blade.php, index-maudu.blade.php)
│   ├── pages/public/          # Pages views (convention: index.blade.php, show.blade.php)
│   └── components/            # Blade components (per tema: telkom/, maudu/)
public/
├── assets_telkom/             # Static assets tema telkom
├── assets_maudu/              # Static assets tema maudu
└── assets_{theme}/            # Static assets tema lainnya
plans/
├── theme-switching-maudu.md   # Dokumentasi detail sistem theme switching
└── theme-system-refactoring.md # ⭐ Plan refactoring theme system + panduan menambah tema
```

---

## 🎨 Sistem Theme Switching

Ini adalah fitur arsitektur utama yang HARUS dipahami oleh AI.

### Arsitektur (Setelah Refactoring)

```
config/themes.php          ← Theme Registry (metadata semua tema)
config/themes/{theme}.php  ← Theme Settings (per tema: menu, contact, dll)
Database theme_settings    ← Admin-editable overrides (upload logo/favicon)
.env DEFAULT_THEME         ← Default theme
```

**Priority**: Route Override → `DEFAULT_THEME` env → Registry → Config → DB → Hardcoded

### Helper Functions (ThemeHelper.php)

| Fungsi | Deskripsi |
|--------|-----------|
| [`theme_info($key)`](app/Helpers/ThemeHelper.php:165) | Ambil data dari registry `config/themes.php` |
| [`theme_image($key)`](app/Helpers/ThemeHelper.php:226) | 4-tier resolution: per-theme DB → global setting → registry default → hardcoded |
| [`theme_view($base)`](app/Helpers/ThemeHelper.php:285) | Convention-based: `{base}-{theme}.blade.php` → fallback `{base}.blade.php` |
| [`theme_config($key)`](app/Helpers/ThemeHelper.php:34) | Ambil config tema (priority: DB → Config file → Default) |
| [`current_theme()`](app/Helpers/ThemeHelper.php:108) | Get active theme (supports route override) |
| [`theme_asset($path)`](app/Helpers/ThemeHelper.php:204) | Generate themed asset URL |
| [`resolve_theme_url($url)`](app/Helpers/ThemeHelper.php:319) | Resolve `route:name` syntax dari config files |
| [`available_themes()`](app/Helpers/ThemeHelper.php:188) | List semua registered theme names |
| [`is_theme($theme)`](app/Helpers/ThemeHelper.php:123) | Generic theme check |
| `is_telkom()` / `is_maudu()` | Backward compat (deprecated) |

### Controller Landing Page
[`LandingController`](app/Http/Controllers/LandingController.php) adalah controller utama (GENERIC — tidak hardcode tema):
- `index()` → route `GET /` → render berdasarkan `current_theme()`
- `telkom()` → route `GET /telkom` → force telkom theme (deprecated)
- `maudu()` → route `GET /maudu` → force maudu theme (deprecated)
- `buildData()` → private method yang mengumpulkan semua data untuk landing page

### Routes
```php
Route::get('/', [LandingController::class, 'index'])->name('landing');              // Default theme
Route::get('/telkom', [LandingController::class, 'telkom'])->name('landing.telkom'); // Backward compat
Route::get('/maudu', [LandingController::class, 'maudu'])->name('landing.maudu');    // Backward compat
Route::get('/theme/{theme}', function (string $theme) { ... })->name('landing.theme'); // Dynamic
```

### View Naming Convention

Pattern: `{path}/{base}-{theme}.blade.php` → fallback `{path}/{base}.blade.php`

| Path | Default | Tema Override |
|------|---------|---------------|
| `berita/public/` | `index.blade.php` | `index-maudu.blade.php` |
| `pages/public/` | `index.blade.php` | `index-telkom.blade.php` (jika ada) |
| `public/elulus/` | `check.blade.php` | `check-maudu.blade.php` |
| `instagram/` | `activities.blade.php` | `activities-maudu.blade.php` |

### Favicon/Logo Resolution (4-Tier)

`theme_image('favicon')` resolve dengan prioritas:
1. **Per-theme DB** (`theme_settings` table) — upload via Admin → Theme Settings
2. **Global site setting** (`site_settings` table) — upload via Admin → Settings
3. **Theme registry default** (`config/themes.php` → `defaults.favicon`)
4. **Hardcoded fallback** (parameter kedua `theme_image()`)

> Favicon/logo otomatis benar di: landing page, dashboard admin, halaman login — sesuai tema aktif.

---

## 🔑 Konvensi Coding

### PHP & Laravel
- Gunakan **typed properties** dan **return types** di semua method
- Gunakan **Eloquent** untuk query (hindari raw query kecuali performance-critical)
- **Service classes** untuk logic kompleks (letakkan di `app/Services/`)
- **Helper functions** di `app/Helpers/` dengan pattern `if (!function_exists('...'))`
- **Cache** untuk data yang sering diakses (`Cache::remember()`)
- Gunakan **`safe()` helper pattern** untuk try-catch konsolidasi:
  ```php
  private function safe(callable $callback, mixed $fallback = null): mixed
  {
      try { return $callback(); }
      catch (\Exception $e) { return $fallback; }
  }
  ```

### Blade Templates
- Components di `resources/views/components/`
- Layout per tema: `layouts/telkom.blade.php`, `layouts/maudu.blade.php`
- Landing page views: `telkom.blade.php`, `maudu.blade.php`
- Gunakan `{{ theme_config('key') }}` untuk akses data tema

### Database
- MySQL dengan charset utf8mb4
- Session & cache di database (bukan Redis/file)
- Naming convention: `snake_case` untuk tabel dan kolom
- Foreign keys: `{model}_id`

---

## ⚙️ Environment Variables Penting

```env
DEFAULT_THEME=telkom          # atau 'maudu'
APP_NAME="SMK Telekomunikasi Darul Ulum"
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta
DB_DATABASE=telkom_db
FILESYSTEM_DISK=local
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## 📋 Modul Aplikasi

| Modul | Controller | Deskripsi |
|-------|-----------|-----------|
| Landing Page | `LandingController` | Multi-theme landing page |
| Dashboard | `DashboardController` | Admin dashboard dengan analytics |
| Siswa | `SiswaController` | CRUD data siswa + import |
| Guru | `GuruController` | CRUD data guru |
| Kelulusan | `KelulusanController` | Kelulusan siswa + sertifikat |
| OSIS Voting | `OSISController` | Pemilihan OSIS (calon, pemilih, voting) |
| Sarpras | `SarprasController` | Sarana prasarana + barcode |
| Sarana | `SaranaController` | Sarana sekolah |
| Surat Masuk | `LetterInController` | Surat masuk |
| Surat Keluar | `LetterOutController` | Surat keluar |
| Instagram | `InstagramController` | Instagram feed integration |
| Events | `EventController` | Kegiatan/events |
| Testimonial | `TestimonialController` | Testimoni |
| Pages | `PageController` | CMS pages (CRUD + versioning) |
| Attendance | `AttendanceController` | Absensi (ZKTeco iClock) |
| Settings | `SettingsController` | Pengaturan umum + SEO |
| Theme Settings | `ThemeSettingController` | Pengaturan tema per theme |
| Notifications | `NotificationController` | Push notifications |
| Role Management | `RoleManagementController` | Role & permission |
| Superadmin | `SuperadminController` | User management |
| Log Monitoring | `LogMonitoringController` | Monitoring logs |
| System Health | `SystemHealthController` | Health check |

---

## 🔧 Perintah Umum

```bash
# Jalankan server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Seed theme defaults
php artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()"

# Check syntax PHP
php -l app/Http/Controllers/LandingController.php

# Database migration
php artisan migrate
php artisan db:seed
```

---

## 🚀 Panduan: Menambah Tema Baru (Manual, Tanpa AI)

> **Prinsip**: Menambah tema baru = buat 4 file + set 1 env variable. Tidak perlu mengubah controller, routes, atau ThemeHelper.

### Step 1: Buat Config Tema

Buat file `config/themes/smk_xyz.php` (copy dari `config/themes/telkom.php`):

```php
<?php
// config/themes/smk_xyz.php
return [
    'name'        => 'SMK XYZ',
    'short_name'  => 'SMK XYZ',
    'type'        => 'SMK',
    'tagline'     => 'Sekolah Unggulan',
    'address'     => 'Jl. Contoh No.1, Kota',
    'phone'       => '0812-3456-7890',
    'whatsapp'    => '6281234567890',
    'email'       => 'info@smkxyz.sch.id',
    'ppdb_url'    => 'https://ppdb.smkxyz.sch.id/',

    // Social Media
    'facebook_url'  => 'https://facebook.com/smkxyz',
    'instagram_url' => 'https://instagram.com/smkxyz',
    'youtube_url'   => 'https://youtube.com/@smkxyz',

    // Assets
    'assets_path' => 'assets_smk_xyz',
    'favicon'     => 'assets_smk_xyz/images/favicon.png',
    'logo'        => 'assets_smk_xyz/images/logo.png',
    'logo_light'  => 'assets_smk_xyz/images/logo-light.png',

    // Jurusan
    'jurusan' => [
        ['name' => 'TKJ', 'full_name' => 'Teknik Komputer & Jaringan', 'desc' => '...', 'icon' => 'fas fa-network-wired'],
        ['name' => 'RPL', 'full_name' => 'Rekayasa Perangkat Lunak', 'desc' => '...', 'icon' => 'fas fa-code'],
    ],

    // Menu Navigasi (URL pakai format 'route:name' untuk route Laravel)
    'menu' => [
        [
            'label' => 'Profil',
            'url'   => '#',
            'children' => [
                ['label' => 'Tentang Kami', 'url' => 'route:pages.public.show,tentang-smk'],
                ['label' => 'Visi & Misi',  'url' => 'route:pages.public.show,visi-misi'],
            ],
        ],
        [
            'label' => 'Berita',
            'url'   => 'route:berita.public.index',
        ],
        [
            'label' => 'E-Lulus',
            'url'   => 'route:public.graduation.check',
        ],
    ],

    // CTA
    'cta_title'       => 'Pendaftaran Siswa Baru',
    'cta_button_url'  => 'https://ppdb.smkxyz.sch.id/',
    'cta_button_text' => 'Daftar Sekarang',

    // Working Hours
    'working_hours' => ['days' => 'Senin - Sabtu', 'hours' => '07:00 - 15:00 WIB'],
];
```

### Step 2: Register di Theme Registry

Buka `config/themes.php`, tambah entry di array `available`:

```php
// config/themes.php → available array
'smk_xyz' => [
    'name'        => 'SMK XYZ',
    'short_name'  => 'SMK XYZ',
    'type'        => 'SMK',
    'view'        => 'smk_xyz',           // nama view landing page
    'layout'      => 'layouts.smk_xyz',   // nama layout
    'assets_path' => 'assets_smk_xyz',
    'components'  => 'components.smk_xyz',
    'colors'      => ['primary' => '#ff0000', 'secondary' => '#cc0000'],
    'defaults'    => [
        'favicon'    => 'assets_smk_xyz/images/favicon.png',
        'logo'       => 'assets_smk_xyz/images/logo.png',
        'logo_light' => 'assets_smk_xyz/images/logo-light.png',
    ],
],
```

### Step 3: Buat Layout

Buat `resources/views/layouts/smk_xyz.blade.php` (copy dari `layouts/telkom.blade.php`, lalu customize):

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? theme_config('name') }} - {{ config('app.name') }}</title>

    <!-- ⭐ Favicon otomatis benar via theme_image() -->
    <link rel="icon" type="image/x-icon" href="{{ theme_image('favicon', theme_info('defaults.favicon')) }}">

    <!-- CSS tema -->
    <link rel="stylesheet" href="{{ asset(theme_info('assets_path') . '/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(theme_info('assets_path') . '/css/style.css') }}">
</head>
<body>
    {{-- Header --}}
    @include('components.smk_xyz.header')

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('components.smk_xyz.footer')

    <!-- JS -->
    <script src="{{ asset(theme_info('assets_path') . '/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

### Step 4: Buat Landing Page

Buat `resources/views/smk_xyz.blade.php`:

```html
@extends(theme_info('layout'))

@section('content')
    {{-- Hero Section --}}
    <section class="hero">
        <h1>{{ theme_config('name') }}</h1>
        <p>{{ theme_config('tagline') }}</p>
        <a href="{{ theme_config('cta_button_url') }}">{{ theme_config('cta_button_text') }}</a>
    </section>

    {{-- Jurusan/Program --}}
    @foreach(theme_config('jurusan', []) as $j)
        <div class="card">
            <i class="{{ $j['icon'] }}"></i>
            <h3>{{ $j['full_name'] }}</h3>
            <p>{{ $j['desc'] }}</p>
        </div>
    @endforeach
@endsection
```

### Step 5: Buat Header & Footer Components

```bash
mkdir -p resources/views/components/smk_xyz
```

Buat `resources/views/components/smk_xyz/header.blade.php` — menu otomatis render dari `theme_config('menu')`:

```html
<header>
    <a href="{{ route('landing') }}">
        <!-- ⭐ Logo otomatis benar via theme_image() -->
        <img src="{{ theme_image('logo', theme_info('defaults.logo')) }}" alt="{{ theme_config('name') }}">
    </a>
    <nav>
        <ul>
            <li><a href="{{ route('landing') }}">Beranda</a></li>
            @foreach(theme_config('menu', []) as $item)
                @if(isset($item['children']) && count($item['children']) > 0)
                    <li class="dropdown">
                        <a href="{{ resolve_theme_url($item['url'] ?? '#') }}">{{ $item['label'] }}</a>
                        <ul>
                            @foreach($item['children'] as $child)
                                <li><a href="{{ resolve_theme_url($child['url'] ?? '#') }}">{{ $child['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a href="{{ resolve_theme_url($item['url'] ?? '#') }}">{{ $item['label'] }}</a></li>
                @endif
            @endforeach
        </ul>
    </nav>
</header>
```

Buat `resources/views/components/smk_xyz/footer.blade.php`:

```html
<footer>
    <img src="{{ theme_image('logo_light', theme_info('defaults.logo_light')) }}" alt="{{ theme_config('name') }}">
    <p>{{ theme_config('address') }}</p>
    <p>{{ theme_config('phone') }}</p>
</footer>
```

### Step 6: Buat Assets

```bash
mkdir -p public/assets_smk_xyz/css
mkdir -p public/assets_smk_xyz/js
mkdir -p public/assets_smk_xyz/images
```

Siapkan file-file:
- `public/assets_smk_xyz/css/style.css` — CSS utama
- `public/assets_smk_xyz/css/bootstrap.min.css` — Bootstrap (atau CDN)
- `public/assets_smk_xyz/js/bootstrap.bundle.min.js` — JS Bootstrap
- `public/assets_smk_xyz/images/logo.png` — Logo dark
- `public/assets_smk_xyz/images/logo-light.png` — Logo light
- `public/assets_smk_xyz/images/favicon.png` — Favicon

### Step 7: Set Environment

Edit `.env`:

```env
DEFAULT_THEME=smk_xyz
```

### Step 8: (Optional) View Overrides

Jika ingin custom view untuk halaman tertentu (berita, pages, dll), buat file dengan naming convention:

```
resources/views/berita/public/index-smk_xyz.blade.php    ← override berita index
resources/views/berita/public/show-smk_xyz.blade.php     ← override berita show
resources/views/pages/public/index-smk_xyz.blade.php     ← override pages index
```

> **Tanpa override**: otomatis fallback ke view default (e.g., `berita/public/index.blade.php`)

### Step 9: Upload Branding via Admin (Opsional)

```
Admin → Theme Settings → SMK XYZ → General
├── Upload favicon    → otomatis tampil di semua halaman
├── Upload logo       → otomatis tampil di header/footer
├── Upload logo_light → otomatis tampil di footer
└── Lainnya (headmaster_photo, video_thumbnail, dll)
```

### Step 10: Clear Cache & Test

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan serve
```

**Testing Checklist:**
- [ ] Landing page (`GET /`)
- [ ] Berita index (`GET /berita`)
- [ ] Berita detail (`GET /berita/{slug}`)
- [ ] Pages index (`GET /pages`)
- [ ] Kegiatan (`GET /kegiatan`)
- [ ] E-Lulus check (`GET /check-graduation`)
- [ ] Header navigation links
- [ ] Footer links
- [ ] Login button
- [ ] Responsive design
- [ ] **Favicon di dashboard admin** (`/admin`)
- [ ] **Favicon di halaman login** (`/login`)
- [ ] **Logo di halaman login**
- [ ] **Favicon di landing page**
- [ ] **Upload favicon via Theme Settings** — muncul di semua halaman

---

## ⚠️ Hal Penting yang Harus Diperhatikan AI

1. **Jangan hapus import yang masih dipakai** — Cek semua usage sebelum menghapus `use` statement
2. **Theme system punya 4-tier image resolution** — Selalu gunakan `theme_image()` untuk favicon/logo
3. **`View::share()` digunakan** untuk sharing data global ke semua Blade views
4. **Static pages** di-generate oleh `StaticPageGenerator`, bukan di controller langsung
5. **Partner logos** punya fallback system (lihat `getPartners()` di LandingController)
6. **Storage symlink** harus ada: `php artisan storage:link`
7. **Assets per tema**: `public/assets_telkom/`, `public/assets_maudu/`, `public/assets_{theme}/` — jangan campur aduk
8. **Menu URL format**: Gunakan `route:name` syntax di config (e.g., `'route:berita.public.index'`), bukan `route()` langsung
9. **View naming**: Pattern `{base}-{theme}.blade.php` untuk override per tema

---

## 📚 Dokumentasi Terkait

- [`plans/theme-system-refactoring.md`](plans/theme-system-refactoring.md) — Plan lengkap refactoring theme system + panduan menambah tema
- [`plans/theme-switching-maudu.md`](plans/theme-switching-maudu.md) — Dokumentasi detail sistem theme switching (sebelum refactor)
- [`README.md`](README.md) — Readme project
- [`.kiro/hooks/laravel-expert.kiro.hook`](.kiro/hooks/laravel-expert.kiro.hook) — Laravel best practices hook
