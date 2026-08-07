FROM php:8.3-cli

# Prevent Composer memory limits during build
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

# Copy composer manifest files first for Docker layer caching
COPY composer.json composer.lock ./

# Install Composer packages without running scripts (prevents premature boot errors)
RUN composer install --no-interaction --no-scripts --prefer-dist --ignore-platform-reqs

# Copy full application codebase
COPY . /var/www

# Generate optimized autoloader & build frontend Vite assets
RUN composer dump-autoload --optimize --ignore-platform-reqs
RUN npm install && npm run build

# Make entrypoint script executable & set directory permissions
RUN chmod +x /var/www/docker-entrypoint.sh
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 10000

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
