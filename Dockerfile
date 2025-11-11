 
# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git unzip zip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql gd

# Aktifkan mod_rewrite untuk Laravel (routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy semua file project ke container
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Laravel build cache
RUN php artisan key:generate || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# Expose port 8080 (yang digunakan Railway/Render)
EXPOSE 8080

# Jalankan Laravel pakai PHP built-in server
CMD php artisan serve --host=0.0.0.0 --port=8080