#!/bin/sh
set -e

# Sync public files from the image into the shared volume (nginx reads from here)
rsync -a --delete /var/www/html/public-src/ /var/www/html/public/

# Ensure storage directories exist with correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Discover packages and upgrade Filament (requires .env to be present)
php artisan package:discover --ansi
php artisan filament:upgrade

# Run migrations
php artisan migrate --force

# Cache config, routes, and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
