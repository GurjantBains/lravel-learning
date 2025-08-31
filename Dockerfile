## Use official PHP 8.3 Apache image
#FROM php:8.3-apache
#
## ------------------------------
## Install system dependencies
## ------------------------------
#RUN apt-get update && apt-get install -y \
#    git unzip zip curl \
#    libpng-dev libonig-dev libxml2-dev libzip-dev \
#    libpq-dev libicu-dev libjpeg-dev libfreetype6-dev \
#    supervisor nodejs npm \
#    && apt-get clean && rm -rf /var/lib/apt/lists/* \
#    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
## Enable Apache rewrite module
#RUN a2enmod rewrite
#
## ------------------------------
## Install PHP extensions
## ------------------------------
#RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd intl
#
## ------------------------------
## Set working directory
## ------------------------------
#WORKDIR /var/www/html
#
## ------------------------------
## Copy the full project first
## ------------------------------
#COPY . .
#
## ------------------------------
## Install PHP dependencies
## ------------------------------
#RUN composer install --no-dev --optimize-autoloader
#
## ------------------------------
## Install frontend deps & build assets
## ------------------------------
#RUN npm install && npm run build
#
## ------------------------------
## Fix permissions
## ------------------------------
#RUN chown -R www-data:www-data /var/www/html \
#    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
#
## ------------------------------
## Set Apache document root to Laravel public
## ------------------------------
#ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
#    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
#
## Expose port 80
#EXPOSE 80
# ================================
# Stage 1: Composer dependencies
# ================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --optimize

# ================================
# Stage 2: Node / Vite build
# ================================
FROM node:20 AS frontend
WORKDIR /app
COPY package*.json vite.config.* ./
RUN npm install
COPY . .
RUN npm run build

# ================================
# Stage 3: Final PHP Apache runtime
# ================================
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev libicu-dev libjpeg-dev libfreetype6-dev \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd intl

# Set working directory
WORKDIR /var/www/html

# Copy application source (without node_modules/vendor)
COPY . .

# Copy vendor from Composer build stage
COPY --from=vendor /app/vendor ./vendor

# Copy built assets from Node build stage
COPY --from=frontend /app/public/build ./public/build

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose HTTP port
EXPOSE 80

CMD ["apache2-foreground"]
