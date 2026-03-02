FROM composer:2 AS composer_stage

FROM php:8.4-apache

# Install system dependencies and PHP extensions needed for Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring bcmath exif pcntl \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for pretty URLs
RUN a2enmod rewrite

# Set Apache document root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer in the runtime image
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# Copy the application code
COPY . .

# Install dependencies (uses composer.json / composer.lock from project)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Ensure correct permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
