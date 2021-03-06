FROM php:8.0-fpm-alpine

ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html

RUN apk add --no-cache \
    yaml-dev \
    bzip2-dev \
    g++ \
    freetype \
    libxslt-dev \
    libbz2 \
    libpng \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    zlib-dev \
    zstd-dev


RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install bz2 xsl ctype filter opcache tokenizer gd pdo pdo_mysql exif pcntl sockets
