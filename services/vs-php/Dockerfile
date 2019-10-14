FROM php:7-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

COPY vs-php/config/php-log.conf /usr/local/etc/php-fpm.d/zz-log.conf
