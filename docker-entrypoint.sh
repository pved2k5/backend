#!/bin/bash
set -e

# Define all functions first
start_services() {
    echo "=== Starting PHP-FPM ==="
    php-fpm -D

    echo "=== Starting Nginx ==="
    nginx -g "daemon off;"
}

# Simple database setup function
setup_database() {
    echo "Setting up database..."

    # Wait a bit for MySQL to be ready
    sleep 10

    # Create database and user if they don't exist
    mysql -h"${MYSQL_HOST:-db}" -P"${MYSQL_PORT:-3306}" -uroot -p"${MYSQL_ROOT_PASSWORD:-root}" << EOF 2>/dev/null || true
CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE:-drupal};
CREATE USER IF NOT EXISTS '${MYSQL_USER:-drupal}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD:-drupal}';
GRANT ALL PRIVILEGES ON ${MYSQL_DATABASE:-drupal}.* TO '${MYSQL_USER:-drupal}'@'%';
FLUSH PRIVILEGES;
EOF
}

# Function to check if Drupal is REALLY installed (in database)
check_drupal_installed() {
    if [ -f "vendor/bin/drush" ]; then
        # Check if drush can connect to the database and Drupal is installed
        if ./vendor/bin/drush status --fields=bootstrap 2>/dev/null | grep -q "Successful"; then
            return 0
        fi
    fi
    return 1
}

install_drupal() {
    echo "=== Installing Drupal ==="

    # Setup database first
    setup_database

    # Remove existing settings.php to start fresh
    if [ -f "web/sites/default/settings.php" ]; then
        echo "Removing existing settings.php to start fresh..."
        rm -f web/sites/default/settings.php
    fi

    # Create settings.php from default.settings.php
    echo "Creating settings.php..."
    cp web/sites/default/default.settings.php web/sites/default/settings.php
    chmod 666 web/sites/default/settings.php
    # Add simple database configuration
    echo "Adding database configuration to settings.php..."
    cat << EOF >> web/sites/default/settings.php

// Database configuration
\$settings['config_sync_directory'] = '../config/sync';
\$settings['http_client_config'] = ['verify' => false];
\$settings['simple_oauth.key_directory'] = realpath(__DIR__ . '/../keys');
EOF

    # Make settings.php read-only
    chmod 444 web/sites/default/settings.php
    echo "settings.php permissions set to read-only"

    # Install Drupal using drush
    if [ -f "vendor/bin/drush" ]; then
        echo "Running Drupal installation..."

        # Use simple installation
        ./vendor/bin/drush site:install standard -y \
            --db-url="mysql://${MYSQL_USER:-drupal}:${MYSQL_PASSWORD:-drupal}@${MYSQL_HOST:-db}:${MYSQL_PORT:-3306}/${MYSQL_DATABASE:-drupal}" \
            --site-name="${SITE_NAME:-My Drupal Site}" \
            --account-name="${ADMIN_USER:-admin}" \
            --account-pass="${ADMIN_PASS:-admin}"

        echo "Drupal installation completed!"
    else
        echo "Drush not available - cannot install Drupal automatically"
    fi
}

# Simple configuration import
import_configuration() {
    echo "=== Attempting Configuration Import ==="

    # Check if config files exist
    if [ ! -d "config/sync" ] || [ -z "$(find config/sync -name '*.yml' -type f | head -n 1)" ]; then
        echo "No config files found, skipping import."
        return 0
    fi

        # Force enable the required module and rebuild container
    ./vendor/bin/drush en -y metatag
    ./vendor/bin/drush cr

    # Fix for Shortcut link and Shortcut set conflicts
    echo "Cleaning up shortcut entities to prevent config import conflicts..."
    ./vendor/bin/drush entity:delete shortcut_set --yes 2>/dev/null || true

    # First, check if we need to fix UUID mismatch
if [ -f "config/sync/system.site.yml" ]; then
    echo "Checking for UUID mismatch..."
    CONFIG_UUID=$(grep 'uuid:' config/sync/system.site.yml | head -1 | awk '{print $2}')
    DB_UUID=$(./vendor/bin/drush config:get system.site uuid --format=string 2>/dev/null || echo "")

    if [ -n "$CONFIG_UUID" ] && [ -n "$DB_UUID" ] && [ "$CONFIG_UUID" != "$DB_UUID" ]; then
        echo "Fixing UUID mismatch: setting site UUID to $CONFIG_UUID"
        ./vendor/bin/drush config:set system.site uuid "$CONFIG_UUID" -y
    fi
fi

    # Try import with error suppression
    echo "Importing configuration..."
    if ./vendor/bin/drush -y config:import 2>/dev/null; then
        echo "Configuration imported successfully!"
        ./vendor/bin/drush cache:rebuild 2>/dev/null || true
    else
        echo "Configuration import failed or not needed."
        echo "You may need to resolve conflicts manually with: drush config:import"
    fi
}

# Main execution starts here
# Check if setup has already been completed
if [ -f "/var/www/html/.setup_completed" ]; then
    echo "Setup already completed, starting services..."
    start_services
    exit 0
fi

echo "=== Starting Drupal Setup ==="

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
else
    echo "Vendor directory already exists, skipping composer install"
fi

# Check if Drupal is REALLY installed (not just settings.php exists)
if check_drupal_installed; then
    echo "Drupal is properly installed in database."
    echo "Proceeding with configuration import..."
    import_configuration
else
    echo "Drupal not installed or not operational - installing now..."
    install_drupal

    # Import configuration after installation
    echo "Proceeding with configuration import after installation..."
    import_configuration
fi

# Mark setup as completed
#touch /var/www/html/.setup_completed
echo "Setup completed successfully."

# Start services
echo "=== Starting Services ==="
start_services

