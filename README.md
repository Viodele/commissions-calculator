## Commissions Calculator

### Docker Console Commands
Build Local Workspace
```bash
make
```
Run Containers With Docker Compose:
```shell
docker-compose up -d --build --force-recreate
```
Stop All:
```shell
docker-compose down -v --remove-orphans
```
or
```shell
docker stop $(docker ps -a -q)
```
Enter PHP Container:
```shell
docker exec -it cc_php /bin/sh
```
Show logs:
```shell
docker-compose logs -f
```
