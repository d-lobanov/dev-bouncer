FROM php:7.2-fpm

WORKDIR /var/www/bot

RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    zlib1g-dev \
    git \
    gnupg \
    apt-transport-https \
    zip \
    unzip \
    cron

RUN docker-php-ext-install zip

# Cron
RUN printf "* * * * * cd /var/www/bot && /usr/local/bin/php artisan schedule:run >> /var/www/bot/storage/logs/cron.log 2>&1\n" >> /etc/cron.d/scheduler-cron
RUN chmod 0644 /etc/cron.d/scheduler-cron
RUN crontab /etc/cron.d/scheduler-cron
RUN touch /var/log/cron.log
# End

# Xdebug
RUN pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug
# End

CMD php-fpm
