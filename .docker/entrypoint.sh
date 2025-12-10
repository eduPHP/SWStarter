#!/bin/sh
set -e

cd /var/www/html

# 1) Always create .env for a fresh install
cp -f .env.example .env

# 3) Generate and inject APP_KEY via PHP (same format as key:generate)
php <<'PHP'
<?php
$file = '.env';
$env = file_get_contents($file);
$env = preg_replace(
    ['/^APP_KEY=.*$/m', '/^REDIS_HOST=.*$/m'],
    [
      'APP_KEY='.'base64:'.base64_encode(random_bytes(32)),
      'REDIS_HOST=redis'
    ],
    $env
);
file_put_contents($file, $env);
PHP

[ -f /var/www/html/database/database.sqlite ] || touch /var/www/html/database/database.sqlite
chmod 666 /var/www/html/database/database.sqlite

# 4) Run migrations
php artisan migrate --force

# 5) Hand off to the container's CMD
exec "$@"
