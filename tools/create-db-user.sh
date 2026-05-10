#!/bin/bash
# Database User Creator - Shell Script Wrapper
# For use in WSL, Git Bash, or other Unix-like environments
# Usage: bash create-db-user.sh

XAMPP_PATH="${XAMPP_PATH:-C:\\custom-xampp}"
PHP_BIN="${XAMPP_PATH}\\php\\php.exe"
SCRIPT_PATH="${XAMPP_PATH}\\tools\\create-db-user.php"

# Check if running on Windows
if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
    # Windows (Git Bash, MSYS)
    if [ ! -f "$PHP_BIN" ]; then
        echo "Error: PHP not found at $PHP_BIN"
        echo "Make sure XAMPP is installed at $XAMPP_PATH"
        exit 1
    fi

    "$PHP_BIN" "$SCRIPT_PATH"
else
    # Unix-like (WSL, native Linux/Mac)
    # Try common PHP paths
    PHP_PATHS=(
        "/c/custom-xampp/php/php.exe"
        "/mnt/c/custom-xampp/php/php.exe"
        "/usr/bin/php"
        "/opt/homebrew/bin/php"
    )

    PHP_BIN=""
    for path in "${PHP_PATHS[@]}"; do
        if [ -f "$path" ]; then
            PHP_BIN="$path"
            break
        fi
    done

    if [ -z "$PHP_BIN" ]; then
        echo "Error: PHP not found"
        echo "Please ensure PHP is installed and accessible"
        exit 1
    fi

    "$PHP_BIN" "$SCRIPT_PATH"
fi
