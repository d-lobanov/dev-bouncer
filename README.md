[![Build Status](https://travis-ci.com/d-lobanov/dev-bouncer.svg?branch=master)](https://travis-ci.com/d-lobanov/dev-bouncer)

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
docker exec -it bouncer.php php vendor/bin/phpunit --coverage-clover build/coverage/xml
```

