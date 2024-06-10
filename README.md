## Commissions Calculator

### Setup
To be able to use exchangeratesapi.io as currency rates provider add your security token before launching a container
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
