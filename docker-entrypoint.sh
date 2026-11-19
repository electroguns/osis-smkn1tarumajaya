#!/bin/bash
set -e

# Port binding Render
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Buat symbolic link storage jika belum ada
php artisan storage:link || true

# Optimasi konfigurasi, route, dan view
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan migrasi database otomatis saat deploy
php artisan migrate --force || true

exec "$@"
