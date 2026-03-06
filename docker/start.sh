#!/usr/bin/env sh
set -e

# Normalize APP_KEY for Laravel encryption.
if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
else
  case "$APP_KEY" in
    base64:*) ;;
    *) export APP_KEY="base64:$APP_KEY" ;;
  esac
fi

# Ensure Laravel writable/runtime directories exist in container.
mkdir -p storage/framework/cache
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p resources/views
chmod -R 775 storage bootstrap/cache || true

# Do not fail startup on clear commands in ephemeral container filesystems.
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan package:discover --ansi

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
