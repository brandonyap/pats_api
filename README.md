# pats_api
`pats_api` is the RESTful API for the back end of the Patients with Alzeimers Tracking System (PATS).

The `pats_api` runs on `localhost:8888`

# Setup
## Docker
In order to run the `pats_api` you will need Docker Desktop installed. See https://www.docker.com/products/docker-desktop for installation instructions.

## Docker Container Setup
Once Docker Desktop is installed and running do the following. All of the commands are done in the `pats_api` directory.

1. Run the following command: `make setup`

2. Check if the `pats-api` and `pats-mysql` containers are running with the following command: `docker ps` - There should be 2 containers listed.

3. Test out the API by going to http://localhost:8888

## Error Logging
### Running Error Logs
In terminal do the following command to see error logs for the php-apache server:

`make api-log`

To see logs for the MySQL server run:

`make sql-log`

# Running pats API
## Starting the API
In the `pats_api` directory run the following command: 

`make start`

## Stopping the API
In the `pats_api` directory run the following command: 

`make stop`

# Testing
To start the tests run the following command:

`make test`

Or this command for verbose output:

`make test TEST_OPTIONS="-vvv"`

## Testing Specific Groups
To test specific groups of tests run the following command:

`make test TEST_OPTIONS="-vvv --group {group_name}"`

# Endpoints
`{{host}}` is `localhost:8888` if running locally.

GET `{{host}}/api/beacons`

POST `{{host}}/api/beacons` with x-www-form-urlencoded data

# Cleaning up the project
When you're finished with the project and no longer want any of the Docker containers and networks on your computer run the following command:

`make clean`