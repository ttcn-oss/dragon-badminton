FROM php:8.3-apache

RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev libsqlite3-dev nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip gd bcmath \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf \
    && sed -ri -e 's!Listen 80!Listen ${PORT}!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!<VirtualHost \*:80>!<VirtualHost *:${PORT}>!g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install && npm run build \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force; apache2-foreground"]