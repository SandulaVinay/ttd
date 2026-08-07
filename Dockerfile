FROM php:8.3-cli

# Prevent Composer memory exhaustion during build
ENV COMPOSER_MEMORY_LIMIT=-1
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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for layer caching
COPY composer.json composer.lock ./

# Install Composer packages without scripts (prevents early framework boot)
RUN composer install --no-interaction --no-scripts --prefer-dist --ignore-platform-reqs

# Copy full application code
COPY . /var/www

# Generate optimized autoloader & build frontend assets
RUN composer dump-autoload --optimize --ignore-platform-reqs
RUN npm install && npm run build

# Ensure storage & cache permissions
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

CMD php artisan config:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
