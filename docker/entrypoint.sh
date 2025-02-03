#!/bin/sh

set -e

php-fpm && /usr/bin/supervisord

php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan route:cache

if [ ! -f "/var/www/storage/migrated.lock" ]; then
    php artisan migrate --seed
    touch /var/www/storage/migrated.lock
fi

exec "$@"