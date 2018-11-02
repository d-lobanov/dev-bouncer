#!/usr/bin/env bash

git pull

# Build and run docker
docker-compose down
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Composer install
docker exec -it bouncer.php php composer.phar install

if [ ! -f .env ]; then
    # Init env
    cp .env.example .env

    # Generate key
    docker exec -it bouncer.php php artisan key:generate
fi

# Run migration
docker exec -it bouncer.php php artisan migrate --force
