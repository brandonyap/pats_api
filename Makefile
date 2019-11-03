mac: mac_dbstart
	php -S localhost:8888 -t routes routes/index.php

mac_dbstart:
	sudo /usr/local/mysql/support-files/mysql.server start

mac_dbstop:
	sudo /usr/local/mysql/support-files/mysql.server stop

mac_dbrestart:
	sudo /usr/local/mysql/support-files/mysql.server restart