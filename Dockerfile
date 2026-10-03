FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mbstring pdo_mysql \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

COPY deploy/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/

RUN mkdir -p /var/www/html/runtime/cache /var/www/html/runtime/logs \
    && chown -R www-data:www-data /var/www/html/runtime

EXPOSE 80
