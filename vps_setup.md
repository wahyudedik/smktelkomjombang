# 🖥️ Setup Production (VPS Ubuntu)

Panduan lengkap untuk setup dan deploy aplikasi IG to Web di VPS Ubuntu.

## 📋 Persyaratan Server

- **OS**: Ubuntu 20.04 LTS atau 22.04 LTS
- **RAM**: Minimum 2GB (Recommended 4GB+)
- **Storage**: Minimum 20GB
- **CPU**: 2 cores atau lebih
- **Domain**: Domain yang sudah pointing ke IP server

---

## 🚀 Instalasi Server (VPS Ubuntu)

### 1. Update System

```bash
# Login sebagai root
ssh root@your-server-ip

# Update package list
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y software-properties-common curl wget git unzip
```

### 2. Setup Firewall

```bash
# Enable UFW firewall
sudo ufw enable

# Allow SSH (IMPORTANT!)
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Check status
sudo ufw status
```

### 3. Install PHP 8.2 dan Extensions

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan extensions
sudo apt install -y php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-gd \
    php8.2-intl \
    php8.2-soap \
    php8.2-readline \
    php8.2-opcache \
    php8.2-tokenizer

# Verify PHP installation
php -v

# Configure PHP
sudo nano /etc/php/8.2/fpm/php.ini
```

Edit PHP configuration:
```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 256M
max_execution_time = 300
date.timezone = Asia/Jakarta
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### 4. Install Composer

```bash
# Download Composer
curl -sS https://getcomposer.org/installer | php

# Move to global location
sudo mv composer.phar /usr/local/bin/composer

# Verify installation
composer --version

# Make composer globally accessible
sudo chmod +x /usr/local/bin/composer
```

### 5. Install Node.js 18.x

```bash
# Install Node.js 18.x LTS
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify installation
node --version
npm --version
```

### 6. Install MySQL 8.0

```bash
# Install MySQL Server
sudo apt install mysql-server -y

# Secure MySQL installation
sudo mysql_secure_installation
```

Jawab prompts:
- Set root password: **YES**
- Remove anonymous users: **YES**
- Disallow root login remotely: **YES**
- Remove test database: **YES**
- Reload privilege tables: **YES**

Create database and user:
```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user
CREATE DATABASE ig_to_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ig_to_web_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON ig_to_web.* TO 'ig_to_web_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Optimize MySQL:
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Add under `[mysqld]`:
```ini
max_connections = 200
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

### 7. Install Nginx

```bash
# Install Nginx
sudo apt install nginx -y

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Verify Nginx is running
sudo systemctl status nginx
```

### 8. Install SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate (setelah domain pointing)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal test
sudo certbot renew --dry-run

# Setup auto-renewal cron job
sudo crontab -e
# Add: 0 3 * * * certbot renew --quiet
```

---

## 📦 Deploy Aplikasi

### 1. Clone Repository

```bash
# Create web directory
sudo mkdir -p /var/www/ig-to-web

# Set ownership
sudo chown -R $USER:$USER /var/www/ig-to-web

# Clone repository
cd /var/www
git clone https://github.com/your-username/ig-to-web.git
cd ig-to-web
```

### 2. Install Dependencies

```bash
# Install PHP dependencies (production)
composer install --optimize-autoloader --no-dev

# Note: minishlink/web-push sudah terinstall di composer.json untuk Push Notifications

# Install Node dependencies
npm install

# Build assets for production
npm run build
```

### 3. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file
nano .env
```

Configure `.env` untuk production:
```env
APP_NAME="IG to Web"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ig_to_web
DB_USERNAME=ig_to_web_user
DB_PASSWORD=your_strong_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Instagram Configuration
INSTAGRAM_APP_ID=your_instagram_app_id
INSTAGRAM_APP_SECRET=your_instagram_app_secret
INSTAGRAM_REDIRECT_URI=https://yourdomain.com/instagram/callback
INSTAGRAM_WEBHOOK_TOKEN=your_webhook_token

# VAPID Keys untuk Push Notifications
VAPID_PUBLIC_KEY=your_vapid_public_key
VAPID_PRIVATE_KEY=your_vapid_private_key
VAPID_SUBJECT=https://yourdomain.com
```

**Cara Generate VAPID Keys:**

**Option 1: Menggunakan Online Tool (Recommended untuk Production)**
1. Buka: https://vapidkeys.com/
2. Klik tombol "Generate"
3. Salin Public Key dan Private Key
4. Tambahkan ke file `.env` seperti contoh di atas
5. Set `VAPID_SUBJECT` ke URL domain Anda (contoh: `https://yourdomain.com`)

**Option 2: Menggunakan Artisan Command (Jika library terinstall)**
```bash
# Install library terlebih dahulu (opsional)
composer require minishlink/web-push

# Generate keys menggunakan artisan command
php artisan push:vapid-keys --generate
```

**Setelah Generate Keys:**
1. Copy Public Key dan Private Key ke file `.env`
2. Pastikan `VAPID_SUBJECT` sesuai dengan `APP_URL` (contoh: `https://yourdomain.com`)
   - **PENTING**: `VAPID_SUBJECT` harus menggunakan format URL yang valid
3. Clear dan cache config:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```
4. Verifikasi keys sudah ter-load:
   ```bash
   php artisan push:vapid-keys --show
   ```
   Output harus menampilkan Public Key dan Private Key yang sudah di-set

### 4. Run Migrations

```bash
# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Verifikasi migration push_subscriptions sudah dibuat (untuk Push Notifications)
php artisan migrate:status | grep push_subscriptions

# Create storage link
php artisan storage:link

# CRITICAL: Clear semua cache SEBELUM cache ulang (mencegah error service provider)
php artisan optimize:clear

# Regenerate composer autoload (untuk memastikan hanya package yang terinstall)
composer dump-autoload --optimize

# Rediscover packages (hanya register packages yang benar-benar terinstall)
php artisan package:discover --ansi

# Cache configuration (setelah clear dan rediscover)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

**Catatan:** Migration `push_subscriptions` akan dibuat otomatis untuk menyimpan subscription push notifications dari user.

---

## 🔄 Update/Deploy Workflow (Sangat Penting!)

**Setiap kali melakukan `git pull` atau update code, WAJIB jalankan workflow berikut untuk mencegah error:**

### Workflow Update Aplikasi

```bash
# 1. Masuk ke direktori aplikasi
cd /var/www/ig-to-web

# 2. Backup database (opsional tapi recommended)
mysqldump -u ig_to_web_user -p ig_to_web > backup_$(date +%Y%m%d_%H%M%S).sql

# 3. Pull latest code dari GitHub
git pull origin main

# 4. CRITICAL: Hapus cache bootstrap yang corrupt (jika ada)
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/config.php

# 5. Clear semua Laravel cache SEBELUM install dependencies
php artisan optimize:clear

# 6. Install/Update dependencies
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize

# 7. Rediscover packages (hanya register packages yang terinstall)
php artisan package:discover --ansi

# 8. Build assets (jika ada perubahan di frontend)
npm install
npm run build

# 9. Run migrations (jika ada migration baru)
php artisan migrate --force

# 10. Create storage link (jika belum ada)
php artisan storage:link

# 11. Cache ulang untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 12. Restart queue worker (jika menggunakan queue)
sudo systemctl restart laravel-worker

# 13. Verifikasi aplikasi masih berjalan
php artisan about
curl -I https://yourdomain.com
```

### ⚠️ ALWAYS DO THIS After Git Pull:

**Workflow Singkat (Quick Update):**
```bash
cd /var/www/ig-to-web
git pull origin main
rm -f bootstrap/cache/*.php
php artisan optimize:clear
composer dump-autoload --optimize
php artisan package:discover --ansi
npm run build
php artisan migrate --force
php artisan optimize
sudo systemctl restart laravel-worker
```

**Workflow Lengkap (Full Update):**
```bash
cd /var/www/ig-to-web
git pull origin main
rm -f bootstrap/cache/*.php
php artisan optimize:clear
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
php artisan about
```

### 🚨 Mengapa Workflow Ini Penting?

1. **`rm -f bootstrap/cache/*.php`**: Menghapus cache yang mungkin menyimpan service provider dari package yang sudah dihapus
2. **`php artisan optimize:clear`**: Menghapus semua cache Laravel sebelum regenerate
3. **`composer dump-autoload --optimize`**: Regenerate autoload berdasarkan package yang benar-benar terinstall
4. **`php artisan package:discover --ansi`**: Rediscover dan register hanya package yang ada di `composer.json` dan terinstall
5. **Baru kemudian cache ulang**: Setelah semua clear dan regenerate, baru cache untuk production

**Tanpa workflow ini, error seperti "PhikiServiceProvider not found" akan terus muncul!**

### 5. Set Permissions

```bash
# Set ownership to www-data
sudo chown -R www-data:www-data /var/www/ig-to-web

# Set proper permissions
sudo chmod -R 755 /var/www/ig-to-web
sudo chmod -R 775 /var/www/ig-to-web/storage
sudo chmod -R 775 /var/www/ig-to-web/bootstrap/cache
```

### 6. Configure Nginx

```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/ig-to-web
```

Add configuration:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/ig-to-web/public;

    # SSL Configuration (will be auto-configured by Certbot)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json application/javascript;

    index index.php index.html;
    charset utf-8;

    # Increase max upload size
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { 
        access_log off; 
        log_not_found off; 
    }
    
    location = /robots.txt  { 
        access_log off; 
        log_not_found off; 
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        
        # Increase timeout for long-running processes
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/ig-to-web /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### 7. Setup Queue Worker

```bash
# Create systemd service
sudo nano /etc/systemd/system/laravel-worker.service
```

Add content:
```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/ig-to-web/artisan queue:work --sleep=3 --tries=3 --timeout=90

[Install]
WantedBy=multi-user.target
```

Enable and start:
```bash
# Reload systemd
sudo systemctl daemon-reload

# Enable service
sudo systemctl enable laravel-worker

# Start service
sudo systemctl start laravel-worker

# Check status
sudo systemctl status laravel-worker
```

### 8. Setup Push Notifications (Lengkap)

#### Step 1: Generate dan Konfigurasi VAPID Keys

Pastikan VAPID keys sudah di-generate dan di-set di `.env` (lihat section 3 di atas).

#### Step 2: Verifikasi Setup

```bash
# 1. Verifikasi VAPID keys terkonfigurasi
php artisan push:vapid-keys --show

# 2. Verifikasi database migration untuk push_subscriptions sudah dibuat
php artisan migrate:status | grep push_subscriptions
# Harus menunjukkan: [Ran] 2025_10_31_060221_create_push_subscriptions_table

# 3. Test endpoint VAPID key (harus return JSON dengan publicKey)
curl -H "Accept: application/json" https://yourdomain.com/admin/push/vapid-key
# Harus return: {"success":true,"publicKey":"..."}

# 4. Verifikasi package minishlink/web-push terinstall
composer show minishlink/web-push
```

#### Step 3: Verifikasi Service Worker

Service Worker (`public/sw.js`) sudah otomatis ter-register saat user pertama kali mengakses aplikasi. Tidak perlu setup manual.

#### Step 4: Test di Browser

Setelah deploy, test push notifications:

1. **Login ke aplikasi** di browser (Chrome/Firefox/Edge modern)
2. **Buka browser console** (F12 → Console tab)
3. **Cek service worker** terdaftar:
   - Console harus menampilkan: `SW registered: ServiceWorkerRegistration {...}`
4. **Cek push subscription**:
   - Console harus menampilkan: `Subscription saved to server`
   - Browser akan meminta permission untuk notifications (allow)
5. **Verifikasi di database**:
   ```bash
   mysql -u ig_to_web_user -p
   USE ig_to_web;
   SELECT * FROM push_subscriptions;
   ```
   Harus ada data subscription dengan `user_id`, `endpoint`, dll.

#### Step 5: Test Mengirim Notifikasi

1. Login sebagai admin/superadmin
2. Buka halaman yang memiliki notifikasi (misalnya: Notification Center)
3. Trigger notifikasi (misalnya: buat data baru, approve sesuatu)
4. Pastikan notifikasi muncul di browser

**Catatan Penting:**
- ✅ Push notifications hanya bekerja di **HTTPS** (atau localhost untuk development)
- ✅ Browser harus support Web Push API (Chrome 42+, Firefox 44+, Edge 17+)
- ✅ Service Worker otomatis ter-register saat user pertama kali load aplikasi
- ✅ User harus **allow notification permission** saat pertama kali (browser akan prompt)
- ✅ VAPID_SUBJECT harus sesuai dengan APP_URL (contoh: https://yourdomain.com)
- ✅ Semua notifikasi sistem akan otomatis terkirim sebagai push notification

### 9. Setup Scheduler (Cron) - PENTING!

```bash
# Edit crontab for www-data user
sudo crontab -u www-data -e

# Add Laravel scheduler
* * * * * cd /var/www/ig-to-web && php artisan schedule:run >> /dev/null 2>&1

# Verify cron is added
sudo crontab -u www-data -l
```

**Scheduler akan menjalankan:**
- `instagram:sync` - Setiap 5 menit (auto-sync Instagram posts)
- `instagram:refresh-token` - Monthly (refresh long-lived token)

**Test Scheduler:**
```bash
# Run scheduler manually
sudo -u www-data php /var/www/ig-to-web/artisan schedule:run

# Check if Instagram sync works
sudo -u www-data php /var/www/ig-to-web/artisan instagram:sync --force

# Monitor logs
tail -f /var/www/ig-to-web/storage/logs/laravel.log
```

### 10. Setup Log Rotation

```bash
# Create log rotation config
sudo nano /etc/logrotate.d/laravel
```

Add:
```
/var/www/ig-to-web/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload php8.2-fpm > /dev/null 2>&1
    endscript
}
```

### 11. Setup Backup Script

```bash
# Create backup script
sudo nano /usr/local/bin/backup-ig-to-web.sh
```

Add:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/ig-to-web"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u ig_to_web_user -p'your_password' ig_to_web | gzip > $BACKUP_DIR/database_$DATE.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/ig-to-web

# Delete old backups (keep last 7 days)
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

Make executable and setup cron:
```bash
# Make executable
sudo chmod +x /usr/local/bin/backup-ig-to-web.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-ig-to-web.sh
```

---

## 🔒 Security Hardening

### 1. Setup Fail2Ban

```bash
# Install Fail2Ban
sudo apt install fail2ban -y

# Configure for Nginx
sudo nano /etc/fail2ban/jail.local
```

Add:
```ini
[nginx-http-auth]
enabled = true

[nginx-noscript]
enabled = true

[nginx-badbots]
enabled = true

[nginx-noproxy]
enabled = true
```

```bash
# Restart Fail2Ban
sudo systemctl restart fail2ban
```

### 2. Disable Root Login

```bash
# Edit SSH config
sudo nano /etc/ssh/sshd_config

# Set: PermitRootLogin no
# Set: PasswordAuthentication no (setelah setup SSH key)

# Restart SSH
sudo systemctl restart sshd
```

---

## 🔄 Deployment Script (Otomatis)

Untuk memudahkan deployment, buat script otomatis:

```bash
# Buat deployment script
nano /var/www/ig-to-web/deploy.sh
```

Tambahkan isi script berikut:
```bash
#!/bin/bash

set -e  # Exit on error

echo "🚀 Starting deployment..."

cd /var/www/ig-to-web

# Pull latest code
echo "📥 Pulling latest code..."
git pull origin main

# Remove corrupt cache (CRITICAL untuk mencegah error service provider)
echo "🧹 Clearing bootstrap cache..."
rm -f bootstrap/cache/*.php

# Clear Laravel cache
echo "🗑️ Clearing Laravel cache..."
php artisan optimize:clear

# Update dependencies
echo "📦 Updating dependencies..."
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize

# Rediscover packages (CRITICAL untuk mencegah error)
echo "🔍 Rediscovering packages..."
php artisan package:discover --ansi

# Build assets
echo "🎨 Building assets..."
npm install
npm run build

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Restart queue worker
echo "🔄 Restarting queue worker..."
sudo systemctl restart laravel-worker

# Verify
echo "✅ Verifying application..."
php artisan about

echo "🎉 Deployment completed successfully!"
```

Beri permission execute:
```bash
chmod +x /var/www/ig-to-web/deploy.sh
```

**Cara menggunakan script:**
```bash
cd /var/www/ig-to-web
./deploy.sh
```

**Atau jalankan manual step-by-step mengikuti workflow di section "Update/Deploy Workflow" di atas.**

---

## 🛠️ Maintenance Commands

```bash
# Clear cache (lengkap)
php artisan optimize:clear
rm -f bootstrap/cache/*.php

# Clear cache (individual)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerate dan rediscover (WAJIB setelah clear cache)
composer dump-autoload --optimize
php artisan package:discover --ansi

# Optimize for production (setelah regenerate)
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Instagram commands
php artisan instagram:sync --force          # Force sync posts
php artisan instagram:refresh-token         # Refresh token
php artisan schedule:list                   # List scheduled tasks

# Push Notifications commands
php artisan push:vapid-keys --show          # Show current VAPID keys
php artisan push:vapid-keys --generate     # Generate new VAPID keys (jika library terinstall)

# View logs
tail -f storage/logs/laravel.log
tail -f storage/logs/laravel.log | grep -i instagram  # Instagram only
tail -f storage/logs/laravel.log | grep -i push       # Push notifications only

# Check queue status
php artisan queue:failed
php artisan queue:retry all

# Monitor system
htop
df -h
free -m
```

---

## 📊 Monitoring Tips

**Install Monitoring Tools:**
```bash
sudo apt install htop iotop nethogs -y
```

**Performance Optimization:**
1. Enable OPcache untuk PHP
2. Setup Redis untuk cache dan sessions
3. Use CDN untuk static assets
4. Optimize database queries
5. Enable Gzip compression
6. Setup load balancing untuk high traffic

---

## ✅ Verifikasi Aplikasi Berjalan Normal

Setelah semua langkah di atas, verifikasi dengan:

1. **Test Website:**
   ```bash
   curl -I https://yourdomain.com
   ```
   Harus return `200 OK`

2. **Test Database Connection:**
   ```bash
   php artisan tinker
   # Jalankan: DB::connection()->getPdo();
   ```

3. **Test Queue Worker:**
   ```bash
   sudo systemctl status laravel-worker
   ```

4. **Test Scheduler:**
   ```bash
   sudo -u www-data php artisan schedule:run
   ```

5. **Test SSL Certificate:**
   - Akses https://yourdomain.com di browser
   - Pastikan tidak ada warning SSL

6. **Test Push Notifications:**
   ```bash
   # Verifikasi VAPID keys sudah terkonfigurasi
   php artisan push:vapid-keys --show
   ```
   - Login ke aplikasi di browser
   - Buka browser console (F12), pastikan tidak ada error
   - Cek apakah service worker terdaftar (lihat console log: "SW registered")
   - Verifikasi push subscription berhasil (lihat console log: "Subscription saved to server")
   - Test dengan mengirim notifikasi dari admin panel
   - Pastikan browser meminta permission untuk notifications
   - Check database: `SELECT * FROM push_subscriptions;` (harus ada data)

7. **Test Instagram Sync:**
   ```bash
   php artisan instagram:sync --force
   ```

---

## 🐛 Troubleshooting

**Problem:** Website tidak bisa diakses
- Check Nginx status: `sudo systemctl status nginx`
- Check Nginx config: `sudo nginx -t`
- Check firewall: `sudo ufw status`

**Problem:** Database connection error
- Check MySQL status: `sudo systemctl status mysql`
- Verify credentials di `.env`
- Test connection: `mysql -u ig_to_web_user -p`

**Problem:** Permission denied
```bash
sudo chown -R www-data:www-data /var/www/ig-to-web
sudo chmod -R 755 /var/www/ig-to-web
sudo chmod -R 775 /var/www/ig-to-web/storage
```

**Problem:** Queue worker tidak jalan
```bash
sudo systemctl restart laravel-worker
sudo journalctl -u laravel-worker -f
```

**Problem:** Class "Phiki\Adapters\Laravel\PhikiServiceProvider" not found atau service provider class not found lainnya
- **Penyebab**: Cache bootstrap masih menyimpan service provider dari package yang sudah dihapus atau tidak terinstall
- **Solusi**:
```bash
cd /var/www/ig-to-web

# 1. Hapus semua cache bootstrap
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php
rm -f bootstrap/cache/config.php

# 2. Clear semua Laravel cache
php artisan optimize:clear

# 3. Regenerate cache (pastikan composer dependencies sudah benar)
composer dump-autoload --optimize
php artisan package:discover --ansi

# 4. Cache ulang untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

**Catatan Penting:**
- Pastikan semua package di `composer.json` sudah terinstall: `composer install --optimize-autoloader --no-dev`
- Jangan commit file `bootstrap/cache/*.php` ke git (harus di `.gitignore`)
- Setelah deploy atau update code, selalu clear cache: `php artisan optimize:clear`

**Problem:** Push notifications tidak bekerja
- Pastikan VAPID keys sudah di-set di `.env` dengan format yang benar
- Verify keys: `php artisan push:vapid-keys --show`
- Pastikan `VAPID_SUBJECT` sesuai dengan `APP_URL` (harus https://yourdomain.com)
- Clear config: `php artisan config:clear && php artisan config:cache`
- Check browser console untuk error (F12 → Console tab)
- Verify service worker terdaftar (Application tab → Service Workers)
- Pastikan browser mendukung push notifications (Chrome, Firefox, Edge modern)
- Check HTTPS: Push notifications hanya bekerja di HTTPS (atau localhost)
- Verify package terinstall: `composer show minishlink/web-push`
- Check database: Pastikan table `push_subscriptions` sudah dibuat (migration sudah jalan)
- Test endpoint: `curl -I https://yourdomain.com/admin/push/vapid-key` (harus return 200 OK)

---

## 📞 Support

Jika ada masalah, cek:
- Log files: `tail -f /var/www/ig-to-web/storage/logs/laravel.log`
- Nginx error log: `sudo tail -f /var/log/nginx/error.log`
- PHP-FPM log: `sudo tail -f /var/log/php8.2-fpm.log`

---

**© 2025 IG to Web. All rights reserved.**

