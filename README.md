# Patients with Alzeimers Tracking System (PATS)
The Patients with Alzeimers Tracking System was developed for our final design course (ENGG_41X0) at the University of Guelph. This portion of the project contains a setup for the API and MySQL server to be ran in a Docker Container. The repositories associated with this project are all imported from Bitbucket and may contain Bitbucket pipeline files. 

# pats_api
`pats_api` is the RESTful API for the back end server of the Patients with Alzeimers Tracking System (PATS).

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

## Beacons
GET `{{host}}/api/beacons/all`

GET `{{host}}/api/beacons/{id}`

POST `{{host}}/api/beacons` with x-www-form-urlencoded data or json:
```
	uuid:{uuid} with format 12345678abcdefgh12345678abcdefgh (unique with 32 characters no '-')
	name:{name}
	description:{description} (optional)
```

PUT `{{host}}/api/beacons/{id}` with x-www-form-urlencoded data or json:
```
	uuid:{uuid} with format 12345678abcdefgh12345678abcdefgh (unique with 32 characters no '-')
	name:{name}
	description:{description} (optional)
```

DELETE `{{host}}/api/beacons/{id}`

## Beacon Groups
GET `{{host}}/api/beacons/group/all`

GET `{{host}}/api/beacons/group/{id}`

POST `{{host}}/api/beacons/group` with x-www-form-urlencoded data or json:
```
	name:{name}
	description:{description} (optional)
```

PUT `{{host}}/api/beacons/group/{id}` with x-www-form-urlencoded data or json:
```
	name:{name}
	description:{description} (optional)
```

DELETE `{{host}}/api/beacons/group/{id}`

## Beacon Locations
GET `{{host}}/api/beacons/group/{group_id}/location/all`

GET `{{host}}/api/beacons/group/{group_id}/location/{beacon_id}`

POST `{{host}}/api/beacons/{beacons_id}/location` with x-www-form-urlencoded data or json:
```
	group_id:{group_id}
	location_x:{location_x} (Float value)
	location_y:{location_y} (Float value)
```

PUT `{{host}}/api/beacons/{beacons_id}/location` with x-www-form-urlencoded data or json:
```
	group_id:{group_id}
	location_x:{location_x} (Float value)
	location_y:{location_y} (Float value)
```

DELETE `{{host}}/api/beacons/{beacons_id}/location` with x-www-form-urlencoded data or json:
```
	group_id:{group_id}
```

## Sensors
GET `{{host}}/api/sensors/all?active={boolean}` `true` means being used by a patient and `false` means not being used. `active` is also optional so if it is not declared then it will display all sensors.

GET `{{host}}/api/sensors/{id}`

POST `{{host}}/api/sensors` with x-www-form-urlencoded data or json:
```
	bluetooth_address:{bluetooth_address} with format 11:22:33:44:55:66 (unique)
	name:{name}
	description:{description} (optional)
```

PUT `{{host}}/api/sensors/{id}` with x-www-form-urlencoded data or json:
```
	bluetooth_address:{bluetooth_address} with format 11:22:33:44:55:66 (unique)
	name:{name}
	description:{description} (optional)
```

DELETE `{{host}}/api/sensors/{id}`

## Patients
GET `{{host}}/api/patients/all`

GET `{{host}}/api/patients/{id}`

POST `{{host}}/api/patients` with x-www-form-urlencoded data or json:
```
	sensors_id:{sensors_id} (must be existing) (unique)
	first_name:{name}
	last_name:{name}
	birthday:{birthday} format: 2000-01-01
	hospital_id:{hospital_id} (unique)
	physician:{physician_name}
	caretaker:{caretaker_name}
```

PUT `{{host}}/api/patients/{id}` with x-www-form-urlencoded data or json:
```
	sensors_id:{sensors_id} (must be existing) (unique)
	first_name:{name}
	last_name:{name}
	birthday:{birthday} format: 2000-01-01
	hospital_id:{hospital_id} (unique)
	physician:{physician_name}
	caretaker:{caretaker_name}
```

DELETE `{{host}}/api/patients/{id}`

## Locations
GET `{{host}}/api/patients/{id}/location`

GET `{{host}}/api/patients/location/all`

POST `{{host}}/api/sensors/location` with x-www-form-urlencoded data or json:
```
	sensors_address:{sensors_address} with format 11:22:33:44:55:66
	location_x:{location_x} (Float value)
	location_y:{location_y} (Float value)
```

# Cleaning up the project
When you're finished with the project and no longer want any of the Docker containers and networks on your computer run the following command:

`make clean`
