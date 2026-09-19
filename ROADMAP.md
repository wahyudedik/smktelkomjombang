# 🚀 Roadmap Pengembangan — SMK Telekomunikasi Darul Ulum

> Dokumen ini mencatat seluruh rencana pengembangan proyek, status implementasi, dan prioritas.
> Diperbarui: 2026-08-21

---

## 📊 Status Proyek

| Metrik | Nilai |
|--------|-------|
| Tech Stack | Laravel 12 + PHP 8.2 + MySQL |
| Total Controller | 47 |
| Total Model | 41 |
| Total Migration | 70+ |
| Total Route | 822 baris |
| Total Test | 15 file |
| Total View | 100+ Blade templates |
| Tema Aktif | Telkom, MAUDU |
| Status | 🟢 **PRODUCTION** |

---

## ✅ Fitur yang Sudah Selesai

### Tahap 1: Fondasi (Selesai)
- [x] Setup Laravel 12 + Vite + Tailwind + Alpine.js
- [x] Setup Spatie Permission (RBAC)
- [x] Setup Laravel Breeze (Auth)
- [x] Setup MySQL database
- [x] Setup deployment script (`deploy.sh`)

### Tahap 2: Modul Akademik (Selesai)
- [x] Guru Management (CRUD + Import/Export)
- [x] Siswa Management (CRUD + Import/Export)
- [x] Kelas & Jurusan Management
- [x] Mata Pelajaran & Ekstrakurikuler
- [x] Jadwal Pelajaran (CRUD + Calendar + Conflict Check)
- [x] E-Lulus/Kelulusan (CRUD + Certificate + Public Check)

### Tahap 3: Absensi ZKTeco (Selesai)
- [x] ZKTeco iClock Controller (cdata, getrequest, devicecmd)
- [x] IClockIngestService + IClockPayloadParser
- [x] IClockCommandQueue (pending/sent/done/failed)
- [x] UserSyncService (ADMS commands)
- [x] BiometricEnrollmentService (fingerprint/face/RFID)
- [x] AttendanceSync Artisan Command
- [x] Device Management
- [x] PIN Mapping
- [x] Raw Logs Viewer
- [x] Export Excel (harian/periode/summary/user)

### Tahap 4: Modul Lainnya (Selesai)
- [x] OSIS Voting (calon, pemilih, voting, anti-fraud, analytics)
- [x] E-Surat (surat masuk/keluar, auto-numbering, blocking logic)
- [x] Sarpras (kategori, barang, ruang, maintenance, barcode/QR)
- [x] CMS Pages (CRUD + versioning + menu management)
- [x] Berita (CRUD + public view)
- [x] Instagram Integration (OAuth, webhook, feed)
- [x] Testimonials (submit + approve/reject)
- [x] Partners & Events
- [x] Push Notifications (VAPID)
- [x] Multi-Language (EN, ID, AR)
- [x] Audit Logging
- [x] Dashboard & Analytics
- [x] System Health & Log Monitoring

### Tahap 5: Theme System (Selesai)
- [x] Theme registry (`config/themes.php`)
- [x] Theme config files (`config/themes/{theme}.php`)
- [x] ThemeHelper functions (theme_info, theme_image, theme_view, theme_config)
- [x] 4-tier favicon/logo resolution
- [x] Convention-based view override
- [x] Theme settings admin (database-backed)
- [x] Theme preview, clone, import/export, comparison, analytics

### Tahap 5b: Theme System Audit & Fix (Selesai — 2026-08-16)
- [x] Fix cache keys di LandingController → generic `landing_{theme}_*` prefix
- [x] Fix logo inconsistency di `config/themes/telkom.php` (logo-dark.png vs logo.png)
- [x] Tambah `ThemeSettingsSeeder` ke `deploy.sh` & `update.sh`
- [x] Tambah permission granular `themes.view` / `themes.edit` ke routes & RolePermissionSeeder
- [x] Refactor `ThemeSetting::getRegisteredThemes()` → reads from `config('themes.available')`
- [x] Fix header canvas menu logo → `theme_image()` (bukan hardcoded `asset()`)
- [x] Fix footer & header "Link Terkait" → render dari `theme_config('related_links')`
- [x] Update FEATURES.md & ROADMAP.md

### Tahap 6: Lengkapi View Report Absensi (Selesai — 2026-08-20)
- [x] View report mingguan (`attendance/report/weekly.blade.php`)
- [x] View report bulanan (`attendance/report/monthly.blade.php`)
- [x] View report keterlambatan (`attendance/report/latecomers.blade.php`)
- [x] View report user detail (`attendance/report/user-detail.blade.php`)

### Tahap 7: Sistem Izin/Sakit/Alpha (Selesai — 2026-08-20)
- [x] Migration `attendance_excuses` table
- [x] Model `AttendanceExcuse` (182 baris)
- [x] Controller `AttendanceExcuseController` (277 baris)
- [x] View CRUD izin/sakit (index, create, edit, show)
- [x] `MarkAlphaCommand` + scheduler (jam 23:00)
- [x] Integrasi dengan AttendanceSync

### Tahap 8: Notifikasi Absensi (Selesai — 2026-08-20)
- [x] Notification `AttendanceNotification`
- [x] `AttendanceNotifyCommand` + scheduler (summary + late + excuse)
- [x] Integrasi dengan AttendanceHelper

### Tahap 9: Export PDF Absensi (Selesai — 2026-08-20)
- [x] PDF view daily (`attendance/pdf/daily.blade.php`)
- [x] PDF view period (`attendance/pdf/period.blade.php`)
- [x] PDF view summary (`attendance/pdf/summary.blade.php`)
- [x] Update AttendanceExportService
- [x] Update AttendanceExportController

### Tahap 10: Config Attendance Terpusat (Selesai — 2026-08-20)
- [x] `config/attendance.php` (164 baris — centralized)
- [x] Semua env vars `ATTENDANCE_*` terdefinisi

### Tahap 11: MAUDU Theme Polish (Selesai — 2026-08-21)
- [x] Footer links validation (pastikan semua link aktif)
- [x] Login button visibility check
- [x] Menu config audit (semua menu routing benar)
- [x] MAUDU component review (header, footer, sidebar)
- [x] Breadcrumb MAUDU untuk semua halaman (`<x-maudu.breadcrumb>`)
- [x] Dual-theme testing (Telkom + MAUDU)

### Tahap 12: Documentation Sync (Selesai — 2026-08-21)
- [x] Sinkronisasi FEATURES.md dengan codebase
- [x] Sinkronisasi ROADMAP.md dengan codebase
- [x] Update README.md (judul, deskripsi, tech stack)
- [x] Update `.env.example` dengan semua env vars
- [x] Review & update AGENTS.md
- [x] Review & update semua plan docs di `plans/`

### Tahap 13: Security Hardening (Selesai — 2026-08-21)
- [x] Rate limiting untuk semua routes sensitif (42+ routes dilindungi throttle middleware)
- [x] Content Security Policy (CSP) headers via `SecurityHeaders` middleware
- [x] XSS audit semua form input + `ContentSanitizer` diperluas ke 5 controller
- [x] N+1 query audit
- [x] Security headers: X-Frame-Options, X-Content-Type-Options, HSTS, Referrer-Policy, Permissions-Policy
- [x] Session security hardening

### Tahap 14: Mobile Responsive Improvements (Selesai — 2026-08-21)
- [x] Audit responsive design di semua halaman admin (6 views)
- [x] Fix table overflow di mobile
- [x] Fix form layout di small screen
- [x] Touch-friendly buttons & links
- [x] Test di berbagai viewport (320px, 768px, 1024px)

---

## 🔄 Fitur yang Sedang Dikerjakan

_(Kosong — semua tahap sudah selesai)_

---

## 📋 Fitur yang Belum Dikerjakan

### Kategori Prioritas Sedang (🟡)
1. ~~**MAUDU Theme Polish**~~ ✅ Selesai
2. ~~**Security Hardening**~~ ✅ Selesai
3. ~~**Mobile Responsive**~~ ✅ Selesai
4. **Theme Inheritance** — Base → child themes
5. **Performance Optimization** — CDN, eager loading audit

### Kategori Prioritas Rendah (🟢)
6. **Admin UI Modernization** — Chart.js, Alpine.js interactivity
7. **Dark Mode** — Toggle light/dark
8. **Comprehensive Test Suite** — Target 80% coverage
9. **Compliance & Accessibility** — WCAG 2.1 AA
10. **Internationalization Expansion** — Tambah bahasa baru

---

## 🎯 Milestones

### Milestone 1: Attendance Complete — ✅ TERCAPAI (2026-08-20)
- [x] Lengkapi semua view report absensi (weekly, monthly, latecomers, user-detail)
- [x] Sistem izin/sakit/alpha (CRUD + approve/reject + MarkAlphaCommand)
- [x] Export PDF absensi (daily, period, summary)
- [x] Notifikasi absensi (AttendanceNotifyCommand)
- [x] Config attendance terpusat (`config/attendance.php`)

### Milestone 2: MAUDU Theme Complete — ✅ TERCAPAI (2026-08-21)
- [x] Theme-aware controllers (generic `LandingController`)
- [x] Semua view publik MAUDU (berita, pages, instagram, elulus)
- [x] Fix component MAUDU (header/footer review)
- [x] Breadcrumb MAUDU untuk semua halaman
- [x] Dual-theme testing

### Milestone 3: Security & Quality — ✅ TERCAPAI (2026-08-21)
- [x] Security hardening (CSP via SecurityHeaders middleware, rate limiting 42+ routes)
- [x] XSS audit + ContentSanitizer diperluas (BeritaController, EventController, PageController, TestimonialController, SettingsController)
- [x] Security headers (X-Frame-Options, X-Content-Type-Options, HSTS, Referrer-Policy, Permissions-Policy)
- [x] N+1 query audit
- [x] Documentation update (FEATURES.md, ROADMAP.md, README.md, .env.example, AGENTS.md)
- [ ] Comprehensive test suite (target 80% coverage — milestone berikutnya)

### Milestone 4: Enhancement (Target: November 2026)
- [ ] Admin UI modernization
- [ ] Dark mode
- [ ] Performance optimization
- [ ] CDN integration
- [ ] Theme inheritance

---

## 📝 Changelog / Recent Updates

### 2026-08-21
- ✅ **Tahap 11 selesai**: MAUDU Theme Polish — breadcrumb, component review, dual-theme testing
- ✅ **Tahap 12 selesai**: Documentation Sync — sinkronisasi ROADMAP, FEATURES, AGENTS, .env.example
- ✅ **Tahap 13 selesai**: Security Hardening — 42+ routes dilindungi throttle, CSP headers, ContentSanitizer diperluas ke 5 controller
- ✅ **Tahap 14 selesai**: Mobile Responsive — 6 admin views di-audit dan di-fix
- ✅ **Milestone 2 & 3 tercapai**: MAUDU Theme Complete dan Security & Quality
- 🔧 Update `.env.production`, `.env.production.telkom`, `.env.production.maudu` — pastikan semua env vars lengkap

### 2026-08-20
- ✅ **Tahap 6-10 selesai**: Lengkapi view report, sistem izin, notifikasi, export PDF, config attendance

### 2026-08-16
- ✅ **Tahap 5b selesai**: Theme System Audit & Fix

---

## 📝 Catatan Pengembangan

### Aturan Eksekusi
1. **Maksimal 10 file per tahap** — Mencegah code corrupt
2. **Incremental execution** — Selesaikan tahap sebelum lanjut
3. **Tidak merusak migration production** — Selalu buat migration baru
4. **Database transaction** — Operasi multi-tabel harus pakai `DB::transaction`
5. **Update deployment script** — Setiap perubahan dependency
6. **Update dokumentasi** — Sinkron setiap perubahan

### Git Hygiene
- Commit atomic dengan pesan deskriptif
- Format: `type(scope): description`
- Contoh: `fix(attendance): add missing report views`

### Testing
- Jalankan `php artisan test` setiap perubahan
- Test dual-theme (Telkom + MAUDU)
- Test semua role akses
- Test empty state dan null input
