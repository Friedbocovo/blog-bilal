FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    oniguruma-dev \
    libxml2-dev \
    supervisor

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache

# Installer Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier les fichiers de dépendances en premier (cache Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci

# Copier le reste du projet
COPY . .

# Finaliser Composer
RUN composer dump-autoload --optimize

# Builder les assets Vite/Tailwind
RUN npm run build

# Permissions storage et cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/run \
    && mkdir -p /var/lib/nginx/tmp/client_body \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log/nginx \
    && chown -R www-data:www-data /var/run

# Config Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Config Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# Script de démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
