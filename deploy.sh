#!/bin/bash

# =============================================================================
# Deploy Script — Laravel SMK Telekomunikasi / MAUDU
# =============================================================================
# Gunakan script ini untuk setup awal pertama kali di VPS.
# Gunakan update.sh untuk update incremental setelahnya.
#
# Usage:
#   bash deploy.sh --theme telkom       # Deploy tema Telkom
#   bash deploy.sh --theme maudu        # Deploy tema MAUDU
#   bash deploy.sh                      # Auto-detect dari .env (fallback: telkom)
#
# Script ini OTOMATIS memperbaiki permission sendiri.
# =============================================================================

set -e

# =============================================================================
# Self-healing: Jika script dijalankan tanpa permission execute, auto-fix
# =============================================================================
SCRIPT_PATH="$(realpath "$0")"
if [ ! -x "$SCRIPT_PATH" ]; then
    echo "[FIX] deploy.sh tidak memiliki permission execute. Memperbaiki..."
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
            echo "Usage: bash deploy.sh [--theme telkom|maudu]"
            echo ""
            echo "Options:"
            echo "  --theme <nama>    Deploy untuk tema tertentu (telkom atau maudu)"
            echo "  --help, -h        Tampilkan bantuan"
            echo ""
            echo "Examples:"
            echo "  bash deploy.sh --theme telkom"
            echo "  bash deploy.sh --theme maudu"
            echo "  bash deploy.sh"
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
        APP_URL="https://smktelkom.sch.id"
        DB_NAME="telkom_db"
        DB_USER="telkom_user"
        ENV_TEMPLATE=".env.production.telkom"
        ;;
    maudu)
        APP_NAME="Maudu Rejoso"
        APP_URL="https://maudu-rejoso.sch.id"
        DB_NAME="sekolah"
        DB_USER="sekolah"
        ENV_TEMPLATE=".env.production.maudu"
        ;;
esac

# =============================================================================
# Mulai Deploy
# =============================================================================
echo ""
echo "============================================="
echo -e "  🚀 Deploy Laravel — ${CYAN}$THEME${NC}"
echo "============================================="
echo ""
theme_info "Nama Aplikasi : $APP_NAME"
theme_info "Domain         : $APP_URL"
theme_info "Database       : $DB_NAME"
theme_info "Env Template   : $ENV_TEMPLATE"
theme_info "Direktori      : $APP_DIR"
theme_info "Branch         : $GIT_BRANCH"
echo ""

# 1. Aktifkan Maintenance Mode
info "Mengaktifkan maintenance mode..."
$PHP_BIN artisan down --refresh=15 --retry=60 || true

# 2. Setup .env jika belum ada
if [ ! -f "$APP_DIR/.env" ]; then
    if [ -f "$APP_DIR/$ENV_TEMPLATE" ]; then
        info "Membuat .env dari template $ENV_TEMPLATE..."
        cp "$APP_DIR/$ENV_TEMPLATE" "$APP_DIR/.env"
        warn "⚠️  .env telah dibuat dari template. PASTIKAN untuk mengisi nilai PLACEHOLDER sebelum melanjutkan!"
        warn "   Edit .env: nano $APP_DIR/.env"
        warn "   Nilai yang perlu diisi:"
        grep -n "PLACEHOLDER" "$APP_DIR/.env" | while read -r line; do
            warn "   - $line"
        done
        echo ""
        read -p "Tekan Enter untuk melanjutkan setelah mengisi .env (atau Ctrl+C untuk berhenti)... "
    else
        warn "Template $ENV_TEMPLATE tidak ditemukan. Menggunakan .env.example..."
        cp "$APP_DIR/.env.example" "$APP_DIR/.env"
        $PHP_BIN artisan key:generate
    fi
else
    info ".env sudah ada. Memeriksa DEFAULT_THEME..."
    CURRENT_THEME=$(grep DEFAULT_THEME "$APP_DIR/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'" | tr -d ' ')
    if [ "$CURRENT_THEME" != "$THEME" ]; then
        warn "DEFAULT_THEME di .env adalah '$CURRENT_THEME', tapi deploy untuk '$THEME'"
        read -p "Update DEFAULT_THEME ke '$THEME'? (y/n): " -n 1 -r
        echo ""
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            sed -i "s/DEFAULT_THEME=.*/DEFAULT_THEME=$THEME/" "$APP_DIR/.env"
            info "DEFAULT_THEME diupdate ke: $THEME"
        fi
    fi
fi

# 3. Reset local changes & pull perubahan terbaru dari Git
info "Mereset perubahan lokal..."
git checkout -- .
git clean -fd -e public/.user.ini -e public/.well-known -e .env -e .env.production -e .env.production.maudu -e .env.production.telkom

info "Pulling perubahan terbaru dari git..."
git pull origin "$GIT_BRANCH" || error "Gagal pull dari git"

# Pastikan deploy.sh tetap executable setelah pull
chmod +x "$APP_DIR/deploy.sh"
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

# 6. Generate APP_KEY jika masih placeholder
if grep -q "PLACEHOLDER_APP_KEY" "$APP_DIR/.env" 2>/dev/null; then
    info "Generating APP_KEY..."
    $PHP_BIN artisan key:generate
fi

# 7. Jalankan migrasi database
info "Menjalankan migrasi database..."
$PHP_BIN artisan migrate --force

# 8. Seed permissions Spatie (jika ada perubahan)
info "Sync permissions Spatie..."
$PHP_BIN artisan db:seed --class=RolePermissionSeeder 2>/dev/null || true

# 9. Seed static pages
info "Seed static pages..."
$PHP_BIN artisan tinker --execute="app(\App\Services\StaticPageGenerator::class)->generate()" 2>/dev/null || warn "Static page generator dilewati."

# 10. Optimasi Laravel
info "Mengoptimasi aplikasi..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache
$PHP_BIN artisan icons:cache 2>/dev/null || true

# 11. Clear cache data (bukan config/route/view)
info "Membersihkan data cache..."
$PHP_BIN artisan cache:clear

# 12. Link storage (jika belum)
info "Memastikan storage link..."
$PHP_BIN artisan storage:link 2>/dev/null || true

# 13. Restart queue worker
info "Merestart queue worker..."
$PHP_BIN artisan queue:restart

# 14. Restart scheduler (jika menggunakan supervisor)
info "Merestart scheduler..."
$PHP_BIN artisan schedule:work --stop-when-empty 2>/dev/null || true

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

# Set ownership ke web server user
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/storage" 2>/dev/null || warn "Gagal chown storage. Coba: sudo chown -R $WEB_USER:$WEB_USER $APP_DIR/storage"
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/bootstrap/cache" 2>/dev/null || warn "Gagal chown bootstrap/cache."
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/public/uploads" 2>/dev/null || true

# Set permission file spesifik
find "$APP_DIR/storage" -type f -exec chmod 664 {} \; 2>/dev/null || true
find "$APP_DIR/storage" -type d -exec chmod 775 {} \; 2>/dev/null || true
chmod -R o-w "$APP_DIR/storage" 2>/dev/null || true

# 16. Nonaktifkan Maintenance Mode
info "Menonaktifkan maintenance mode..."
$PHP_BIN artisan up

# =============================================================================
# Selesai
# =============================================================================
echo ""
echo "============================================="
echo -e "  ${GREEN}✅ Deploy $THEME selesai!${NC}"
echo "============================================="
echo ""
theme_info "Website    : $APP_URL"
theme_info "Theme      : $THEME"
theme_info "Database   : $DB_NAME"
echo ""
info "Jangan lupa cek:"
echo "  - Website bisa diakses: $APP_URL"
echo "  - Landing page default: $APP_URL/ (theme: $THEME)"
echo "  - Theme Telkom: $APP_URL/telkom"
echo "  - Theme MAUDU : $APP_URL/maudu"
echo "  - Queue worker berjalan (supervisord)"
echo "  - Scheduler berjalan: crontab -e → * * * * * cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1"
echo "  - Log error: storage/logs/laravel.log"
echo ""
