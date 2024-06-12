## Commissions Calculator

### Annotation
Let's begin from general problems. Both API's used in project are inaccessible on freemium model. Talking about `binlist.net` it provides 5 free requests per hour and works unpredictable that could be an issue when you try to cover it with integration tests.

In the project, function `isEu` was removed completely and replaced by `BaseFeeProvider` class to provide us with ability to add different fees dependent on region.

All providers have interfaces so the code could be extended due to SOLID standards. You can implement another provider by modifying `config/config.php` file.

The idea was to avoid using third-party packages and make this demo as pure as it could be.

### Setup
To be able to use exchangeratesapi.io as a currency rates provider add your security token before launching a container:
```shell
echo PROVIDER_API_ACCESS_KEY=your_security_code >> .env
```

### Docker Console Commands
Build Local Workspace
```bash
make
```
Run container manually with Docker Compose (don't forget to run `composer install`):
```shell
docker-compose --env-file=.env -p 'commissions-calculator' up -d --build --force-recreate
```
Stop all:
```shell
docker-compose down -v --remove-orphans
```
or
```shell
docker stop $(docker ps -a -q)
```
Enter PHP container:
```shell
docker exec -it cc_php /bin/sh
```
Show logs:
```shell
docker-compose logs -f
```

### Executing
To execute the Calculator run:
```bash
docker exec -i cc_php /bin/sh -c "php bin/calculate input.txt"
```

### Running Tests
To launch static code analysis run:
```shell
docker exec -it cc_php bin/phpcs --standard=phpcs.xml -s -p -w
```
To launch PHPUnit tests run:
```shell
docker exec -it cc_php bin/phpunit tests/
```
To execute all tests together run:
```shell
make test
```