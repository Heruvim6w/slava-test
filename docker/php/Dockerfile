FROM php:8.0.9-fpm-alpine3.14

RUN apk add --no-cache \
    npm \
    curl \
    bash \
    nano \
    $PHPIZE_DEPS \
    libzip-dev \
    libxml2-dev \
    icu \
    icu-dev \
    mysql-client \
    redis \
    && docker-php-ext-install zip

RUN apk add libpng-dev \
    gd \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    zip \
    soap \
    bcmath \
    exif

RUN pecl install redis && \
    docker-php-ext-enable redis

RUN npm install -g npm
