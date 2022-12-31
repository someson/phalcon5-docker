[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://choosealicense.com/licenses/mit/)
[![Made in Ukraine](https://img.shields.io/badge/made_in-ukraine-ffd700.svg?labelColor=0057b7)](https://supportukrainenow.org/)
[![Russian Warship Go Fuck Yourself](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/RussianWarship.svg)](https://stand-with-ukraine.pp.ua)

# Setup

```sh
$ git clone --branch php81 https://github.com/someson/phalcon5-docker.git .
$ docker-compose up -d --build
```

- add to your ```[...]/etc/hosts```

```text
127.0.0.1 phalcon5.test
```

```sh
$ docker-compose exec app-service composer install
```

# CLI

## Run console commands

```bash
$ php ./scripts/cli.php [handler] [action] [param1] [param2] ... [paramN] -v -r -s
```
or for docker
```bash
$ docker-compose exec [service-name] php ./scripts/cli.php [handler] [action] [param1] [param2] ... [paramN] -v -r -s
```
Example:
```bash
$ docker-compose exec app-service php ./scripts/cli.php main main -v -r -s
```
- ```-s``` = single instance allowed
- ```-v``` = verbose info
- ```-r``` = recording the process into several resources of your choice (MySQL, Logs, ...)


## CLI Debugging (xdebug 3.x) in PhpStorm under docker

2 aspects to realize:
1. `-dxdebug.mode=debug -dxdebug.client_host=host.docker.internal -dxdebug.client_port=9003 -dxdebug.start_with_request=yes` has to be in called console command
2. `docker-compose.yml` has to have ENV variable in PHP container: `PHP_IDE_CONFIG=serverName=phalcon5.test`, where `phalcon5.test` is your Settings > PHP > Servers > Name value.

where docker host for Windows or Linux:
> host.docker.internal

Result:

```bash
$ docker-compose exec app-service php -dxdebug.mode=debug -dxdebug.client_host=host.docker.internal -dxdebug.client_port=9003 -dxdebug.start_with_request=yes ./scripts/cli.php main main -v -s -r
```
with started listenings for PHP debug connections, certainly.

# Tests

```bash
$ docker-compose exec app-service vendor/bin/codecept run
```
