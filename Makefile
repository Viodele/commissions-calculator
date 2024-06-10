build: up composer

up:
	docker-compose -p 'commissions-calculator' up -d --build --force-recreate

composer:
	docker exec -it cc_php /bin/sh -c "composer install"

test:
	docker exec -it cc_php bin/phpcs --standard=phpcs.xml -s -p -w
