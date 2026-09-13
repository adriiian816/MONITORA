FROM php:8.2-cli

# Instal dependensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Instal ekstensi PHP untuk MySQL
RUN docker-php-ext-install pdo_mysql mbstring gd

# Ambil Composer resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tentukan direktori kerja di dalam kontainer
WORKDIR /var/www

# Salin semua file project ke dalam kontainer
COPY . .

# Instal dependensi PHP (Laravel)
RUN composer install --no-dev --optimize-autoloader

# Atur izin akses untuk storage dan cache Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Port default yang digunakan oleh Render
EXPOSE 10000

# Perintah untuk menjalankan Laravel di Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}