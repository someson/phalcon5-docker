FROM php:8.2-fpm-bullseye

RUN apt-get update  \
    && apt-get install -y --no-install-recommends \
        apt-transport-https \
        git curl \
        libtool libicu-dev libonig-dev libpng-dev libxml2-dev libxslt1-dev libzip-dev libyaml-dev libssl-dev \
        zip unzip

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && install-php-extensions yaml

ENV APCU_VERSION=5.1.22
ENV PHALCON_VERSION=5.8.0

RUN set -xe \
    && pecl channel-update pecl.php.net \
    && pecl install apcu-${APCU_VERSION} xdebug-3.3.1 phalcon-${PHALCON_VERSION} \
    && docker-php-ext-enable xdebug apcu phalcon \
    && docker-php-ext-install -j$(nproc) \
        zip \
        calendar \
        intl \
        pdo_mysql \
        opcache \
    && pecl clear-cache

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY php/php.ini /usr/local/etc/php/conf.d/php-override.ini
COPY fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN touch /var/log/remote.log && chmod 666 /var/log/remote.log

WORKDIR /var/www
