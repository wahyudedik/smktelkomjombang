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
│   ├── LandingController.php  # ⭐ Controller utama landing page (telkom + maudu)
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
│   ├── ThemeHelper.php        # ⭐ theme_config(), current_theme(), theme_asset()
│   ├── helpers.php            # General helpers
│   ├── RoleHelper.php         # Role-based helpers
│   ├── NotificationHelper.php # Notification dispatch helpers
│   └── i18n.php              # Internationalization helpers
config/
├── themes/                    # ⭐ Theme config files per tema
│   ├── telkom.php
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
│   │   └── app.blade.php      # Admin layout
│   └── components/            # Blade components
public/
├── assets_telkom/             # Static assets tema telkom
└── assets_maudu/              # Static assets tema maudu
plans/
└── theme-switching-maudu.md   # ⭐ Dokumentasi detail sistem theme switching
```

---

## 🎨 Sistem Theme Switching

Ini adalah fitur arsitektur utama yang HARUS dipahami oleh AI.

### Mekanisme
1. **`DEFAULT_THEME`** di `.env` menentukan tema default (`telkom` atau `maudu`)
2. **3-tier priority**: Database (`theme_settings` table) → Config file (`config/themes/*.php`) → Hardcoded default
3. **`ThemeHelper`** (`app/Helpers/ThemeHelper.php`) menyediakan helper functions:
   - `theme_config($key)` — ambil config tema (priority: DB → Config → Default)
   - `current_theme()` — get active theme name
   - `is_telkom()` / `is_maudu()` — check theme
   - `theme_asset($path)` — generate themed asset URL

### Controller Landing Page
[`LandingController`](app/Http/Controllers/LandingController.php) adalah controller utama:
- `index()` → route `GET /` → render berdasarkan `DEFAULT_THEME`
- `telkom()` → route `GET /telkom` → force telkom theme
- `maudu()` → route `GET /maudu` → force maudu theme
- `buildData()` → private method yang mengumpulkan semua data untuk landing page
- `createStaticPages()` → delegate ke [`StaticPageGenerator`](app/Services/StaticPageGenerator.php)

### Route
```php
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/telkom', [LandingController::class, 'telkom']);
Route::get('/maudu', [LandingController::class, 'maudu']);
```

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

## ⚠️ Hal Penting yang Harus Diperhatikan AI

1. **Jangan hapus import yang masih dipakai** — Cek semua usage sebelum menghapus `use` statement
2. **Theme system punya 3-tier priority** — Selalu cek `theme_config()` sebelum hardcode value
3. **`View::share()` digunakan** untuk sharing data global ke semua Blade views
4. **Static pages** di-generate oleh `StaticPageGenerator`, bukan di controller langsung
5. **Partner logos** punya fallback system (lihat `getPartners()` di LandingController)
6. **Portfolio section** di MAUDU saat ini di-comment (placeholder images)
7. **Storage symlink** harus ada: `php artisan storage:link`
8. **Assets per tema**: `public/assets_telkom/` dan `public/assets_maudu/` — jangan campur aduk

---

## 📚 Dokumentasi Terkait

- [`plans/theme-switching-maudu.md`](plans/theme-switching-maudu.md) — Dokumentasi lengkap sistem theme switching
- [`README.md`](README.md) — Readme project
- [`.kiro/hooks/laravel-expert.kiro.hook`](.kiro/hooks/laravel-expert.kiro.hook) — Laravel best practices hook
