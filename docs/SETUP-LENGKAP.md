# SETUP LENGKAP ABSENSI ZKTECO MB20-VL - UNTUK PEMULA

**Panduan ini JELAS dan LANGSUNG ke poin. Tidak ada yang berbelit-belit.**

---

## PERSIAPAN AWAL

### Yang Anda Butuhkan:
1. **Komputer/Laptop** (untuk setup web)
2. **Device ZKTeco MB20-VL** (mesin absensi)
3. **Kabel Ethernet** (WAJIB untuk device)
4. **Router/Switch Ethernet** (untuk menghubungkan device ke jaringan)
5. **Domain** (contoh: absensi.sekolah.sch.id)
6. **VPS** (server untuk host aplikasi)

### Koneksi Jaringan:
```
Internet
    ↓
Router/Modem
    ├─ Ethernet → Device MB20-VL
    └─ WiFi/Ethernet → Komputer Anda
```

**PENTING:** Device MB20-VL HARUS menggunakan **Ethernet**, bukan WiFi!

---

## STEP 1: SETUP VPS (30 MENIT)

### 1.1 Upload Project ke VPS

```bash
# SSH ke VPS
ssh root@your-vps-ip

# Navigate ke folder
cd /www/wwwroot/absensi.sekolah.sch.id

# Upload project (via Git atau FTP)
git clone <your-repo> .

# Install dependencies
composer install --no-dev --optimize-autoloader

# Copy .env
cp .env.example .env

# Generate key
php artisan key:generate
```

### 1.2 Setup Database

```bash
# Buat database
mysql -u root -p
CREATE DATABASE absensi_db;
CREATE USER 'absensi_user'@'localhost' IDENTIFIED BY 'password-yang-kuat';
GRANT ALL PRIVILEGES ON absensi_db.* TO 'absensi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Jalankan migration
php artisan migrate --force
```

### 1.3 Update .env

Edit file `.env` di VPS:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://absensi.sekolah.sch.id

DB_HOST=127.0.0.1
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=password-yang-kuat

ATTENDANCE_ICLOCK_SECRET=smk-telkom-attendance-2025-secure-token
ATTENDANCE_REQUIRE_USER_IDENTITY=true
ATTENDANCE_REQUIRE_USER_VERIFIED=false
```

### 1.4 Setup Scheduler (WAJIB!)

Di aaPanel, buat Cron Job:
- **Frequency:** Every 1 minute
- **Command:** `cd /www/wwwroot/absensi.sekolah.sch.id && php artisan schedule:run >> /dev/null 2>&1`

### 1.5 Test Endpoint

Buka browser:
```
https://absensi.sekolah.sch.id/iclock/cdata?SN=TEST&token=smk-telkom-attendance-2025-secure-token
```

Harus muncul: `OK`

---

## STEP 2: SETUP DEVICE MB20-VL (45 MENIT)

### 2.1 Hubungkan Device ke Jaringan

**PENTING: Gunakan Ethernet, bukan WiFi!**

```
Device MB20-VL
    ↓ (Kabel Ethernet)
Router/Switch
    ↓ (Kabel Ethernet)
Komputer Anda (untuk setup)
```

### 2.2 Akses Device

1. Hubungkan device ke power
2. Tunggu 2-3 menit sampai boot
3. Buka browser di komputer Anda
4. Ketik: `http://192.168.1.201` (atau IP device Anda)
5. Login: `admin / admin`

### 2.3 Setup Jaringan di Device

Menu → **Network / TCP/IP**

Atur:
```
IP Address:    192.168.1.201
Subnet Mask:   255.255.255.0
Gateway:       192.168.1.1 (IP router Anda)
DNS:           8.8.8.8
```

Simpan → Restart device

### 2.4 Setup Waktu di Device

Menu → **System / Time**

Atur:
```
Date:          [Tanggal hari ini]
Time:          [Jam sekarang]
Timezone:      UTC+7 (Indonesia)
```

Simpan

### 2.5 Setup PUSH iClock (PALING PENTING!)

Menu → **Communication / ADMS / Cloud Server** (atau **Push**)

Atur:
```
Server URL:    https://absensi.sekolah.sch.id/iclock/cdata?token=smk-telkom-attendance-2025-secure-token
Push Interval: 60 (detik)
Enable Push:   ON/YES
```

Simpan → Restart device

**CATATAN:**
- Ganti `absensi.sekolah.sch.id` dengan domain Anda
- Ganti token dengan `ATTENDANCE_ICLOCK_SECRET` di `.env`

### 2.6 Verifikasi Koneksi

1. Tunggu 1-2 menit
2. Buka web: `https://absensi.sekolah.sch.id/admin/absensi/devices`
3. Device harus muncul dengan serial number

Jika tidak muncul:
- Cek URL di device (pastikan benar)
- Cek token (pastikan sama dengan `.env`)
- Cek koneksi internet device (ping 8.8.8.8 dari device)
- Restart device

---

## STEP 3: SETUP USER DI WEB (15 MENIT)

### 3.1 Login ke Admin Panel

```
https://absensi.sekolah.sch.id/admin
```

Login dengan akun admin/superadmin

### 3.2 Tambah User

Menu → **Absensi → Manage User Absensi**

Form **Tambah User Baru**:
```
Jenis:  User
Nama:   Admin Sekolah
PIN:    9001
→ Klik Tambah
```

Ulangi untuk user lain:
```
Jenis:  Guru
Nama:   Budi Santoso
PIN:    1001

Jenis:  Siswa
Nama:   Andi Wijaya
PIN:    2001
```

### 3.3 Sync ke Device

Klik **Sync Semua User ke Device**

Tunggu 1-2 menit sampai status: **done**

---

## STEP 4: ENROLL BIOMETRIC DI DEVICE (30 MENIT)

### 4.1 Enroll Fingerprint

Di device:
```
Menu → Users / User Management
Pilih user (PIN 1001)
Menu → Fingerprint

Letakkan jari di sensor
Angkat dan letakkan lagi (3-4 kali)
Tunggu "Success"

Ulangi untuk jari lain (2-4 jari)
```

### 4.2 Enroll Face (Optional)

```
Menu → Face
Posisikan wajah di depan kamera
Tunggu "Success"
```

### 4.3 Enroll RFID (Optional)

```
Menu → Card / RFID
Tempel kartu ke reader
Tunggu "Success"
```

---

## STEP 5: TEST SCAN (10 MENIT)

### 5.1 Test Scan

1. Minta user untuk scan (fingerprint/face/RFID)
2. Device akan menampilkan nama user

### 5.2 Verifikasi Log

Buka: `https://absensi.sekolah.sch.id/admin/absensi/logs`

Log harus muncul dalam 1-2 menit

### 5.3 Verifikasi Rekap

Buka: `https://absensi.sekolah.sch.id/admin/absensi`

Pilih tanggal hari ini → Rekap harus muncul

---

## TROUBLESHOOTING CEPAT

### Device tidak muncul di web

**Penyebab:** Device tidak terhubung ke web

**Solusi:**
1. Cek URL di device (pastikan benar)
2. Cek token (pastikan sama dengan `.env`)
3. Cek koneksi Ethernet device (pastikan terhubung ke router)
4. Cek DNS (domain harus resolve ke IP VPS)
5. Restart device

### 403 Forbidden

**Penyebab:** Token salah

**Solusi:**
1. Cek token di `.env`
2. Cek URL di device (pastikan ada `?token=...`)
3. Pastikan token sama persis (case-sensitive)

### Log ada tapi rekap kosong

**Penyebab:** Mapping PIN belum dibuat

**Solusi:**
1. Buka: `/admin/absensi/users`
2. Pastikan user sudah ditambahkan
3. Pastikan PIN sama dengan di device
4. Jalankan: `php artisan attendance:sync`

---

## FITUR YANG TERSEDIA

### ✅ Sudah Ada
- CRUD user (add/update/delete)
- Auto-sync ke device
- Dashboard rekap
- Log viewer
- Device management
- Enroll biometric dari web
- Export rekap ke Excel
- Report absensi per periode

### ❌ Belum Ada
- Enroll biometric dari web (harus manual di device)
- Notifikasi real-time

---

## MENU ADMIN

```
Admin Panel
├── Absensi
│   ├── Rekap (Dashboard)
│   ├── Logs (Raw data)
│   ├── Devices (Manage device)
│   ├── Manage User Absensi (CRUD user)
│   ├── Enroll Biometric (Enroll fingerprint/face/RFID)
│   ├── Export Rekap (Export ke Excel)
│   └── Report (Report absensi)
```

---

## CHECKLIST SETUP

- [ ] VPS setup selesai
- [ ] Database migration selesai
- [ ] Scheduler aktif
- [ ] Device terhubung ke web
- [ ] User ditambahkan
- [ ] User ter-sync ke device
- [ ] Biometric enrolled
- [ ] Test scan berhasil
- [ ] Log muncul di web
- [ ] Rekap muncul di web

---

## SELESAI! 🎉

Sistem absensi ZKTeco siap digunakan.

**Total waktu setup:** ~2 jam

**Selamat go-live!** 🚀

---

## KONTAK SUPPORT

Jika ada masalah:
1. Cek troubleshooting di atas
2. Cek log: `storage/logs/laravel.log`
3. Hubungi admin dengan informasi:
   - Serial number device
   - IP address device
   - Error message (jika ada)
   - Screenshot (jika perlu)

---

**Last Updated:** 2025-01-15  
**Status:** PRODUCTION READY
