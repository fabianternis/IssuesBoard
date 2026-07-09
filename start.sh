#!/bin/bash

TARGET_FILE=".env"
SOURCE_FILE=".env.example"

# Terminate sequence if any command fails
set -e

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

# Replace bash process with PHP server
exec php -S 127.0.0.1:54345 -t public/