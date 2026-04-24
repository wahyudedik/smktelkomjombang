# VPS DEPLOYMENT GUIDE
# SMK Telekomunikasi Darul Ulum — Laravel App + Absensi ZKTeco
# From zero to production, step by step.

---

## PERSYARATAN

- VPS Ubuntu 20.04 / 22.04 LTS (min 2GB RAM, 20GB disk)
- Domain sudah pointing ke IP VPS
- Akses SSH root
- Repository di GitHub/GitLab

---

## STEP 1 — LOGIN & UPDATE SERVER

```bash
ssh root@YOUR_VPS_IP

apt update && apt upgrade -y
apt install -y software-properties-common curl wget git unzip zip
```

---

## STEP 2 — FIREWALL

```bash
ufw enable
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw status
```

---

## STEP 3 — INSTALL PHP 8.3

```bash
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml \
    php8.3-mbstring php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd \
    php8.3-intl php8.3-soap php8.3-readline php8.3-opcache php8.3-redis

php -v
```

Edit konfigurasi PHP:
```bash
nano /etc/php/8.2/fpm/php.ini
```

Cari dan ubah:
```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 256M
max_execution_time = 300
date.timezone = Asia/Jakarta
```

```bash
systemctl restart php8.3-fpm
```

---

## STEP 4 — INSTALL COMPOSER

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version
```

---

## STEP 5 — INSTALL NODE.JS 18

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs
node --version
npm --version
```

---

## STEP 6 — INSTALL MYSQL 8

```bash
apt install mysql-server -y
mysql_secure_installation
# Set root password: YES
# Remove anonymous users: YES
# Disallow root login remotely: YES
# Remove test database: YES
# Reload privilege tables: YES
```

Buat database dan user:
```bash
mysql -u root -p
```

```sql
CREATE DATABASE telkom_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'telkom_user'@'localhost' IDENTIFIED BY 'fsdfsfsfs4354WED';
GRANT ALL PRIVILEGES ON telkom_db.* TO 'telkom_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Optimasi MySQL:
```bash
nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Tambahkan di bawah `[mysqld]`:
```ini
max_connections = 200
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
```

```bash
systemctl restart mysql
```

---

## STEP 7 — INSTALL NGINX

```bash
apt install nginx -y
systemctl start nginx
systemctl enable nginx
systemctl status nginx
```

---

## STEP 8 — INSTALL SSL (LET'S ENCRYPT)

```bash
apt install certbot python3-certbot-nginx -y
certbot --nginx -d smktelekomunikasidu.sch.id -d www.smktelekomunikasidu.sch.id;
certbot renew --dry-run
```

---

## STEP 9 — CLONE PROJECT DARI GITHUB

```bash
mkdir -p /var/www/telkom
chown -R $USER:$USER /var/www/telkom

cd /var/www
git clone https://github.com/wahyudedik/smktelkomjombang.git telkom
cd telkom
```

---

## STEP 10 — INSTALL DEPENDENCIES

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# Node dependencies + build assets
npm install
npm run build
```

---

## STEP 11 — SETUP .ENV

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

Isi konfigurasi:
```env
APP_NAME="SMK Telekomunikasi Darul Ulum"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telkom_db
DB_USERNAME=telkom_user
DB_PASSWORD=GANTI_PASSWORD_KUAT

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"

# Instagram Business API
INSTAGRAM_APP_ID=
INSTAGRAM_APP_SECRET=
INSTAGRAM_REDIRECT_URI=https://yourdomain.com/instagram/callback
INSTAGRAM_WEBHOOK_URI=https://yourdomain.com/instagram/webhook
INSTAGRAM_WEBHOOK_TOKEN=

# Push Notification (VAPID) — generate with: php artisan push:vapid-keys --generate
VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=
VAPID_SUBJECT=https://yourdomain.com

# Absensi ZKTeco
ATTENDANCE_ICLOCK_SECRET=BUAT_TOKEN_RANDOM_MINIMAL_32_KARAKTER
ATTENDANCE_REQUIRE_USER_IDENTITY=true
ATTENDANCE_REQUIRE_USER_VERIFIED=false
```

---

## STEP 12 — MIGRATE & SEED DATABASE

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

---

## STEP 13 — SET PERMISSIONS

```bash
chown -R www-data:www-data /var/www/telkom
chmod -R 755 /var/www/telkom
chmod -R 775 /var/www/telkom/storage
chmod -R 775 /var/www/telkom/bootstrap/cache
```

---

## STEP 14 — KONFIGURASI NGINX

```bash
nano /etc/nginx/sites-available/telkom
```

Paste konfigurasi berikut:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name smktelekomunikasidu.sch.id www.smktelekomunikasidu.sch.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/telkom/public;

    ssl_certificate /etc/letsencrypt/live/smktelekomunikasidu.sch.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/smktelekomunikasidu.sch.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript;

    client_max_body_size 100M;
    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
ln -s /etc/nginx/sites-available/smktelekomunikasidu.sch.id /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx
```

---

## STEP 15 — OPTIMIZE UNTUK PRODUCTION

```bash
cd /var/www/telkom

composer dump-autoload --optimize
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## STEP 16 — SETUP QUEUE WORKER DENGAN SUPERVISOR

Install Supervisor:
```bash
apt install supervisor -y
```

Buat konfigurasi worker:
```bash
nano /etc/supervisor/conf.d/telkom-worker.conf
```

Paste:
```ini
[program:telkom-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/telkom/artisan queue:work --sleep=3 --tries=3 --timeout=90 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/telkom/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:
```bash
supervisorctl reread
supervisorctl update
supervisorctl start telkom-worker:*
supervisorctl status
```

---

## STEP 17 — SETUP SCHEDULER (CRON JOB)

```bash
crontab -u www-data -e
```

Tambahkan baris ini:
```
* * * * * cd /var/www/telkom && php artisan schedule:run >> /dev/null 2>&1
```

Verifikasi:
```bash
crontab -u www-data -l
```

Test manual:
```bash
sudo -u www-data php /var/www/telkom/artisan schedule:run
```

---

## STEP 18 — SETUP ABSENSI ZKTECO

⚠️ **PENTING: Baca ini dulu sebelum setup device!**

### 18.0 Penjelasan Protokol ZKTeco (WAJIB TAHU)

Device ZKTeco **TIDAK fleksibel** seperti REST API biasa. Dia hanya ngerti 3 endpoint:
- `/iclock/getrequest` — Device minta perintah dari server
- `/iclock/cdata` — Device kirim data absensi
- `/iclock/devicecmd` — Device kirim hasil command

**Format request dari device:**
```
GET /iclock/getrequest?SN=SERIAL_NUMBER&table=rtlog
POST /iclock/cdata?SN=SERIAL_NUMBER&table=rtlog
```

**Token handling:**
- ❌ Token BUKAN di URL (`?token=...`)
- ✅ Token dikirim di **query parameter** atau **header**
- Controller kita sudah handle ini di `requireToken()`

**HTTPS vs HTTP:**
- ⚠️ Banyak device ZKTeco lama gagal konek ke HTTPS
- ✅ Gunakan **HTTP** untuk testing awal
- ✅ Setelah stabil, bisa upgrade ke HTTPS dengan certificate yang valid

---

### 18.1 Pre-Setup: Konfigurasi .env

Pastikan di `.env` sudah ada:
```env
ATTENDANCE_ICLOCK_SECRET=iloveSMKkuYangIndahTelkomJayaAbadinusantara
ATTENDANCE_REQUIRE_USER_IDENTITY=true
ATTENDANCE_REQUIRE_USER_VERIFIED=false
```

Token ini akan divalidasi di setiap request dari device.

---

### 18.2 Test Endpoint (Sebelum Setup Device)

**Test via curl (HTTP dulu):**
```bash
# Test getrequest
curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST123&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara"
# Harus return: 200 OK + beberapa baris config

# Test cdata
curl -X POST "http://smktelekomunikasidu.sch.id/iclock/cdata?SN=TEST123&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara" \
  -d "test data"
# Harus return: 200 OK
```

**Cek log Laravel:**
```bash
tail -f /var/www/telkom/storage/logs/laravel.log | grep -i attendance
```

---

### 18.3 Setup Device MB20-VL (BENAR)

**STEP 1: Masuk ke menu device**
```
Menu → Communication → ADMS / Cloud Server
```

**STEP 2: Isi konfigurasi (PERHATIAN FORMAT INI):**

| Setting | Value |
|---------|-------|
| **Server Address** | `smktelekomunikasidu.sch.id` |
| **Port** | `80` (HTTP) atau `443` (HTTPS) |
| **Protocol** | HTTP atau HTTPS |
| **Path** | `/iclock/cdata` |
| **Token** | `iloveSMKkuYangIndahTelkomJayaAbadinusantara` |
| **Push Interval** | `60` (detik) |
| **Enable Push** | `ON` |

**ATAU jika device support URL lengkap:**
```
Server URL: http://smktelekomunikasidu.sch.id/iclock/cdata?token=iloveSMKkuYangIndahTelkomJayaAbadinusantara
```

**STEP 3: Simpan & Restart device**
```
Tekan: Save → Restart
```

---

### 18.4 Verifikasi Device Terhubung

**Cek di Laravel admin:**
```
https://smktelekomunikasidu.sch.id/admin/absensi/devices
```

Device harus muncul dengan:
- Serial number
- IP address
- Last seen (waktu terbaru)

Jika belum muncul dalam 2 menit:
1. Cek log Laravel: `tail -f storage/logs/laravel.log`
2. Cek koneksi device ke server
3. Pastikan token di device sama dengan `.env`

---

### 18.5 Tambah User & Sync ke Device

1. Buka: `https://smktelekomunikasidu.sch.id/admin/absensi/users`
2. Tambah user dengan:
   - Nama
   - PIN (harus unik, 1-5 digit)
   - Kelas
3. Klik **Sync Semua User ke Device**
4. Tunggu status berubah menjadi **done**

**Cek log sync:**
```bash
tail -f /var/www/telkom/storage/logs/laravel.log | grep -i "sync\|user"
```

---

### 18.6 Enroll Biometric (di device langsung)

**Fingerprint:**
```
Menu → Users → Pilih user → Fingerprint
Ikuti instruksi: Letakkan jari 3-4 kali
```

**Face Recognition:**
```
Menu → Users → Pilih user → Face
Ikuti instruksi: Hadap ke kamera, gerakkan kepala
```

**Card / Badge:**
```
Menu → Users → Pilih user → Card
Tempelkan kartu ke reader
```

---

### 18.7 Test Absensi (Scan Pertama)

1. Scan fingerprint / face / card di device
2. Cek di Laravel: `https://smktelekomunikasidu.sch.id/admin/absensi/logs`
3. Data harus muncul dalam 1-2 menit

**Jika tidak muncul:**
```bash
# Cek log Laravel
tail -f /var/www/telkom/storage/logs/laravel.log

# Cek database
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_logs ORDER BY created_at DESC LIMIT 5;"

# Cek device connection
curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=DEVICE_SERIAL&token=YOUR_TOKEN"
```

---

## STEP 19 — VERIFIKASI AKHIR

```bash
# Website
curl -I https://smktelekomunikasidu.sch.id

# Database
mysql -u telkom_user -p -e "USE telkom_db; SHOW TABLES;"

# Queue worker
supervisorctl status

# Scheduler
sudo -u www-data php /var/www/telkom/artisan schedule:run

# Logs
tail -f /var/www/telkom/storage/logs/laravel.log
```

---

## UPDATE CODE (SETELAH INITIAL SETUP)

### Quick Update
```bash
cd /var/www/telkom
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
supervisorctl restart telkom-worker:*
```

### Full Update (ada migration / dependency baru)
```bash
cd /var/www/telkom
mysqldump -u telkom_user -p telkom_db > backup_$(date +%Y%m%d_%H%M%S).sql
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
supervisorctl restart telkom-worker:*
```

---

## TROUBLESHOOTING

### 500 Error / Website tidak bisa diakses
```bash
tail -f /var/www/telkom/storage/logs/laravel.log
tail -f /var/log/nginx/error.log
nginx -t
systemctl status php8.3-fpm
```

### Permission denied
```bash
chown -R www-data:www-data /var/www/telkom
chmod -R 775 /var/www/telkom/storage
chmod -R 775 /var/www/telkom/bootstrap/cache
```

### Class "ServiceProvider" not found
```bash
cd /var/www/telkom
rm -f bootstrap/cache/*.php
php artisan optimize:clear
composer dump-autoload --optimize
php artisan package:discover --ansi
php artisan optimize
```

### Queue tidak jalan
```bash
supervisorctl status
supervisorctl restart telkom-worker:*
tail -f /var/www/telkom/storage/logs/worker.log
```

### Device absensi tidak muncul / tidak connect

**Checklist debugging (urutan penting):**

1. **Cek token di .env**
   ```bash
   grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env
   ```
   Pastikan sama dengan token di device.

2. **Cek endpoint bisa diakses (HTTP dulu)**
   ```bash
   curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST&token=YOUR_TOKEN"
   ```
   Harus return `200 OK` + beberapa baris config.

3. **Cek log Laravel**
   ```bash
   tail -f /var/www/telkom/storage/logs/laravel.log | grep -i "attendance\|iclock"
   ```
   Cari error atau warning.

4. **Cek device setting**
   - Server Address: `smktelekomunikasidu.sch.id` (tanpa https://)
   - Port: `80` (HTTP) atau `443` (HTTPS)
   - Token: sama dengan `.env`
   - Push Interval: `60`
   - Enable Push: `ON`

5. **Restart device**
   ```
   Menu → System → Restart
   ```

6. **Cek database (device sudah terdaftar?)**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
   ```

7. **Jika masih tidak connect, coba HTTP dulu (bukan HTTPS)**
   - HTTPS sering bermasalah di device lama
   - Setelah stabil, baru upgrade ke HTTPS

### Data absensi tidak masuk ke database

**Checklist:**

1. **Device sudah terhubung?**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices WHERE last_seen_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE);"
   ```

2. **User sudah di-sync ke device?**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_identities LIMIT 5;"
   ```

3. **Cek log scan di device**
   ```
   Menu → Logs → Attendance
   ```
   Harus ada record scan.

4. **Cek log Laravel saat scan**
   ```bash
   tail -f /var/www/telkom/storage/logs/laravel.log
   # Lalu scan di device, lihat apa yang muncul
   ```

5. **Cek database attendance_logs**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_logs ORDER BY created_at DESC LIMIT 10;"
   ```

6. **Jika queue worker tidak jalan**
   ```bash
   supervisorctl status telkom-worker:*
   supervisorctl restart telkom-worker:*
   tail -f /var/www/telkom/storage/logs/worker.log
   ```

---

## MAINTENANCE RUTIN

```bash
# Backup database (jalankan setiap hari)
mysqldump -u telkom_user -p telkom_db > /backup/telkom_$(date +%Y%m%d).sql

# Monitor disk
df -h

# Monitor memory
free -m

# Renew SSL (otomatis, tapi bisa manual)
certbot renew

# Update sistem (bulanan)
apt update && apt upgrade -y
```

---

## CHECKLIST DEPLOYMENT

- [ ] Server Ubuntu setup & update
- [ ] PHP 8.3 + extensions terinstall
- [ ] Composer & Node.js terinstall
- [ ] MySQL database & user dibuat
- [ ] Nginx terinstall & dikonfigurasi
- [ ] SSL certificate aktif
- [ ] Repository di-clone ke /var/www/telkom
- [ ] composer install & npm run build selesai
- [ ] .env dikonfigurasi (DB, APP_URL, ATTENDANCE_SECRET)
- [ ] php artisan migrate --force selesai
- [ ] php artisan storage:link selesai
- [ ] Permissions sudah benar (www-data)
- [ ] Nginx site enabled & reload
- [ ] Production cache (config/route/view)
- [ ] Supervisor queue worker berjalan (2 proses)
- [ ] Cron job scheduler aktif
- [ ] Website bisa diakses via HTTPS
- [ ] **[ZKTeco Setup]**
  - [ ] ATTENDANCE_ICLOCK_SECRET sudah di .env
  - [ ] Test endpoint HTTP: `/iclock/getrequest?SN=TEST&token=...`
  - [ ] Device setting: Server Address (tanpa https://), Port 80, Token sesuai
  - [ ] Device restart & tunggu 2 menit
  - [ ] Device muncul di `/admin/absensi/devices`
  - [ ] User ditambahkan & sync ke device
  - [ ] Biometric enrolled (fingerprint/face/card)
  - [ ] Test scan berhasil & log muncul di `/admin/absensi/logs`
  - [ ] Database attendance_logs terisi data

---

## DOKUMENTASI TAMBAHAN

- **[ZKTECO-SETUP.md](./ZKTECO-SETUP.md)** — Panduan lengkap setup ZKTeco iClock (troubleshooting, testing, monitoring)
- **[ZKTECO-CORRECTIONS.md](./ZKTECO-CORRECTIONS.md)** — Koreksi penting dari feedback GPT (HTTP vs HTTPS, format endpoint, token placement)

---

**Last Updated:** 2026-04-23
**Status:** PRODUCTION READY
