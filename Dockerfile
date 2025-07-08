# Gunakan image resmi PHP + Composer
FROM php:8.2-cli

# Install ekstensi dan tools
RUN apt update && apt install -y \
    unzip zip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www

# Salin project Laravel ke container
COPY . .

# Install dependency
RUN composer install --no-dev --optimize-autoloader

# Generate key
RUN php artisan key:generate

# Jalankan Laravel dengan server built-in
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
