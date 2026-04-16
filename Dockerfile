# ── Stage 1: Node — build Vite assets ──────────────────────────────────────
FROM node:22-alpine AS node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci

COPY resources/ resources/
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
RUN npm run build

# ── Stage 2: Composer — install PHP dependencies ────────────────────────────
FROM composer:2 AS composer-builder

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ── Stage 3: Production — PHP-FPM ───────────────────────────────────────────
FROM php:8.4-fpm-alpine AS production

# System deps
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# PHP config
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /var/www

# Copy vendor from composer stage
COPY --from=composer-builder /app/vendor ./vendor

# Copy built assets from node stage
COPY --from=node-builder /app/public/build ./public/build

# Copy application code
COPY . .

# Fix permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
