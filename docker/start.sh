#!/bin/sh
set -e

echo "==> Génération de la clé APP_KEY si absente..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "==> Création du lien storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Mise en cache de la config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Migrations..."
php artisan migrate --force

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
