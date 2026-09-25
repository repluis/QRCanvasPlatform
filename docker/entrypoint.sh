#!/bin/bash
# Container entrypoint for QRCanvasPlatform on Render.
# Runs once on container start. Render does NOT support a separate
# "release" command in its Docker runtime, so we chain migrations
# + config caching here, before the web server comes up.

set -e

cd /var/www/html

echo "[entrypoint] running artisan package:discover"
php artisan package:discover --ansi || true

echo "[entrypoint] ensuring storage symlink"
php artisan storage:link || true

echo "[entrypoint] running migrations (before config cache so DATABASE_URL is resolved)"
php artisan migrate --force --no-interaction || true

echo "[entrypoint] fixing legacy domain in existing pages (no-op if none left)"
php artisan pages:fix-domain || true

echo "[entrypoint] caching config / routes / views"
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[entrypoint] starting php artisan serve on 0.0.0.0:${PORT}"
exec php artisan serve --host 0.0.0.0 --port "${PORT}"
