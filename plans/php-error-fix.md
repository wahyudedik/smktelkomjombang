# 🔧 Fix: PHP Error `optimize:clear` di Server

## Error

```
PHP Warning:  Module "fileinfo" is already loaded in Unknown on line 0
PHP Warning:  Module "mbstring" is already loaded in Unknown on line 0
PHP Warning:  Module "zip" is already loaded in Unknown on line 0
php: symbol lookup error: /www/server/php/83/lib/php/extensions/no-debug-non-zts-20230831/mbstring.so: undefined symbol: php_pcre2_compile
```

## Analisis Masalah

### 1. Module Already Loaded (Warning)
Beberapa PHP extension dimuat **dua kali** — kemungkinan:
- Dimuat via `extension=` di `php.ini`
- **DAN** dimuat lagi via file di `/www/server/php/83/etc-php.d/` atau `/www/server/php/83/lib/php/conf.d/`

### 2. `undefined symbol: php_pcre2_compile` (Fatal Error)
Ini masalah **in-kompatibilitas versi**:
- `mbstring.so` di-compile against versi PCRE2 tertentu
- Tapi versi PCRE2 yang ter-install di server **berbeda**
- Biasanya terjadi setelah **PHP di-update** tapi extension **tidak di-recompile**

## Solusi

### Step 1: Cek Lokasi Extension Loading

```bash
# Cek apakah ada duplicate loading
php -i 2>&1 | grep "Scan this dir"
php -i 2>&1 | grep "Additional .ini"

# Cek file yang load mbstring
grep -r "mbstring" /www/server/php/83/etc/php.ini
grep -r "mbstring" /www/server/php/83/etc-php.d/
```

### Step 2: Fix Duplicate Loading

Jika ditemukan duplicate, **hapus salah satu** (yang di `php.ini` atau yang di `conf.d`):

```bash
# Backup dulu
cp /www/server/php/83/etc/php.ini /www/server/php/83/etc/php.ini.bak

# Cari dan komentari duplicate extension di php.ini
# Cari baris seperti:
# extension=mbstring.so
# extension=fileinfo.so
# extension=zip.so
# dan komentari (#) yang di php.ini, biarkan yang di conf.d
```

### Step 3: Fix mbstring PCRE2 Error

**Opsi A: Reinstall mbstring via aaPanel** (Paling Mudah)
```
aaPanel → PHP 8.3 → Install Extension → uncheck mbstring → Save → check mbstring → Save
```

**Opsi B: Reinstall via Command Line**
```bash
# Untuk server aaPanel/BRPanel
cd /www/server/php/83/bin
phpize
./configure --with-php-config=/www/server/php/83/bin/php-config
make && make install
```

**Opsi C: Update PCRE2**
```bash
# Cek versi PCRE2 saat ini
pcre2-config --version

# Jika menggunakan aaPanel, update PHP dari panel
aaPanel → Software Store → PHP 8.3 → Update/Reinstall
```

### Step 4: Restart PHP-FPM

```bash
# Restart PHP-FPM setelah perubahan
/etc/init.d/php-fpm-83 restart

# Atau via aaPanel
aaPanel → App Store → PHP 8.3 → Restart
```

### Step 5: Test

```bash
# Test PHP berjalan normal
php -v
php -m | grep mbstring

# Test artisan
php artisan optimize:clear
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## Alternatif: Jalankan Cache Clear Tanpa `optimize:clear`

Jika error PHP ini tidak bisa di-fix sekarang, `update.sh` sudah menjalankan cache clear secara terpisah dan berhasil:

```bash
# Ini sudah dilakukan oleh update.sh dan BERHASIL:
php artisan config:clear    # ✅
php artisan view:clear       # ✅  
php artisan route:clear      # ✅
php artisan event:clear      # ✅
```

Command `optimize:clear` hanya **gabungan** dari semua clear di atas + `php artisan clear-compiled`. Jadi website tetap berjalan normal.

## Root Cause

Server menggunakan **aaPanel/BRPanel** dengan PHP 8.3. Extension `mbstring` perlu di-reinstall karena:
1. PHP mungkin di-update tapi extension tidak di-recompile
2. Atau ada conflict antara system PHP dan custom PHP dari aaPanel

## Prioritas

🟢 **LOW** — Website berjalan normal. Error ini hanya muncul saat manual `optimize:clear`. `update.sh` sudah menangani cache clear dengan benar.
