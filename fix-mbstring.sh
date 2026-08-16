#!/bin/bash
# ==============================================
# 🔧 Fix mbstring PCRE2 Error
# Jalankan di VPS: bash fix-mbstring.sh
# ==============================================

set -e

echo "=============================================="
echo "  🔧 Fix mbstring PCRE2 Error"
echo "=============================================="

PHP_DIR="/www/server/php/83"
PHP_CONFIG="${PHP_DIR}/bin/php-config"
PHPIZE="${PHP_DIR}/bin/phpize"

# Step 1: Cek versi PHP
echo ""
echo "[INFO] PHP Version:"
${PHP_DIR}/bin/php -v

# Step 2: Cek PCRE2
echo ""
echo "[INFO] PCRE2 Version:"
if command -v pcre2-config &> /dev/null; then
    pcre2-config --version
else
    echo "pcre2-config not found, checking manually..."
    ls -la /www/server/php/83/lib/php/extensions/no-debug-non-zts-20230831/ | grep pcre
fi

# Step 3: Backup mbstring.so
echo ""
echo "[INFO] Backing up current mbstring.so..."
MBSTRING_PATH="${PHP_DIR}/lib/php/extensions/no-debug-non-zts-20230831/mbstring.so"
if [ -f "$MBSTRING_PATH" ]; then
    cp "$MBSTRING_PATH" "${MBSTRING_PATH}.bak"
    echo "[OK] Backup saved: ${MBSTRING_PATH}.bak"
else
    echo "[WARN] mbstring.so not found at expected path"
fi

# Step 4: Rebuild mbstring
echo ""
echo "[INFO] Running phpize..."
cd /tmp
mkdir -p mbstring-rebuild && cd mbstring-rebuild

# Download PHP source matching version
PHP_VERSION=$(${PHP_DIR}/bin/php -r 'echo PHP_VERSION;')
echo "[INFO] PHP Version: ${PHP_VERSION}"

# Check if PHP source exists
PHP_SRC="/tmp/php-${PHP_VERSION}"
if [ ! -d "$PHP_SRC" ]; then
    echo "[INFO] Downloading PHP ${PHP_VERSION} source..."
    wget -q "https://www.php.net/distributions/php-${PHP_VERSION}.tar.gz" -O /tmp/php-${PHP_VERSION}.tar.gz
    tar -xzf /tmp/php-${PHP_VERSION}.tar.gz -C /tmp/
fi

cd "${PHP_SRC}/ext/mbstring"

echo "[INFO] Running phpize..."
${PHPIZE}

echo "[INFO] Configuring..."
./configure --with-php-config=${PHP_CONFIG}

echo "[INFO] Compiling (this may take a few minutes)..."
make -j$(nproc)

echo "[INFO] Installing..."
make install

# Step 5: Restart PHP-FPM
echo ""
echo "[INFO] Restarting PHP-FPM..."
if [ -f /etc/init.d/php-fpm-83 ]; then
    /etc/init.d/php-fpm-83 restart
    echo "[OK] PHP-FPM restarted"
else
    echo "[WARN] php-fpm-83 init script not found, trying systemctl..."
    systemctl restart php-fpm-83 2>/dev/null || echo "[WARN] Could not restart PHP-FPM automatically"
fi

# Step 6: Test
echo ""
echo "[INFO] Testing PHP..."
${PHP_DIR}/bin/php -v
${PHP_DIR}/bin/php -m | grep mbstring

echo ""
echo "[INFO] Testing artisan optimize:clear..."
cd /www/wwwroot/maudu-rejoso.sch.id
${PHP_DIR}/bin/php artisan optimize:clear

echo ""
echo "=============================================="
echo "  ✅ Fix selesai!"
echo "=============================================="
