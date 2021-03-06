[![Build Status](https://semaphoreci.com/api/v1/d-lobanov/dev-bouncer-2/branches/master/shields_badge.svg)](https://semaphoreci.com/d-lobanov/dev-bouncer-2)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e08f2a3ddfa4dab8b2945fd942208e1)](https://app.codacy.com/app/dmitry.lobanow/dev-bouncer?utm_source=github.com&utm_medium=referral&utm_content=d-lobanov/dev-bouncer&utm_campaign=Badge_Grade_Dashboard)
[![codecov](https://codecov.io/gh/d-lobanov/dev-bouncer/branch/master/graph/badge.svg)](https://codecov.io/gh/d-lobanov/dev-bouncer)

## Description
TBD.

## Tests
Unit
```bash
docker exec -it bouncer.php ./bin/run_unit_tests.sh
```

BotMan
```bash
docker exec -it bouncer.php ./bin/run_botman_tests.sh
```
Coverage
```bash
docker exec -it bouncer.php vendor/bin/phpunit --coverage-clover=coverage.xml
```

## Docker useful commands
Build images
```bash
docker image build --file=./docker/prod/Dockerfile-nginx --tag=dmitrylobanow/bouncer_nginx .
docker image build --file=./docker/prod/Dockerfile-php --tag=dmitrylobanow/bouncer_php .

docker push dmitrylobanow/bouncer_nginx
docker push dmitrylobanow/bouncer_php
```
