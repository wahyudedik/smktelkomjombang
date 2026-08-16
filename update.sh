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
            echo "Examples:"
            echo "  bash update.sh --theme telkom"
            echo "  bash update.sh --theme maudu"
            echo "  bash update.sh"
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
PHP_BIN="php"
COMPOSER_BIN="composer"
NPM_BIN="npm"
GIT_BRANCH="main"

# Izinkan Composer berjalan sebagai root tanpa warning
export COMPOSER_ALLOW_SUPERUSER=1

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
echo ""

# 1. Aktifkan Maintenance Mode
info "Mengaktifkan maintenance mode..."
$PHP_BIN artisan down --refresh=15 --retry=60 || true

# 2. Backup database (opsional, tapi direkomendasikan)
info "Membuat backup database..."
BACKUP_DIR="$APP_DIR/storage/backups"
mkdir -p "$BACKUP_DIR"
BACKUP_FILE="$BACKUP_DIR/db_backup_${THEME}_$(date +%Y%m%d_%H%M%S).sql"
if command -v mysqldump &>/dev/null; then
    DB_NAME_CHECK=$(grep DB_DATABASE "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    DB_USER=$(grep DB_USERNAME "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    DB_PASS=$(grep DB_PASSWORD "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    if [ -n "$DB_NAME_CHECK" ]; then
        if [ -n "$DB_PASS" ]; then
            mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME_CHECK" > "$BACKUP_FILE" 2>/dev/null || warn "Gagal backup database. Melanjutkan..."
        else
            mysqldump -u "$DB_USER" "$DB_NAME_CHECK" > "$BACKUP_FILE" 2>/dev/null || warn "Gagal backup database. Melanjutkan..."
        fi
        if [ -f "$BACKUP_FILE" ]; then
            info "Backup database tersimpan: $BACKUP_FILE"
        fi
    fi
else
    warn "mysqldump tidak ditemukan. Backup database dilewati."
fi

# 3. Reset local changes & pull perubahan terbaru dari Git
info "Mereset perubahan lokal..."
git checkout -- .
git clean -fd -e public/.user.ini -e public/.well-known -e .env -e .env.production -e .env.production.maudu -e .env.production.telkom

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
info "Menginstall dependencies PHP (production)..."
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

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
$PHP_BIN artisan migrate --force

# 7. Seed permissions Spatie (jika ada perubahan)
info "Sync permissions Spatie..."
$PHP_BIN artisan db:seed --class=RolePermissionSeeder 2>/dev/null || true

# 8. Clear cache lama (individual commands — hindari optimize:clear mbstring error)
info "Membersihkan cache lama..."
$PHP_BIN artisan cache:clear
$PHP_BIN artisan config:clear
$PHP_BIN artisan view:clear
$PHP_BIN artisan event:clear

# 9. Optimasi Laravel
info "Mengoptimasi aplikasi..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache
$PHP_BIN artisan icons:cache 2>/dev/null || true

# 10. Link storage (jika belum)
info "Memastikan storage link..."
$PHP_BIN artisan storage:link 2>/dev/null || true

# 11. Seed static pages (jika ada perubahan theme)
info "Seed static pages..."
$PHP_BIN artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()" 2>/dev/null || warn "Static page generator dilewati."

# 12. Restart queue worker
info "Merestart queue worker..."
$PHP_BIN artisan queue:restart

# 13. Restart scheduler (jika menggunakan supervisor)
info "Merestart scheduler..."
$PHP_BIN artisan schedule:work --stop-when-empty 2>/dev/null || true

# 14. Set permission yang benar
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

# Set ownership ke web server user
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/storage" 2>/dev/null || warn "Gagal chown storage. Coba: sudo chown -R $WEB_USER:$WEB_USER $APP_DIR/storage"
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/bootstrap/cache" 2>/dev/null || warn "Gagal chown bootstrap/cache."
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/public/uploads" 2>/dev/null || true

# Set permission file spesifik
find "$APP_DIR/storage" -type f -exec chmod 664 {} \; 2>/dev/null || true
find "$APP_DIR/storage" -type d -exec chmod 775 {} \; 2>/dev/null || true
chmod -R o-w "$APP_DIR/storage" 2>/dev/null || true

# 15. Cleanup backup lama (simpan 7 hari terakhir)
info "Membersihkan backup lama..."
find "$BACKUP_DIR" -name "db_backup_*.sql" -mtime +7 -delete 2>/dev/null || true

# 16. Cleanup old logs (simpan 30 hari terakhir)
info "Membersihkan log lama..."
find "$APP_DIR/storage/logs" -name "*.log" -mtime +30 -delete 2>/dev/null || true

# 17. Cleanup old views cache
info "Membersihkan view cache lama..."
find "$APP_DIR/storage/framework/views" -name "*.php" -mtime +7 -delete 2>/dev/null || true

# 18. Nonaktifkan Maintenance Mode
info "Menonaktifkan maintenance mode..."
$PHP_BIN artisan up

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
