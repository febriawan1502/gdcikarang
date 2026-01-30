#!/bin/bash

# Run migrations (careful in production, maybe only if explicitly asked?)
# For now, let's just clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm
