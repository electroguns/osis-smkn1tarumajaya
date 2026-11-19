# Gunakan image resmi PHP 8.3 dengan Apache
FROM php:8.3-apache

# Install ekstensi & dependensi sistem untuk Laravel, Intervention Image, dan PDF
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan Apache mod_rewrite untuk routing URL Laravel
RUN a2enmod rewrite

# Konfigurasi Apache DocumentRoot ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Pasang Composer versi 2 resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin source code project ke container
WORKDIR /var/www/html
COPY . .

# Install paket PHP tanpa dependensi development
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Berikan izin tulis untuk storage dan cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Salin dan pasang startup entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port HTTP
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
