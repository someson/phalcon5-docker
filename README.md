## Setup

```sh
$ git clone https://github.com/someson/phalcon5-docker.git
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

- **nginx 1.21.3 + ssl + http2**
    - port 443 by default

- **php 7.4**
    - fpm port 9000
    - xdebug 3 (port 9003)
    - apcu
    - intl
