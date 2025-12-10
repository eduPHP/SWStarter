FROM php:8.4-fpm

# System deps
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libonig-dev \
    pkg-config \
    nodejs \
    npm \
    $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions commonly needed by Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip bcmath

# Redis PHP extension (phpredis)
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source (assume it was just pulled from GitHub)
COPY . .

# Make sure app is writable
RUN chown -R www-data:www-data /var/www/html

# Install composer deps
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# Install JS deps + build assets
RUN NODE_OPTIONS="--max-old-space-size=512" npm ci && npm run build

# Copy entrypoint
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 8011

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8011"]
