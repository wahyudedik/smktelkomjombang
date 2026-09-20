# 🚀 VPS Deployment Commands — SMK Telekomunikasi

> Dokumen lengkap command deployment untuk project Laravel 12 SMK Telekomunikasi Darul Ulum.
> Diperbarui: 2026-09-20
>
> **Tech Stack**: Laravel 12, PHP 8.2+, MySQL, Node.js 24, Vite, Spatie Laravel-Permission, Maatwebsite Excel, Barryvdh DomPDF

---

## Daftar Isi

- [A. Full Deployment (Setup Awal)](#a-full-deployment-setup-awal)
- [B. Update / Rollback](#b-update--rollback)
- [C. VPS Management Commands](#c-vps-management-commands)
- [D. Troubleshooting](#d-troubleshooting)

---

## A. Full Deployment (Setup Awal)

> Gunakan saat pertama kali deploy ke VPS baru.
> Alternatif cepat: jalankan `bash deploy.sh --theme telkom` atau `bash deploy.sh --theme maudu`.

### A.1 Install System Dependencies (Ubuntu/Debian)

```bash
# Update package list
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 + extensions
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml \
  php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
  php8.2-intl php8.2-tokenizer php8.2-dom php8.2-sqlite3

# Install MySQL
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Node.js 24 (via NVM)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
source ~/.bashrc
nvm install 24
nvm use 24
node -v  # Pastikan v24.x

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version  # Pastikan terinstall
```

### A.2 Setup MySQL Database & User

```bash
# Login ke MySQL
sudo mysql -u root

-- Jalankan SQL berikut di MySQL prompt:
```

```sql
-- Untuk tema TELKOM
CREATE DATABASE telkom_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'telkom_user'@'127.0.0.1' IDENTIFIED BY 'PASSWORD_YANG_KUAT';
GRANT ALL PRIVILEGES ON telkom_db.* TO 'telkom_user'@'127.0.0.1';
FLUSH PRIVILEGES;

-- Untuk tema MAUDU
CREATE DATABASE sekolah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sekolah'@'127.0.0.1' IDENTIFIED BY 'PASSWORD_YANG_KUAT';
GRANT ALL PRIVILEGES ON sekolah.* TO 'sekolah'@'127.0.0.1';
FLUSH PRIVILEGES;

EXIT;
```

### A.3 Clone & Setup Project

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git telkom
sudo chown -R $USER:$USER telkom
cd telkom

# Checkout branch main
git checkout main
```

### A.4 Setup Environment

```bash
# Pilih salah satu sesuai tema:
cp .env.production.telkom .env    # Untuk tema Telkom
# atau
cp .env.production.maudu .env     # Untuk tema MAUDU

# Generate APP_KEY
php artisan key:generate

# Edit .env — isi nilai PLACEHOLDER:
nano .env
```

**Nilai yang WAJIB diisi di `.env`:**

| Key | Keterangan |
|-----|-----------|
| `APP_KEY` | Auto-generate via `php artisan key:generate` |
| `DB_PASSWORD` | Password MySQL yang dibuat di A.2 |
| `MAIL_USERNAME` / `MAIL_PASSWORD` | SMTP credentials |
| `INSTAGRAM_APP_ID` / `INSTAGRAM_APP_SECRET` | Instagram API (opsional) |
| `VAPID_PUBLIC_KEY` / `VAPID_PRIVATE_KEY` | Web Push (opsional) |
| `ATTENDANCE_ICLOCK_SECRET` | ZKTeco iClock (opsional) |

### A.5 Install Dependencies & Build

```bash
# Composer (production — tanpa dev dependencies)
composer install --no-dev --optimize-autoloader --no-interaction

# NPM
npm ci

# Build assets (Vite)
npm run build
```

### A.6 Database Migration & Seed

```bash
# Migrate semua tables
php artisan migrate --force

# Seed roles & permissions (Spatie)
php artisan db:seed --class=PermissionSeeder

# Seed theme settings
php artisan db:seed --class=ThemeSettingsSeeder

# Generate static pages
php artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()"
```

### A.7 Storage Link

```bash
# Buat symlink storage → public/storage
php artisan storage:link
```

### A.8 Permissions

```bash
# Deteksi web server user
# Ubuntu/Debian default: www-data
# aaPanel/BT Panel: www

# Set ownership & permissions
sudo chown -R www-data:www-data storage bootstrap/cache public/uploads
sudo chmod -R 775 storage bootstrap/cache public/uploads
sudo chmod 775 public

# Fix static assets permissions
find public/assets_* -type d -exec chmod 755 {} \;
find public/assets_* -type f -exec chmod 644 {} \;

# Set file permissions
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;
```

### A.9 Cache Warmup

```bash
# Clear semua cache dulu
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan event:clear

# Lalu rebuild/optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### A.10 Setup Queue Worker (Supervisor)

```bash
# Install Supervisor
sudo apt install -y supervisor

# Buat config queue worker
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

**Isi file `/etc/supervisor/conf.d/laravel-worker.conf`:**

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/telkom/artisan queue:work --sleep=3 --tries=3 --max-time=3600
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

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start "laravel-worker:*"
sudo supervisorctl status
```

### A.11 Setup Cron Scheduler

```bash
# Edit crontab
crontab -e
```

**Tambahkan baris berikut:**

```cron
* * * * * cd /var/www/telkom && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduler yang sudah dijalankan (otomatis via Laravel):**

| Waktu | Command | Keterangan |
|-------|---------|------------|
| Setiap menit | `schedule:run` | Entry point scheduler |
| Jam 23:00 | `attendance:mark-alpha` | Tandai alpha otomatis |
| Jam 16:00 | `attendance:notify --summary` | Rekap harian ke admin |

### A.12 Setup Nginx Virtual Host

```bash
sudo nano /etc/nginx/sites-available/telkom
```

**Isi file Nginx config:**

```nginx
server {
    listen 80;
    server_name smktelekomunikasidu.sch.id www.smktelekomunikasidu.sch.id;
    root /var/www/telkom/public;

    add_header X-Frame-Options "DENY";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realroot$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|ico|gif|jpeg|jpg|png|woff|woff2|ttf|svg|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # Block access to sensitive files
    location ~ /\.env {
        deny all;
    }
    location ~ /composer\.(json|lock) {
        deny all;
    }
}
```

```bash
# Aktifkan site
sudo ln -s /etc/nginx/sites-available/telkom /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default 2>/dev/null

# Test config & restart
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### A.13 SSL (Let's Encrypt)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Dapatkan SSL certificate
sudo certbot --nginx -d smktelekomunikasidu.sch.id -d www.smktelekomunikasidu.sch.id

# Auto-renew (sudah diatur oleh certbot, tapi cek:)
sudo certbot renew --dry-run
```

### A.14 Final Checklist

```bash
# Cek semua service berjalan
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
sudo supervisorctl status

# Cek website
curl -I https://smktelekomunikasidu.sch.id

# Cek queue worker
php artisan queue:monitor

# Cek log error
tail -50 storage/logs/laravel.log
```

---

## B. Update / Rollback

> Gunakan [`update.sh`](../update.sh) untuk update incremental, atau jalankan command manual di bawah.

### B.1 Update via Script (Recommended)

```bash
# Update tema Telkom
bash update.sh --theme telkom

# Update tema MAUDU
bash update.sh --theme maudu

# Auto-detect dari .env
bash update.sh
```

### B.2 Update Manual (Step-by-Step)

```bash
# 1. Maintenance mode
php artisan down --refresh=15 --retry=60

# 2. Backup database (OTOMATIS via update.sh, tapi bisa manual)
BACKUP_FILE="storage/backups/db_backup_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u telkom_user -p telkom_db > "$BACKUP_FILE"

# 3. Pull perubahan terbaru
git fetch origin
git reset --hard origin/main

# 4. Install dependencies (jika ada dependency baru)
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Build assets (jika ada perubahan frontend)
npm ci
npm run build

# 6. Jalankan migration (termasuk migration Buku Tamu baru)
php artisan migrate --force

# 7. Seed permissions (termasuk buku-tamu.* permissions baru)
php artisan db:seed --class=PermissionSeeder

# 8. Seed theme settings
php artisan db:seed --class=ThemeSettingsSeeder

# 9. Clear & rebuild cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 10. Pastikan storage link
php artisan storage:link

# 11. Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache public/uploads
sudo chmod -R 775 storage bootstrap/cache public/uploads

# 12. Restart queue worker
php artisan queue:restart

# 13. Matikan maintenance mode
php artisan up
```

### B.3 Update Khusus: Fitur Buku Tamu

> Fitur baru yang ditambahkan: Guest Book dengan migration `2026_09_20_100000_create_guest_books_table.php`

```bash
# 1. Pull code
git fetch origin
git reset --hard origin/main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build

# 3. Jalankan migration Buku Tamu
php artisan migrate --force
# Ini akan menjalankan: 2026_09_20_100000_create_guest_books_table.php
# Membuat tabel: guest_books

# 4. Seed permissions buku-tamu.* (6 permissions baru)
php artisan db:seed --class=PermissionSeeder
# Permissions yang ditambahkan:
#   - buku-tamu.view
#   - buku-tamu.create
#   - buku-tamu.update
#   - buku-tamu.delete
#   - buku-tamu.export
#   - buku-tamu.checkout

# 5. Buat direktori upload foto tamu
mkdir -p storage/app/public/guest-photos
php artisan storage:link  # Jika belum

# 6. Set permissions
sudo chown -R www-data:www-data storage/app/public/guest-photos
sudo chmod -R 775 storage/app/public/guest-photos

# 7. Clear cache & restart
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart

# 8. Matikan maintenance mode
php artisan up
```

### B.4 Rollback Database

```bash
# Rollback 1 migration terakhir
php artisan migrate:rollback

# Rollback semua migration
php artisan migrate:rollback --all

# Restore dari backup
# 1. Matikan maintenance mode dulu
php artisan down

# 2. Restore database
mysql -u telkom_user -p telkom_db < storage/backups/db_backup_YYYYMMDD_HHMMSS.sql

# 3. Jalankan ulang cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Matikan maintenance mode
php artisan up
```

### B.5 Rollback Code (Git)

```bash
# Lihat commit sebelumnya
git log --oneline -10

# Rollback ke commit tertentu
git checkout <previous-commit-hash>

# Atau reset ke commit sebelumnya
git reset --hard HEAD~1

# Setelah rollback, jalankan:
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan cache:clear && php artisan config:cache
php artisan queue:restart
```

---

## C. VPS Management Commands

### C.1 Restart Services

```bash
# Nginx
sudo systemctl restart nginx

# PHP-FPM
sudo systemctl restart php8.2-fpm

# MySQL
sudo systemctl restart mysql

# Queue Worker (via Supervisor)
sudo supervisorctl restart "laravel-worker:*"

# Semua service sekaligus
sudo systemctl restart nginx php8.2-fpm mysql
sudo supervisorctl restart all
```

### C.2 View Logs

```bash
# Laravel application logs
tail -100 storage/logs/laravel.log
tail -f storage/logs/laravel.log  # Real-time

# Nginx access log
sudo tail -100 /var/log/nginx/access.log

# Nginx error log
sudo tail -100 /var/log/nginx/error.log

# PHP-FPM error log
sudo tail -100 /var/log/php8.2-fpm.log

# MySQL error log
sudo tail -100 /var/log/mysql/error.log

# Supervisor queue worker log
tail -100 storage/logs/worker.log
tail -f storage/logs/worker.log  # Real-time

# Semua error logs sekaligus
echo "=== Laravel ===" && tail -5 storage/logs/laravel.log
echo "=== Nginx ===" && sudo tail -5 /var/log/nginx/error.log
echo "=== PHP-FPM ===" && sudo tail -5 /var/log/php8.2-fpm.log
echo "=== Worker ===" && tail -5 storage/logs/worker.log
```

### C.3 Clear All Caches

```bash
# Laravel cache (perintah individual — hindari optimize:clear mbstring error)
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan event:clear

# Rebuild cache setelah clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Nginx cache (jika menggunakan FastCGI cache)
sudo rm -rf /var/cache/nginx/*
sudo systemctl reload nginx

# Browser cache — biasanya handled oleh versioned Vite assets
```

### C.4 Monitor System Resources

```bash
# Disk space
df -h
du -sh storage/ public/ vendor/ node_modules/

# Memory
free -h

# CPU & Processes
top -bn1 | head -20
htop  # Jika terinstall

# Service status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
sudo supervisorctl status

# Check MySQL connections
mysqladmin -u root -p processlist

# Check listening ports
sudo netstat -tlnp
# atau
sudo ss -tlnp

# Check disk usage by directory
ncdu /var/www/telkom --exclude node_modules --exclude .git
```

### C.5 Database Backup & Restore

```bash
# ── Backup ──────────────────────────────────────────────────────────────

# Backup manual
BACKUP_DIR="/var/www/telkom/storage/backups"
mkdir -p "$BACKUP_DIR"
BACKUP_FILE="$BACKUP_DIR/db_backup_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u telkom_user -p telkom_db > "$BACKUP_FILE"
echo "Backup tersimpan: $BACKUP_FILE"

# Backup semua database
mysqldump -u root -p --all-databases > /root/all_databases_$(date +%Y%m%d).sql

# ── Automasi Backup Harian via Cron ─────────────────────────────────────

# Tambah di crontab (crontab -e):
# Backup jam 2:00 pagi setiap hari
0 2 * * * mysqldump -u telkom_user -p'PASSWORD' telkom_db | gzip > /var/www/telkom/storage/backups/db_$(date +\%Y\%m\%d).sql.gz

# Cleanup backup lama (> 30 hari)
0 3 * * * find /var/www/telkom/storage/backups -name "db_backup_*.sql" -mtime +30 -delete

# ── Restore ─────────────────────────────────────────────────────────────

# Restore dari .sql
mysql -u telkom_user -p telkom_db < /path/to/backup.sql

# Restore dari .sql.gz
gunzip < /path/to/backup.sql.gz | mysql -u telkom_user -p telkom_db
```

### C.6 Maintenance Mode

```bash
# Aktifkan maintenance mode
php artisan down --refresh=15 --retry=60

# Aktifkan dengan pesan custom
php artisan down --message="Server sedang dalam pemeliharaan. Silakan kembali 5 menit lagi."

# Aktifkan dengan allowed IPs (untuk admin)
php artisan down --allow=127.0.0.1 --allow=YOUR_IP_ADDRESS

# Matikan maintenance mode
php artisan up
```

### C.7 File & Storage Management

```bash
# Cek ukuran storage
du -sh storage/app/public/
du -sh storage/logs/

# Cleanup foto tamu lama (opsional)
find storage/app/public/guest-photos -type f -mtime +90 -delete

# Cleanup log Laravel (> 30 hari)
find storage/logs -name "*.log" -mtime +30 -delete

# Cleanup view cache (> 7 hari)
find storage/framework/views -name "*.php" -mtime +7 -delete

# Cleanup backup database (> 7 hari)
find storage/backups -name "db_backup_*.sql" -mtime +7 -delete

# Cleanup expired sessions (jika session driver database)
php artisan session:gc
```

---

## D. Troubleshooting

### D.1 Common Error Fixes

```bash
# ── "500 Internal Server Error" ────────────────────────────────────────

# Cek log error
tail -50 storage/logs/laravel.log

# Biasanya: permission, .env salah, atau cache corrupt
php artisan config:clear && php artisan cache:clear
sudo chown -R www-data:www-data storage bootstrap/cache

# ── "APP_KEY not set" ─────────────────────────────────────────────────

php artisan key:generate

# ── "No application encryption key has been specified" ─────────────────

php artisan key:generate
php artisan config:cache  # Rebuild config cache

# ── "Database connection refused" ──────────────────────────────────────

# Cek MySQL berjalan
sudo systemctl status mysql
sudo systemctl start mysql

# Cek .env DB config
grep DB_ .env

# ── "View not found" ──────────────────────────────────────────────────

php artisan view:clear
php artisan view:cache

# ── "Route not defined" ───────────────────────────────────────────────

php artisan route:clear
php artisan route:cache

# ── "Class not found" ─────────────────────────────────────────────────

composer dump-autoload
composer install --no-dev --optimize-autoloader

# ── "Token Mismatch" ──────────────────────────────────────────────────

php artisan config:clear
# Jika pakai load balancer, set SESSION_DOMAIN di .env
```

### D.2 Permission Issues

```bash
# ── "Permission denied" pada storage/ ──────────────────────────────────

# Fix ownership
sudo chown -R www-data:www-data storage bootstrap/cache

# Fix permissions
chmod -R 775 storage bootstrap/cache
chmod -R o-w storage  # Remove write for others

# ── "Permission denied" pada public/assets_* ───────────────────────────

find public/assets_* -type d -exec chmod 755 {} \;
find public/assets_* -type f -exec chmod 644 {} \;

# ── "403 Forbidden" pada halaman ──────────────────────────────────────

# Cek Nginx config
sudo nginx -t

# Cek root directory
ls -la /var/www/telkom/public/

# Fix permission publik
chmod 755 public
find public -type f -name "*.html" -o -name "*.css" -o -name "*.js" | xargs chmod 644

# ── "Unable to create configured storage directory" ────────────────────

mkdir -p storage/app/public
mkdir -p storage/app/public/guest-photos  # Untuk Buku Tamu
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
chmod -R 775 storage
chown -R www-data:www-data storage
```

### D.3 Symlink Issues

```bash
# ── Storage link tidak ada ─────────────────────────────────────────────

# Buat symlink
php artisan storage:link

# Jika symlink sudah ada tapi broken
ls -la public/storage  # Cek apakah symlink exist
rm -f public/storage    # Hapus broken symlink
php artisan storage:link  # Buat ulang

# ── Storage link error "Target directory already exists" ────────────────

rm -rf public/storage
php artisan storage:link

# ── Vite manifest not found ────────────────────────────────────────────

# Rebuild assets
npm ci
npm run build

# Cek public/build/ directory
ls -la public/build/

# ── Composer vendor symlink issue ──────────────────────────────────────

composer dump-autoload
composer install --no-dev --optimize-autoloader
```

### D.4 Queue Worker Issues

```bash
# ── Queue worker berhenti ─────────────────────────────────────────────

# Cek status
sudo supervisorctl status

# Restart worker
sudo supervisorctl restart "laravel-worker:*"

# Cek log worker
tail -50 storage/logs/worker.log

# ── "Max tries exceeded" ──────────────────────────────────────────────

# Reset failed jobs
php artisan queue:flush

# Atau cek failed_jobs table
php artisan tinker --execute="dd(DB::table('failed_jobs')->count())"
```

### D.5 Disk Space Issues

```bash
# ── Cek disk usage ────────────────────────────────────────────────────

df -h
du -sh /* | sort -rh | head -10

# ── Cleanup agresif ───────────────────────────────────────────────────

# Hapus node_modules (bisa reinstall)
rm -rf node_modules

# Hapus log lama
find storage/logs -name "*.log" -mtime +7 -delete

# Hapus cache framework
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# Hapus backup lama
find storage/backups -name "*.sql" -mtime +3 -delete

# Hapus vendor (reinstall)
rm -rf vendor
composer install --no-dev --optimize-autoloader --no-interaction

# ── Cleanup MySQL ──────────────────────────────────────────────────────

# Optimize tables
mysqlcheck -u root -p --optimize telkom_db

# Hapus orphaned sessions
php artisan session:gc
```

### D.6 PHP & Composer Issues

```bash
# ── PHP version mismatch ──────────────────────────────────────────────

php -v
# Pastikan 8.2+

# Jika ada beberapa versi PHP
update-alternatives --config php

# ── Composer "require php ^8.2" error ──────────────────────────────────

# Pastikan pakai PHP 8.2+
php -v

# Jika perlu install PHP 8.2
sudo apt install php8.2-cli

# ── mbstring error saat optimize:clear ─────────────────────────────────

# Gunakan perintah individual (bukan optimize:clear)
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan event:clear
# JANGAN: php artisan optimize:clear  # Bisa error mbstring

# ── Vite build error ──────────────────────────────────────────────────

rm -rf node_modules
npm ci
npm run build
```

---

## 📋 Quick Reference

### One-Liner Commands

```bash
# Full update (recommended)
bash update.sh --theme telkom

# Quick cache clear & rebuild
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache

# Quick permission fix
sudo chown -R www-data:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache

# Check everything
sudo systemctl status nginx php8.2-fpm mysql && sudo supervisorctl status

# Database backup one-liner
mysqldump -u telkom_user -p telkom_db | gzip > storage/backups/db_$(date +%Y%m%d_%H%M%S).sql.gz

# Tail all logs
tail -f storage/logs/laravel.log &
tail -f storage/logs/worker.log &
```

### Environment Variables Per Theme

| Variable | Telkom | MAUDU |
|----------|--------|-------|
| `APP_NAME` | SMK Telekomunikasi Darul Ulum | Maudu Rejoso |
| `APP_URL` | https://smktelekomunikasidu.sch.id | https://maudu-rejoso.sch.id |
| `DB_DATABASE` | telkom_db | sekolah |
| `DB_USERNAME` | telkom_user | sekolah |
| `DEFAULT_THEME` | telkom | maudu |
| `ATTENDANCE_EXPORT_INSTITUTION` | SMK Telekomunikasi Darul Ulum | Maudu Rejoso |

### Service Ports

| Service | Port | Config |
|---------|------|--------|
| Nginx | 80 (HTTP), 443 (HTTPS) | `/etc/nginx/sites-available/telkom` |
| PHP-FPM | unix socket | `/etc/php/8.2/fpm/pool.d/www.conf` |
| MySQL | 3306 | `/etc/mysql/mysql.conf.d/mysqld.cnf` |
| Supervisor | - | `/etc/supervisor/conf.d/laravel-worker.conf` |

---

## 🔗 Referensi

- [`deploy.sh`](../deploy.sh) — Script setup awal pertama kali
- [`update.sh`](../update.sh) — Script update incremental
- [`.env.production.telkom`](../.env.production.telkom) — Template env Telkom
- [`.env.production.maudu`](../.env.production.maudu) — Template env MAUDU
- [`plans/theme-system-refactoring.md`](theme-system-refactoring.md) — Panduan menambah tema baru
