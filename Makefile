mac: start
	php -S localhost:8888 -t routes routes/index.php

start:
	sudo /usr/local/mysql/support-files/mysql.server start

stop:
	sudo /usr/local/mysql/support-files/mysql.server stop

restart:
	sudo /usr/local/mysql/support-files/mysql.server restart