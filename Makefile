build: up composer

up:
	docker-compose --env-file=.env -p 'commissions-calculator' up -d --build --force-recreate

composer:
	docker exec -i cc_php /bin/sh -c "composer install"

test:
	docker exec -i cc_php bin/phpcs --standard=phpcs.xml -s -p -w
	docker exec -i cc_php bin/phpunit tests/

cli:
	docker exec -it cc_php /bin/sh
