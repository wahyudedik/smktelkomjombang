# 🚀 Roadmap Pengembangan — SMK Telekomunikasi Darul Ulum

> Dokumen ini mencatat seluruh rencana pengembangan proyek, status implementasi, dan prioritas.
> Diperbarui: 2026-08-20

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

---

## 🔄 Fitur yang Sedang Dikerjakan

### Tahap 11: MAUDU Theme Polish
- [ ] Footer links validation (pastikan semua link aktif)
- [ ] Login button visibility check
- [ ] Menu config audit (semua menu routing benar)
- [ ] MAUDU component review (header, footer, sidebar)
- [ ] Breadcrumb MAUDU untuk semua halaman
- [ ] Dual-theme testing (Telkom + MAUDU)

### Tahap 12: Documentation Sync
- [ ] Sinkronisasi FEATURES.md dengan codebase
- [ ] Sinkronisasi ROADMAP.md dengan codebase
- [ ] Update README.md (judul, deskripsi, tech stack)
- [ ] Update `.env.example` dengan semua env vars
- [ ] Review & update AGENTS.md
- [ ] Review & update semua plan docs di `plans/`

### Tahap 13: Security Hardening
- [ ] Rate limiting untuk semua routes sensitif
- [ ] Content Security Policy (CSP) headers
- [ ] XSS audit semua form input
- [ ] N+1 query audit
- [ ] Dependency audit (`composer audit`, `npm audit`)
- [ ] Session security hardening

### Tahap 14: Mobile Responsive Improvements
- [ ] Audit responsive design di semua halaman admin
- [ ] Fix table overflow di mobile
- [ ] Fix form layout di small screen
- [ ] Touch-friendly buttons & links
- [ ] Test di berbagai viewport (320px, 768px, 1024px)

---

## 📋 Fitur yang Belum Dikerjakan

### Kategori Prioritas Sedang (🟡)
1. **MAUDU Theme Polish** — Footer links, login button, menu config
2. **Security Hardening** — CSP, rate limiting, XSS audit
3. **Mobile Responsive** — Audit & fix semua halaman
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

### Milestone 1: Attendance Complete — TERCAPAI (2026-08-20)
- [x] Lengkapi semua view report absensi (weekly, monthly, latecomers, user-detail)
- [x] Sistem izin/sakit/alpha (CRUD + approve/reject + MarkAlphaCommand)
- [x] Export PDF absensi (daily, period, summary)
- [x] Notifikasi absensi (AttendanceNotifyCommand)
- [x] Config attendance terpusat (`config/attendance.php`)

### Milestone 2: MAUDU Theme Complete (Target: September 2026)
- [x] Theme-aware controllers (generic `LandingController`)
- [x] Semua view publik MAUDU (berita, pages, instagram, elulus)
- [ ] Fix component MAUDU (header/footer review)
- [ ] Breadcrumb MAUDU untuk semua halaman
- [ ] Dual-theme testing

### Milestone 3: Security & Quality (Target: Oktober 2026)
- [ ] Security hardening (CSP, rate limiting)
- [ ] N+1 query audit
- [ ] XSS audit
- [ ] Comprehensive test suite
- [x] Documentation update (FEATURES.md, ROADMAP.md, README.md, .env.example)

### Milestone 4: Enhancement (Target: November 2026)
- [ ] Admin UI modernization
- [ ] Dark mode
- [ ] Performance optimization
- [ ] CDN integration
- [ ] Theme inheritance

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
