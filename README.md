# IG to Web - Sistem Manajemen Sekolah Terintegrasi

Sistem manajemen sekolah berbasis web yang terintegrasi dengan Instagram untuk menampilkan kegiatan sekolah secara real-time.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📑 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Absensi Fingerprint (ZKTeco)](#-absensi-fingerprint-zkteco)
- [E-Surat](#-e-surat)
- [Fitur Masa Depan](#-fitur-masa-depan-roadmap)
- [Setup Development](#-setup-development-local)
- [Setup Production VPS](#-setup-production-vps)

---

## 🚀 Fitur Utama

### 📊 Dashboard & Analytics
- **Dashboard Interaktif**: Overview statistik sekolah dengan grafik real-time
- **Analytics Mendalam**: Tracking aktivitas user, penggunaan modul, dan trend data
- **Role-based Dashboard**: Dashboard khusus untuk setiap role (Superadmin, Admin, Guru, Siswa, Sarpras)

### 👥 Manajemen User & Role
- **Multi-Role System**: Superadmin, Admin, Guru, Siswa, Sarpras dengan permission granular
- **User Management**: CRUD lengkap dengan email verification dan invitation system
- **Role & Permission**: Sistem permission yang fleksibel dan aman
- **Audit Logging**: Tracking semua aktivitas penting dalam sistem

### 🏫 Manajemen Akademik
- **Guru Management**: Data lengkap guru dengan NIP, sertifikasi, dan prestasi
- **Siswa Management**: Data siswa dengan NIS/NISN, kelas, jurusan, dan prestasi
- **Kelulusan (E-Lulus)**: Sistem kelulusan dengan sertifikat digital
- **Mata Pelajaran**: Manajemen mata pelajaran dan kurikulum
- **Jadwal Pelajaran**: Sistem penjadwalan mata pelajaran dengan manajemen kelas dan pengajar
- **Import/Export**: Excel import/export untuk data bulk

### ⏱️ Absensi Fingerprint (ZKTeco)
- **Sumber Data**: Log absensi diambil dari perangkat ZKTeco via endpoint iClock `GET|POST /iclock/cdata?SN=...` (LAN/VPN).
- **Rekap Harian**: Sistem membentuk rekap `first_in` dan `last_out` per hari via command `attendance:sync` (terjadwal tiap 5 menit).
- **Mapping PIN**: PIN perangkat dipetakan ke data user/guru/siswa agar log bisa dihubungkan ke identitas.
- **Menu Admin**: Academic → Absensi (`/admin/absensi`) untuk melihat rekap, logs, devices, dan mapping.
- **Permission**: `attendance.view`, `attendance.sync`, `attendance.devices.*`, `attendance.mapping.manage`, `attendance.export`.
- **Dokumentasi Setup**: Lihat [absensi-zkteco-setup.md](file:///d:/PROJECT/LARAVEL/ig-to-web/docs/absensi-zkteco-setup.md)

**Rekomendasi perangkat (populer & stabil di Indonesia):**
- Entry–Menengah: **ZKTeco MB20** (fingerprint + face + RFID, LAN) | **ZKTeco MB160** (face lebih cepat, finger + card).
- Menengah–Profesional: **ZKTeco MB360**, **ZKTeco iFace 302 / iFace 402** (akurasi tinggi, cocok skala besar).

**Catatan implementasi:**
- Pastikan perangkat dan server berada di jaringan yang sama (atau lewat VPN) dan perangkat bisa mengakses URL server.
- Jika menggunakan HTTPS, pastikan sertifikat valid agar device dapat mengirim data secara stabil.

### 🗳️ Sistem OSIS
- **Pemilihan OSIS**: Sistem voting online yang aman dan transparan
- **Kandidat Management**: Data kandidat dengan visi-misi dan program kerja
- **Voting System**: Real-time voting dengan tracking IP dan user agent
- **Hasil Voting**: Dashboard hasil dengan grafik dan statistik

### ✉️ E-Surat
- **Surat Masuk**: Pencatatan surat masuk (nomor manual), upload scan, status `received`, dan log aktivitas.
- **Surat Keluar**: Pembuatan draft surat keluar dengan nomor otomatis, cetak PDF, upload scan untuk menyelesaikan status `sent`.
- **Format Surat**: Builder format berbasis segmen (sequence, text, unit code, tanggal/bulan/romawi/tahun) dan preview template.
- **Counter Nomor Surat**: Mendukung reset per tahun/per bulan, serta scope global atau per unit (berdasarkan `unit_code` user).
- **Audit Log**: Setiap perubahan surat dicatat pada activity log.

### 🏢 Sarpras Management
- **Inventory Management**: Manajemen barang dengan barcode dan QR code
- **Kategori & Ruang**: Organisasi sarana prasarana yang terstruktur
- **Maintenance Tracking**: Sistem perawatan dan maintenance
- **Barcode System**: Generate dan scan barcode untuk tracking barang
- **Sarana Management**: Sistem inventaris sarana dengan kode inventaris otomatis
  - **Kode Inventaris Otomatis**: Format `INV/NO.KodeBarang.KodeRuang.JumlahBarang.KodeSumberDana`
  - **Multi-Barang per Ruang**: Satu ruang dapat memiliki multiple barang
  - **Harga & Total**: Tracking harga satuan dan total per barang
  - **Invoice Printing**: Cetak invoice inventaris dalam format PDF
  - **Filter & Search**: Filter berdasarkan kategori dan sumber dana
  - **Dynamic Item Assignment**: Update ruang_id barang secara otomatis
- **Laporan**: Export data sarpras dalam berbagai format

### 📱 Instagram Integration
- **OAuth Integration**: Login dengan Instagram Business/Creator Account
- **Auto-Sync Posts**: Sinkronisasi otomatis posts Instagram ke website
- **Manual Sync**: Sync manual via button atau command
- **Customizable Sync**: Atur frequency sync (5-60 menit)
- **Gallery Management**: Manajemen galeri kegiatan sekolah
- **Real-time Display**: Posts tampil di homepage dan halaman kegiatan
- **Analytics**: Tracking engagement (likes, comments) dan reach

### 📄 Content Management
- **Page Management**: CMS untuk halaman website sekolah
- **Menu Management**: Sistem menu dinamis dengan hierarki
- **SEO Optimization**: Meta tags dan struktur SEO yang optimal
- **Version Control**: Tracking perubahan konten dengan rollback

### 🎨 Landing Page Customization
- **Hero Section**: Slider dengan konten yang dapat dikustomisasi
- **Feature Cards**: Kartu fitur unggulan sekolah
- **Campus Life**: Profil kepala sekolah dan visi-misi
- **Program Peminatan**: 3 program unggulan yang dapat dikustomisasi
- **Gallery**: Integrasi dengan Instagram posts
- **Testimonials**: Sistem testimonial dengan link publik
- **About Section**: Informasi sekolah yang lengkap

### 🔔 Notification System
- **Real-time Notifications**: Notifikasi sistem yang komprehensif
- **Email Notifications**: Integrasi dengan email untuk notifikasi penting
- **Role-based Alerts**: Notifikasi sesuai dengan role user
- **Maintenance Alerts**: Peringatan maintenance sistem

### 📊 Reporting & Export
- **Excel Export**: Export data dalam format Excel
- **PDF Reports**: Generate laporan dalam PDF
- **CSV Export**: Export data untuk analisis
- **Custom Reports**: Laporan yang dapat dikustomisasi

### 🔒 Security & Authorization
- **CSRF Protection**: Perlindungan dari serangan CSRF
- **XSS Protection**: Filter input untuk mencegah XSS
- **SQL Injection Protection**: Menggunakan Eloquent ORM
- **Role-Based Access Control**: Permission granular dengan policies
- **Audit Logging**: Tracking semua aktivitas penting
- **Rate Limiting**: Pembatasan request untuk mencegah abuse

### 🤖 MCP Server (Model Context Protocol)
- **AI Integration**: Claude AI dapat berinteraksi langsung dengan codebase
- **Quick Commands**: Jalankan artisan commands, lihat routes, inspect models
- **Natural Language**: Tanya ke Claude dengan bahasa natural
- **Documentation**: [README_MCP.md](README_MCP.md) | [QUICKSTART](mcp-server/QUICKSTART.md)

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
- **Instagram Graph API**: Integrasi dengan Instagram Business
- **Laravel Sanctum**: API authentication
- **Vite**: Modern asset bundler

---

## 🎯 Fitur Masa Depan (Roadmap)

### ⏰ Penjadwalan Pembelajaran (Jadwal Pelajaran) - DONE
- [X] **Otomatisasi Pembuatan Jadwal Pelajaran**: Fitur untuk generate jadwal/mapel kelas secara otomatis dan berkala
- [X] **Tampilan Kustomisasi Jadwal**: UI pengaturan jadwal pelajaran di dashboard admin (drag&drop)
- [X] **Manual & Instan Generate**: Tombol "Generate Sekarang" untuk jadwal
- [X] **Monitoring Status**: Log dan notifikasi proses pembuatan jadwal/mapel
- [X] **Error Handling**: Penanganan gagal generate & otomatis retry

### 📱 Mobile & Cross-Platform
- [ ] **Mobile App (React Native)**: Aplikasi mobile untuk iOS dan Android
- [x] **Progressive Web App**: Instalasi sebagai aplikasi native ✅ **IMPLEMENTED**
- [x] **Offline Mode**: Akses data saat tidak ada koneksi internet ✅ **IMPLEMENTED**
  - ✅ Service Worker untuk caching static assets
  - ✅ Cache strategy: Cache First untuk assets, Network First untuk pages
  - ✅ Offline page fallback
  - ✅ Online/Offline event detection dengan notifikasi
  - ✅ Auto-update service worker
  - ✅ PWA manifest.json dengan icon & theme
- [x] **Push Notifications**: Notifikasi real-time di mobile devices ✅ **IMPLEMENTED**
  - ✅ Service Worker untuk menerima push notifications
  - ✅ Push subscription management (subscribe/unsubscribe)
  - ✅ VAPID keys configuration
  - ✅ Real-time push notifications via Web Push API
  - ✅ Notification click handling
  - ✅ Automatic push notification pada semua notifikasi sistem
  - ✅ Multi-device support

### 📊 Analytics & Reporting
- [x] **Export to Multiple Formats**: PDF, Excel, JSON, XML ✅ **IMPLEMENTED**
  - ✅ Guru: Excel, PDF, JSON, XML export dengan filter support
  - ✅ Siswa: Excel, PDF, JSON, XML export dengan filter support
  - ✅ Jadwal Pelajaran: Excel, PDF, JSON, XML export dengan grouping by day
  - ✅ Barang Sarpras: Excel, PDF, JSON, XML export dengan kategori/status filter
  - ✅ OSIS Voting Results: PDF, JSON, XML export dengan statistik lengkap
  - ✅ Kelulusan: Excel, PDF, JSON, XML export dengan filter tahun/jurusan
- [x] **Advanced Analytics Dashboard**: Dashboard analytics yang lebih mendalam ✅ **IMPLEMENTED**
  - ✅ Date range filtering untuk analisis periodik
  - ✅ Real-time data dengan API endpoints
  - ✅ Chart.js visualizations (line, bar, doughnut charts)
  - ✅ Audit analytics (actions by type, most active users, peak hours)
  - ✅ Performance metrics (module efficiency, database performance, system health)
  - ✅ Engagement metrics (voting engagement, module adoption, user retention)
  - ✅ Feature usage tracking
  - ✅ Export functionality (JSON, CSV)
  - ✅ User growth tracking & retention rates
  - ✅ Comprehensive trend analysis (30/90 days)
- [ ] **Custom Report Designer**: Pembuat laporan dengan drag-and-drop
- [ ] **Data Visualization**: Grafik dan chart interaktif yang lebih kaya

### 🤖 AI & Automation
- [ ] **AI-powered Content Suggestions**: Rekomendasi konten otomatis
- [ ] **Chatbot Support**: Asisten virtual untuk help desk
- [ ] **Automatic Translation**: Terjemahan otomatis multi-bahasa
- [ ] **Smart Scheduling**: Penjadwalan cerdas berdasarkan AI
- [ ] **Predictive Analytics**: Prediksi trend dan pattern

### 🌐 Integration & API
- [ ] **WhatsApp Integration**: Notifikasi via WhatsApp Business API
- [ ] **Google Classroom Integration**: Sinkronisasi dengan Google Classroom
- [ ] **Zoom/Google Meet Integration**: Video conference terintegrasi
- [ ] **Payment Gateway**: Integrasi dengan Midtrans, Xendit, dll
- [ ] **SSO (Single Sign-On)**: Login dengan Google, Microsoft, dll
- [ ] **REST API Documentation**: API documentation dengan Swagger/OpenAPI
- [ ] **GraphQL API**: Alternative API dengan GraphQL

### 📚 E-Learning Features
- [ ] **Online Class Module**: Kelas online dengan video streaming
- [ ] **Assignment & Quiz**: Tugas dan quiz online
- [ ] **Grade Management**: Sistem penilaian terintegrasi
- [ ] **Discussion Forum**: Forum diskusi untuk siswa dan guru
- [ ] **Live Streaming**: Live streaming untuk acara sekolah
- [ ] **Video Library**: Perpustakaan video pembelajaran
- [ ] **Interactive Whiteboard**: Papan tulis digital interaktif

### 👨‍👩‍👧‍👦 Parent & Guardian Features
- [ ] **Parent Portal**: Portal khusus untuk orang tua
- [ ] **Student Progress Tracking**: Tracking perkembangan siswa
- [ ] **Parent-Teacher Communication**: Komunikasi orang tua dan guru
- [ ] **Event Notification**: Notifikasi event sekolah ke orang tua

### 🔐 Security & Performance
- [ ] **Two-Factor Authentication (2FA)**: Keamanan login 2 faktor
- [ ] **Biometric Login**: Login dengan fingerprint/face recognition
- [ ] **Attendance with Face Recognition**: Absensi dengan face recognition
- [ ] **Advanced Security Audit**: Audit keamanan yang lebih mendalam
- [ ] **Performance Optimization**: Optimasi performa dan caching
- [ ] **CDN Integration**: Content Delivery Network untuk assets
- [ ] **Load Balancing**: Load balancing untuk high traffic
- [ ] **API Rate Limiting**: Pembatasan rate untuk API

### 🌍 Internationalization
- [x] **Multi-language Support**: Dukungan multi-bahasa (EN, ID, AR, dll) ✅ **IMPLEMENTED**
  - ✅ Laravel localization dengan language files (EN, ID, AR)
  - ✅ Language switcher di profile dropdown menu
  - ✅ Auto-detect browser language
  - ✅ Session & user preference storage
  - ✅ Middleware untuk set locale otomatis
- [x] **RTL Language Support**: Dukungan bahasa RTL (Arab, Hebrew) ✅ **IMPLEMENTED**
  - ✅ RTL detection berdasarkan locale
  - ✅ HTML dir attribute untuk RTL
  - ✅ CSS utilities untuk RTL layout
  - ✅ RTL-aware component positioning
- [x] **Currency Support**: Dukungan multi-currency untuk pembayaran ✅ **IMPLEMENTED**
  - ✅ Multi-currency configuration (IDR, USD, EUR, SAR, AED)
  - ✅ Currency formatting helper function
  - ✅ Currency switcher support
  - ✅ User currency preference
- [x] **Timezone Support**: Dukungan multiple timezone ✅ **IMPLEMENTED**
  - ✅ Timezone configuration dengan grouping
  - ✅ User timezone preference
  - ✅ Timezone conversion helper functions
  - ✅ Date formatting per locale

### 📱 Social Media Enhancement
- [ ] **Facebook Integration**: Posting otomatis ke Facebook
- [ ] **Twitter Integration**: Posting otomatis ke Twitter
- [ ] **TikTok Integration**: Integrasi dengan TikTok
- [ ] **YouTube Integration**: Upload video ke YouTube
- [ ] **Social Media Analytics**: Analytics untuk semua platform

### 🎨 UI/UX Improvements
- [ ] **Dark Mode**: Mode gelap untuk UI
- [ ] **Theme Customization**: Kustomisasi tema dan warna
- [ ] **Accessibility Features**: Fitur aksesibilitas untuk disabilitas
- [ ] **Voice Control**: Kontrol dengan suara
- [ ] **Gesture Navigation**: Navigasi dengan gesture

### 📦 Additional Modules
- [ ] **Library Management**: Manajemen perpustakaan sekolah
- [ ] **Canteen Management**: Manajemen kantin dan pembayaran
- [ ] **Transport Management**: Manajemen transportasi sekolah
- [ ] **Health Record**: Rekam kesehatan siswa
- [ ] **Dormitory Management**: Manajemen asrama (jika ada)
- [ ] **Alumni Portal**: Portal untuk alumni sekolah

### 📝 Advanced Grade Input & Analysis (Per Semester & Integrated Attendance)
- [ ] **Input Nilai Per Semester**: Nilai diinput dan dikelola berdasarkan semester, sehingga memudahkan rekap dan pelaporan periodik.
- [ ] **Absensi Terintegrasi & Centang Siswa Per Kelas**: Absensi dibuat model centang per siswa di tiap kelas, terhubung dengan data siswa dan bisa dicatat per semester. Absensi ini juga langsung terintegrasi ke laporan nilai, jadi kehadiran siswa tiap semester otomatis muncul di raport & rekap akademik.
- [ ] **Input Nilai Harian & Ulangan**: Guru bisa memasukkan nilai harian, ulangan harian, UTS, UAS, serta berbagai jenis penilaian lain, semuanya terorganisir per semester.
- [ ] **Kustomisasi Jenis Penilaian**: Sekolah dapat menambah kategori nilai (praktik, projek, sikap, dll) sesuai kebutuhan masing-masing semester.
- [ ] **Tampilan Tabel Seperti Excel**: Input nilai banyak siswa sekaligus dengan tampilan tabel (bisa copy-paste dari spreadsheet).
- [ ] **Perhitungan Otomatis**: Rata-rata, median, dan statistik nilai dihitung otomatis untuk tiap jenis penilaian per semester.
- [ ] **Bobot Nilai Fleksibel**: Atur bobot/proporsi masing-masing komponen nilai sesuai kebijakan setiap mata pelajaran atau sekolah di setiap semester.
- [ ] **Analisis, Statistik & Rekap Kelas**: Lihat distribusi nilai, ranking siswa, grafik perbandingan antar kelas/waktu, sekaligus rekap absensi dalam satu laporan.
- [ ] **Export Data & Laporan Lengkap**: Semua data nilai dan absensi bisa diexport ke Excel/CSV, siap cetak untuk guru maupun wali kelas.
- [ ] **Feedback & Catatan Otomatis**: Sistem dapat memberikan feedback otomatis (misal: "Perlu peningkatan kehadiran", "Nilai ulangan naik drastis", dsb) berdasarkan hasil nilai & kehadiran.
- [ ] **Integrasi Raport Digital**: Seluruh data nilai dan absensi langsung terintegrasi dengan pembuatan raport digital tiap semester, jadi orang tua dapat melihat laporan yang komprehensif.



---

## 💻 Setup Development (Local)

### 📋 Persyaratan Sistem

**Software yang Diperlukan:**
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18.x atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Git

**PHP Extensions:**
- BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD, Zip

### 🚀 Langkah Instalasi

#### 1. Clone Repository
```bash
# Clone repository
git clone https://github.com/your-username/ig-to-web.git
cd ig-to-web
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

#### 3. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ig_to_web
DB_USERNAME=root
DB_PASSWORD=your_password
```

Buat database:
```bash
mysql -u root -p
CREATE DATABASE ig_to_web;
exit
```

#### 5. Run Migrations & Seeders
```bash
# Run migrations and seeders
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link
```

#### 6. Konfigurasi Email (Development)
Edit file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sekolah.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan:** Gunakan [Mailtrap](https://mailtrap.io) untuk testing email di development.

#### 7. Konfigurasi Instagram (Opsional)
Edit file `.env`:
```env
INSTAGRAM_APP_ID=your_instagram_app_id
INSTAGRAM_APP_SECRET=your_instagram_app_secret
INSTAGRAM_REDIRECT_URI=http://localhost:8000/instagram/callback
INSTAGRAM_WEBHOOK_TOKEN=your_webhook_token
```

**Cara mendapatkan Instagram credentials:**
1. Buat aplikasi di [Facebook Developers](https://developers.facebook.com/)
2. Tambahkan Instagram Graph API
3. Copy App ID dan App Secret
4. Set redirect URI di Facebook App Settings

#### 8. Compile Assets
```bash
# Development mode (with hot reload)
npm run dev

# Or build for development
npm run build
```

#### 9. Jalankan Development Server
```bash
# Terminal 1: Laravel development server
php artisan serve

# Terminal 2: Asset watcher (optional, jika pakai npm run dev)
npm run dev

# Terminal 3: Queue worker (optional)
php artisan queue:work

# Terminal 4: Scheduler (untuk Instagram auto-sync)
php artisan schedule:work
```

Akses aplikasi di: **http://localhost:8000**

#### 10. Login Default
```
Email: superadmin@sekolah.com
Password: password
```

### 🔧 Setup Instagram Auto-Sync (Development)

#### Option 1: Menggunakan `schedule:work` (Recommended)
```bash
# Run scheduler terus menerus
php artisan schedule:work
```

#### Option 2: Manual Testing
```bash
# Sync Instagram posts sekali
php artisan instagram:sync --force

# List scheduled tasks
php artisan schedule:list
```

#### Konfigurasi di Admin Panel
1. Login sebagai superadmin
2. Dashboard → System → Instagram Settings
3. Klik "Connect with Instagram"
4. Login dengan Instagram Business/Creator Account
5. Atur Sync Frequency (5-60 menit)
6. Enable Auto Sync
7. Klik "Save Settings"

### 🛠️ Development Tools

#### Laravel Debugbar (Included)
```bash
# Sudah terinstall, otomatis muncul di development
# Disable dengan set di .env:
DEBUGBAR_ENABLED=false
```

#### Laravel Telescope (Optional)
```bash
# Install Telescope untuk debugging
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Akses Telescope di: **http://localhost:8000/telescope**

#### MCP Server untuk AI Integration
```bash
cd mcp-server
npm install
npm test
```

Lihat dokumentasi lengkap: [README_MCP.md](README_MCP.md)

### 📝 Useful Development Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerate optimizations
php artisan optimize

# Database commands
php artisan migrate:fresh --seed  # Reset database
php artisan db:seed               # Run seeders only

# Tinker (Laravel REPL)
php artisan tinker

# Test Instagram sync
php artisan instagram:sync --force

# View logs
tail -f storage/logs/laravel.log
```

### 🐛 Troubleshooting

**Problem:** `Class "..." not found`
```bash
composer dump-autoload
```

**Problem:** Permission denied pada storage
```bash
chmod -R 775 storage bootstrap/cache
```

**Problem:** Assets tidak muncul
```bash
npm run build
php artisan storage:link
```

**Problem:** Database connection error
- Pastikan MySQL running
- Check credentials di `.env`
- Pastikan database sudah dibuat

---

## 🖥️ Setup Production VPS

📖 **Panduan lengkap setup dan deploy aplikasi di VPS Ubuntu tersedia di: [vps_setup.md](vps_setup.md)**

Dokumentasi setup VPS mencakup:
- ✅ Instalasi server (PHP, MySQL, Nginx, SSL)
- ✅ Deploy aplikasi dan konfigurasi
- ✅ Setup Queue Worker dan Scheduler
- ✅ Security hardening
- ✅ Monitoring dan maintenance
- ✅ Troubleshooting guide

---

## 📞 Support & Documentation

### Bantuan & Pertanyaan
- **Email**: support@sekolah.com
- **Documentation**: [Wiki Repository](https://github.com/wahyudedik/ig-to-web/wiki)
- **Issues**: [GitHub Issues](https://github.com/wahyudedik/ig-to-web/issues)

### Resources
- [VPS Setup Guide](vps_setup.md) - Panduan lengkap setup production di VPS Ubuntu
- [MCP Server Documentation](README_MCP.md)
- [Instagram Setup Guide](mcp-server/QUICKSTART.md)
- [API Documentation](#) (Coming Soon)

### Default Login
```
Email: superadmin@sekolah.com
Password: password
```

⚠️ **PENTING**: Ganti password default setelah instalasi!

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

**IG to Web** - Sistem Manajemen Sekolah Terintegrasi dengan Instagram

Dibuat dengan ❤️ untuk kemajuan pendidikan Indonesia

© 2025 IG to Web. All rights reserved.
