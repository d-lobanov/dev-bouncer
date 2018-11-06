#!/usr/bin/env bash

docker-compose pull
docker-compose up -d
docker exec -it prod.php php artisan config:cache
docker image prune -a -f
