# Dockerfile: create this file in the project root.
# A recipe for building an image. Each instruction creates a "layer", and
# Docker reuses (caches) a layer when nothing it depends on has changed.
# This file has three stages. Each FROM starts a new one.

# ---- Stage 1: Node builds the TypeScript and CSS ----
# Start from an official image that already has Node 24 (alpine = small Linux).
# "AS assets" names this stage so the last stage can copy from it.
FROM node:24-alpine AS assets
# All following commands run inside /app (created if missing)
WORKDIR /app
# Copy ONLY the package files first. The next layer (npm ci) is then reused
# from cache as long as these two files don't change, even when your code
# does. This is the build-caching trick from your CTO interview.
COPY package.json package-lock.json ./
# Install the exact versions in package-lock.json
RUN npm ci
# Now copy the rest of the project (minus .dockerignore)
COPY . .
# Vite compiles everything into public/build
RUN npm run build








# ---- Stage 2: Composer installs PHP packages (no dev tools) ----
FROM composer:2 AS vendor
WORKDIR /app
# Same trick: lock files first, so this install layer gets cached
COPY composer.json composer.lock ./
# --no-dev skips test-only packages like Faker and PHPUnit.
# --no-scripts and --no-autoloader wait until the app code is copied in.
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY . .
# Build the class map that lets PHP find your classes quickly
RUN composer dump-autoload --optimize --no-dev --no-scripts








# ---- Stage 3: the image that actually runs: PHP + Apache only ----
# Node and Composer stay behind in the earlier stages, so this image is
# smaller and has fewer programs that could have security holes.
FROM php:8.4-apache

# Environment variables baked into the image (Render and docker run can override them)
#   APACHE_DOCUMENT_ROOT  serve only public/, never .env or your source code
#   PORT                  the port Apache listens on (Render sets its own)
#   APP_ENV / APP_DEBUG   production mode, no detailed error pages for visitors
#   LOG_CHANNEL=stderr    send Laravel's logs to the container output
#   DB_CONNECTION=sqlite  use a SQLite file as the database
#   SESSION_DRIVER / CACHE_STORE  keep sessions in cookies and cache in files,
#                         so neither needs database tables
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=80 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    SESSION_DRIVER=cookie \
    CACHE_STORE=file

# Configure Apache. sed edits text files in place:
#   1. point the site at public/ instead of the project root
#   2. and 3. listen on $PORT instead of a fixed 80
#   4. enable mod_rewrite so URLs like /athletes/7 reach Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's/^Listen 80$/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -ri -e 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf \
 && a2enmod rewrite

WORKDIR /var/www/html
# Take the app code + vendor/ from stage 2...
COPY --from=vendor /app ./
# ...and only the built frontend files from stage 1
COPY --from=assets /app/public/build ./public/build
# Make the startup script executable
RUN chmod +x docker/entrypoint.sh

# Document which port the container listens on
EXPOSE 80
# The command that runs when a container starts
CMD ["docker/entrypoint.sh"]