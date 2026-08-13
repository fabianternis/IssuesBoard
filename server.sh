#!/bin/bash

set -e

TARGET_FILE=".env"
SOURCE_FILE=".env.example"

# 1. Target Environment Check
if [ ! -f "$TARGET_FILE" ]; then
    if [ ! -f "$SOURCE_FILE" ]; then
        echo "Error: Configuration missing. $SOURCE_FILE not found." >&2
        exit 1
    fi
    cp "$SOURCE_FILE" "$TARGET_FILE"
    echo "Created $TARGET_FILE from $SOURCE_FILE. Update production secrets before proceeding."
fi

# 2. Production Dependency Resolution
echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Database Migration
echo "Executing database migrations..."
php db/migrator.php

# 4. Process Handoff
# Use system service manager or process manager (e.g., systemd, Supervisor, Docker CMD)
# Do NOT use `php -S` in production.
echo "Deployment tasks complete. Handoff to web server / application runner (e.g., php-fpm)."