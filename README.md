# pats_api
`pats_api` is the RESTful API for the back end of the Patients with Alzeimers Tracking System (PATS).

The `pats_api` runs on `localhost:8888`

## Setup
In order to run the `pats_api` you will need the following:

- PHP installed.
- MySQL installed.
- bash or zsh to execute commands.

### Error Logging
#### macOS
In order to see errors in the terminal go to the `private/etc/php.ini` file and uncomment the following line:

`error_log = syslog`

#### Windows
Nothing here at the moment.

In terminal do the following command to see error logs:

`make log`

### PDO Setup
#### macOS
In order to properly setup the database you will need to edit the `private/etc/php.ini` file. Go to the following line:

`pdo_mysql.default_socket=` 

and change it to: 

`pdo_mysql.default_socket=/tmp/mysql.sock`

#### Windows
Nothinng here at the moment.

## Running the API
### macOS
In the root of the folder do the following command to run the API in production mode: 

`make mac_prod`

To stop the API simply do: 

`control-c`

To stop or restart MySQL do: 

`make stop` or `make restart` respectively.

### Windows
Nothing here at the moment.

## Testing
### macOS
To run tests for the API start the server in test mode:

`make mac_test`

### Windows
Nothing here at the moment.

### Both

To start the tests run the following command:

`make test`

Or this command for verbose output:

`make test-vvv`

## Endpoints
There are no set endpoints at this moment.