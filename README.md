### What is this repository for? ###

Application based on Phalcon Framework 5.x

* [https://github.com/phalcon/phalcon](https://github.com/phalcon/phalcon)

### How do I get set up? ###

```sh
$ git clone https://github.com/someson/phalcon5-docker.git
$ docker-compose up -d --build
```

add to your ```[...]/etc/hosts```

```sh
127.0.0.1 phalcon5.test
```

### Containers:

- **nginx 1.19.5 + ssl + http2**
    - port 443 by default

- **php 7.4.12**
    - fpm port 9000
    - xdebug 3 (port 9003)
    - apcu
    - intl

- **composer 2.0.7**
