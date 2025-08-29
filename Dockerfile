# Use official PHP 8.3 Apache image
FROM php:8.3-apache

# ------------------------------
# Install system dependencies
# ------------------------------
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev libicu-dev libjpeg-dev libfreetype6-dev \
    supervisor nodejs npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# ------------------------------
# Install PHP extensions
# ------------------------------
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd intl

# ------------------------------
# Set working directory
# ------------------------------
WORKDIR /var/www/html

# ------------------------------
# Copy composer files & install PHP deps
# ------------------------------
COPY composer.json composer.lock ./
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ------------------------------
# Copy package files & install frontend deps
# ------------------------------
COPY package*.json ./
RUN npm install

# ------------------------------
# Copy the rest of the project
# ------------------------------
COPY . .

# ------------------------------
# Build Vite assets
# ------------------------------
RUN npm run build

# ------------------------------
# Fix permissions
# ------------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ------------------------------
# Set Apache document root to Laravel public
# ------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80
