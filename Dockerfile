#FROM php:8.3-apache
#
## Install dependencies
#RUN apt-get update && apt-get install -y \
#    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
#    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
#
## Enable Apache modules
#RUN a2enmod rewrite
#
## Set working directory
#WORKDIR /var/www/html
#
## Copy project files
#COPY . .
#
## Install Composer
#COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#RUN composer install --no-dev --optimize-autoloader
#
## Fix permissions
#RUN chown -R www-data:www-data /var/www/html \
#    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
#
## Use Laravel public/ as Apache root
#ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
#    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
#
#EXPOSE 80
FROM php:8.3-apache

# --- Install system dependencies ---
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    libicu-dev libpq-dev libjpeg-dev libfreetype6-dev supervisor \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip xml curl

# --- Install Redis PHP extension ---
RUN pecl install redis && docker-php-ext-enable redis

# --- Enable Apache modules ---
RUN a2enmod rewrite

# --- Set working directory ---
WORKDIR /var/www/html

# --- Copy project files ---
COPY . .

# --- Install Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# --- Set permissions ---
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- Set Apache root to Laravel public/ ---
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# --- Optional: Install Node.js & npm for frontend build ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# --- Optional: Build frontend assets if needed ---
# COPY package*.json ./
# RUN npm install
# RUN npm run build

# --- Expose Apache port ---
EXPOSE 80

# --- Start Apache ---
CMD ["apache2-foreground"]
