#!/bin/sh
# docker/entrypoint.sh: create this file (and the docker/ folder).
# The first line says "run me with sh". This script runs every time a
# container starts.

# Stop at the first command that fails, instead of starting a broken app
set -e

# Create the folders Laravel writes to (-p: no error if they already exist)
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
# Create an empty SQLite database file if there isn't one yet
touch database/database.sqlite

# Create or update the tables. --force is required in production.
php artisan migrate --force
# Load the demo athletes (safe to repeat thanks to updateOrCreate)
php artisan db:seed --force
# Combine all config files into one cached file (faster). From now on
# env() returns null outside config files, which is why the app uses config().
php artisan config:cache
# Pre-compile the Blade templates
php artisan view:cache

# Apache runs as the user www-data, so give it ownership of the folders it writes to
chown -R www-data:www-data storage bootstrap/cache database

# Replace this script with Apache in the foreground. "exec" makes Apache the
# container's main process, so it receives the stop signal directly.
exec apache2-foreground