# VPS SETUP - ABSENSI ZKTECO (PRODUCTION DEPLOYMENT)

**Panduan lengkap untuk deploy aplikasi Absensi ZKTeco ke VPS.**  
**JELAS, LANGSUNG, dan STEP-BY-STEP untuk pemula.**

---

## 📋 PERSYARATAN

- **OS**: Ubuntu 20.04 LTS atau 22.04 LTS
- **RAM**: Minimum 2GB (Recommended 4GB+)
- **Storage**: Minimum 20GB
- **CPU**: 2 cores atau lebih
- **Domain**: Domain yang sudah pointing ke IP VPS
- **SSH Access**: Akses root ke VPS

---

## 🚀 STEP 1: SETUP SERVER UBUNTU (30 MENIT)

### 1.1 Update System

```bash
# Login ke VPS
ssh root@your-vps-ip

# Update package list
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y software-properties-common curl wget git unzip
```

### 1.2 Setup Firewall

```bash
# Enable UFW firewall
sudo ufw enable

# Allow SSH (PENTING!)
sudo ufw allow 22/tcp

# Allow HTTP dan HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Verifikasi
sudo ufw status
```

### 1.3 Install PHP 8.2 dan Extensions

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan extensions yang dibutuhkan
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
    php8.2-opcache

# Verifikasi PHP
php -v

# Configure PHP
sudo nano /etc/php/8.2/fpm/php.ini
```

Edit file dan cari/ubah:
```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 256M
max_execution_time = 300
date.timezone = Asia/Jakarta
```

Simpan (Ctrl+X → Y → Enter)

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### 1.4 Install Composer

```bash
# Download Composer
curl -sS https://getcomposer.org/installer | php

# Move ke global location
sudo mv composer.phar /usr/local/bin/composer

# Verifikasi
composer --version
```

### 1.5 Install Node.js 18.x

```bash
# Install Node.js 18.x LTS
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verifikasi
node --version
npm --version
```

### 1.6 Install MySQL 8.0

```bash
# Install MySQL Server
sudo apt install mysql-server -y

# Secure MySQL
sudo mysql_secure_installation
```

Jawab prompts:
- Set root password: **YES**
- Remove anonymous users: **YES**
- Disallow root login remotely: **YES**
- Remove test database: **YES**
- Reload privilege tables: **YES**

Buat database dan user:
```bash
# Login ke MySQL
sudo mysql -u root -p

# Jalankan commands ini:
CREATE DATABASE absensi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'absensi_user'@'localhost' IDENTIFIED BY 'password-yang-kuat-12345';
GRANT ALL PRIVILEGES ON absensi_db.* TO 'absensi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Optimize MySQL:
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Cari section `[mysqld]` dan tambahkan:
```ini
max_connections = 200
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

### 1.7 Install Nginx

```bash
# Install Nginx
sudo apt install nginx -y

# Start dan enable
sudo systemctl start nginx
sudo systemctl enable nginx

# Verifikasi
sudo systemctl status nginx
```

### 1.8 Install SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate (setelah domain pointing ke VPS)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## 📦 STEP 2: DEPLOY APLIKASI ABSENSI (30 MENIT)

### 2.1 Clone Repository

```bash
# Create web directory
sudo mkdir -p /var/www/absensi

# Set ownership
sudo chown -R $USER:$USER /var/www/absensi

# Clone repository
cd /var/www
git clone https://github.com/your-username/your-repo.git absensi
cd absensi
```

### 2.2 Install Dependencies

```bash
# Install PHP dependencies (production)
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build assets
npm run build
```

### 2.3 Setup Environment File

```bash
# Copy .env
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env
nano .env
```

Konfigurasi `.env` untuk production:

```env
APP_NAME="Absensi ZKTeco"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=absensi_user
DB_PASSWORD=password-yang-kuat-12345

# Attendance Configuration (PENTING!)
ATTENDANCE_ICLOCK_SECRET=buat-token-random-panjang-minimal-32-karakter
ATTENDANCE_REQUIRE_USER_IDENTITY=true
ATTENDANCE_REQUIRE_USER_VERIFIED=false

# Session & Queue
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**PENTING:** Ganti `ATTENDANCE_ICLOCK_SECRET` dengan token random yang panjang. Ini adalah password untuk device berkomunikasi dengan server.

### 2.4 Run Migrations

```bash
# Run migrations
php artisan migrate --force

# Run seeders (opsional, untuk data contoh)
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Clear cache
php artisan optimize:clear

# Regenerate autoload
composer dump-autoload --optimize

# Rediscover packages
php artisan package:discover --ansi

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 2.5 Set Permissions

```bash
# Set ownership ke www-data
sudo chown -R www-data:www-data /var/www/absensi

# Set permissions
sudo chmod -R 755 /var/www/absensi
sudo chmod -R 775 /var/www/absensi/storage
sudo chmod -R 775 /var/www/absensi/bootstrap/cache
```

### 2.6 Configure Nginx

```bash
# Create Nginx config
sudo nano /etc/nginx/sites-available/absensi
```

Paste konfigurasi ini:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect ke HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/absensi/public;

    # SSL (auto-configured by Certbot)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json application/javascript;

    index index.php index.html;
    charset utf-8;

    # Max upload size
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
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

Simpan (Ctrl+X → Y → Enter)

Enable site:
```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/absensi /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx config
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### 2.7 Setup Queue Worker

```bash
# Create systemd service
sudo nano /etc/systemd/system/laravel-worker.service
```

Paste:
```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/absensi/artisan queue:work --sleep=3 --tries=3 --timeout=90

[Install]
WantedBy=multi-user.target
```

Simpan dan enable:
```bash
# Reload systemd
sudo systemctl daemon-reload

# Enable service
sudo systemctl enable laravel-worker

# Start service
sudo systemctl start laravel-worker

# Verifikasi
sudo systemctl status laravel-worker
```

### 2.8 Setup Scheduler (WAJIB!)

```bash
# Edit crontab untuk www-data
sudo crontab -u www-data -e

# Tambahkan baris ini:
* * * * * cd /var/www/absensi && php artisan schedule:run >> /dev/null 2>&1

# Verifikasi
sudo crontab -u www-data -l
```

Scheduler akan menjalankan:
- `attendance:sync` - Setiap 5 menit (membuat rekap dari log)

---

## ✅ STEP 3: VERIFIKASI DEPLOYMENT (10 MENIT)

### 3.1 Test Website

```bash
# Test HTTPS
curl -I https://yourdomain.com

# Harus return: HTTP/2 200
```

### 3.2 Test Database

```bash
# Login ke MySQL
mysql -u absensi_user -p

# Jalankan:
USE absensi_db;
SHOW TABLES;
EXIT;
```

### 3.3 Test Scheduler

```bash
# Run scheduler manual
sudo -u www-data php /var/www/absensi/artisan schedule:run

# Check logs
tail -f /var/www/absensi/storage/logs/laravel.log
```

### 3.4 Test iClock Endpoint

```bash
# Test endpoint (harus return OK atau 403 jika token salah)
curl -I "https://yourdomain.com/iclock/cdata?SN=TEST&token=ATTENDANCE_ICLOCK_SECRET"
```

---

## 🔄 STEP 4: UPDATE/DEPLOY WORKFLOW

**Setiap kali ada update code, jalankan workflow ini:**

### Quick Update (Jika hanya ada perubahan kecil)

```bash
cd /var/www/absensi

# Pull latest code
git pull origin main

# Clear cache
php artisan optimize:clear
rm -f bootstrap/cache/*.php

# Regenerate
composer dump-autoload --optimize
php artisan package:discover --ansi

# Build assets (jika ada perubahan frontend)
npm run build

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Restart queue worker
sudo systemctl restart laravel-worker
```

### Full Update (Jika ada perubahan dependencies atau database)

```bash
cd /var/www/absensi

# Backup database (recommended)
mysqldump -u absensi_user -p absensi_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Pull latest code
git pull origin main

# Clear cache
php artisan optimize:clear
rm -f bootstrap/cache/*.php

# Update dependencies
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize
php artisan package:discover --ansi

# Build assets
npm install
npm run build

# Run migrations (jika ada migration baru)
php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Restart queue worker
sudo systemctl restart laravel-worker

# Verifikasi
php artisan about
```

---

## 🛠️ MAINTENANCE COMMANDS

```bash
# Clear cache (lengkap)
php artisan optimize:clear
rm -f bootstrap/cache/*.php

# Regenerate autoload
composer dump-autoload --optimize

# Rediscover packages
php artisan package:discover --ansi

# Optimize untuk production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# View logs
tail -f /var/www/absensi/storage/logs/laravel.log

# Check queue status
php artisan queue:failed
php artisan queue:retry all

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sudo systemctl restart laravel-worker
sudo systemctl restart mysql
```

---

## 🐛 TROUBLESHOOTING

### Website tidak bisa diakses

```bash
# Check Nginx status
sudo systemctl status nginx

# Check Nginx config
sudo nginx -t

# Check firewall
sudo ufw status

# Check logs
sudo tail -f /var/log/nginx/error.log
```

### Database connection error

```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u absensi_user -p -h 127.0.0.1

# Check .env credentials
cat /var/www/absensi/.env | grep DB_
```

### Permission denied

```bash
sudo chown -R www-data:www-data /var/www/absensi
sudo chmod -R 755 /var/www/absensi
sudo chmod -R 775 /var/www/absensi/storage
sudo chmod -R 775 /var/www/absensi/bootstrap/cache
```

### Class "ServiceProvider" not found

```bash
cd /var/www/absensi

# Clear cache
rm -f bootstrap/cache/*.php
php artisan optimize:clear

# Regenerate
composer dump-autoload --optimize
php artisan package:discover --ansi

# Cache ulang
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Queue worker tidak jalan

```bash
# Restart worker
sudo systemctl restart laravel-worker

# Check status
sudo systemctl status laravel-worker

# View logs
sudo journalctl -u laravel-worker -f
```

### Device tidak muncul di admin panel

```bash
# Check endpoint
curl -I "https://yourdomain.com/iclock/cdata?SN=TEST&token=YOUR_TOKEN"

# Check logs
tail -f /var/www/absensi/storage/logs/laravel.log | grep -i attendance

# Run scheduler manual
sudo -u www-data php /var/www/absensi/artisan schedule:run
```

---

## 📊 MONITORING

```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Monitor system
htop

# Monitor disk
df -h

# Monitor memory
free -m

# Monitor network
nethogs
```

---

## 🔒 SECURITY TIPS

1. **Ganti password MySQL** dengan password yang kuat
2. **Ganti ATTENDANCE_ICLOCK_SECRET** dengan token random yang panjang
3. **Enable firewall** dan hanya buka port yang dibutuhkan
4. **Setup SSH key** dan disable password login
5. **Enable SSL** (Let's Encrypt)
6. **Regular backups** database dan files
7. **Monitor logs** untuk aktivitas mencurigakan

---

## 📝 CHECKLIST DEPLOYMENT

- [ ] Server Ubuntu setup selesai
- [ ] PHP 8.2 dan extensions terinstall
- [ ] Composer dan Node.js terinstall
- [ ] MySQL database dan user dibuat
- [ ] Nginx terinstall dan dikonfigurasi
- [ ] SSL certificate aktif
- [ ] Repository di-clone
- [ ] Dependencies terinstall (composer + npm)
- [ ] .env dikonfigurasi dengan benar
- [ ] Migrations dijalankan
- [ ] Permissions sudah benar
- [ ] Queue worker berjalan
- [ ] Scheduler aktif (cron job)
- [ ] Website bisa diakses via HTTPS
- [ ] iClock endpoint bisa diakses
- [ ] Database connection OK
- [ ] Logs tidak ada error

---

## 🎉 SELESAI!

Aplikasi Absensi ZKTeco sudah siap di production.

**Langkah selanjutnya:**
1. Setup device MB20-VL (lihat: SETUP-LENGKAP.md)
2. Tambah user di admin panel
3. Sync user ke device
4. Enroll biometric
5. Test scan

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek troubleshooting di atas
2. Cek log: `tail -f /var/www/absensi/storage/logs/laravel.log`
3. Cek Nginx error: `sudo tail -f /var/log/nginx/error.log`
4. Cek PHP-FPM: `sudo tail -f /var/log/php8.2-fpm.log`

---

**Last Updated:** 2025-01-15  
**Status:** PRODUCTION READY
