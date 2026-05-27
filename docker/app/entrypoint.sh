#!/bin/sh
set -e

# Run Laravel migrations
echo "Executing database migrations..."
php artisan migrate --force

# Uncomment this when seeders/factories are ready
# echo "Executing database seeding..."
# php artisan db:seed --force

# Check if package.json exists
if [ -f "package.json" ]; then
    # Check if node_modules directory does NOT exist
    if [ ! -d "node_modules" ]; then
        echo "node_modules not found. Running npm install..."
        npm install
        echo "Frontend dependencies installed successfully."
    else
        echo "node_modules already exists. Skipping npm install."
    fi
fi

npm run build

# Execute the main container command (usually php-fpm)
exec "$@"
