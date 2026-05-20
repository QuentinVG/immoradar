#!/bin/sh
set -e

php artisan storage:link >/dev/null 2>&1 || true
php artisan migrate --force

if [ "${DEPLOY_SEED_DEMO:-false}" = "true" ]; then
    php artisan db:seed --force
fi

exec "$@"
