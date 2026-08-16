# 🚀 Roadmap Pengembangan — SMK Telekomunikasi Darul Ulum

> Dokumen ini mencatat seluruh rencana pengembangan proyek, status implementasi, dan prioritas.
> Diperbarui: 2026-08-16

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

---

## 🔄 Fitur yang Sedang Dikerjakan

### Tahap 6: Lengkapi View Report Absensi
- [ ] View report mingguan (`weekly.blade.php`)
- [ ] View report bulanan (`monthly.blade.php`)
- [ ] View report keterlambatan (`latecomers.blade.php`)
- [ ] View report user detail (`user-detail.blade.php`)

### Tahap 7: Sistem Izin/Sakit/Alpha
- [ ] Migration `attendance_excuses` table
- [ ] Model `AttendanceExcuse`
- [ ] Controller `AttendanceExcuseController`
- [ ] View CRUD izin/sakit (index, create, edit, show)
- [ ] `MarkAlphaCommand` + scheduler
- [ ] Integrasi dengan AttendanceSync

### Tahap 8: Notifikasi Absensi
- [ ] Notification `AttendanceNotification`
- [ ] `AttendanceNotifyCommand` + scheduler
- [ ] Integrasi dengan AttendanceHelper

### Tahap 9: Export PDF Absensi
- [ ] PDF view (daily, period, summary)
- [ ] Update AttendanceExportService
- [ ] Update AttendanceExportController
- [ ] Update export index view

### Tahap 10: Config Attendance Terpusat
- [ ] `config/attendance.php`
- [ ] Update `.env.example`

---

## 📋 Fitur yang Belum Dikerjakan

### Kategori Prioritas Tinggi (🔴)
1. **Sistem Izin/Sakit/Alpha** — Tidak ada sama sekali
2. **Report Absensi Lengkap** — 4 view missing
3. **Export PDF Absensi** — Hanya Excel
4. **Notifikasi Absensi** — Belum diintegrasi
5. **Config Attendance Terpusat** — Belum ada

### Kategori Prioritas Sedang (🟡)
6. **MAUDU Public Pages** — Masih hardcoded ke Telkom
7. **MAUDU Header/Footer** — Broken links
8. **MAUDU Breadcrumb** — Tidak ada
9. **Theme-Aware Controllers** — MAUDU belum support
10. **Security Hardening** — Rate limiting belum lengkap

### Kategori Prioritas Rendah (🟢)
11. **Admin UI Modernization** — Chart.js, Alpine.js interactivity
12. **Dark Mode** — Toggle light/dark
13. **Theme Inheritance** — Base → child themes
14. **Performance Optimization** — CDN, eager loading audit
15. **Comprehensive Test Suite** — Target 80% coverage

---

## 🎯 Milestones

### Milestone 1: Attendance Complete (Target: Agustus 2026)
- [ ] Lengkapi semua view report absensi
- [ ] Sistem izin/sakit/alpha
- [ ] Export PDF absensi
- [ ] Notifikasi absensi
- [ ] Config attendance terpusat

### Milestone 2: MAUDU Theme Complete (Target: September 2026)
- [ ] Theme-aware controllers
- [ ] Semua view publik MAUDU
- [ ] Fix component MAUDU
- [ ] Breadcrumb MAUDU
- [ ] Dual-theme testing

### Milestone 3: Security & Quality (Target: Oktober 2026)
- [ ] Security hardening (CSP, rate limiting)
- [ ] N+1 query audit
- [ ] XSS audit
- [ ] Comprehensive test suite
- [ ] Documentation update

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
