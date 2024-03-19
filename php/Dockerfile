FROM php:fpm-alpine

WORKDIR /var/www/html

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./data/* /var/www/html/
RUN composer require predis/predis
