# pats_api
`pats_api` is the RESTful API for the back end of the Patients with Alzeimers Tracking System (PATS).

The `pats_api` runs on `localhost:8888`

# Setup
In order to run the `pats_api` you will need the following:

- PHP installed.
- Docker installed. See https://www.docker.com/products/docker-desktop

Once Docker is installed and running, execute the following command to set MySQL up:

`make setup`

## Error Logging
### macOS
In order to see errors in the terminal go to the `private/etc/php.ini` file and uncomment the following line:

`error_log = syslog`

### Windows
Nothing here at the moment.

### Running Error Logs
In terminal do the following command to see error logs for the php sever:

`make log`

To see logs for the MySQL server run:

`make sql-log`

## PDO Setup
### macOS
In order to properly setup the database you will need to edit the `private/etc/php.ini` file. Go to the following line:

`pdo_mysql.default_socket=` 

and change it to: 

`pdo_mysql.default_socket=/tmp/mysql.sock`

### Windows
Nothinng here at the moment.

# Running the API
In the root of the folder do the following command to run the API: 

`make`

To stop the API simply do: 

`control-c`

To stop the MySQL container do: 

`make stop`

# Testing
To start the tests run the following command:

`make test`

Or this command for verbose output:

`make test-vvv`

# Endpoints
There are no set endpoints at this moment.