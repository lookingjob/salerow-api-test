# Salerow API test

## Install

* Clone project repository with:
```shell
$ git clone git@github.com:lookingjob/salerow-api-test.git
$ cd salerow-api-test
```

* Run the following command to start all services using Docker Compose:
```shell
$ docker compose up
```

* Open https://localhost/api

## Scheduling

* Start the crond service manually:

```shell
$ docker compose exec php crond -b -l 2 -L /dev/stdout
```

* Alternatively you can run the command manually without scheduling:

```shell
$ docker-compose exec php bin/console app:create-indexes --marketCap=50000000
```

