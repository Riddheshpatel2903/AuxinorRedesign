# =============================================================================
# Stage 1: Node.js — Build Vite frontend assets
# =============================================================================
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy package files first for caching
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci --include=dev

# Copy application source for the Vite build
COPY resources/ resources/
COPY public/ public/
COPY vite.config.js ./

# Build production assets
RUN npm run build


# =============================================================================
# Stage 2: Composer — Install PHP dependencies (no dev)
# =============================================================================
FROM composer:2.8 AS composer_builder

WORKDIR /app

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install production dependencies only, optimized autoloader
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist


# =============================================================================
# Stage 3: Final production image — PHP 8.3 + Nginx
# =============================================================================
FROM php:8.3-fpm-alpine AS production

# ---- System dependencies ----
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    openssl-dev \
    postgresql-client \
    postgresql-dev \
    mysql-client \
    sqlite \
    && rm -rf /var/cache/apk/*

# ---- PHP extensions required by Laravel 13 ----
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# ---- OPcache tuning for production ----
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.save_comments=1'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# ---- PHP production settings ----
RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=60'; \
    echo 'expose_php=Off'; \
    echo 'display_errors=Off'; \
    echo 'log_errors=On'; \
    echo 'error_log=/var/log/php_errors.log'; \
} > /usr/local/etc/php/conf.d/production.ini

# ---- Set working directory ----
WORKDIR /var/www/html

# ---- Copy vendor from Composer stage ----
COPY --from=composer_builder /app/vendor ./vendor

# ---- Copy built frontend assets from Node stage ----
COPY --from=node_builder /app/public/build ./public/build

# ---- Copy application source ----
COPY . .

# ---- Remove .env if it was accidentally included ----
RUN rm -f .env

# ---- Nginx configuration ----
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# ---- PHP-FPM pool configuration ----
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# ---- Supervisor configuration (Nginx + PHP-FPM) ----
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# ---- Entrypoint script ----
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# ---- Create required directories and set permissions ----
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        storage \
        bootstrap/cache

# ---- Nginx log directories ----
RUN mkdir -p /var/log/nginx /var/run/nginx \
    && chown -R www-data:www-data /var/log/nginx

# ---- Expose HTTP port ----
EXPOSE 8080

# ---- Healthcheck (Laravel built-in /up endpoint) ----
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost:8080/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
