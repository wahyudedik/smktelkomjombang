# ZKTeco Quick Fix Guide
## Jika Device Tidak Connect

---

## 🔥 QUICK DIAGNOSIS (5 MENIT)

### Step 1: Cek Token di .env
```bash
grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env
```
**Harus ada dan tidak kosong.**

### Step 2: Test Endpoint HTTP
```bash
curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara"
```
**Harus return 200 OK + config lines.**

### Step 3: Cek Device Setting
```
Menu → Communication → ADMS / Cloud Server
```
**Harus:**
- Server Address: `smktelekomunikasidu.sch.id` (tanpa http://)
- Port: `80`
- Token: `iloveSMKkuYangIndahTelkomJayaAbadinusantara`
- Push Interval: `60`
- Enable Push: `ON`

### Step 4: Restart Device
```
Menu → System → Restart
```
**Tunggu 2 menit.**

### Step 5: Cek Database
```bash
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
```
**Device harus muncul dengan last_seen_at terbaru.**

---

## ❌ Jika Masih Tidak Connect

### Cek 1: Endpoint Accessible?
```bash
curl -I "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara"
```
- ✅ 200 OK → Endpoint OK
- ❌ 403 Forbidden → Token salah
- ❌ 404 Not Found → Route tidak ada
- ❌ Connection refused → Server down

### Cek 2: Laravel Log
```bash
tail -f /var/www/telkom/storage/logs/laravel.log
```
Cari error atau warning.

### Cek 3: Device Network
```bash
ping DEVICE_IP_ADDRESS
```
Device harus bisa ping server.

### Cek 4: Firewall
```bash
ufw status
```
Port 80 harus allow.

### Cek 5: Nginx
```bash
nginx -t
systemctl status nginx
```
Nginx harus running.

### Cek 6: PHP-FPM
```bash
systemctl status php8.3-fpm
```
PHP-FPM harus running.

---

## 🎯 COMMON ISSUES & FIXES

### Issue 1: Device tidak muncul di admin

**Penyebab:** Token salah atau endpoint tidak accessible.

**Fix:**
```bash
# 1. Cek token
grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env

# 2. Cek endpoint
curl -v "http://domain.com/iclock/getrequest?SN=TEST&token=..."

# 3. Cek device setting
# Menu → Communication → ADMS / Cloud Server

# 4. Restart device
# Menu → System → Restart

# 5. Cek database
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
```

### Issue 2: Device connect tapi data tidak masuk

**Penyebab:** User tidak di-sync atau biometric tidak enrolled.

**Fix:**
```bash
# 1. Cek user di-sync
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_identities LIMIT 5;"

# 2. Sync user ke device
# Admin → Absensi → Users → Sync Semua User ke Device

# 3. Enroll biometric
# Device → Menu → Users → Pilih user → Fingerprint

# 4. Test scan
# Scan di device → cek admin /admin/absensi/logs
```

### Issue 3: Error 403 Forbidden

**Penyebab:** Token salah.

**Fix:**
```bash
# 1. Cek token di .env
grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env

# 2. Cek token di device setting
# Menu → Communication → ADMS / Cloud Server

# 3. Pastikan sama persis (case-sensitive)

# 4. Restart device
```

### Issue 4: Error 500 Internal Server Error

**Penyebab:** Database error atau service error.

**Fix:**
```bash
# 1. Cek log Laravel
tail -f /var/www/telkom/storage/logs/laravel.log

# 2. Cek database connection
mysql -u telkom_user -p telkom_db -e "SELECT 1;"

# 3. Cek queue worker
supervisorctl status telkom-worker:*

# 4. Restart services
systemctl restart php8.3-fpm
supervisorctl restart telkom-worker:*
```

### Issue 5: Device connect tapi "Last Seen" tidak update

**Penyebab:** Device tidak push data berkala.

**Fix:**
```bash
# 1. Cek device setting
# Menu → Communication → ADMS / Cloud Server
# Push Interval: 60
# Enable Push: ON

# 2. Restart device
# Menu → System → Restart

# 3. Cek device internet connection
# Menu → Network → Test Connection
```

---

## 🚀 QUICK COMMANDS

### Restart Everything
```bash
systemctl restart php8.3-fpm
systemctl restart nginx
supervisorctl restart telkom-worker:*
```

### Clear Cache
```bash
cd /var/www/telkom
php artisan optimize:clear
rm -f bootstrap/cache/*.php
php artisan optimize
```

### Check All Services
```bash
systemctl status php8.3-fpm
systemctl status nginx
systemctl status mysql
supervisorctl status telkom-worker:*
```

### View Logs
```bash
# Laravel
tail -f /var/www/telkom/storage/logs/laravel.log

# Nginx
tail -f /var/log/nginx/error.log

# Queue worker
tail -f /var/www/telkom/storage/logs/worker.log

# System
journalctl -u php8.3-fpm -f
```

### Database Queries
```bash
# Check devices
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"

# Check users
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_identities LIMIT 5;"

# Check logs
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_logs ORDER BY created_at DESC LIMIT 10;"

# Count today's scans
mysql -u telkom_user -p telkom_db -e "SELECT COUNT(*) FROM attendance_logs WHERE DATE(created_at) = CURDATE();"
```

---

## 📞 ESCALATION PATH

1. **Device tidak connect?**
   - Cek endpoint HTTP
   - Cek token
   - Cek device setting
   - Restart device

2. **Device connect tapi data tidak masuk?**
   - Cek user di-sync
   - Cek biometric enrolled
   - Cek queue worker
   - Cek database

3. **Error 403 / 500?**
   - Cek log Laravel
   - Cek database connection
   - Restart services

4. **Masih tidak bisa?**
   - Baca: `docs/ZKTECO-SETUP.md`
   - Baca: `docs/ZKTECO-CORRECTIONS.md`
   - Cek: `docs/VPS-DEPLOY.md` STEP 18

---

**Last Updated:** 2026-04-23
**Status:** QUICK REFERENCE
