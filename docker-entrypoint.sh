#!/bin/bash
set -e

# cd /var/www/html

# Function for logging
log_message() {
    echo "$(date +'%Y-%m-%d %T') - $1"
}

# Create directories and set permissions
log_message "Setting up directories and permissions..."
# mkdir -p storage/framework/{cache,sessions,views}
# mkdir -p storage/logs
# mkdir -p bootstrap/cache

# Only set permissions (don't change ownership of host-mounted volumes)
# chmod -R 775 storage bootstrap/cache

# Wait for database to be ready
log_message "Waiting for database..."
max_retries=30
count=0

while [ $count -lt $max_retries ]; do
    if mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; then
        log_message "MySQL is ready!"
        break
    fi
    count=$((count+1))
    log_message "Waiting for MySQL... attempt $count/$max_retries"
    sleep 2
done

if [ $count -eq $max_retries ]; then
    log_message "ERROR: Failed to connect to MySQL after $max_retries attempts"
    exit 1
fi

# Run Laravel migrations
log_message "Running migrations..."
php artisan migrate --force

# Run Laravel optimizations
log_message "Running Laravel optimizations..."
php artisan config:cache
php artisan view:cache
php artisan route:cache

# Start Apache
log_message "Starting Apache..."
exec apache2-foreground