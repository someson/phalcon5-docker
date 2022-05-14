[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://choosealicense.com/licenses/mit/)

## Setup

```sh
$ git clone --branch php74 https://github.com/someson/phalcon5-docker.git .
$ docker-compose up -d --build
```

- add to your ```[...]/etc/hosts```

```sh
127.0.0.1 phalcon5.test
```

- composer update from the host:

```sh
$ docker-compose exec app-service composer install
```

## Containers:

- **nginx latest (alpine) + ssl + http2**
    - port 443 by default

- **php 7.4.x**
    - fpm port 9000
    - xdebug 3 (port 9003)
    - apcu
    - intl
