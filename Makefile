TEST_OPTIONS:=-v
# *************************
# SERVER CONTROLS 
# *************************
start: start_mysql start_apache

stop: stop_mysql stop_apache

start_mysql:
	docker start pats-mysql

stop_mysql:
	docker kill pats-mysql

start_apache:
	docker start pats-api

stop_apache:
	docker kill pats-api

# *************************
# LOGGING
# *************************
sql-log:
	docker logs -f pats-mysql

api-log:
	docker logs -f pats-api

# *************************
# SQL MODIFICATIONS
# *************************
# Backup the DB
backup:
	docker exec pats-mysql /usr/bin/mysqldump -u pats --password=41xgroup69 pats > backup.sql

# Restores DB from backup
restore:
	cat backup.sql | docker exec -i pats-mysql /usr/bin/mysql -u pats --password=41xgroup69 pats

# Resets the DB to original state
reset:
	cat docker/mysql/pats.sql | docker exec -i pats-mysql /usr/bin/mysql -u pats --password=41xgroup69 pats

# *************************
# TESTING
# *************************
test: backup 
	${MAKE} codeception && ${MAKE} restore || ${MAKE} restore

codeception:
	php vendor/bin/codecept run $(TEST_OPTIONS)

# *************************
# SETUP
# *************************
# This sets up the docker containers and the network connections
setup: composer-install composer-update composer-dump-autoload setup-mysql setup-apache setup-network

setup-mysql:
	cd docker/mysql && docker build -t pats-mysql . && docker run --name pats-mysql -p 3306:3306 -d pats-mysql && cd ../..

setup-apache:
	cd docker/php-apache && docker build -t pats-api . && cd ../.. && docker run --name pats-api -p 8888:80 -v $(CURDIR):/var/www/html -d pats-api
	docker cp docker/php-apache/apache2.conf pats-api:/etc/apache2/apache2.conf
	docker cp docker/php-apache/php.ini pats-api:/usr/local/etc/php/

setup-network:
	docker network create patsnetwork
	docker network connect patsnetwork pats-api
	docker network connect patsnetwork pats-mysql

update: composer-update composer-dump-autoload

composer-install:
	docker run --rm -it --volume $(CURDIR):/app prooph/composer:7.3 install

composer-update:
	docker run --rm -it --volume $(CURDIR):/app prooph/composer:7.3 update

composer-dump-autoload:
	docker run --rm -it --volume $(CURDIR):/app prooph/composer:7.3 dump-autoload

# *************************
# CONTAINER BASH
# *************************
# Bash for the containers
api-bash:
	docker exec -it pats-api /bin/bash

mysql-bash:
	docker exec -it pats-mysql /bin/bash

# *************************
# CLEAN PROJECT
# *************************
clean:
	docker container rm pats-mysql
	docker container rm pats-api
	docker network rm patsnetwork