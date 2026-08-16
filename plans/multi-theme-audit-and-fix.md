# 🔍 Audit & Perbaikan Sistem Multi-Theme

> **Tanggal:** 2026-08-16
> **Status:** ✅ Selesai (2026-08-16)
> **Fokus:** Audit menyeluruh fitur multi-theme, perbaikan bug, dan peningkatan kualitas

---

## 📊 Ringkasan Hasil Audit

### ✅ Yang Sudah Aman & Benar

| Komponen | Status | Keterangan |
|----------|--------|-----------|
| Theme Registry (`config/themes.php`) | ✅ | 2 tema terdaftar: telkom, maudu |
| Theme Config Files | ✅ | `config/themes/telkom.php` & `config/themes/maudu.php` lengkap |
| ThemeHelper Functions | ✅ | 10 fungsi utama: `theme_config`, `theme_image`, `theme_view`, `theme_info`, `current_theme`, `theme_asset`, `resolve_theme_url`, `available_themes`, `is_theme`, `theme_config_set` |
| 4-Tier Image Resolution | ✅ | Per-theme DB → Global setting → Registry → Hardcoded |
| Convention-Based View Override | ✅ | `{base}-{theme}.blade.php` → fallback ke base |
| LandingController (Generic) | ✅ | Tidak hardcode tema, resolve via `current_theme()` |
| Theme Settings Admin Panel | ✅ | CRUD + preview, clone, import/export, comparison, analytics |
| Theme Settings Routes Security | ✅ | Protected by `role:admin\|superadmin` middleware |
| Theme Settings Seeder | ✅ | Safe `seedDefaults()`, tidak overwrites data existing |
| Deployment Scripts | ✅ | `deploy.sh` & `update.sh` lengkap, auto-detect theme dari `.env` |
| `.env` Configuration | ✅ | `DEFAULT_THEME=telkom` sudah benar |
| Dynamic Theme Route | ✅ | `/theme/{theme}` untuk preview/akses tema manapun |
| Views Telkom | ✅ | Landing page + 14 komponen Blade |
| Views MAUDU | ✅ | Landing page + 18 komponen Blade |
| Theme-Aware Controllers | ✅ | Berita, Pages, Kelulusan, Instagram sudah pakai `theme_view()` |

### ⚠️ Temuan Issues (Perlu Perbaikan)

| # | Prioritas | Kategori | Deskripsi |
|---|-----------|----------|-----------|
| 1 | 🔴 Tinggi | Bug | Cache keys di `LandingController` masih hardcode prefix `telkom_` — seharusnya generic per-theme |
| 2 | 🔴 Tinggi | Security | Theme settings routes tidak ada permission granular (hanya role check) |
| 3 | 🟡 Sedang | Refactor | `ThemeSetting::getRegisteredThemes()` hardcode — redundant dengan `available_themes()` |
| 4 | 🟡 Sedang | Inconsistency | `logo` & `logo_light` di `config/themes/telkom.php` pointing ke file yang sama |
| 5 | 🟡 Sedang | Deployment | `ThemeSettingsSeeder` belum ada di `deploy.sh` & `update.sh` |
| 6 | 🟡 Sedang | Hardcode | Footer telkom: "Link Terkait" masih hardcoded (`#`), belum dari config |
| 7 | 🟡 Sedang | Hardcode | Header telkom: "Link Terkait" dropdown masih hardcoded |
| 8 | 🟢 Rendah | Audit | Perlu cek semua komponen telkom untuk hardcoded values lainnya |

---

## 🏗 Arsitektur Theme System (Current State)

```
┌─────────────────────────────────────────────────────────┐
│                    REQUEST FLOW                         │
│                                                         │
│  Browser → Route → LandingController::index()           │
│                        ↓                                │
│                  current_theme()                         │
│                        ↓                                │
│     ┌── Route Override? ──→ config('app.theme_override')│
│     ├── DEFAULT_THEME env ──→ 'telkom'                  │
│     └── Fallback ──→ 'telkom'                           │
│                        ↓                                │
│              view($theme, $data)                        │
│         telkom.blade.php or maudu.blade.php             │
│                        ↓                                │
│         @extends(theme_info('layout'))                  │
│         layouts/telkom.blade.php                        │
│                        ↓                                │
│     @include('components.telkom.header')                │
│     @yield('content')                                   │
│     @include('components.telkom.footer')                │
│                                                         │
│  DATA RESOLUTION:                                       │
│  theme_config('key') → DB > Config File > Default       │
│  theme_image('key')  → Per-theme DB > Global > Registry │
│  theme_view('base')  → {base}-{theme} > {base}         │
└─────────────────────────────────────────────────────────┘
```

---

## 📋 Rencana Perbaikan (Detail)

### Tahap 1: Bug Fixes Kritis (Maks 10 File)

#### 1.1 Fix Cache Keys di LandingController (Bug)
- **File:** `app/Http/Controllers/LandingController.php`
- **Masalah:** Cache keys hardcode `telkom_siswa_count`, `telkom_blogs`, dll
- **Fix:** Gunakan prefix `{theme}_` atau generic prefix tanpa nama tema
- **Dampak:** Jika user switch theme, data cache tetap valid (karena data shared) tapi tidak konsisten namingnya

```php
// Sebelum:
Cache::remember('telkom_siswa_count', 86400, function () { ... });

// Sesudah:
$theme = current_theme();
Cache::remember("landing_{$theme}_siswa_count", 86400, function () { ... });
```

#### 1.2 Fix Logo Inconsistency (Config)
- **File:** `config/themes/telkom.php`
- **Masalah:** `logo` = `logo.png` (sama dengan `logo_light`). Registry defaults: `logo` = `logo-dark.png`, `logo_light` = `logo.png`
- **Fix:** Sinkronkan `config/themes/telkom.php` dengan registry defaults di `config/themes.php`

#### 1.3 Tambah ThemeSettingsSeeder ke Deployment Scripts
- **File:** `deploy.sh`, `update.sh`
- **Masalah:** Theme settings tidak di-seed otomatis saat deploy
- **Fix:** Tambahkan `php artisan db:seed --class=ThemeSettingsSeeder` setelah migration

#### 1.4 Tambah Permission Granular untuk Theme Settings
- **File:** `routes/web.php`
- **Masalah:** Theme settings hanya pakai `role:admin|superadmin`, tidak ada permission check
- **Fix:** Tambahkan `permission:themes.view`, `permission:themes.edit` seperti modul lain

### Tahap 2: Hardcode Cleanup (Maks 10 File)

#### 2.1 Refactor ThemeSetting::getRegisteredThemes()
- **File:** `app/Models/ThemeSetting.php`
- **Masalah:** Hardcoded array `['telkom' => ..., 'maudu' => ...]`
- **Fix:** Gunakan `available_themes()` dari config atau `config('themes.available')`

#### 2.2 Fix Footer Telkom Hardcoded Links
- **File:** `resources/views/components/telkom/footer.blade.php`
- **Masalah:** "Link Terkait" section masih hardcoded (E-Rapor, E-Osis, E-Learning, E-Perpus)
- **Fix:** Render dari `theme_config('menu')` atau section baru `theme_config('footer_links')`

#### 2.3 Fix Header Telkom Hardcoded Links
- **File:** `resources/views/components/telkom/header.blade.php`
- **Masalah:** Dropdown "Link Terkait" masih hardcoded
- **Fix:** Render dari config atau buat config key baru `related_links`

### Tahap 3: Audit & Documentation

#### 3.1 Audit Komponen Telkom Lainnya
- Cek `hero-slider.blade.php`, `about.blade.php`, `programs.blade.php`, `services.blade.php`, `cta.blade.php`, `testimonials.blade.php`, `blog.blade.php`, `events.blade.php`, `partners.blade.php`, `instagram.blade.php`, `contact.blade.php`, `breadcrumb.blade.php`

#### 3.2 Update FEATURES.md & ROADMAP.md
- Perbarui status fitur multi-theme
- Tambahkan catatan tentang perbaikan yang dilakukan

---

## 🔄 Alur Perbaikan (Execution Flow)

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  Tahap 1:    │     │  Tahap 2:    │     │  Tahap 3:    │
│  Bug Fixes   │ ──→ │  Hardcode    │ ──→ │  Audit &     │
│  Kritis      │     │  Cleanup     │     │  Doc Update  │
│  (5 items)   │     │  (3 items)   │     │  (2 items)   │
└──────────────┘     └──────────────┘     └──────────────┘
     │                    │                    │
     ▼                    ▼                    ▼
  Max 10 file         Max 10 file          Max 10 file
  per tahap           per tahap            per tahap
```

---

## ⚙️ Deployment Checklist

Setelah semua perbaikan selesai:

- [ ] Jalankan `php artisan config:clear && php artisan view:clear && php artisan cache:clear`
- [ ] Jalankan `php artisan db:seed --class=ThemeSettingsSeeder`
- [ ] Test landing page Telkom (`GET /`)
- [ ] Test landing page MAUDU (`GET /maudu`)
- [ ] Test dynamic theme route (`GET /theme/telkom`)
- [ ] Test theme settings admin (`/admin/settings/themes`)
- [ ] Test theme preview dari admin
- [ ] Test theme clone dari admin
- [ ] Test theme import/export dari admin
- [ ] Test favicon/logo berubah sesuai tema di: landing, dashboard admin, login page
- [ ] Verify `deploy.sh` dan `update.sh` berjalan lancar

---

## 📝 Catatan Penting

1. **Telkom adalah default theme** — Semua config Telkom sudah lengkap sebagai baseline
2. **Theme settings bisa di-update dari dashboard** — Login sebagai superadmin → Settings → Theme Settings
3. **Permission berbasis role** — Superadmin punya akses penuh, admin tergantung permission yang diberikan
4. **Tidak perlu ubah controller saat tambah tema baru** — Cukup tambah config + view + assets
5. **Cache otomatis clear saat update theme settings** — `ThemeSetting::clearCache($theme)` dipanggil otomatis
