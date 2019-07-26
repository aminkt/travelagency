## Travelagency

Index
-------

- [Dev Installation](#development-installation)
- [Test or Production Installation](#test-stage-or-production-installation)
- [Run tests](#run-tests)
- [Getting start](#getting-start)
- [Authors](#authors)


### DEVELOPMENT INSTALLATION
This section will describe how run `api` in your local machine for development proposes. 

1. Install `docker` and `docker-compose`.
2. Run `docker-compose up -d`.
3. Use command `docker-compose exec -T api composer install` install dependencies.
4. Use command `docker-compose exec -T api php core/init --env=Development --overwrite=y` to setup development environment.
5. Visit `localhost:80` in your browser.

> You may need use proxy if you connect to internet from Iran.

### TEST STAGE OR PRODUCTION INSTALLATION
This section describe how install `api` on server for `test` or `production` proposes.

1. Install `docker` and `docker-compose`.
2. Run `docker-compose -f docker-compose-deploy.yml up -d`.
4. Use command `docker-compose -f docker-compose-deploy.yml exec -T api php core/init --env=Development --overwrite=y` to setup development environment.
5. Visit `localhost:80` in your browser.
    
Done! You can visit your api from `localhost:80`.

> Debug panel available in production. username:debuguser password:XjbZG&6aXk%Q

### APPLICATION INIT CONFIGURATIONS

For db config run below command.
```bash
docker-compose -f docker-compose-deploy.yml exec -T api php core/yii config-db -d=database-name -u=database-user -p=database-password
```

And then run below command to create project tables:
```bash
docker-compose -f docker-compose-deploy.yml exec -T api php core/yii migrate
```

> Remove `-f docker-compose-deploy.yml` from top commands to run it in development env.


#### Cron jobs
We use cron jobs to run some commands for example in exact time per day.
For example in this project, we have a cron job that check `end_time` of tour and if it's pass current time, then change
status of tour to `finished`.

Use blow command to define this type of commands.

```bash
crontab -e
0 3 * * * docker-compose exec api php core/yii crons/tour-crob-job
```
By running up command, every 3h an command will run and will handle finished tours.

### Run tests

Use below command to run common and api tests in level unit test and api test.

> This commands work in development installation.
```bash
docker-compose exec -T api vendor/bin/codecept -c core run
```


For running test in test stage installation use bellow commands:
```bash
docker exec -it travelagency-api vendor/bin/codecept -c core run
```

### GETTING start

See [API doc](https://documenter.getpostman.com/view/2413517/SVSPpSfz) to know exist rules.


### Authors

[Amin Keshavarz](https://github.com/aminkt)

