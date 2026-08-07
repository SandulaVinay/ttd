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

# Ensure database directory and sqlite file exist
mkdir -p /var/www/database
if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
fi

# Ensure storage directories exist
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs

# Generate autoloader at container boot
composer dump-autoload --optimize --no-dev --ignore-platform-reqs || true

# Set full permissions for storage, bootstrap cache, and database
chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Storage link
php artisan storage:link --force || true

# Clear configuration cache
php artisan config:clear || true

# 1. Run migrations FIRST to create database tables (cache, sessions, users, etc.)
php artisan migrate --force || true

# 2. Seed database if tables were just created
php artisan db:seed --force || true

# 3. Clear cache AFTER database tables exist
php artisan cache:clear || true

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
