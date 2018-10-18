FROM php:7.2-fpm

WORKDIR /var/www/bot

RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    zlib1g-dev \
    git \
    gnupg \
    apt-transport-https \
    zip \
    unzip

RUN docker-php-ext-install zip

RUN pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug

CMD php-fpm
