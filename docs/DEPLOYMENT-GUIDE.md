# DEPLOYMENT GUIDE - ABSENSI ZKTECO

**Panduan lengkap untuk deploy dan setup Absensi ZKTeco dari awal sampai production.**

---

## 📚 DOKUMENTASI YANG TERSEDIA

### 1. **SETUP-LENGKAP.md** ⭐ BACA INI DULU!
   - Setup lengkap dari awal sampai selesai
   - Untuk pemula
   - Jelas dan langsung ke poin
   - Waktu: ~2 jam
   - Mencakup:
     - Setup VPS (30 menit)
     - Setup Device MB20-VL (45 menit)
     - Setup User (15 menit)
     - Enroll Biometric (30 menit)
     - Test Scan (10 menit)

### 2. **vps_setup.md** 
   - Setup VPS untuk production
   - Step-by-step deployment
   - Untuk admin/DevOps
   - Mencakup:
     - Setup server Ubuntu
     - Install PHP, MySQL, Nginx
     - Deploy aplikasi
     - Setup scheduler & queue worker
     - Maintenance & troubleshooting

### 3. **docs/absensi-zkteco-setup.md**
   - Dokumentasi teknis
   - Cara kerja sistem
   - Untuk developer/admin advanced
   - Mencakup:
     - Integrasi PUSH iClock
     - Database schema
     - API endpoints
     - Protocol ZKTeco

---

## 🚀 QUICK START (UNTUK PEMULA)

### Langkah 1: Baca SETUP-LENGKAP.md
```
Buka file: SETUP-LENGKAP.md 
Ikuti step by step
Waktu: ~2 jam
```

### Langkah 2: Setup VPS
```
Buka file: vps_setup.md
Ikuti STEP 1 & STEP 2
Waktu: ~1 jam
```

### Langkah 3: Setup Device
```
Buka file: SETUP-LENGKAP.md
Ikuti STEP 2 (Setup Device MB20-VL)
Waktu: ~45 menit
```

### Langkah 4: Setup User & Test
```
Buka file: SETUP-LENGKAP.md
Ikuti STEP 3, 4, 5
Waktu: ~55 menit
```

---

## 🔑 POIN-POIN PENTING

### 1. DEVICE HARUS ETHERNET!
```
Device MB20-VL TIDAK support WiFi!

Koneksi yang BENAR:
  Internet → Router → Device (Ethernet) + Komputer

Koneksi yang SALAH:
  Internet → Router → Device (WiFi) ❌
```

### 2. INSTALL DEPENDENCY EXCEL
```bash
composer require maatwebsite/excel 
```

### 3. SETUP SCHEDULER (WAJIB!)
```bash
# Cron job every 1 minute
* * * * * cd /var/www/absensi && php artisan schedule:run >> /dev/null 2>&1
```

### 4. ATTENDANCE_ICLOCK_SECRET
```env
# Di .env, set token yang kuat
ATTENDANCE_ICLOCK_SECRET=buat-token-random-panjang-minimal-32-karakter
```

### 5. DEVICE URL HARUS BENAR
```
Di device, set URL:
https://yourdomain.com/iclock/cdata?token=ATTENDANCE_ICLOCK_SECRET
```

---

## 📋 FITUR YANG TERSEDIA

### ✅ CRUD User
- Tambah/edit/hapus user dari web
- Auto-sync ke device via ADMS command queue
- Lihat status sync (pending/sent/done/failed)

### ✅ Biometric Enrollment
- Enroll fingerprint (10 jari)
- Enroll face recognition
- Enroll RFID card
- Semua dilakukan di device (manual)

### ✅ Export Rekap
- Export harian
- Export periode
- Export summary
- Export detail per user
- Format Excel dengan styling

### ✅ Report Absensi
- Report harian
- Report mingguan
- Report bulanan
- Report per user
- Report keterlambatan
- Dengan statistik (hadir, tidak hadir, persentase)

### ✅ Dashboard
- Rekap harian
- Log viewer
- Device management
- User management

---

## 🔄 WORKFLOW DEPLOYMENT

### Pertama Kali (Initial Setup)

1. **Setup VPS** (vps_setup.md - STEP 1 & 2)
   ```bash
   # Install server, PHP, MySQL, Nginx
   # Deploy aplikasi
   # Setup scheduler & queue worker
   ```

2. **Setup Device** (SETUP-LENGKAP.md - STEP 2)
   ```bash
   # Hubungkan Ethernet
   # Setup network
   # Setup PUSH iClock URL
   ```

3. **Setup User** (SETUP-LENGKAP.md - STEP 3)
   ```bash
   # Tambah user di web
   # Sync ke device
   ```

4. **Enroll Biometric** (SETUP-LENGKAP.md - STEP 4)
   ```bash
   # Enroll fingerprint/face/RFID di device
   ```

5. **Test** (SETUP-LENGKAP.md - STEP 5)
   ```bash
   # Test scan
   # Verifikasi log & rekap
   ```

### Update Code (Setelah Initial Setup)

**Quick Update:**
```bash
cd /var/www/absensi
git pull origin main
php artisan optimize:clear
rm -f bootstrap/cache/*.php
composer dump-autoload --optimize
php artisan package:discover --ansi
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo systemctl restart laravel-worker
```

**Full Update:**
```bash
cd /var/www/absensi
git pull origin main
php artisan optimize:clear
rm -f bootstrap/cache/*.php
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize
php artisan package:discover --ansi
npm install && npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo systemctl restart laravel-worker
```

---

## 🛠️ MAINTENANCE

### Daily
```bash
# Monitor logs
tail -f /var/www/absensi/storage/logs/laravel.log

# Check queue status
php artisan queue:failed
```

### Weekly
```bash
# Backup database
mysqldump -u absensi_user -p absensi_db > backup_$(date +%Y%m%d).sql

# Check disk space
df -h

# Check system resources
htop
```

### Monthly
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Renew SSL certificate
sudo certbot renew

# Optimize database
php artisan tinker
# DB::statement('OPTIMIZE TABLE attendances');
# DB::statement('OPTIMIZE TABLE attendance_logs');
```

---

## 🐛 TROUBLESHOOTING

### Device tidak muncul di admin panel

**Penyebab:** Device tidak terhubung ke web

**Solusi:**
1. Cek URL di device (pastikan benar)
2. Cek token (pastikan sama dengan `.env`)
3. Cek Ethernet terhubung ke router
4. Cek DNS (domain harus resolve ke IP VPS)
5. Restart device

**Test endpoint:**
```bash
curl -I "https://yourdomain.com/iclock/cdata?SN=TEST&token=YOUR_TOKEN"
# Harus return: HTTP/2 200 atau 403 (jika token salah)
```

### 403 Forbidden

**Penyebab:** Token salah

**Solusi:**
1. Cek token di `.env`
2. Cek URL di device (pastikan ada `?token=...`)
3. Pastikan token sama persis (case-sensitive)

### Log ada tapi rekap kosong

**Penyebab:** Mapping PIN belum dibuat atau scheduler tidak jalan

**Solusi:**
1. Buka: `/admin/absensi/users`
2. Pastikan user sudah ditambahkan
3. Pastikan PIN sama dengan di device
4. Jalankan: `php artisan attendance:sync`
5. Cek scheduler: `sudo crontab -u www-data -l`

### Class "ServiceProvider" not found

**Penyebab:** Cache bootstrap corrupt

**Solusi:**
```bash
cd /var/www/absensi
rm -f bootstrap/cache/*.php
php artisan optimize:clear
composer dump-autoload --optimize
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Queue worker tidak jalan

**Penyebab:** Service tidak aktif

**Solusi:**
```bash
# Restart worker
sudo systemctl restart laravel-worker

# Check status
sudo systemctl status laravel-worker

# View logs
sudo journalctl -u laravel-worker -f
```

---

## 📊 MONITORING COMMANDS

```bash
# Check website
curl -I https://yourdomain.com

# Check database
mysql -u absensi_user -p -e "SELECT COUNT(*) FROM absensi_db.attendance_logs;"

# Check scheduler
sudo crontab -u www-data -l

# Check queue worker
sudo systemctl status laravel-worker

# Check logs
tail -f /var/www/absensi/storage/logs/laravel.log

# Check system
htop
df -h
free -m
```

---

## 🔒 SECURITY CHECKLIST

- [ ] Ganti password MySQL dengan password kuat
- [ ] Ganti ATTENDANCE_ICLOCK_SECRET dengan token random panjang
- [ ] Enable firewall dan hanya buka port yang dibutuhkan
- [ ] Setup SSH key dan disable password login
- [ ] Enable SSL (Let's Encrypt)
- [ ] Regular backups database dan files
- [ ] Monitor logs untuk aktivitas mencurigakan
- [ ] Disable root login SSH
- [ ] Setup fail2ban untuk brute force protection

---

## 📞 SUPPORT

Jika ada masalah:

1. **Cek dokumentasi:**
   - SETUP-LENGKAP.md (untuk setup)
   - vps_setup.md (untuk VPS)
   - docs/absensi-zkteco-setup.md (untuk teknis)

2. **Cek logs:**
   ```bash
   tail -f /var/www/absensi/storage/logs/laravel.log
   sudo tail -f /var/log/nginx/error.log
   sudo tail -f /var/log/php8.2-fpm.log
   ```

3. **Cek troubleshooting di atas**

4. **Hubungi admin dengan informasi:**
   - Serial number device
   - IP address device
   - Error message (jika ada)
   - Screenshot (jika perlu)

---

## ✅ DEPLOYMENT CHECKLIST

### VPS Setup
- [ ] Server Ubuntu setup selesai
- [ ] PHP 8.2 dan extensions terinstall
- [ ] Composer dan Node.js terinstall
- [ ] MySQL database dan user dibuat
- [ ] Nginx terinstall dan dikonfigurasi
- [ ] SSL certificate aktif

### Application Deployment
- [ ] Repository di-clone
- [ ] Dependencies terinstall (composer + npm)
- [ ] .env dikonfigurasi dengan benar
- [ ] Migrations dijalankan
- [ ] Permissions sudah benar
- [ ] Queue worker berjalan
- [ ] Scheduler aktif (cron job)

### Verification
- [ ] Website bisa diakses via HTTPS
- [ ] iClock endpoint bisa diakses
- [ ] Database connection OK
- [ ] Logs tidak ada error
- [ ] Device terhubung ke web
- [ ] User ditambahkan
- [ ] User ter-sync ke device
- [ ] Biometric enrolled
- [ ] Test scan berhasil

---

## 🎉 SELESAI!

Aplikasi Absensi ZKTeco sudah siap di production.

**Total waktu setup:** ~3 jam (VPS + Device + User + Test)

**Selamat go-live!** 🚀

---

**Last Updated:** 2025-01-15  
**Status:** PRODUCTION READY
