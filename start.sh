#!/bin/bash

# Terminate sequence if any command fails
set -e

TARGET_FILE=".env"
SOURCE_FILE=".env.example"
IS_TEST=false
IS_DEV=false

# 1. Parameter Evaluation
for arg in "$@"; do
    if [ "$arg" == "--test" ]; then
        IS_TEST=true
    elif [ "$arg" == "--dev" ]; then
        IS_DEV=true
    fi
done

# 2. Environment State Initialization
if [ ! -f "$TARGET_FILE" ]; then
    if [ ! -f "$SOURCE_FILE" ]; then
        echo "Error: Source state missing. $SOURCE_FILE not found." >&2
        exit 1
    fi
    
    cp "$SOURCE_FILE" "$TARGET_FILE"
    echo "State modified: Copied $SOURCE_FILE to $TARGET_FILE."
else
    echo "State unchanged: $TARGET_FILE already exists."
fi

# 3. Dependency Resolution
if [ "$IS_TEST" = true ] || [ "$IS_DEV" = true ]; then
    echo "State: TEST/DEV. Installing all dependencies (including dev)..."
    composer install
else
    echo "State: PRODUCTION. Installing dependencies (--no-dev)..."
    composer install --no-dev --optimize-autoloader
fi

# 4. Database Migration
echo "Executing migrations..."
php db/migrator.php

# 5. Conditional Test Execution
if [ "$IS_TEST" = true ]; then
    echo "Executing PHPUnit..."
    # Verifying binary existence prevents obscure command-not-found errors
    if [ -f "vendor/bin/phpunit" ]; then
        vendor/bin/phpunit
    else
        echo "Structural Error: vendor/bin/phpunit missing. Ensure phpunit/phpunit is in require-dev." >&2
        exit 1
    fi
elif [ "$IS_DEV" = true ]; then
    echo "State: DEV. Bypassing PHPUnit execution."
fi

# 6. Server Initialization
echo "Deploying built-in server..."
# Replace bash process with PHP server
exec php -S 127.0.0.1:54345 -t public/