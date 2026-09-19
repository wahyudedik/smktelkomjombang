# 📋 Daftar Lengkap Fitur — SMK Telekomunikasi Darul Ulum

> Dokumentasi lengkap semua fitur yang tersedia di sistem informasi SMK Telekomunikasi.
> Diperbarui: 2026-08-21

---

## 🏫 Ringkasan

**SMK Telekomunikasi Darul Ulum** adalah sistem informasi terpadu berbasis Laravel 12 yang mengelola seluruh operasional sekolah, mulai dari data akademik, absensi terintegrasi perangkat ZKTeco, sarana prasarana, OSIS voting, surat-menyurat, hingga landing page multi-tema.

**Legenda Status:**
- `✅ Selesai` — Fitur sudah selesai dan production-ready
- `🔄 Dalam Pengembangan` — Fitur sedang dalam pengembangan/polynomial
- `⬜ Belum Dikerjakan` — Fitur belum dikerjakan

---

## 🎯 Modul Utama

### 1. 🌐 Landing Page Multi-Tema
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Theme switching (Telkom & MAUDU) | `✅ Selesai` | Convention-based, 4-tier image resolution |
| Dynamic menu dari config | `✅ Selesai` | Menu navigasi dari `config/themes/{theme}.php` |
| Favicon/Logo otomatis | `✅ Selesai` | Per-theme DB → Global setting → Registry → Hardcoded |
| Theme settings admin | `✅ Selesai` | Upload logo/favicon via admin panel |
| Theme preview | `✅ Selesai` | Preview tema sebelum mengaktifkan |
| Theme clone | `✅ Selesai` | Duplikasi tema untuk varian sekolah |
| Theme import/export | `✅ Selesai` | Export/import settings sebagai JSON |
| Theme comparison | `✅ Selesai` | Side-by-side comparison dua tema |
| Theme analytics | `✅ Selesai` | Track engagement per tema |

### 2. 📊 Dashboard & Analytics
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Dashboard interaktif | `✅ Selesai` | Overview statistik dengan caching |
| Analytics mendalam | `✅ Selesai` | Data export, engagement tracking |
| System health check | `✅ Selesai` | Monitoring status server |
| Log monitoring | `✅ Selesai` | View/download/clear Laravel logs |
| Module usage calculation | `✅ Selesai` | Persentase penggunaan per modul |
| User growth data | `✅ Selesai` | Grafik pertumbuhan user |

### 3. 👥 Manajemen User & Role
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Multi-role system | `✅ Selesai` | superadmin, admin, guru, siswa, sarpras, osis |
| Spatie Permission | `✅ Selesai` | RBAC granular dengan permission |
| User CRUD + import/export | `✅ Selesai` | Excel import/export |
| Email verification | `✅ Selesai` | Admin + auto verification |
| Profile management | `✅ Selesai` | Edit profil, ganti password |
| User management (superadmin) | `✅ Selesai` | Invite system, bulk import |
| Role management | `✅ Selesai` | CRUD roles + assign users |
| Permission management | `✅ Selesai` | CRUD permissions + bulk create |
| Audit logging | `✅ Selesai` | Tracking semua aktivitas penting |

### 4. 📚 Modul Akademik

#### 4.1 Guru Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | NIP, nama, gelar, status kepegawaian |
| Filter & search | `✅ Selesai` | By status, employment, subject |
| Import/Export | `✅ Selesai` | Excel/PDF/JSON/XML |
| Mata pelajaran | `✅ Selesai` | JSON array per guru |
| PIN mapping absensi | `✅ Selesai` | Mapping NIP ke device PIN |

#### 4.2 Siswa Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | NIS/NISN, kelas, jurusan |
| Import/Export | `✅ Selesai` | Excel/PDF/JSON/XML |
| Data orang tua | `✅ Selesai` | Nama ayah/ibu, pekerjaan |
| Nilai akademik | `✅ Selesai` | JSON per siswa |
| Ekstrakurikuler | `✅ Selesai` | JSON per siswa |
| Voting tracking | `✅ Selesai` | has_voted_osis, voted_at, voting_ip |

#### 4.3 Kelas & Jurusan
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD Kelas | `✅ Selesai` | Data management via admin |
| CRUD Jurusan | `✅ Selesai` | Data management via admin |
| CRUD Mata Pelajaran | `✅ Selesai` | Data management via admin |
| CRUD Ekstrakurikuler | `✅ Selesai` | Data management via admin |

#### 4.4 Jadwal Pelajaran
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | Jadwal lengkap per kelas |
| Calendar view | `✅ Selesai` | Tampilan kalender interaktif |
| Conflict check | `✅ Selesai` | Deteksi bentrok jadwal |
| Import/Export | `✅ Selesai` | Excel/PDF/JSON/XML |

#### 4.5 E-Lulus (Kelulusan)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | Status: lulus/tidak_lulus/mengulang |
| Public check | `✅ Selesai` | `/check-graduation` tanpa login |
| Certificate generation | `✅ Selesai` | PDF sertifikat via DomPDF |
| Import/Export | `✅ Selesai` | Excel/PDF/JSON/XML |
| Tracking fields | `✅ Selesai` | IP, user_agent, timestamp |

### 5. ⏱️ Absensi ZKTeco iClock

#### 5.1 Core Attendance
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Push logs (cdata) | `✅ Selesai` | Device → Server via HTTP POST |
| Pull commands (getrequest) | `✅ Selesai` | Server → Device via HTTP GET |
| Command results (devicecmd) | `✅ Selesai` | Device → Server hasil eksekusi |
| Dedup logs | `✅ Selesai` | Unique constraint: device + pin + log_time |
| Daily recap | `✅ Selesai` | first_in_at, last_out_at per hari |
| AttendanceSync cron | `✅ Selesai` | Setiap 5 menit proses log → rekap |

#### 5.2 Device Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Device list | `✅ Selesai` | Serial number, IP, last seen |
| Device update/delete | `✅ Selesai` | Edit hapus perangkat |
| Device status | `✅ Selesai` | Online/offline detection |

#### 5.3 User Management (Attendance)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD user absensi | `✅ Selesai` | PIN mapping ke user/guru/siswa |
| Auto-sync ke device | `✅ Selesai` | Via ADMS command queue |
| Sync status | `✅ Selesai` | pending/sent/done/failed |
| Sync all users | `✅ Selesai` | Bulk sync ke semua device |

#### 5.4 Biometric Enrollment
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Fingerprint (0-9 jari) | `✅ Selesai` | Via TCP socket port 4370 |
| Face recognition | `✅ Selesai` | Via TCP socket |
| RFID card | `✅ Selesai` | Via TCP socket |
| Test connection | `✅ Selesai` | Test koneksi ke device |

#### 5.5 Export & Report
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Export harian (Excel) | `✅ Selesai` | Rekap per hari |
| Export periode (Excel) | `✅ Selesai` | Rekap per rentang tanggal |
| Export summary (Excel) | `✅ Selesai` | Ringkasan per user |
| Export user detail (Excel) | `✅ Selesai` | Detail per user |
| Report harian | `✅ Selesai` | View sudah ada |
| Report mingguan | `✅ Selesai` | `attendance/report/weekly.blade.php` |
| Report bulanan | `✅ Selesai` | `attendance/report/monthly.blade.php` |
| Report keterlambatan | `✅ Selesai` | `attendance/report/latecomers.blade.php` |
| Report user detail | `✅ Selesai` | `attendance/report/user-detail.blade.php` |
| Export PDF | `✅ Selesai` | 3 PDF views: daily, period, summary |

#### 5.6 Sistem Izin/Sakit/Alpha
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD izin/sakit | `✅ Selesai` | `AttendanceExcuseController` (277 baris) + 4 views |
| Approve/reject izin | `✅ Selesai` | Routes dengan throttle middleware |
| Mark alpha otomatis | `✅ Selesai` | `MarkAlphaCommand` (jam 23:00) |
| Notifikasi absensi | `✅ Selesai` | `AttendanceNotifyCommand` (summary + late + excuse) |
| Attendance config | `✅ Selesai` | `config/attendance.php` (164 baris) — centralized |

### 6. 🗳️ OSIS Voting
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Dashboard voting | `✅ Selesai` | Stats cached (2 min) |
| CRUD calon | `✅ Selesai` | nama_ketua, nama_wakil, foto, visi_misi |
| CRUD pemilih | `✅ Selesai` | Generate from users |
| Proses voting | `✅ Selesai` | Anti-fraud: IP, user_agent, one-vote |
| Hasil voting | `✅ Selesai` | Analytics + export PDF/JSON/XML |
| Teacher view | `✅ Selesai` | View khusus guru |
| Import/Export calon | `✅ Selesai` | Excel |
| Import/Export pemilih | `✅ Selesai` | Excel |

### 7. 📨 E-Surat
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Surat keluar CRUD | `✅ Selesai` | Auto-numbering, upload scan |
| Surat masuk CRUD | `✅ Selesai` | Full CRUD |
| Format surat | `✅ Selesai` | Template management |
| Blocking logic | `✅ Selesai` | Sequential upload requirement |
| Print PDF | `✅ Selesai` | Via DomPDF |
| Activity logging | `✅ Selesai` | Tracking semua aktivitas surat |

### 8. 📸 Instagram Integration
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| OAuth callback | `✅ Selesai` | Meta Business Login Flow |
| Webhook endpoints | `✅ Selesai` | GET verify + POST notifications |
| Feed public kegiatan | `✅ Selesai` | Cache 1 hour |
| Mock posts fallback | `✅ Selesai` | Jika API down |
| Token refresh | `✅ Selesai` | Short-lived → long-lived |
| Analytics | `✅ Selesai` | Engagement, top posts |
| Settings management | `✅ Selesai` | CRUD + test connection + sync |
| Admin analytics | `✅ Selesai` | Dashboard analytics |

### 9. 🏗️ Sarana Prasarana (Sarpras)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Kategori barang | `✅ Selesai` | CRUD + is_active toggle |
| Barang CRUD | `✅ Selesai` | Full CRUD + filter |
| Barcode/QR code | `✅ Selesai` | Generate, print, bulk print, scan |
| Ruang CRUD | `✅ Selesai` | Status aktif/nonaktif |
| Sarana (pengadaan) | `✅ Selesai` | CRUD + pivot sarana_barang |
| Maintenance | `✅ Selesai` | Status: pending/dalam_proses/selesai |
| Import/Export | `✅ Selesai` | Excel/PDF/JSON/XML |
| Reports | `✅ Selesai` | Sarana report + export PDF |
| Foto barang | `✅ Selesai` | Upload + storage |

### 10. 📝 CMS Pages
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | Title, slug, content, status |
| Publish/unpublish | `✅ Selesai` | Toggle status |
| Duplicate | `✅ Selesai` | Clone page |
| Versioning | `✅ Selesai` | Restore + compare versions |
| Menu management | `✅ Selesai` | Header/footer, sort order, parent-child |
| Templates | `✅ Selesai` | default, landing, about, blog, contact, gallery |
| Public view | `✅ Selesai` | Index + show pages |

### 11. 📰 Berita
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | `✅ Selesai` | Title, slug, content, image |
| Image upload | `✅ Selesai` | Upload gambar berita |
| Public view | `✅ Selesai` | Index + detail (theme-aware) |

### 12. 🔔 Notifications
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| In-app notifications | `✅ Selesai` | Database-based |
| Push notifications (VAPID) | `✅ Selesai` | WebPush via Minishlink |
| Notification center | `✅ Selesai` | View, mark read, delete |
| Notification preferences | `✅ Selesai` | User bisa set preferensi |
| Notification history | `✅ Selesai` | Log semua notifikasi |
| NotificationHelper | `✅ Selesai` | Helper untuk dispatch |
| SendNotificationJob | `✅ Selesai` | Background queue |

### 13. 🌍 Multi-Language (i18n)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| EN, ID, AR support | `✅ Selesai` | 3 bahasa |
| RTL support (Arab) | `✅ Selesai` | Right-to-left layout |
| Locale switching | `✅ Selesai` | Via URL `/locale/{locale}` |
| Currency switching | `✅ Selesai` | Via API |
| Timezone switching | `✅ Selesai` | Via API |

### 14. 🎯 Fitur Tambahan
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Testimoni | `✅ Selesai` | Public submit + admin approve/reject |
| Testimonial links | `✅ Selesai` | Token-based public links |
| Partners | `✅ Selesai` | Partners/sponsors management |
| Events | `✅ Selesai` | School events CRUD |
| PWA (Progressive Web App) | `✅ Selesai` | Installable + offline mode |
| SweetAlert2 | `✅ Selesai` | Modal dialogs |
| Chart.js | `⬜ Belum Dikerjakan` | Belum terintegrasi secara optimal di admin UI |
| Alpine.js | `⬜ Belum Dikerjakan` | Belum terintegrasi secara optimal di admin UI |
| Responsive design | `🔄 Dalam Pengembangan` | Landing page responsive, admin perlu audit & fix |
| CSRF protection | `✅ Selesai` | Laravel default |
| Rate limiting | `🔄 Dalam Pengembangan` | Sudah ada di import routes (10/minute), perlu diperluas ke semua routes sensitif |
| Barcode generation | `✅ Selesai` | 1D barcode + QR code (Milon) |

---

## 🔒 Keamanan

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CSRF Protection | `✅ Selesai` | Laravel default |
| XSS Protection | `✅ Selesai` | Eloquent ORM + Blade escaping |
| SQL Injection Protection | `✅ Selesai` | Eloquent ORM |
| Role-Based Access Control | `✅ Selesai` | Spatie Permission |
| Permission-Based Access | `✅ Selesai` | Granular per action |
| Audit Logging | `✅ Selesai` | Create/update/delete tracking |
| Rate Limiting (import routes) | `✅ Selesai` | Import routes (10/minute) |
| Email Verification | `✅ Selesai` | Admin + auto |
| Anti-Fraud Voting | `✅ Selesai` | IP tracking, user_agent, one-vote |
| Blocking Logic E-Surat | `✅ Selesai` | Sequential upload requirement |
| Auto-Delete Device User | `✅ Selesai` | Observer pattern |
| Token-Based Auth ZKTeco | `✅ Selesai` | ATTENDANCE_ICLOCK_SECRET |

---

## 🎨 Multi-Theme System

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Convention-based theming | `✅ Selesai` | `{base}-{theme}.blade.php` |
| 4-tier favicon/logo resolution | `✅ Selesai` | DB → Global → Registry → Hardcoded |
| Dynamic menu navigation | `✅ Selesai` | From config, not hardcoded |
| Theme registry | `✅ Selesai` | `config/themes.php` |
| Theme config files | `✅ Selesai` | `config/themes/{theme}.php` |
| Database theme settings | `✅ Selesai` | Admin-editable overrides |
| Route override | `✅ Selesai` | `/theme/{theme}` dynamic route |
| Theme-aware controllers | `✅ Selesai` | Generic via `current_theme()` + `theme_view()` |
| Theme-aware cache keys | `✅ Selesai` | `landing_{theme}_*` prefix (per-theme cache isolation) |
| Theme permission granularity | `✅ Selesai` | `themes.view` / `themes.edit` (Spatie) |
| Related links from config | `✅ Selesai` | Header dropdown + footer render dari `theme_config('related_links')` |
| Canvas menu logo theme-aware | `✅ Selesai` | Menggunakan `theme_image()` bukan hardcoded `asset()` |
| Deployment seeder | `✅ Selesai` | `ThemeSettingsSeeder` otomatis di `deploy.sh` & `update.sh` |
| Dynamic registered themes | `✅ Selesai` | `ThemeSetting::getRegisteredThemes()` reads from `config('themes.available')` |
| MAUDU theme views | `✅ Selesai` | 7 views: berita, pages, instagram, elulus (index + show + check + result) |
| Theme settings admin (full) | `✅ Selesai` | `ThemeSettingController` (566 baris) — preview, clone, import/export, comparison, analytics |

---

## 🔄 Dalam Pengembangan

### MAUDU Theme Polish
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Footer links validation | `🔄 Dalam Pengembangan` | Pastikan semua link aktif dan benar |
| Login button visibility | `🔄 Dalam Pengembangan` | Check visibility di semua halaman MAUDU |
| Menu config audit | `🔄 Dalam Pengembangan` | Semua menu routing benar |
| MAUDU component review | `🔄 Dalam Pengembangan` | Header, footer, sidebar perlu review |
| Breadcrumb MAUDU | `🔄 Dalam Pengembangan` | Breadcrumb untuk semua halaman |
| Dual-theme testing | `🔄 Dalam Pengembangan` | Testing Telkom + MAUDU side-by-side |

### Documentation Sync
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Sinkronisasi .env.example | `✅ Selesai` | Semua env vars sudah terdefinisi |
| Sinkronisasi FEATURES.md | `🔄 Dalam Pengembangan` | Update dengan kondisi aktual |
| Sinkronisasi ROADMAP.md | `🔄 Dalam Pengembangan` | Update dengan kondisi aktual |
| Update README.md | `🔄 Dalam Pengembangan` | Judul, deskripsi, tech stack |
| Review AGENTS.md | `🔄 Dalam Pengembangan` | Review & update konteks AI |
| Review plan docs | `🔄 Dalam Pengembangan` | Review semua plan di `plans/` |

---

## ⬜ Belum Dikerjakan

### 🔒 Security Hardening
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Rate limiting komprehensif | `⬜ Belum Dikerjakan` | Rate limiting untuk semua routes sensitif (bukan hanya import) |
| Content Security Policy (CSP) | `⬜ Belum Dikerjakan` | CSP headers untuk mencegah XSS |
| XSS audit semua form input | `⬜ Belum Dikerjakan` | Audit keamanan form inputs |
| N+1 query audit | `⬜ Belum Dikerjakan` | Optimasi query Eloquent |
| Dependency audit | `⬜ Belum Dikerjakan` | `composer audit` + `npm audit` |
| Session security hardening | `⬜ Belum Dikerjakan` | Secure cookies, session timeout |

### 📱 Mobile Responsive Improvements
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Audit responsive design admin | `⬜ Belum Dikerjakan` | Audit semua halaman admin |
| Fix table overflow mobile | `⬜ Belum Dikerjakan` | Tabel tidak overflow di mobile |
| Fix form layout small screen | `⬜ Belum Dikerjakan` | Form responsif di layar kecil |
| Touch-friendly buttons | `⬜ Belum Dikerjakan` | Tombol & link touch-friendly |
| Viewport testing | `⬜ Belum Dikerjakan` | Test di 320px, 768px, 1024px |

### ⚡ Performance Optimization
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CDN integration | `⬜ Belum Dikerjakan` | CDN untuk static assets |
| Eager loading audit | `⬜ Belum Dikerjakan` | Optimasi N+1 queries dengan eager loading |
| Theme inheritance | `⬜ Belum Dikerjakan` | Base → child themes |

### 🎨 Admin UI Modernization
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Chart.js integration | `⬜ Belum Dikerjakan` | Grafik interaktif di admin dashboard |
| Alpine.js interactivity | `⬜ Belum Dikerjakan` | Interactive components di admin |
| Dark Mode | `⬜ Belum Dikerjakan` | Toggle light/dark theme admin |

### 🧪 Testing
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Comprehensive test suite | `⬜ Belum Dikerjakan` | Target 80% coverage |
| Unit tests | `⬜ Belum Dikerjakan` | Test semua model & helper |
| Feature tests | `⬜ Belum Dikerjakan` | Test semua controller & flow |
| Browser tests | `⬜ Belum Dikerjakan` | Test end-to-end |

### ♿ Compliance & Accessibility
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| WCAG 2.1 AA | `⬜ Belum Dikerjakan` | Accessibility compliance |

---

## ⚠️ Known Issues

| # | Issue | Prioritas | Keterangan |
|---|-------|-----------|-----------|
| 1 | MAUDU footer links belum divalidasi | 🟡 Sedang | Beberapa link mungkin belum aktif |
| 2 | MAUDU login button visibility | 🟡 Sedang | Perlu check di semua halaman |
| 3 | Responsive admin belum optimal | 🟡 Sedang | Table overflow di mobile |
| 4 | Rate limiting belum komprehensif | 🟡 Sedang | Hanya di import routes |
| 5 | N+1 query belum diaudit | 🟢 Rendah | Potensi performance issue |

---

## 📋 Backlog

| # | Fitur | Prioritas | Target |
|---|-------|-----------|--------|
| 1 | MAUDU Theme Polish | 🟡 Sedang | September 2026 |
| 2 | Security Hardening | 🟡 Sedang | Oktober 2026 |
| 3 | Mobile Responsive Audit | 🟡 Sedang | Oktober 2026 |
| 4 | Performance Optimization | 🟡 Sedang | November 2026 |
| 5 | Admin UI Modernization | 🟢 Rendah | November 2026 |
| 6 | Dark Mode | 🟢 Rendah | November 2026 |
| 7 | Comprehensive Test Suite | 🟢 Rendah | Desember 2026 |
| 8 | WCAG 2.1 AA Compliance | 🟢 Rendah | Q1 2027 |
| 9 | Theme Inheritance | 🟢 Rendah | Q1 2027 |
| 10 | Internationalization Expansion | 🟢 Rendah | Q1 2027 |

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 12 |
| PHP | ≥ 8.2 |
| Database | MySQL 8.0 (`telkom_db`) |
| Frontend Admin | Tailwind CSS 3 + Alpine.js |
| Frontend Landing | Bootstrap 5 + jQuery |
| JS Libraries | Alpine.js, jQuery, Owl Carousel, WOW.js, SweetAlert2, Chart.js |
| Auth | Laravel Breeze |
| Permission | Spatie Laravel-Permission |
| Import/Export | Maatwebsite Excel |
| PDF | barryvdh/laravel-dompdf |
| Barcode | milon/barcode |
| Push Notification | minishlink/web-push |
| Asset Bundler | Vite 7 |
| Icons | Font Awesome |

---

## 📂 Struktur File Penting

| File | Deskripsi |
|------|-----------|
| `config/themes.php` | Theme registry — central definition |
| `config/themes/{theme}.php` | Theme settings per tema |
| `config/attendance.php` | Attendance centralized config |
| `app/Helpers/ThemeHelper.php` | Theme helper functions |
| `app/Helpers/NotificationHelper.php` | Notification dispatch helpers |
| `app/Http/Controllers/LandingController.php` | Landing page controller (generic) |
| `app/Http/Controllers/AttendanceController.php` | Attendance dashboard |
| `app/Services/ZKTeco/` | ZKTeco iClock integration (5 files) |
| `app/Services/InstagramService.php` | Instagram API integration |
| `routes/web.php` | Semua routes (822 baris) |
| `deploy.sh` | Deployment script |
| `update.sh` | Incremental update script |
