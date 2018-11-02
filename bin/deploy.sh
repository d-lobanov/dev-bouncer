#!/usr/bin/env bash

echo "Build and run docker"
docker-compose down
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

echo "Composer install"
docker exec -t prod.php php composer.phar install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

if [ ! -f .env ]; then
    echo "Init env"
    cp .env.example .env

    echo "Generate key"
    docker exec -t prod.php php artisan key:generate
fi
