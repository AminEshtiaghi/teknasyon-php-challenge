
FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache --virtual .persistent-deps \
    # for soap
    libxml2-dev

RUN docker-php-ext-configure soap --enable-soap \
    && docker-php-ext-install soap