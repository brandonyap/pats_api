mac_prod: db_prod start
	php -S localhost:8888 -t public public/index.php

mac_test: db_test start
	php -S localhost:8888 -t public public/index.php

start:
	sudo /usr/local/mysql/support-files/mysql.server start

stop:
	cd src/db && rm db.json && cd ../..
	sudo /usr/local/mysql/support-files/mysql.server stop

restart:
	sudo /usr/local/mysql/support-files/mysql.server restart

db_prod:
	cd src/db && echo '{"type":"prod"}' >db.json && cd ../..

db_test:
	cd src/db && echo '{"type":"test"}' >db.json && cd ../..

log:
	log stream --predicate 'processImagePath contains "php"'