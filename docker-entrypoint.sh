#!/bin/sh
set -e

# Create .env if missing
if [ ! -f /var/www/.env ]; then
    if [ -f /var/www/.env.example ]; then
        cp /var/www/.env.example /var/www/.env
    else
        touch /var/www/.env
    fi
fi

# Ensure database directory and sqlite file exist if sqlite is used
mkdir -p /var/www/database
if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
fi

# Ensure storage directories exist
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs

# Set full permissions for storage and bootstrap cache
chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Storage link
php artisan storage:link --force || true

# Clear previous configuration caches
php artisan config:clear || true
php artisan cache:clear || true

# Run migrations if database is available
php artisan migrate --force || true

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
