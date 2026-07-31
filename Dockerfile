# =============================================================
# QRCanvasPlatform — Dockerfile for Render.com
# Stack: PHP 8.3 (Laravel 13) + Node 20 (Vite 8 / Tailwind 4)
# =============================================================
# Render injects $PORT and expects the app to listen on it.
# We use the built-in PHP dev server (artisan serve) — Render's
# official PHP runtime works the same way.

# ---------- Stage 1: build the JS/CSS bundle with Node ----------
# Node 22 LTS is the safest pairing for Vite 8 + Rolldown + Tailwind 4 oxide.
# Debian slim (glibc), not Alpine: rolldown/oxide/lightningcss ship musl
# native bindings that have repeatedly failed to load on Alpine's newer
# musl builds (fails fast with "aggregateBindingErrorsIntoJsError").
# This stage's output is just static assets copied into the final image,
# so the extra size here doesn't affect the runtime image at all.
FROM node:22-bookworm-slim AS assets

WORKDIR /app

# Install only what npm needs to fetch packages
COPY package.json package-lock.json* .npmrc ./
RUN npm ci

# Copy the source Vite needs and build.
# NODE_OPTIONS raises V8's max heap so rolldown doesn't OOM during
# the link phase on Render's starter-tier build (≈512MB–1GB containers).
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
ENV NODE_OPTIONS="--max-old-space-size=4096"
RUN npm run build


# ---------- Stage 2: install PHP deps ----------
FROM composer:2.7 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader


# ---------- Stage 3: runtime image ----------
FROM php:8.3-cli-alpine

# Render-specific: install the system libs the PHP extensions need.
# pdo_pgsql + pgsql client for Postgres.
# mbstring, exif, gd, zip are commonly required by Laravel apps
# that handle uploads and QR codes.
RUN apk add --no-cache \
        bash \
        git \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        libxml2-dev \
        postgresql-dev \
        autoconf \
        g++ \
        make \
        linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        gd \
        zip \
        bcmath \
        intl \
        opcache \
    && apk del autoconf g++ make linux-headers libxml2-dev

# Install Composer inside the runtime image so we can run any
# artisan-level helper at boot.
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP production tuning
ENV PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=192 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=20000 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

# Render requires listening on $PORT (default 10000)
ENV PORT=10000 \
    APP_ENV=production \
    APP_DEBUG=false

# Copy composer artifacts first so they cache independently
COPY --from=vendor /app/vendor ./vendor

# Now copy the rest of the application
COPY . .

# Put the prebuilt Vite assets on top of whatever was checked in
COPY --from=assets /app/public/build ./public/build

# Render runs as uid 1000 (the "render" user). Match it so the
# storage and cache directories are writable.
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        storage/app/public/qr \
    && chown -R 1000:1000 storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# Entrypoint: run package discovery, ensure storage link exists,
# cache config/routes/views, run migrations, then start the server.
# We use a shell script so each step is readable and we can use
# Render env vars at runtime (NOT baked into the image).
# Must run as root (before USER 1000) — chmod on a just-copied,
# root-owned file fails once we've already dropped privileges.
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER 1000

EXPOSE 10000

# Health check — Render uses this to confirm the service is up.
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS "http://127.0.0.1:${PORT}/up" || exit 1

ENTRYPOINT ["entrypoint.sh"]
