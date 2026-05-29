#!/bin/sh
set -e

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Run Laravel migrations
echo "Executing database migrations..."
php artisan migrate

echo "Executing database seeding..."
php artisan db:seed

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
