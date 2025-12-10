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

USER www-data

EXPOSE 8011

CMD ["sh", "-lc", "\
    cd /var/www/html && \
    \
    # 1) Always create .env for a fresh install
    if [ -f .env.example ]; then \
        cp -f .env.example .env; \
    else \
        : > .env; \
    fi && \
    \
    # 2) Ensure there is an APP_KEY line
    if ! grep -q '^APP_KEY=' .env; then \
        printf 'APP_KEY=\n' >> .env; \
    fi && \
    \
    # 3) Generate and inject APP_KEY via PHP (avoid sed escaping issues)
    php -r 'file_put_contents(\".env\", preg_replace(\"/^APP_KEY=.*$/m\", \"APP_KEY=\".\"base64:\".base64_encode(random_bytes(32)), file_get_contents(\".env\")));' && \
    \
    # 4) Run migrations
    touch database/database.sqlite && \
    php artisan migrate --force && \
    \
    # 5) Start Laravel dev server
    php artisan serve --host=0.0.0.0 --port=8011 \
"]
