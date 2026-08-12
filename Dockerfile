# Step 1: Vendor Stage using official Composer 2 image
FROM composer:2 as vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer update \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-audit \
    --ignore-platform-reqs \
    --prefer-dist \
    --no-interaction

# Step 2: Final Production Stage (PHP 8.4 for Laravel 13 compatibility)
FROM php:8.4-cli

ENV PORT=10000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libsqlite3-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite mbstring exif pcntl bcmath gd

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy pre-built vendor dependencies from Stage 1
COPY --from=vendor /app/vendor /var/www/vendor

# Copy application codebase
COPY . /var/www

# Build frontend Vite assets
RUN npm install && npm run build

# Make entrypoint script executable & set permissions
RUN chmod +x /var/www/docker-entrypoint.sh
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 10000

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
