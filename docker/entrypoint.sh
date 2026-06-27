#!/bin/bash
# =============================================================================
# Entrypoint / startup script for Novasyn Chemicals on Render
# =============================================================================
set -e

echo "======================================================"
echo " Novasyn Chemicals — Container Startup"
echo "======================================================"

# -----------------------------------------------------------------------------
# 1. Verify required environment variables (Skip if SQLite)
# -----------------------------------------------------------------------------
DB_CONN="${DB_CONNECTION:-sqlite}"

if [ "$DB_CONN" != "sqlite" ]; then
    : "${DB_HOST:?ERROR: DB_HOST environment variable is not set.}"
    : "${DB_DATABASE:?ERROR: DB_DATABASE environment variable is not set.}"
    : "${DB_USERNAME:?ERROR: DB_USERNAME environment variable is not set.}"
    : "${DB_PASSWORD:?ERROR: DB_PASSWORD environment variable is not set.}"
fi

# Validate and normalize APP_KEY. If it's invalid or wrong length, generate a valid one on the fly.
VALIDATED_APP_KEY=$(php -r '
    $key = getenv("APP_KEY") ?: "";
    if (str_starts_with($key, "base64:")) {
        $decoded = base64_decode(substr($key, 7), true);
        if ($decoded !== false && strlen($decoded) === 32) {
            echo $key;
            exit(0);
        }
    }
    if (strlen($key) === 32) {
        echo $key;
        exit(0);
    }
    echo "base64:" . base64_encode(random_bytes(32));
')

export APP_KEY="$VALIDATED_APP_KEY"
echo "[✓] Environment variables present and validated."

# -----------------------------------------------------------------------------
# 2. Create .env from environment variables
# -----------------------------------------------------------------------------
if [ ! -f .env ]; then
    echo "[→] Writing .env from environment variables..."
    cat > .env <<EOF
APP_NAME="${APP_NAME:-Novasyn Chemicals}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-stderr}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="${DB_CONN}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"

CACHE_STORE="${CACHE_STORE:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-public}"
EOF
    echo "[✓] .env written."
else
    echo "[✓] .env already exists."
fi

# -----------------------------------------------------------------------------
# 3. Fix permissions for storage and cache directories
# -----------------------------------------------------------------------------
echo "[→] Setting storage & bootstrap/cache permissions..."
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "[✓] Permissions set."

# -----------------------------------------------------------------------------
# 4. Wait for database or configure SQLite
# -----------------------------------------------------------------------------
if [ "$DB_CONN" = "sqlite" ]; then
    # SQLite configuration
    SQLITE_PATH="${DB_DATABASE:-database/database.sqlite}"
    echo "[→] Configuring SQLite database at ${SQLITE_PATH}..."
    mkdir -p "$(dirname "${SQLITE_PATH}")"
    touch "${SQLITE_PATH}"
    chown www-data:www-data "${SQLITE_PATH}"
    chmod 664 "${SQLITE_PATH}"
    echo "[✓] SQLite file initialized."
elif [ "$DB_CONN" = "mysql" ]; then
    echo "[→] Waiting for MySQL database at ${DB_HOST}:${DB_PORT:-3306}..."
    MAX_TRIES=30
    COUNT=0
    until mysqladmin ping -h "${DB_HOST}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME}" --password="${DB_PASSWORD}" --silent; do
        COUNT=$((COUNT + 1))
        if [ "$COUNT" -ge "$MAX_TRIES" ]; then
            echo "[!] MySQL not reachable after ${MAX_TRIES} attempts. Continuing anyway..."
            break
        fi
        echo "    Attempt ${COUNT}/${MAX_TRIES} — retrying in 3s..."
        sleep 3
    done
    echo "[✓] Database connection confirmed."
elif [ "$DB_CONN" = "pgsql" ]; then
    echo "[→] Waiting for PostgreSQL database at ${DB_HOST}:${DB_PORT:-5432}..."
    MAX_TRIES=30
    COUNT=0
    until pg_isready -h "${DB_HOST}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME}" -d "${DB_DATABASE}" -q; do
        COUNT=$((COUNT + 1))
        if [ "$COUNT" -ge "$MAX_TRIES" ]; then
            echo "[!] PostgreSQL not reachable after ${MAX_TRIES} attempts. Continuing anyway..."
            break
        fi
        echo "    Attempt ${COUNT}/${MAX_TRIES} — retrying in 3s..."
        sleep 3
    done
    echo "[✓] Database connection confirmed."
fi

# -----------------------------------------------------------------------------
# 5. Create storage symlink
# -----------------------------------------------------------------------------
if [ ! -L public/storage ]; then
    echo "[→] Creating storage symlink..."
    php artisan storage:link --no-interaction || true
    echo "[✓] Storage symlink created."
else
    echo "[✓] Storage symlink already exists."
fi

# -----------------------------------------------------------------------------
# 6. Run migrations / seeders
# -----------------------------------------------------------------------------
if [ "${FRESH_DATABASE}" = "true" ]; then
    echo "[→] FRESH_DATABASE=true detected! Wiping database, running migrations and seeders..."
    php artisan migrate:fresh --force --seed --no-interaction
    echo "[✓] Fresh migration and seeding complete."
elif [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "[→] Running database migrations..."
    php artisan migrate --force --no-interaction
    echo "[✓] Migrations complete."

    if [ "${RUN_SEEDER}" = "true" ]; then
        echo "[→] Seeding default database settings..."
        php artisan db:seed --force --no-interaction
        echo "[✓] Seeding complete."
    fi
else
    echo "[!] Skipping migrations (set RUN_MIGRATIONS=true to enable)."
fi

# -----------------------------------------------------------------------------
# 7. Cache bootstrapping
# -----------------------------------------------------------------------------
echo "[→] Caching configuration, routes, and views..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction || true
echo "[✓] Caching complete."

# -----------------------------------------------------------------------------
# 8. Start Nginx + PHP-FPM
# -----------------------------------------------------------------------------
echo "======================================================"
echo " Startup complete — launching Nginx + PHP-FPM"
echo "======================================================"
exec /usr/bin/supervisord -c /etc/supervisord.conf
