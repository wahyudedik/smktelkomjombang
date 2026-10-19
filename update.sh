#!/bin/bash

# =============================================================================
# Update Script — Laravel SMK Telekomunikasi / MAUDU
# =============================================================================
# Gunakan script ini untuk update incrementally di VPS (production)
#
# Usage:
#   bash update.sh --theme telkom       # Update tema Telkom
#   bash update.sh --theme maudu        # Update tema MAUDU
#   bash update.sh                      # Auto-detect dari .env (fallback: telkom)
#
# Environment (opsional):
#   PHP_BIN=/www/server/php/83/bin/php    # Override binary PHP (default: auto-detect)
#   COMPOSER_BIN=/usr/bin/composer        # Override binary Composer (default: /usr/bin/composer)
#
# Script ini untuk UPDATE yang sudah berjalan di production.
# Gunakan deploy.sh untuk setup awal pertama kali.
# =============================================================================

set -e

# =============================================================================
# Self-healing: Jika script dijalankan tanpa permission execute, auto-fix
# =============================================================================
SCRIPT_PATH="$(realpath "$0")"
if [ ! -x "$SCRIPT_PATH" ]; then
    echo "[FIX] update.sh tidak memiliki permission execute. Memperbaiki..."
    chmod +x "$SCRIPT_PATH"
    echo "[FIX] Permission diperbaiki. Menjalankan ulang..."
    exec bash "$SCRIPT_PATH" "$@"
fi

# Pastikan git selalu simpan permission execute
git config core.fileMode true 2>/dev/null || true

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Fungsi helper
info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

theme_info() {
    echo -e "${CYAN}[THEME]${NC} $1"
}

# Baca nilai key dari .env secara MURNI-BASH (grep/sed, tanpa php).
# Dipakai untuk kredensial backup DB agar tidak ikut fatal saat binary php
# bermasalah / versi salah (php CLI di PATH server bisa PHP 7.2 lama).
env_get() {
    grep -E "^[[:space:]]*${1}[[:space:]]*=" "$APP_DIR/.env" 2>/dev/null \
        | head -n1 \
        | cut -d= -f2- \
        | sed -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//' \
              -e "s/^['\"]//" -e "s/['\"]$//"
}

# =============================================================================
# Parsing Arguments
# =============================================================================
THEME=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --theme)
            THEME="$2"
            shift 2
            ;;
        --help|-h)
            echo "Usage: bash update.sh [--theme telkom|maudu]"
            echo ""
            echo "Options:"
            echo "  --theme <nama>    Update untuk tema tertentu (telkom atau maudu)"
            echo "  --help, -h        Tampilkan bantuan"
            echo ""
            echo "Environment (opsional):"
            echo "  PHP_BIN=<path>       Override binary PHP (default: auto-detect, minimal PHP dari composer.json)"
            echo "  COMPOSER_BIN=<path>  Override binary Composer (default: /usr/bin/composer)"
            echo ""
            echo "Examples:"
            echo "  bash update.sh --theme telkom"
            echo "  bash update.sh --theme maudu"
            echo "  bash update.sh"
            echo "  PHP_BIN=/www/server/php/83/bin/php bash update.sh --theme maudu"
            exit 0
            ;;
        *)
            error "Parameter tidak dikenal: $1. Gunakan --help untuk bantuan."
            ;;
    esac
done

# =============================================================================
# Konfigurasi - Sesuaikan dengan VPS kamu
# =============================================================================
APP_DIR=$(dirname "$(realpath "$0")")
NPM_BIN="npm"
GIT_BRANCH="main"

# Binary PHP & Composer bisa di-override via environment variable:
#   PHP_BIN=/www/server/php/83/bin/php bash update.sh --theme maudu
#   COMPOSER_BIN=/usr/local/bin/composer bash update.sh --theme maudu
PHP_BIN="${PHP_BIN:-}"
COMPOSER_BIN="${COMPOSER_BIN:-/usr/bin/composer}"

# Izinkan Composer berjalan sebagai root tanpa warning
export COMPOSER_ALLOW_SUPERUSER=1

# =============================================================================
# PREFLIGHT: Resolusi binary PHP (SEBELUM langkah destruktif apa pun)
# =============================================================================
# Masalah yang pernah terjadi di server aaPanel: site berjalan dengan PHP-FPM
# 8.3, tapi `php` di PATH adalah PHP CLI sistem yang LAMA (7.2.33) sehingga:
#   - `php artisan down` fatal:
#       "Your Composer dependencies require a PHP version >= 8.3.0.
#        You are running 7.2.33"
#   - composer fatal: "putenv() has been disabled for security reasons"
# Script mencari binary PHP yang versinya memenuhi composer.json ("require": php)
# di path aaPanel (/www/server/php/8x/bin/php) SEBELUM artisan down / backup /
# git pull, supaya tidak ada deploy yang berhenti setengah jalan.

# Helper: cetak versi "MAJOR.MINOR" sebuah binary PHP (kosong jika gagal)
get_php_version() {
    "$1" -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;' 2>/dev/null || true
}

# Helper: true jika versi PHP ($1, format "MAJOR.MINOR") >= $MIN_PHP_VERSION
php_meets_min() {
    local major="${1%%.*}"
    local minor="${1##*.}"
    [ -n "$major" ] && [ -n "$minor" ] || return 1
    [ "$major" -gt "$MIN_PHP_MAJOR" ] && return 0
    [ "$major" -eq "$MIN_PHP_MAJOR" ] && [ "$minor" -ge "$MIN_PHP_MINOR" ]
}

# Versi minimum PHP dari composer.json "require.php" (mis. "^8.2" -> 8.2)
MIN_PHP_VERSION="$(sed -n 's/.*"php"[[:space:]]*:[[:space:]]*"[^0-9]*\([0-9][0-9]*\.[0-9][0-9]*\).*/\1/p' "$APP_DIR/composer.json" | head -n1)"
if [ -z "$MIN_PHP_VERSION" ]; then
    MIN_PHP_VERSION="8.2"
fi
MIN_PHP_MAJOR="${MIN_PHP_VERSION%%.*}"
MIN_PHP_MINOR="${MIN_PHP_VERSION##*.}"

PHP_BIN_VERSION=""
if [ -n "$PHP_BIN" ]; then
    # Override manual via env -> validasi ketat
    if [ ! -x "$PHP_BIN" ] && ! command -v "$PHP_BIN" &>/dev/null; then
        error "PHP_BIN='$PHP_BIN' tidak ditemukan / tidak executable. Periksa path-nya."
    fi
    PHP_BIN_VERSION="$(get_php_version "$PHP_BIN")"
    if [ -z "$PHP_BIN_VERSION" ] || ! php_meets_min "$PHP_BIN_VERSION"; then
        error "PHP_BIN='$PHP_BIN' -> PHP ${PHP_BIN_VERSION:-tidak diketahui} TIDAK memenuhi minimum PHP $MIN_PHP_VERSION (composer.json). Set PHP_BIN ke binary PHP >= $MIN_PHP_VERSION."
    fi
else
    # Auto-detect: kandidat path aaPanel dulu, baru `php` di PATH sistem
    PHP_CANDIDATES=(
        /www/server/php/84/bin/php
        /www/server/php/83/bin/php
        /www/server/php/82/bin/php
        php
    )
    DETECTION_REPORT=""
    for PHP_CAND in "${PHP_CANDIDATES[@]}"; do
        if [ -x "$PHP_CAND" ] || command -v "$PHP_CAND" &>/dev/null; then
            PHP_CAND_REAL="$(command -v "$PHP_CAND" 2>/dev/null || echo "$PHP_CAND")"
            PHP_CAND_VERSION="$(get_php_version "$PHP_CAND")"
        else
            PHP_CAND_REAL="$PHP_CAND"
            PHP_CAND_VERSION=""
        fi
        DETECTION_REPORT+="    $PHP_CAND_REAL -> PHP ${PHP_CAND_VERSION:-tidak ditemukan}"$'\n'
        if [ -n "$PHP_CAND_VERSION" ] && php_meets_min "$PHP_CAND_VERSION"; then
            PHP_BIN="$PHP_CAND_REAL"
            PHP_BIN_VERSION="$PHP_CAND_VERSION"
            break
        fi
    done
    if [ -z "$PHP_BIN" ]; then
        echo ""
        echo "Versi PHP yang ditemukan di tiap path kandidat:"
        printf '%s' "$DETECTION_REPORT"
        echo ""
        error "PHP >= $MIN_PHP_VERSION (require composer.json) tidak ditemukan. Install PHP >= $MIN_PHP_VERSION (aaPanel -> App Manager -> PHP), atau set manual: PHP_BIN=/www/server/php/83/bin/php bash update.sh --theme <tema>"
    fi
fi

# Helper: jalankan Composer dengan PHP yang benar
run_composer() {
    # aaPanel umumnya memasang /usr/bin/composer sebagai shell-script wrapper
    # yang memanggil `php` dari PATH. Jika file ternyata wrapper semacam itu,
    # jalankan langsung dengan PATH yang menyertakan direktori $PHP_BIN.
    # Selain itu (PHAR composer), jalankan via $PHP_BIN dengan `-d disable_functions=`
    # untuk menimpa php.ini production aaPanel yang men-disable putenv()
    # (error: "putenv() has been disabled for security reasons").
    # Override disable_functions ini HANYA berlaku untuk proses composer ini,
    # tidak memengaruhi php artisan atau proses PHP lain.
    local composer_path="$COMPOSER_BIN"
    if [[ "$composer_path" != */* ]] && command -v "$composer_path" &>/dev/null; then
        composer_path="$(command -v "$composer_path")"
    fi
    if [ -f "$composer_path" ] \
        && head -n 5 "$composer_path" 2>/dev/null | grep -q '^#!' \
        && ! head -n 5 "$composer_path" 2>/dev/null | grep -q '<?php'; then
        # Shell-script wrapper -> jalankan langsung, sisipkan direktori PHP_BIN ke PATH
        if [[ "$PHP_BIN" == */* ]]; then
            PATH="$(dirname "$PHP_BIN"):$PATH" "$composer_path" "$@"
        else
            "$composer_path" "$@"
        fi
    else
        # PHAR -> jalankan via PHP_BIN yang benar
        "$PHP_BIN" -d disable_functions= "$composer_path" "$@"
    fi
}

# =============================================================================
# Auto-detect Theme dari .env jika tidak dispesifikasikan
# =============================================================================
cd "$APP_DIR" || error "Gagal masuk ke direktori aplikasi: $APP_DIR"

if [ -z "$THEME" ]; then
    if [ -f "$APP_DIR/.env" ]; then
        THEME=$(grep DEFAULT_THEME "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'" | tr -d ' ')
        if [ -n "$THEME" ]; then
            info "Theme auto-detected dari .env: $THEME"
        fi
    fi
    if [ -z "$THEME" ]; then
        THEME="telkom"
        warn "Theme tidak ditemukan di .env. Menggunakan default: $THEME"
    fi
fi

# Validasi theme
if [[ "$THEME" != "telkom" && "$THEME" != "maudu" ]]; then
    error "Theme '$THEME' tidak valid. Pilihan: telkom atau maudu"
fi

# =============================================================================
# Konfigurasi Theme-Specific
# =============================================================================
case "$THEME" in
    telkom)
        APP_NAME="SMK Telekomunikasi Darul Ulum"
        APP_URL="https://smktelekomunikasidu.sch.id"
        DB_NAME="telkom_db"
        ;;
    maudu)
        APP_NAME="Maudu Rejoso"
        APP_URL="https://maudu-rejoso.sch.id"
        DB_NAME="sekolah"
        ;;
esac

# =============================================================================
# Mulai Update
# =============================================================================
echo ""
echo "============================================="
echo -e "  🔄 Update Laravel — ${CYAN}$THEME${NC}"
echo "============================================="
echo ""
theme_info "Nama Aplikasi : $APP_NAME"
theme_info "Domain         : $APP_URL"
theme_info "Database       : $DB_NAME"
theme_info "Direktori      : $APP_DIR"
theme_info "Branch         : $GIT_BRANCH"
theme_info "PHP            : $PHP_BIN (PHP $PHP_BIN_VERSION, min $MIN_PHP_VERSION)"
echo ""

# Trap: jika deploy gagal SETELAH maintenance mode aktif, otomatis jalankan
# `artisan up` supaya site tidak stuck di maintenance mode.
MAINTENANCE_ACTIVE=false
DEPLOY_FINISHED=false
on_exit() {
    if [ "$DEPLOY_FINISHED" != "true" ] && [ "$MAINTENANCE_ACTIVE" = "true" ]; then
        warn "Deploy berhenti saat maintenance mode masih aktif — mengembalikan site..."
        "$PHP_BIN" artisan up \
            && warn "Site berhasil dikembalikan dari maintenance mode." \
            || warn "Gagal otomatis — jalankan manual: $PHP_BIN artisan up"
    fi
}
trap on_exit EXIT

# 1. Aktifkan Maintenance Mode
#    FAIL-FAST: jika artisan down gagal, deploy DIHENTIKAN (belum ada langkah
#    destruktif apa pun — backup/git/pull belum berjalan).
info "Mengaktifkan maintenance mode..."
if ! "$PHP_BIN" artisan down --refresh=15 --retry=60; then
    error "Gagal mengaktifkan maintenance mode (artisan down). Periksa $PHP_BIN & .env, lalu jalankan ulang script. Deploy DIHENTIKAN sebelum ada perubahan apa pun."
fi
MAINTENANCE_ACTIVE=true

# 2. Backup database (opsional, tapi direkomendasikan)
#    Sifatnya NON-FATAL (deploy tetap lanjut jika backup gagal), tapi kegagalan
#    harus TERLIHAT JELAS agar admin segera backup manual.
info "Membuat backup database..."
BACKUP_DIR="$APP_DIR/storage/backups"
mkdir -p "$BACKUP_DIR"
BACKUP_FILE="$BACKUP_DIR/db_backup_${THEME}_$(date +%Y%m%d_%H%M%S).sql"
if command -v mysqldump &>/dev/null; then
    # Kredensial DB dibaca langsung dari .env dengan grep/sed (murni-bash,
    # tanpa php) agar tidak ikut fatal saat binary php bermasalah/versi salah.
    DB_NAME_CHECK=$(env_get DB_DATABASE)
    DB_USER=$(env_get DB_USERNAME)
    DB_PASS=$(env_get DB_PASSWORD)
    if [ -n "$DB_NAME_CHECK" ]; then
        BACKUP_OK=false
        if [ -n "$DB_PASS" ]; then
            if mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME_CHECK" > "$BACKUP_FILE" 2>/dev/null; then
                BACKUP_OK=true
            fi
        else
            if mysqldump -u "$DB_USER" "$DB_NAME_CHECK" > "$BACKUP_FILE" 2>/dev/null; then
                BACKUP_OK=true
            fi
        fi
        if [ "$BACKUP_OK" = true ] && [ -s "$BACKUP_FILE" ]; then
            info "Backup database tersimpan: $BACKUP_FILE"
        else
            rm -f "$BACKUP_FILE" 2>/dev/null || true
            warn "[!] DATABASE TIDAK TERBACKUP — jalankan backup manual segera!"
            warn "    Perintah manual: mysqldump -u <user> -p <db_name> > storage/backups/manual_$(date +%Y%m%d_%H%M%S).sql"
            warn "    Deploy dilanjutkan TANPA backup. Pastikan backup manual SEGERA setelah deploy."
        fi
    else
        warn "[!] DB_DATABASE kosong di .env — backup database TIDAK dibuat."
        warn "    [!] DATABASE TIDAK TERBACKUP — jalankan backup manual segera!"
    fi
else
    warn "mysqldump tidak ditemukan. Backup database dilewati."
    warn "[!] DATABASE TIDAK TERBACKUP — instal mysql-client / jalankan backup manual segera!"
fi

# 3. Reset local changes & pull perubahan terbaru dari Git
info "Mereset perubahan lokal..."
git checkout -- .
# ⭐ PENTING: gunakan -e EXCLUDES untuk melindungi data production.
#    -fdX TIDAK aman di sini: storage/framework/cache/data, storage/framework/sessions,
#    storage/logs, dll justru DIIGNORE oleh .gitignore Laravel → -fdX akan menghapusnya.
#    Dengan -e, direktori data production (uploads, backups, cache, sessions, logs,
#    bootstrap/cache) dijamin tetap ada.
git clean -fd \
    -e public/.user.ini \
    -e public/.well-known \
    -e public/uploads \
    -e .env \
    -e .env.production \
    -e .env.production.maudu \
    -e .env.production.telkom \
    -e storage/backups \
    -e storage/framework/cache \
    -e storage/framework/sessions \
    -e storage/logs \
    -e bootstrap/cache

info "Pulling perubahan terbaru dari git..."
git pull origin "$GIT_BRANCH" || error "Gagal pull dari git"

# Pastikan script tetap executable setelah pull
chmod +x "$APP_DIR/deploy.sh" 2>/dev/null || true
chmod +x "$APP_DIR/update.sh" 2>/dev/null || true

# Buat direktori yang mungkin belum ada (aman jika sudah ada)
mkdir -p "$APP_DIR/storage/logs"
mkdir -p "$APP_DIR/storage/framework/cache/data"
mkdir -p "$APP_DIR/storage/framework/sessions"
mkdir -p "$APP_DIR/storage/framework/views"
mkdir -p "$APP_DIR/storage/app/public"
mkdir -p "$APP_DIR/storage/app/private"
mkdir -p "$APP_DIR/public/uploads"
mkdir -p "$APP_DIR/bootstrap/cache"
mkdir -p "$APP_DIR/storage/backups"

# 4. Install/update dependencies PHP
#    - Dijalankan via run_composer() -> memakai $PHP_BIN yang benar (bukan php
#      di PATH yang bisa versi lama) + menimpa disable_functions untuk putenv.
#    - FAIL-FAST: jika composer install gagal, deploy berhenti; site otomatis
#      keluar dari maintenance mode via trap on_exit.
info "Menginstall dependencies PHP (production)..."
if ! run_composer install --no-dev --optimize-autoloader --no-interaction; then
    error "composer install GAGAL — deploy dihentikan. Cek output composer di atas (versi PHP / disable_functions / koneksi), lalu jalankan ulang script."
fi

# 5. Install/update dependencies Node.js & build assets
if [ -f "package.json" ]; then
    info "Menginstall dependencies Node.js..."
    $NPM_BIN ci

    info "Building assets (Vite)..."
    $NPM_BIN run build
else
    warn "package.json tidak ditemukan. Build assets dilewati."
fi

# 6. Jalankan migrasi database
info "Menjalankan migrasi database..."
"$PHP_BIN" artisan migrate --force

# 7. Seed permissions & settings Spatie
#    - RolePermissionSeeder: backward compat (data lama, deprecated)
#    - PermissionSeeder: pengganti utama (permissions & roles lengkap)
#    - AttendanceSettingSeeder: seed settings default absensi
info "Sync permissions & attendance settings..."
"$PHP_BIN" artisan db:seed --class=RolePermissionSeeder 2>/dev/null || true
"$PHP_BIN" artisan db:seed --class=PermissionSeeder 2>/dev/null || true
"$PHP_BIN" artisan db:seed --class=AttendanceSettingSeeder 2>/dev/null || true

# 8. Clear cache lama (individual commands — hindari optimize:clear mbstring error)
info "Membersihkan cache lama..."
"$PHP_BIN" artisan cache:clear
"$PHP_BIN" artisan config:clear
"$PHP_BIN" artisan view:clear
"$PHP_BIN" artisan event:clear

# 9. Optimasi Laravel
info "Mengoptimasi aplikasi..."
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan event:cache

# 10. Link storage (jika belum)
info "Memastikan storage link..."
if [ -L "$APP_DIR/public/storage" ]; then
    info "  ✓ Storage link sudah ada. Melewati."
else
    "$PHP_BIN" artisan storage:link 2>/dev/null || warn "Gagal membuat storage link. Buat manual: php artisan storage:link"
fi

# 11. Seed theme settings (safe — tidak overwrites data existing)
info "Seed theme settings..."
"$PHP_BIN" artisan db:seed --class=ThemeSettingsSeeder 2>/dev/null || warn "ThemeSettingsSeeder dilewati."

# 12. Seed static pages — GATED: hanya jalan jika SEED_STATIC_PAGES=true (opt-in).
# Alasan: StaticPageGenerator sebelumnya menimpa field menu (is_menu/menu_title/menu_position)
# setiap deploy → menu header yang sudah dikustomisasi admin hilang/reset.
# Default: DILEWATKAN agar produksi tidak otomatis menjalankannya setiap deploy.
# Jalankan bila diperlukan: SEED_STATIC_PAGES=true bash update.sh --theme <tema>
# atau manual: php artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()"
# (Generator versi terbaru sudah aman: page baru non-menu + menu_position default 'header',
#  page existing tidak menimpa field menu.)
# Sifatnya GAGAL-SOFT: jika seed error, deploy TETAP lanjut (tidak dianggap gagal).
if [ "${SEED_STATIC_PAGES:-false}" = "true" ]; then
    info "Seed static pages (SEED_STATIC_PAGES=true)..."
    "$PHP_BIN" artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()" \
        || warn "[WARN] Seed static pages gagal. Melanjutkan deploy..."
else
    info "Seed static pages dilewati (set SEED_STATIC_PAGES=true untuk menjalankan)."
fi

# 13. Restart queue worker
info "Merestart queue worker..."
"$PHP_BIN" artisan queue:restart

# 14. Restart scheduler (jika menggunakan supervisor)
info "Merestart scheduler..."
"$PHP_BIN" artisan schedule:run 2>/dev/null || true

# 15. Set permission yang benar
info "Mengatur permission..."

# Deteksi user web server (www-data untuk Ubuntu/Debian, www untuk aaPanel/BT Panel)
WEB_USER="www-data"
if id "www" &>/dev/null; then
    WEB_USER="www"
fi

# Set permission direktori writable
chmod -R 775 "$APP_DIR/storage" 2>/dev/null || true
chmod -R 775 "$APP_DIR/bootstrap/cache" 2>/dev/null || true
chmod -R 775 "$APP_DIR/public/uploads" 2>/dev/null || true
chmod 775 "$APP_DIR/public" 2>/dev/null || true

# Fix permission static assets (assets_telkom, assets_maudu, dll)
# File: 644 (readable), Direktori: 755 (traversable) — hindari 403 Forbidden
info "Memperbaiki permission static assets..."
for ASSETS_DIR in "$APP_DIR"/public/assets_*/; do
    if [ -d "$ASSETS_DIR" ]; then
        find "$ASSETS_DIR" -type d -exec chmod 755 {} \; 2>/dev/null || true
        find "$ASSETS_DIR" -type f -exec chmod 644 {} \; 2>/dev/null || true
        info "  ✓ $(basename "$ASSETS_DIR") — permission diperbaiki"
    fi
done

# Set ownership ke web server user
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/storage" 2>/dev/null || warn "Gagal chown storage. Coba: sudo chown -R $WEB_USER:$WEB_USER $APP_DIR/storage"
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/bootstrap/cache" 2>/dev/null || warn "Gagal chown bootstrap/cache."
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/public/uploads" 2>/dev/null || true

# Set permission file spesifik
find "$APP_DIR/storage" -type f -exec chmod 664 {} \; 2>/dev/null || true
find "$APP_DIR/storage" -type d -exec chmod 775 {} \; 2>/dev/null || true
chmod -R o-w "$APP_DIR/storage" 2>/dev/null || true

# 16. Cleanup backup lama (simpan 7 hari terakhir)
info "Membersihkan backup lama..."
find "$BACKUP_DIR" -name "db_backup_*.sql" -mtime +7 -delete 2>/dev/null || true

# 17. Cleanup old logs (simpan 30 hari terakhir)
info "Membersihkan log lama..."
find "$APP_DIR/storage/logs" -name "*.log" -mtime +30 -delete 2>/dev/null || true

# 18. Cleanup old views cache
info "Membersihkan view cache lama..."
find "$APP_DIR/storage/framework/views" -name "*.php" -mtime +7 -delete 2>/dev/null || true

# 19. Nonaktifkan Maintenance Mode
info "Menonaktifkan maintenance mode..."
"$PHP_BIN" artisan up
MAINTENANCE_ACTIVE=false
DEPLOY_FINISHED=true

# =============================================================================
# Selesai
# =============================================================================
echo ""
echo "============================================="
echo -e "  ${GREEN}✅ Update $THEME selesai!${NC}"
echo "============================================="
echo ""
theme_info "Website    : $APP_URL"
theme_info "Theme      : $THEME"
theme_info "Database   : $DB_NAME"
echo ""
info "Jangan lupa cek:"
echo "  - Website bisa diakses: $APP_URL"
echo "  - Queue worker berjalan (supervisord)"
echo "  - Log error: storage/logs/laravel.log"
echo "  - Backup database: storage/backups/"
echo ""
info "Rollback jika ada masalah:"
echo "  - Database: mysql -u user -p $DB_NAME < storage/backups/db_backup_${THEME}_YYYYMMDD_HHMMSS.sql"
echo "  - Code: git checkout <previous-commit>"
echo ""
