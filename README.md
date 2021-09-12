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
$ docker-compose run --rm composer-service composer update --ignore-platform-reqs --no-scripts
```

## Containers:

- **nginx 1.21.3 + ssl + http2**
    - port 443 by default

- **php 8.0.10**
    - fpm port 9000
    - xdebug 3 (port 9003)
    - apcu
    - intl

- **composer >= 2**
