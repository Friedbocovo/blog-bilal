#!/bin/sh
set -e

echo "==> Variables d'environnement:"
echo "DB_CONNECTION=$DB_CONNECTION"
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"

echo "==> Génération de la clé APP_KEY si absente..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "==> Nettoyage du cache config..."
php artisan config:clear
php artisan cache:clear

echo "==> Création du lien storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Migrations..."
php artisan migrate --force

echo "==> Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
