mac: db_prod mac_start
	php -S localhost:8888 -t public public/index.php

mac_start:
	sudo /usr/local/mysql/support-files/mysql.server start

stop:
	rm src/db/db.json
	sudo /usr/local/mysql/support-files/mysql.server stop

restart:
	sudo /usr/local/mysql/support-files/mysql.server restart

log:
	log stream --predicate 'processImagePath contains "php"'

test: db_test
	php vendor/bin/codecept run -v
	cd src/db && echo '{"type":"prod"}' >db.json && cd ../..

test-vvv: db_test
	php vendor/bin/codecept run -vvv
	cd src/db && echo '{"type":"prod"}' >db.json && cd ../..

db_prod:
	cd src/db && echo '{"type":"prod"}' >db.json && cd ../..

db_test:
	cd src/db && echo '{"type":"test"}' >db.json && cd ../..