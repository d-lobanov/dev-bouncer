[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e08f2a3ddfa4dab8b2945fd942208e1)](https://app.codacy.com/app/dmitry.lobanow/dev-bouncer?utm_source=github.com&utm_medium=referral&utm_content=d-lobanov/dev-bouncer&utm_campaign=Badge_Grade_Dashboard)
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

