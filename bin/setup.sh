#!/usr/bin/env bash

# Composer install
docker exec -it bouncer.php php composer.phar install

# Init env
cp .env.example .env

# Generate key
docker exec -it bouncer.php php artisan key:generate

# Init database
touch storage/database.sqlite
docker exec -it bouncer.php php artisan migrate

printf "\e[33mAttention:\e[0m change MICROSOFT_APP_ID and MICROSOFT_APP_KEY in .env\n"
