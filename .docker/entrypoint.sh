#!/bin/sh
set -e

cd /var/www/html

# 1) Always create .env for a fresh install
if [ -f .env.example ]; then
    cp -f .env.example .env
else
    : > .env
fi

# 2) Ensure there is an APP_KEY line
if ! grep -q '^APP_KEY=' .env; then
    printf 'APP_KEY=\n' >> .env
fi

# 3) Generate and inject APP_KEY via PHP (same format as key:generate)
php <<'PHP'
<?php
$file = '.env';
$env = file_get_contents($file);
$env = preg_replace(
    '/^APP_KEY=.*$/m',
    'APP_KEY='.'base64:'.base64_encode(random_bytes(32)),
    $env
);
file_put_contents($file, $env);
PHP

# 4) Run migrations
php artisan migrate --force

# 5) Hand off to the container's CMD
exec "$@"
