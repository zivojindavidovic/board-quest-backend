#!/bin/bash
set -e

echo "Clearing caches..."
php artisan config:clear && echo "Config cleared ✅"
php artisan route:clear && echo "Routes cleared ✅"
php artisan view:clear && echo "Views cleared ✅"

echo "Running migrations..."
php artisan migrate --force && echo "Migrations done ✅"

echo "Starting server on port $PORT"
exec php -S 0.0.0.0:${PORT:-8000} -t public
