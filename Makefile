server_start: docker_start
	php -S localhost:8888 -t public public/index.php

stop:
	docker kill pats-mysql

log:
	log stream --predicate 'processImagePath contains "php"'

sql-log:
	docker logs -f pats-mysql

backup:
	docker exec pats-mysql /usr/bin/mysqldump -u pats --password=41xgroup69 pats > backup.sql

restore:
	cat backup.sql | docker exec -i pats-mysql /usr/bin/mysql -u pats --password=41xgroup69 pats

test: backup codeception restore

test-vvv: backup codeception-vvv restore
	
codeception:
	php vendor/bin/codecept run -v

codeception-vvv:
	php vendor/bin/codecept run -vvv

docker_start:
	docker start pats-mysql

setup:
	docker build -t pats-mysql .
	docker run --name pats-mysql -p 3306:3306 -d pats-mysql