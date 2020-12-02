FROM php:7.4-fpm-alpine

ENV APCU_VERSION=5.1.19

RUN apk update && apk add ca-certificates && rm -rf /var/cache/apk/*
COPY php/cert/cacert.pem /usr/local/share/ca-certificates/cacert.pem
RUN chmod 644 /usr/local/share/ca-certificates/cacert.pem && update-ca-certificates

RUN apk add git \
    && apk add --no-cache --virtual .persistent-deps \
    libxml2-dev \
    # for intl extension
    icu-libs

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        icu-libs \
    && pecl install apcu-${APCU_VERSION} apcu_bc xdebug \
    && docker-php-ext-enable xdebug apcu \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install -j$(nproc) \
        calendar \
        intl \
        pdo_mysql \
    && pecl clear-cache \
    && apk del .build-deps \
    && docker-php-source delete \
    && rm -rf /tmp/* /var/cache/apk/*

COPY php/php.ini /usr/local/etc/php/conf.d/php-override.ini
COPY fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN touch /var/log/remote.log && chmod 666 /var/log/remote.log

WORKDIR /var/www/html

