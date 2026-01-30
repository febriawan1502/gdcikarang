#!/bin/bash

# Stop script on first error
set -e

echo "🚀 Starting deployment..."

# 1. Pull latest code
echo "📦 Pulling latest changes..."
git pull origin main

# 2. Install Dependencies (in case composer.json changed)
echo "🔧 Installing dependencies..."
# If using Docker on production:
# docker-compose exec -T app composer install --no-dev --optimize-autoloader
# If using Native PHP on production:
composer install --no-dev --optimize-autoloader

# 3. Run Migrations
echo "🗄️ Running migrations..."
# If using Docker:
# docker-compose exec -T app php artisan migrate --force
# If Native:
php artisan migrate --force

# 4. Clear/Cache Config & Routes
echo "🧹 Clearing and caching configuration..."
# If Docker:
# docker-compose exec -T app php artisan optimize
# If Native:
php artisan optimize

# 5. Build Frontend (since nodejs is available)
if [ -f "package-lock.json" ]; then
    echo "🎨 Building frontend assets..."
    npm install
    npm run build
fi

# 6. Restart Queue (if using Supervisor)
# echo "🔄 Restarting queues..."
# php artisan queue:restart

echo "✅ Deployment finished successfully!"
