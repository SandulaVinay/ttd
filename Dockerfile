FROM php:8.3-cli

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
    libsqlite3-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install Composer dependencies & build frontend assets
RUN composer install --optimize-autoloader --no-interaction --ignore-platform-reqs
RUN npm install && npm run build

# Storage permissions
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD php artisan config:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
