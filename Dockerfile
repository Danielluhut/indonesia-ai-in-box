# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Atur working directory
WORKDIR /var/www/html

# Copy semua file Laravel ke container
COPY . .

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate cache config
RUN php artisan config:clear && php artisan cache:clear

# Ganti permission storage & bootstrap
RUN chmod -R 775 storage bootstrap/cache

# Pastikan semua folder Laravel ada dan bisa ditulis
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod -R 775 storage bootstrap/cache


# Expose port default (Railway otomatis pakai ini)
EXPOSE $PORT
ENV PORT=8000

# Jalankan PHP built-in server, bukan artisan serve!
CMD sh -c "php -S 0.0.0.0:$PORT -t public"
