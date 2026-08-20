# 📋 Daftar Lengkap Fitur — SMK Telekomunikasi Darul Ulum

> Dokumentasi lengkap semua fitur yang tersedia di sistem informasi SMK Telekomunikasi.
> Diperbarui: 2026-08-20

---

## 🏫 Ringkasan

**SMK Telekomunikasi Darul Ulum** adalah sistem informasi terpadu berbasis Laravel 12 yang mengelola seluruh operasional sekolah, mulai dari data akademik, absensi terintegrasi perangkat ZKTeco, sarana prasarana, OSIS voting, surat-menyurat, hingga landing page multi-tema.

---

## 🎯 Modul Utama

### 1. 🌐 Landing Page Multi-Tema
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Theme switching (Telkom & MAUDU) | ✅ | Convention-based, 4-tier image resolution |
| Dynamic menu dari config | ✅ | Menu navigasi dari `config/themes/{theme}.php` |
| Favicon/Logo otomatis | ✅ | Per-theme DB → Global setting → Registry → Hardcoded |
| Theme settings admin | ✅ | Upload logo/favicon via admin panel |
| Theme preview | ✅ | Preview tema sebelum mengaktifkan |
| Theme clone | ✅ | Duplikasi tema untuk varian sekolah |
| Theme import/export | ✅ | Export/import settings sebagai JSON |
| Theme comparison | ✅ | Side-by-side comparison dua tema |
| Theme analytics | ✅ | Track engagement per tema |

### 2. 📊 Dashboard & Analytics
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Dashboard interaktif | ✅ | Overview statistik dengan caching |
| Analytics mendalam | ✅ | Data export, engagement tracking |
| System health check | ✅ | Monitoring status server |
| Log monitoring | ✅ | View/download/clear Laravel logs |
| Module usage calculation | ✅ | Persentase penggunaan per modul |
| User growth data | ✅ | Grafik pertumbuhan user |

### 3. 👥 Manajemen User & Role
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Multi-role system | ✅ | superadmin, admin, guru, siswa, sarpras, osis |
| Spatie Permission | ✅ | RBAC granular dengan permission |
| User CRUD + import/export | ✅ | Excel import/export |
| Email verification | ✅ | Admin + auto verification |
| Profile management | ✅ | Edit profil, ganti password |
| User management (superadmin) | ✅ | Invite system, bulk import |
| Role management | ✅ | CRUD roles + assign users |
| Permission management | ✅ | CRUD permissions + bulk create |
| Audit logging | ✅ | Tracking semua aktivitas penting |

### 4. 📚 Modul Akademik

#### 4.1 Guru Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | NIP, nama, gelar, status kepegawaian |
| Filter & search | ✅ | By status, employment, subject |
| Import/Export | ✅ | Excel/PDF/JSON/XML |
| Mata pelajaran | ✅ | JSON array per guru |
| PIN mapping absensi | ✅ | Mapping NIP ke device PIN |

#### 4.2 Siswa Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | NIS/NISN, kelas, jurusan |
| Import/Export | ✅ | Excel/PDF/JSON/XML |
| Data orang tua | ✅ | Nama ayah/ibu, pekerjaan |
| Nilai akademik | ✅ | JSON per siswa |
| Ekstrakurikuler | ✅ | JSON per siswa |
| Voting tracking | ✅ | has_voted_osis, voted_at, voting_ip |

#### 4.3 Kelas & Jurusan
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD Kelas | ✅ | Data management via admin |
| CRUD Jurusan | ✅ | Data management via admin |
| CRUD Mata Pelajaran | ✅ | Data management via admin |
| CRUD Ekstrakurikuler | ✅ | Data management via admin |

#### 4.4 Jadwal Pelajaran
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | Jadwal lengkap per kelas |
| Calendar view | ✅ | Tampilan kalender interaktif |
| Conflict check | ✅ | Deteksi bentrok jadwal |
| Import/Export | ✅ | Excel/PDF/JSON/XML |

#### 4.5 E-Lulus (Kelulusan)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | Status: lulus/tidak_lulus/mengulang |
| Public check | ✅ | `/check-graduation` tanpa login |
| Certificate generation | ✅ | PDF sertifikat via DomPDF |
| Import/Export | ✅ | Excel/PDF/JSON/XML |
| Tracking fields | ✅ | IP, user_agent, timestamp |

### 5. ⏱️ Absensi ZKTeco iClock

#### 5.1 Core Attendance
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Push logs (cdata) | ✅ | Device → Server via HTTP POST |
| Pull commands (getrequest) | ✅ | Server → Device via HTTP GET |
| Command results (devicecmd) | ✅ | Device → Server hasil eksekusi |
| Dedup logs | ✅ | Unique constraint: device + pin + log_time |
| Daily recap | ✅ | first_in_at, last_out_at per hari |
| AttendanceSync cron | ✅ | Setiap 5 menit proses log → rekap |

#### 5.2 Device Management
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Device list | ✅ | Serial number, IP, last seen |
| Device update/delete | ✅ | Edit hapus perangkat |
| Device status | ✅ | Online/offline detection |

#### 5.3 User Management (Attendance)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD user absensi | ✅ | PIN mapping ke user/guru/siswa |
| Auto-sync ke device | ✅ | Via ADMS command queue |
| Sync status | ✅ | pending/sent/done/failed |
| Sync all users | ✅ | Bulk sync ke semua device |

#### 5.4 Biometric Enrollment
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Fingerprint (0-9 jari) | ✅ | Via TCP socket port 4370 |
| Face recognition | ✅ | Via TCP socket |
| RFID card | ✅ | Via TCP socket |
| Test connection | ✅ | Test koneksi ke device |

#### 5.5 Export & Report
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Export harian (Excel) | ✅ | Rekap per hari |
| Export periode (Excel) | ✅ | Rekap per rentang tanggal |
| Export summary (Excel) | ✅ | Ringkasan per user |
| Export user detail (Excel) | ✅ | Detail per user |
| Report harian | ✅ | View sudah ada |
| Report mingguan | ✅ | `attendance/report/weekly.blade.php` |
| Report bulanan | ✅ | `attendance/report/monthly.blade.php` |
| Report keterlambatan | ✅ | `attendance/report/latecomers.blade.php` |
| Report user detail | ✅ | `attendance/report/user-detail.blade.php` |
| Export PDF | ✅ | 3 PDF views: daily, period, summary |

#### 5.6 Sistem Izin/Sakit/Alpha
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CRUD izin/sakit | ✅ | `AttendanceExcuseController` (277 baris) + 4 views |
| Approve/reject izin | ✅ | Routes dengan throttle middleware |
| Mark alpha otomatis | ✅ | `MarkAlphaCommand` (jam 23:00) |
| Notifikasi absensi | ✅ | `AttendanceNotifyCommand` (summary + late + excuse) |
| Attendance config | ✅ | `config/attendance.php` (164 baris) — centralized |

### 6. 🗳️ OSIS Voting
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Dashboard voting | ✅ | Stats cached (2 min) |
| CRUD calon | ✅ | nama_ketua, nama_wakil, foto, visi_misi |
| CRUD pemilih | ✅ | Generate from users |
| Proses voting | ✅ | Anti-fraud: IP, user_agent, one-vote |
| Hasil voting | ✅ | Analytics + export PDF/JSON/XML |
| Teacher view | ✅ | View khusus guru |
| Import/Export calon | ✅ | Excel |
| Import/Export pemilih | ✅ | Excel |

### 7. 📨 E-Surat
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Surat keluar CRUD | ✅ | Auto-numbering, upload scan |
| Surat masuk CRUD | ✅ | Full CRUD |
| Format surat | ✅ | Template management |
| Blocking logic | ✅ | Sequential upload requirement |
| Print PDF | ✅ | Via DomPDF |
| Activity logging | ✂ | Tracking semua aktivitas surat |

### 8. 📸 Instagram Integration
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| OAuth callback | ✅ | Meta Business Login Flow |
| Webhook endpoints | ✅ | GET verify + POST notifications |
| Feed public kegiatan | ✅ | Cache 1 hour |
| Mock posts fallback | ✅ | Jika API down |
| Token refresh | ✅ | Short-lived → long-lived |
| Analytics | ✅ | Engagement, top posts |
| Settings management | ✅ | CRUD + test connection + sync |
| Admin analytics | ✌ | Dashboard analytics |

### 9. 🏗️ Sarana Prasarana (Sarpras)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Kategori barang | ✅ | CRUD + is_active toggle |
| Barang CRUD | ✅ | Full CRUD + filter |
| Barcode/QR code | ✅ | Generate, print, bulk print, scan |
| Ruang CRUD | ✅ | Status aktif/nonaktif |
| Sarana (pengadaan) | ✅ | CRUD + pivot sarana_barang |
| Maintenance | ✅ | Status: pending/dalam_proses/selesai |
| Import/Export | ✅ | Excel/PDF/JSON/XML |
| Reports | ✅ | Sarana report + export PDF |
| Foto barang | ✅ | Upload + storage |

### 10. 📝 CMS Pages
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | Title, slug, content, status |
| Publish/unpublish | ✅ | Toggle status |
| Duplicate | ✃ | Clone page |
| Versioning | ✅ | Restore + compare versions |
| Menu management | ✅ | Header/footer, sort order, parent-child |
| Templates | ✅ | default, landing, about, blog, contact, gallery |
| Public view | ✅ | Index + show pages |

### 11. 📰 Berita
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Full CRUD | ✅ | Title, slug, content, image |
| Image upload | ✅ | Upload gambar berita |
| Public view | ✅ | Index + detail (theme-aware) |

### 12. 🔔 Notifications
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| In-app notifications | ✅ | Database-based |
| Push notifications (VAPID) | ✅ | WebPush via Minishlink |
| Notification center | ✅ | View, mark read, delete |
| Notification preferences | ✅ | User bisa set preferensi |
| Notification history | ✅ | Log semua notifikasi |
| NotificationHelper | ✅ | Helper untuk dispatch |
| SendNotificationJob | ✅ | Background queue |

### 13. 🌍 Multi-Language (i18n)
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| EN, ID, AR support | ✅ | 3 bahasa |
| RTL support (Arab) | ✅ | Right-to-left layout |
| Locale switching | ✅ | Via URL `/locale/{locale}` |
| Currency switching | ✅ | Via API |
| Timezone switching | ✅ | Via API |

### 14. 🎯 Fitur Tambahan
| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Testimoni | ✅ | Public submit + admin approve/reject |
| Testimonial links | ✅ | Token-based public links |
| Partners | ✅ | Partners/sponsors management |
| Events | ✅ | School events CRUD |
| PWA (Progressive Web App) | ✅ | Installable + offline mode |
| SweetAlert2 | ✅ | Modal dialogs |
| Chart.js | ✅ | Grafik interaktif |
| Alpine.js | ✅ | Interactive JS |
| Responsive design | ✅ | Mobile-first admin + landing |
| CSRF protection | ✅ | Laravel default |
| Rate limiting | ✅ | Import routes (10/minute) |
| Barcode generation | ✅ | 1D barcode + QR code (Milon) |

---

## 🔒 Keamanan

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| CSRF Protection | ✅ | Laravel default |
| XSS Protection | ✅ | Eloquent ORM + Blade escaping |
| SQL Injection Protection | ✅ | Eloquent ORM |
| Role-Based Access Control | ✅ | Spatie Permission |
| Permission-Based Access | ✅ | Granular per action |
| Audit Logging | ✅ | Create/update/delete tracking |
| Rate Limiting | ✅ | Import routes (10/minute) |
| Email Verification | ✅ | Admin + auto |
| Anti-Fraud Voting | ✅ | IP tracking, user_agent, one-vote |
| Blocking Logic E-Surat | ✅ | Sequential upload requirement |
| Auto-Delete Device User | ✅ | Observer pattern |
| Token-Based Auth ZKTeco | ✅ | ATTENDANCE_ICLOCK_SECRET |

---

## 🎨 Multi-Theme System

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Convention-based theming | ✅ | `{base}-{theme}.blade.php` |
| 4-tier favicon/logo resolution | ✅ | DB → Global → Registry → Hardcoded |
| Dynamic menu navigation | ✅ | From config, not hardcoded |
| Theme registry | ✅ | `config/themes.php` |
| Theme config files | ✅ | `config/themes/{theme}.php` |
| Database theme settings | ✅ | Admin-editable overrides |
| Route override | ✅ | `/theme/{theme}` dynamic route |
| Theme-aware controllers | ✅ | Generic via `current_theme()` + `theme_view()` |
| Theme-aware cache keys | ✅ | `landing_{theme}_*` prefix (per-theme cache isolation) |
| Theme permission granularity | ✅ | `themes.view` / `themes.edit` (Spatie) |
| Related links from config | ✅ | Header dropdown + footer render dari `theme_config('related_links')` |
| Canvas menu logo theme-aware | ✅ | Menggunakan `theme_image()` bukan hardcoded `asset()` |
| Deployment seeder | ✅ | `ThemeSettingsSeeder` otomatis di `deploy.sh` & `update.sh` |
| Dynamic registered themes | ✅ | `ThemeSetting::getRegisteredThemes()` reads from `config('themes.available')` |
| MAUDU theme views | ✅ | 7 views: berita, pages, instagram, elulus (index + show + check + result) |
| Theme settings admin (full) | ✅ | `ThemeSettingController` (566 baris) — preview, clone, import/export, comparison, analytics |

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
