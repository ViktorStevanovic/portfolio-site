# Stage 1: Build frontend assets
FROM node:22-alpine AS node-builder
WORKDIR /build
COPY package*.json ./
RUN npm i --frozen-lockfile
COPY . .
RUN npm run build

# Stage 2: Production PHP-FPM image
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    rsync \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        dom \
        exif \
        opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application source and built frontend assets
COPY . .
COPY --from=node-builder /build/public/build ./public/build

# Store public files separately so entrypoint can populate the shared volume
RUN cp -r /var/www/html/public /var/www/html/public-src

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
