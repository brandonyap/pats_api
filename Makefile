mac: start
	php -S localhost:8888 -t public public/index.php

start:
	sudo /usr/local/mysql/support-files/mysql.server start

stop:
	sudo /usr/local/mysql/support-files/mysql.server stop

restart:
	sudo /usr/local/mysql/support-files/mysql.server restart

log:
	log stream --predicate 'processImagePath contains "php"'